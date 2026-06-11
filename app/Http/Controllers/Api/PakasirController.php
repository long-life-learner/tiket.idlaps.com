<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
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
        // Validate required fields per Pakasir documentation
        $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric',
            'project' => 'required|string',
            'status' => 'required|string',
        ]);

        $orderId = $request->order_id;
        $amount = $request->amount;
        $project = $request->project;
        $status = $request->status;
        $paymentMethod = $request->payment_method;
        $completedAt = $request->completed_at;

        // Log webhook for debugging
        Log::info('Pakasir Webhook Received', $request->all());

        // Verify project matches our configuration
        $expectedProject = config('pakasir.slug');
        if ($project !== $expectedProject) {
            Log::warning('Pakasir Webhook: Project mismatch', [
                'expected' => $expectedProject,
                'received' => $project,
            ]);
            return response()->json(['message' => 'Project mismatch'], 400);
        }

        // Find the booking
        $booking = Booking::where('code', $orderId)->first();

        if (!$booking) {
            Log::warning('Pakasir Webhook: Booking not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Verify amount matches
        if ((float) $booking->total !== (float) $amount) {
            Log::warning('Pakasir Webhook: Amount mismatch', [
                'booking_total' => $booking->total,
                'webhook_amount' => $amount,
            ]);
            return response()->json(['message' => 'Amount mismatch'], 400);
        }

        // Map Pakasir status to our payment status
        // Pakasir status: pending, completed, failed, expired
        $paymentStatus = match ($status) {
            'completed' => 'success',
            'pending' => 'pending',
            'expired' => 'expired',
            'failed' => 'failed',
            default => $booking->payment_status,
        };

        // Update booking with payment info
        $booking->update([
            'payment_status' => $paymentStatus,
            'payment_method' => $paymentMethod,
            'paid_at' => $completedAt ? \Carbon\Carbon::parse($completedAt) : now(),
            'webhook_data' => $request->all(),
        ]);

        Log::info('Pakasir Webhook: Booking updated', [
            'order_id' => $orderId,
            'payment_status' => $paymentStatus,
            'payment_method' => $paymentMethod,
        ]);

        return response()->json([
            'message' => 'Webhook processed successfully',
        ], 200);
    }

    /**
     * Handle redirect back from Pakasir.
     * This is called when user clicks "Kembali ke halaman merchant" button.
     */
    public function redirectBack(Request $request)
    {
        $orderId = $request->order_id;
        $status = $request->status;

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Invalid order');
        }

        $booking = Booking::where('code', $orderId)->first();

        if (!$booking) {
            return redirect()->route('home')->with('error', 'Booking not found');
        }

        // Update payment status based on status from Pakasir
        if ($status) {
            $paymentStatus = match ($status) {
                'success', 'settlement', 'paid', 'completed' => 'success',
                'pending' => 'pending',
                'expire' => 'expired',
                'failed', 'deny', 'cancel' => 'failed',
                default => $booking->payment_status,
            };

            $booking->update(['payment_status' => $paymentStatus]);
        }

        return redirect()->route('bookings.finished', ['order_id' => $orderId]);
    }
}
