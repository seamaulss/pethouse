@extends('dokter.layouts.app')

@section('title', 'Profil Dokter')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Profil Dokter</h1>
        <p class="text-gray-600 mt-2">Informasi dan pengaturan akun Anda</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-6">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
            <h2 class="text-white text-xl font-bold flex items-center">
                <i class="fas fa-user-md mr-3"></i> Data Diri
            </h2>
        </div>

        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <!-- Foto Profil -->
                <div class="flex-shrink-0 text-center">
                    <div class="relative w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden border-4 border-teal-200 shadow-md">
                        <img src="{{ $user->foto_url }}" alt="Foto Dokter" class="w-full h-full object-cover">
                    </div>
                    <a href="{{ route('dokter.profile.edit') }}"
                        class="mt-4 inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-5 py-2 rounded-lg transition shadow-md">
                        <i class="fas fa-camera"></i> Edit Profil
                    </a>
                </div>

                <!-- Data -->
                <div class="flex-1 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Username</p>
                            <p class="font-medium text-gray-800">{{ $user->username }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium text-gray-800">{{ $user->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nomor WhatsApp</p>
                            <p class="font-medium text-gray-800">{{ $user->nomor_wa ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Role</p>
                            <p class="font-medium capitalize text-gray-800">{{ $user->role }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Bergabung Sejak</p>
                            <p class="font-medium text-gray-800">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Singkat (Opsional) -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-5 rounded-xl border border-blue-200">
            <div class="flex items-center">
                <div class="p-3 bg-white rounded-lg shadow-sm mr-4">
                    <i class="fas fa-notes-medical text-blue-600 text-xl"></i>
                </div>
                
            </div>
        </div>
        <!-- bisa tambah statistik lain -->
    </div>
</div>
@endsection