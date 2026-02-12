@extends('petugas.layouts.app')

@section('title', 'Edit Profil Petugas')

@section('content')
<div class="max-w-2xl mx-auto p-6" data-aos="fade-up">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
            <h2 class="text-white text-xl font-bold flex items-center">
                <i class="fas fa-user-edit mr-3"></i>
                Edit Profil
            </h2>
        </div>

        <div class="p-6">
            {{-- Tampilkan pesan sukses --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error validasi --}}
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('petugas.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Foto Profil --}}
                <div class="mb-6 text-center">
                    <div class="relative inline-block">
                        <div id="preview-container" class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-teal-200">
                            <img id="preview" 
                                 src="{{ $user->foto_url }}" 
                                 alt="Foto Profil"
                                 class="w-full h-full object-cover">
                        </div>
                        <label for="foto" class="absolute bottom-0 right-0 bg-teal-600 text-white p-2 rounded-full cursor-pointer hover:bg-teal-700 shadow-lg">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="foto" name="foto" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maks 2MB</p>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fas fa-envelope mr-2 text-teal-600"></i>Email
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           value="{{ old('email', $user->email) }}"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 @error('email') border-red-500 @enderror"
                           placeholder="contoh@email.com">
                </div>

                {{-- Nomor WhatsApp --}}
                <div class="mb-6">
                    <label for="nomor_wa" class="block text-gray-700 text-sm font-bold mb-2">
                        <i class="fab fa-whatsapp mr-2 text-green-600"></i>Nomor WhatsApp
                    </label>
                    <input type="text" 
                           name="nomor_wa" 
                           id="nomor_wa" 
                           value="{{ old('nomor_wa', $user->nomor_wa) }}"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                           placeholder="08123456789">
                </div>

                {{-- Tombol --}}
                <div class="flex justify-between items-center">
                    <a href="{{ route('petugas.profile.index') }}" 
                       class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection