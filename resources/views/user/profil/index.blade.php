@extends('layouts.user')

@section('title', 'Profil Saya - PetHouse')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8" data-aos="fade-up">
        <h1 class="text-3xl font-bold text-gray-800">Profil Saya 👤</h1>
        <p class="text-gray-600 mt-2">Kelola informasi profil dan lihat riwayat layanan Anda</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-8" data-aos="fade-up">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl mb-8" data-aos="fade-up">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-3 text-xl"></i>
            <div>
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Kolom Kiri: Data Profil + Foto -->
        <div class="lg:col-span-2 space-y-8">
            <!-- 🖼️ INFORMASI AKUN + FOTO PROFIL -->
            <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Informasi Akun</h2>
                    <span class="px-3 py-1 bg-teal-100 text-teal-800 rounded-full text-sm font-medium">
                        {{ strtoupper($user->role) }}
                    </span>
                </div>

                <!-- 🔥 FORM DENGAN ENCTYPE UNTUK UPLOAD FOTO -->
                <form method="POST" action="{{ route('user.profil.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT') {{-- WAJIB untuk method PUT --}}

                    <!-- ===== BAGIAN FOTO PROFIL ===== -->
                    <div class="flex flex-col md:flex-row items-center gap-8 pb-6 border-b border-gray-200">
                        <!-- Preview Foto -->
                        <div class="flex-shrink-0 text-center">
                            <div class="relative inline-block">
                                <div class="w-28 h-28 md:w-32 md:h-32 rounded-full overflow-hidden border-4 border-teal-200 shadow-md">
                                    <img id="profile-preview" 
                                         src="{{ $user->foto_url }}" 
                                         alt="Foto Profil"
                                         class="w-full h-full object-cover">
                                </div>
                                <label for="foto" 
                                       class="absolute bottom-0 right-0 bg-teal-600 text-white p-2 rounded-full cursor-pointer hover:bg-teal-700 shadow-lg transition">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="foto" name="foto" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </div>
                            <p class="text-xs text-gray-500 mt-3">Format: JPG, PNG. Maks 2MB</p>
                            <p class="text-xs text-teal-600 mt-1">Klik ikon kamera untuk ganti foto</p>
                        </div>

                        <!-- Info Singkat -->
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $user->username }}</h3>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            <p class="text-sm text-gray-500 mt-2">
                                <i class="fas fa-calendar-alt mr-1"></i> Member sejak {{ $user->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    <!-- ===== END FOTO PROFIL ===== -->

                    <!-- Form Fields (Username, Email, No WA, Password) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Username *
                            </label>
                            <input type="text" name="username" required
                                value="{{ old('username', $user->username) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email *
                            </label>
                            <input type="email" name="email" required
                                value="{{ old('email', $user->email) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor WhatsApp
                        </label>
                        <input type="text" name="nomor_wa"
                            value="{{ old('nomor_wa', $user->nomor_wa) }}"
                            placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>

                    <!-- Password Section -->
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Ubah Password</h3>
                        <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Saat Ini
                                </label>
                                <input type="password" name="current_password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Password Baru
                                    </label>
                                    <input type="password" name="new_password"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Konfirmasi Password Baru
                                    </label>
                                    <input type="password" name="new_password_confirmation"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white px-6 py-3 rounded-lg font-medium transition duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Statistik -->
            <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Statistik Layanan</h2>
                <!-- ... (konten statistik tetap sama) ... -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-r from-teal-50 to-teal-100 p-5 rounded-xl border border-teal-200">
                        <div class="flex items-center">
                            <div class="p-3 bg-white rounded-lg shadow-sm mr-4">
                                <i class="fas fa-calendar-check text-teal-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-teal-800 font-medium">Total Booking</p>
                                <p class="text-2xl font-bold text-teal-900">{{ $totalBooking }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-5 rounded-xl border border-blue-200">
                        <div class="flex items-center">
                            <div class="p-3 bg-white rounded-lg shadow-sm mr-4">
                                <i class="fas fa-stethoscope text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-blue-800 font-medium">Total Konsultasi</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $totalKonsultasi }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-5 rounded-xl border border-green-200">
                        <div class="flex items-center">
                            <div class="p-3 bg-white rounded-lg shadow-sm mr-4">
                                <i class="fas fa-home text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-green-800 font-medium">Booking Aktif</p>
                                <p class="text-2xl font-bold text-green-900">{{ $bookingAktif }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Riwayat dan Info (TETAP SAMA) -->
        <div class="space-y-8">
            <!-- Info Akun -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl shadow-lg p-6 border border-purple-100" data-aos="fade-up">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Info Akun</h2>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <i class="fas fa-user-circle text-purple-500 mr-3 text-xl"></i>
                        <div>
                            <p class="text-sm text-gray-500">ID Member</p>
                            <p class="font-medium">{{ $user->id }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt text-purple-500 mr-3 text-xl"></i>
                        <div>
                            <p class="text-sm text-gray-500">Bergabung Sejak</p>
                            <p class="font-medium">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock text-purple-500 mr-3 text-xl"></i>
                        <div>
                            <p class="text-sm text-gray-500">Terakhir Login</p>
                            <p class="font-medium">{{ now()->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Terbaru -->
            @if($bookings->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="100">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Booking Terbaru</h2>
                <div class="space-y-4">
                    @foreach($bookings->take(3) as $booking)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-teal-300 transition">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-teal-700">{{ $booking->nama_hewan }}</h3>
                            <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($booking->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                           'bg-green-100 text-green-800') }}">
                                {{ $booking->status }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-1">{{ $booking->kode_booking }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M Y') }}</p>
                    </div>
                    @endforeach
                </div>
                @if($bookings->count() > 3)
                <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                    <a href="{{ route('user.hewan-saya') }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                        Lihat Semua →
                    </a>
                </div>
                @endif
            </div>
            @endif

            <!-- Konsultasi Terbaru -->
            @if($konsultasi->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-up" data-aos-delay="200">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Konsultasi Terbaru</h2>
                <div class="space-y-4">
                    @foreach($konsultasi->take(3) as $konsul)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                        <h3 class="font-bold text-blue-700 mb-1">{{ Str::limit($konsul->topik, 40) }}</h3>
                        <p class="text-sm text-gray-600 mb-1">{{ $konsul->kode_konsultasi }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">{{ $konsul->created_at->format('d M') }}</span>
                            <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $konsul->status === 'pending' ? 'bg-amber-100 text-amber-800' : 
                                           ($konsul->status === 'selesai' ? 'bg-green-100 text-green-800' : 
                                           'bg-blue-100 text-blue-800') }}">
                                {{ $konsul->status }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($konsultasi->count() > 3)
                <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                    <a href="{{ route('user.konsultasi.index') }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                        Lihat Semua →
                    </a>
                </div>
                @endif
            </div>
            @endif

            <!-- Kontak Bantuan -->
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl shadow-lg p-6 border border-amber-100" data-aos="fade-up" data-aos-delay="300">
                <h2 class="text-xl font-bold text-gray-800 mb-4">💁 Butuh Bantuan?</h2>
                <div class="space-y-3">
                    <a href="https://wa.me/6285942173668" target="_blank"
                        class="flex items-center p-3 bg-white rounded-lg border border-green-200 hover:border-green-300 transition">
                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                            <i class="fab fa-whatsapp text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">WhatsApp Support</p>
                            <p class="text-sm text-gray-600">+62 859-4217-3668</p>
                        </div>
                    </a>
                    <div class="flex items-center p-3 bg-white rounded-lg border border-blue-200">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-envelope text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">Email</p>
                            <p class="text-sm text-gray-600">support@pethouse.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Keamanan -->
    <div class="mt-8 p-6 bg-gray-50 rounded-2xl border border-gray-200" data-aos="fade-up">
        <h3 class="text-lg font-medium text-gray-800 mb-3">🔒 Tips Keamanan Akun</h3>
        <ul class="text-sm text-gray-600 space-y-2">
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                Jangan berikan password Anda kepada siapapun
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                Selalu logout setelah menggunakan komputer umum
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                Gunakan password yang kuat dan unik
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                Laporkan aktivitas mencurigakan ke support kami
            </li>
        </ul>
    </div>
</div>

<!-- JavaScript untuk Preview Foto -->
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection