<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PakasirController extends Controller
{
    /**
     * Handle webhook from Pakasir payment gateway.
     *
     * Pakasir will POST with body:
     * {
     *   "amount": 22000,
     *   "order_id": "240910HDE7C9",
     *   "project": "depodomain",
     *   "status": "completed",
     *   "payment_method": "qris",
     *   "completed_at": "2024-09-10T08:07:02.819+07:00"
     * }
     */
    public function webhook(Request $request)
    {
        Log::info('Pakasir Webhook Received', $request->all());

        // Validate required fields per Pakasir documentation
        $validated = $request->validate([
            'order_id'  => 'required|string',
            'amount'    => 'required|numeric',
            'project'   => 'required|string',
            'status'    => 'required|string',
        ]);

        $orderId       = $request->order_id;
        $amount        = (int) $request->amount;
        $project       = $request->project;
        $status        = $request->status;
        $paymentMethod = $request->payment_method;
        $completedAt   = $request->completed_at;

        // Verify project matches our configuration
        $expectedProject = config('pakasir.slug');
        if ($project !== $expectedProject) {
            Log::warning('Pakasir Webhook: Project mismatch', [
                'expected' => $expectedProject,
                'received' => $project,
            ]);
            return response()->json(['message' => 'Project mismatch'], 400);
        }

        // Find the booking by code (= order_id)
        $booking = Booking::where('code', $orderId)->first();

        if (!$booking) {
            Log::warning('Pakasir Webhook: Booking not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Verify amount — compare as integers to avoid decimal mismatch
        $bookingTotal = (int) round((float) $booking->total);
        if ($bookingTotal !== $amount) {
            Log::warning('Pakasir Webhook: Amount mismatch', [
                'booking_total' => $bookingTotal,
                'webhook_amount' => $amount,
            ]);
            return response()->json(['message' => 'Amount mismatch'], 400);
        }

        // Map Pakasir status to our payment_status
        $paymentStatus = match ($status) {
            'completed' => 'success',
            'pending'   => 'pending',
            'expired'   => 'expired',
            'failed'    => 'failed',
            default     => $booking->payment_status,
        };

        // Update booking with payment info
        $booking->update([
            'payment_status' => $paymentStatus,
            'payment_method' => $paymentMethod,
            'paid_at'        => $completedAt ? \Carbon\Carbon::parse($completedAt) : now(),
            'webhook_data'   => $request->all(),
        ]);

        Log::info('Pakasir Webhook: Booking updated', [
            'order_id'       => $orderId,
            'payment_status' => $paymentStatus,
            'payment_method' => $paymentMethod,
        ]);

        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }

    /**
     * Handle redirect back from Pakasir after user completes or cancels payment.
     * Pakasir hanya mengirimkan ?order_id= pada redirect, tanpa ?status=.
     * Oleh karena itu kita verifikasi status via Transaction Detail API.
     */
    public function redirectBack(Request $request)
    {
        $orderId = $request->query('order_id');

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Invalid order');
        }

        $booking = Booking::where('code', $orderId)->first();

        if (!$booking) {
            return redirect()->route('home')->with('error', 'Booking not found');
        }

        // Jika payment_status sudah success (webhook sudah diterima lebih dulu), langsung arahkan
        if ($booking->payment_status === 'success') {
            return redirect()->route('bookings.ticket', $orderId);
        }

        // Verifikasi status via Pakasir Transaction Detail API
        $pakasirSlug   = config('pakasir.slug');
        $pakasirApiKey = config('pakasir.api_key');
        $baseUrl       = config('pakasir.base_url', 'https://app.pakasir.com');
        $amount        = (int) round((float) $booking->total);

        try {
            $response = Http::timeout(10)->get("{$baseUrl}/api/transactiondetail", [
                'project'  => $pakasirSlug,
                'amount'   => $amount,
                'order_id' => $orderId,
                'api_key'  => $pakasirApiKey,
            ]);

            if ($response->successful()) {
                $transaction = $response->json('transaction');
                $status      = $transaction['status'] ?? null;

                Log::info('Pakasir Transaction Detail', [
                    'order_id'    => $orderId,
                    'api_status'  => $status,
                    'response'    => $transaction,
                ]);

                if ($status) {
                    $paymentStatus = match ($status) {
                        'completed'                    => 'success',
                        'pending'                      => 'pending',
                        'expired'                      => 'expired',
                        'failed', 'deny', 'cancel'     => 'failed',
                        default                        => $booking->payment_status,
                    };

                    $booking->update(['payment_status' => $paymentStatus]);
                }
            } else {
                Log::warning('Pakasir Transaction Detail: Non-200 response', [
                    'order_id' => $orderId,
                    'status'   => $response->status(),
                    'body'     => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Pakasir Transaction Detail: Exception', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);
        }

        // Reload booking setelah kemungkinan update
        $booking->refresh();

        return redirect()->route('bookings.ticket', $orderId);
    }
}
