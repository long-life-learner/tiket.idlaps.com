@extends('layouts.main')

@section('content')
    <div class="bg-[#F6F8FA] min-h-screen">
        <div class="relative flex flex-col w-full max-w-[640px] min-h-screen mx-auto bg-[#F6F8FA] overflow-x-hidden">
            <!-- HEADER SECTION -->
            <div class="relative">
                <div class="bg-[#552BFF] h-[390px] w-full"></div>
                <div class="absolute top-[60px] left-[20px] right-[20px] flex items-center justify-between">
                    <a href="{{ route('events.show', $event->slug) }}"
                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center cursor-pointer hover:bg-gray-50 transition-colors">
                        <img src="{{ asset('assets/icons/arrow-left.svg') }}" class="w-5 h-5" alt="back" />
                    </a>

                    <!-- Title -->
                    <h1 class="text-white font-semibold text-base">
                        Pendaftaran
                    </h1>
                    <div class="w-12 h-12 opacity-0">
                        <img src="{{ asset('assets/icons/save-add.svg') }}" class="w-5 h-5" alt="save" />
                    </div>
                </div>

                <!-- Progress Steps -->
                <div class="absolute top-[138px] left-[40px] right-[40px]">
                    <div class="relative">
                        <div class="absolute top-6 left-[24px] right-[24px]">
                            <div class="w-full border-t-2 border-dashed border-white opacity-80"></div>
                        </div>

                        <!-- Steps -->
                        <div class="flex justify-between relative z-10 gap-16">
                            <!-- Step 1 -->
                            <div class="flex flex-col items-center">
                                <div class="relative">
                                    <div class="w-12 h-12 bg-[#F46325] rounded-full flex items-center justify-center">
                                        <img src="{{ asset('assets/icons/event-2.svg') }}"
                                            class="w-6 h-6 filter brightness-0 invert" alt="booking" />
                                    </div>
                                    <div
                                        class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-5 h-5 bg-gradient-to-r from-[#F0FF2B] to-[#CDFB35] rounded-full flex items-center justify-center">
                                        <span class="text-[#06071C] text-xs font-bold">1</span>
                                    </div>
                                </div>
                                <span class="text-white text-xs font-medium mt-4">Booking</span>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex flex-col items-center">
                                <div class="relative">
                                    <div class="w-12 h-12 bg-[#06071C] rounded-full flex items-center justify-center">
                                        <img src="{{ asset('assets/icons/security-card.svg') }}"
                                            class="w-6 h-6 filter brightness-0 invert" alt="payment" />
                                    </div>
                                    <div
                                        class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-5 h-5 bg-white rounded-full flex items-center justify-center">
                                        <span class="text-[#06071C] text-xs font-bold">2</span>
                                    </div>
                                </div>
                                <span class="text-white text-xs font-medium mt-4">Payment</span>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex flex-col items-center">
                                <div class="relative">
                                    <div class="w-12 h-12 bg-[#06071C] rounded-full flex items-center justify-center">
                                        <img src="{{ asset('assets/icons/flag.svg') }}"
                                            class="w-6 h-6 filter brightness-0 invert" alt="get ready" />
                                    </div>
                                    <div
                                        class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-5 h-5 bg-white rounded-full flex items-center justify-center">
                                        <span class="text-[#06071C] font-bold text-xs">3</span>
                                    </div>
                                </div>
                                <span class="text-white text-xs font-medium mt-4">Get Ready</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONTENT SECTION -->
            <div class="px-5 -mt-[130px] relative z-10 space-y-5 pb-12">
                <!-- Event Registration Form -->
                <div class="bg-white rounded-3xl p-3 flex items-center gap-4">
                    <div class="relative w-[120px] h-[140px] flex-shrink-0">
                        <div class="w-full h-full rounded-3xl overflow-hidden">
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                                class="w-full h-full object-cover" />
                        </div>
                    </div>

                    <!-- Event Info -->
                    <div class="flex-1 space-y-3">
                        <h3 class="font-bold text-base text-[#06071C] leading-tight">{{ $event->title }}</h3>

                        <div class="space-y-2">
                            <!-- Prize -->
                            <div class="flex items-center gap-1.5">
                                <img src="{{ asset('assets/icons/gift.svg') }}" class="w-5 h-5 filter brightness-0"
                                    alt="prize" />
                                <span class="text-[#06071C] text-sm">Total Hadiah Rp
                                    {{ number_format($event->total_prize, 0, ',', '.') }}</span>
                            </div>

                            <!-- Venue -->
                            <div class="flex items-center gap-1.5">
                                <img src="{{ asset('assets/icons/building.svg') }}" class="w-5 h-5 filter brightness-0"
                                    alt="venue" />
                                <span class="text-[#06071C] text-sm">{{ $event->venue->name }}</span>
                            </div>
                            <!-- Date -->
                            <div class="flex items-center gap-1.5">
                                <img src="{{ asset('assets/icons/calendar.svg') }}" class="w-5 h-5 filter brightness-0"
                                    alt="date" />
                                <span class="text-[#06071C] text-sm">
                                    {{ $event->date->locale('id')->translatedFormat('l, d F Y') }}</span>


                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information Form -->
                <form method="POST" action="{{ route('bookings.information.save', $event->slug) }}"
                    class="bg-white rounded-3xl p-5 pb-8 space-y-4">
                    @csrf

                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <h2 class="font-bold text-base text-[#06071C]">Informasi Pribadi</h2>
                        <img src="{{ asset('assets/icons/arrow-circle-down.svg') }}" class="w-6 h-6 transition-transform"
                            alt="expand" />
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-dashed border-[#E4E5E9]"></div>

                    <!-- Complete Name Field -->
                    <div class="space-y-2 py-3">
                        <label class="block font-semibold text-base text-[#06071C]">Nama</label>
                        <!-- notes -->
                        <p class="text-sm text-gray-500">Ini akan menjadi nama pada BIB dan sertifikat kamu</p>
                        <div
                            class="bg-[#F6F8FA] rounded-full px-4 py-3.5 flex items-center gap-2.5 border transition-colors hover:border-[#FF7A00] @error('name') border-red-500 @enderror">
                            <img src="{{ asset('assets/icons/user-octagon.svg') }}" class="w-6 h-6" alt="user" />
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Siapa nama kamu"
                                class="bg-transparent flex-1 text-base text-[#06071C] placeholder-gray-400 outline-none"
                                autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required />
                        </div>

                        @error('name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number Field -->
                    <div class="space-y-2 py-3">
                        <label class="block font-semibold text-base text-[#06071C]">Nomor Telepon / WhatsApp</label>
                        <p class="text-sm text-gray-500">Akan digunakan untuk mengirim update info terkait event</p>
                        <div
                            class="bg-[#F6F8FA] rounded-full px-4 py-3.5 flex items-center gap-2.5 border transition-colors hover:border-[#FF7A00] @error('phone') border-red-500 @enderror">
                            <img src="{{ asset('assets/icons/call-incoming.svg') }}" class="w-6 h-6" alt="phone" />
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                placeholder="Masukkan nomor telepon kamu"
                                class="bg-transparent flex-1 text-base text-[#06071C] placeholder-gray-400 outline-none"
                                autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" required />

                            @error('phone')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Address Field -->
                    <div class="space-y-2 py-3">
                        <label class="block font-semibold text-base text-[#06071C]">Email</label>

                        <p class="text-sm text-gray-500">Akan digunakan untuk mengirim e-tiket dan invoice</p>
                        <div
                            class="bg-[#F6F8FA] rounded-full px-4 py-3.5 flex items-center gap-2.5 border transition-colors hover:border-[#FF7A00] @error('email') border-red-500 @enderror">
                            <img src="{{ asset('assets/icons/sms.svg') }}" class="w-6 h-6" alt="email" />
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="Masukkan alamat email kamu"
                                class="bg-transparent flex-1 text-base text-[#06071C] placeholder-gray-400 outline-none"
                                autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
                        </div>

                        @error('email')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jersey Size Field -->
                    <div class="space-y-2 py-3">
                        <label class="block font-semibold text-base text-[#06071C]">Ukuran Jersey</label>

                        <div
                            class="bg-[#F6F8FA] rounded-full px-4 py-3.5 flex items-center gap-2.5 border transition-colors hover:border-[#FF7A00] @error('jersey_size') border-red-500 @enderror">
                            <img src="{{ asset('assets/icons/shirt.svg') }}" class="w-6 h-6" alt="shirt" />
                            <select name="jersey_size"
                                class="bg-transparent flex-1 text-base text-[#06071C] placeholder-gray-400 outline-none appearance-none cursor-pointer"
                                required>
                                <option value="" disabled selected>Pilih Ukuran</option>
                                <option value="XS" {{ old('jersey_size') == 'XS' ? 'selected' : '' }}>XS</option>
                                <option value="S" {{ old('jersey_size') == 'S' ? 'selected' : '' }}>S</option>
                                <option value="M" {{ old('jersey_size') == 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ old('jersey_size') == 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ old('jersey_size') == 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="XXL" {{ old('jersey_size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                            </select>
                        </div>
                        @error('jersey_size')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror

                        <!-- Size Guide -->
                        <div class="mt-3">
                            <button type="button" onclick="toggleSizeGuide()"
                                class="flex items-center gap-2 text-sm text-[#552BFF] font-medium hover:underline">
                                <img src="{{ asset('assets/icons/ruler.svg') }}" class="w-4 h-4" alt="ruler" />
                                Lihat Panduan Ukuran
                            </button>

                            <!-- Size Guide Modal -->
                            <div id="sizeGuideModal"
                                class="hidden fixed inset-0 bg-black/50 z-50 flex items-end justify-center sm:items-center p-4 h-full">
                                <div class="bg-white rounded-3xl p-5 max-w-lg w-full relative flex flex-col"
                                    style="height: fit-content;">
                                    <div class="flex items-center justify-between mb-4 flex-shrink-0">
                                        <h3 class="font-bold text-lg text-[#06071C]">Panduan Ukuran Jersey</h3>
                                        <button type="button" onclick="toggleSizeGuide()"
                                            class="w-8 h-8 bg-[#F6F8FA] rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors flex-shrink-0">
                                            <img src="{{ asset('assets/icons/close.svg') }}" class="w-4 h-4"
                                                alt="close" />
                                        </button>
                                    </div>
                                    <div class="overflow-y-auto flex-1">
                                        <img src="{{ asset('assets/images/size-guide.jpeg') }}"
                                            alt="Panduan Ukuran Jersey" class="w-full rounded-2xl object-contain" />
                                        <div class="mt-4 text-xs text-gray-500 text-center pb-1">
                                            *Ukuran dalam cm. Toleransi ±2cm
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Event Class Selection -->
                    @if ($event->classes->isNotEmpty())
                        <div class="space-y-2 py-3">
                            <label class="block font-semibold text-base text-[#06071C]">Pilih Kategori</label>


                            @error('event_class_id')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror

                            <div class="space-y-3 mt-2">
                                @foreach ($event->classes as $class)
                                    @php $isSelected = old('event_class_id') == $class->id; @endphp
                                    <label for="class_{{ $class->id }}"
                                        class="class-card relative flex items-center gap-3 bg-[#F6F8FA] rounded-2xl p-4 border-2 cursor-pointer transition-all duration-200 {{ $isSelected ? 'border-[#552BFF] bg-purple-50' : 'border-transparent hover:border-[#552BFF]/40' }}">

                                        {{-- Input disembunyikan sepenuhnya --}}
                                        <input type="radio" id="class_{{ $class->id }}" name="event_class_id"
                                            value="{{ $class->id }}"
                                            style="position:absolute;opacity:0;width:0;height:0;"
                                            {{ $isSelected ? 'checked' : '' }} required />

                                        {{-- Custom radio dot --}}
                                        <div
                                            class="class-radio-dot w-5 h-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center transition-all duration-200 {{ $isSelected ? 'border-[#552BFF]' : 'border-gray-300' }}">
                                            <div
                                                class="class-radio-inner w-2.5 h-2.5 rounded-full bg-[#552BFF] transition-all duration-200 {{ $isSelected ? 'scale-100 opacity-100' : 'scale-0 opacity-0' }}">
                                            </div>
                                        </div>

                                        {{-- Class info --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                                <span class="font-bold text-sm text-[#06071C]">{{ $class->name }}</span>
                                                <span class="font-bold text-sm text-[#552BFF] whitespace-nowrap">
                                                    Rp {{ number_format($class->price, 0, ',', '.') }}
                                                </span>
                                            </div>

                                            @if ($class->gender)
                                                <span
                                                    class="inline-block mt-1 text-xs px-2 py-0.5 rounded-full font-medium {{ $class->gender === 'male' ? 'bg-blue-100 text-blue-700' : ($class->gender === 'female' ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-600') }}">
                                                    {{ $class->gender === 'male' ? 'Pria' : ($class->gender === 'female' ? 'Wanita' : ucfirst($class->gender)) }}
                                                </span>
                                            @endif

                                            @if ($class->description)
                                                <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                                    {{ $class->description }}</p>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Continue Button -->
                    <div class="pt-6">
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-[#F0FF2B] to-[#CDFB35] rounded-full py-3.5 px-5 flex items-center justify-between">
                            <span class="font-bold text-base text-[#06071C]">Lanjutkan</span>
                            <img src="{{ asset('assets/icons/arrow-left.svg') }}" class="w-5 h-5 transform rotate-180"
                                alt="continue" />
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function toggleSizeGuide() {
            const modal = document.getElementById('sizeGuideModal');
            modal.classList.toggle('hidden');
            document.body.style.overflow = modal.classList.contains('hidden') ? 'auto' : 'hidden';
        }

        // Close modal when clicking outside
        document.getElementById('sizeGuideModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                toggleSizeGuide();
            }
        });

        // Class card selection interaction
        document.querySelectorAll('input[name="event_class_id"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                // Reset all cards
                document.querySelectorAll('.class-card').forEach(function(card) {
                    card.classList.remove('border-[#552BFF]', 'bg-purple-50');
                    card.classList.add('border-transparent');
                    card.querySelector('.class-radio-dot').classList.remove('border-[#552BFF]');
                    card.querySelector('.class-radio-dot').classList.add('border-gray-300');
                    card.querySelector('.class-radio-inner').classList.remove('scale-100');
                    card.querySelector('.class-radio-inner').classList.add('scale-0');
                });

                // Highlight selected card
                const selectedCard = this.closest('.class-card');
                selectedCard.classList.add('border-[#552BFF]', 'bg-purple-50');
                selectedCard.classList.remove('border-transparent');
                selectedCard.querySelector('.class-radio-dot').classList.add('border-[#552BFF]');
                selectedCard.querySelector('.class-radio-dot').classList.remove('border-gray-300');
                selectedCard.querySelector('.class-radio-inner').classList.add('scale-100');
                selectedCard.querySelector('.class-radio-inner').classList.remove('scale-0');
            });
        });
    </script>
@endsection
