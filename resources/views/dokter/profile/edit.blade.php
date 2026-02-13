@extends('dokter.layouts.app')

@section('title', 'Edit Profil Dokter')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
            <h2 class="text-white text-xl font-bold flex items-center">
                <i class="fas fa-user-edit mr-3"></i> Edit Profil
            </h2>
        </div>

        <div class="p-6 md:p-8">
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('dokter.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Foto Profil -->
                <div class="mb-6 text-center">
                    <div class="relative inline-block">
                        <div class="w-32 h-32 mx-auto rounded-full overflow-hidden border-4 border-teal-200">
                            <img id="preview" src="{{ $user->foto_url }}" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <label for="foto" class="absolute bottom-0 right-0 bg-teal-600 text-white p-2 rounded-full cursor-pointer hover:bg-teal-700 shadow-lg">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" id="foto" name="foto" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maks 2MB</p>
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500" required>
                </div>

                <!-- Nomor WhatsApp -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nomor WhatsApp</label>
                    <input type="text" name="nomor_wa" value="{{ old('nomor_wa', $user->nomor_wa) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500"
                           placeholder="08xxxxxxxxxx">
                </div>

                <hr class="my-6">

                <!-- Ubah Password -->
                <h3 class="text-lg font-semibold mb-4">Ubah Password</h3>
                <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password Saat Ini</label>
                    <input type="password" name="current_password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru</label>
                        <input type="password" name="new_password" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                </div>

                <div class="flex justify-between items-center mt-8">
                    <a href="{{ route('dokter.profile.index') }}" 
                       class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition shadow-md">
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