@extends('admin.layout')

@section('title', 'Master Jenis Hewan')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-paw text-teal"></i>
            Master Jenis Hewan
        </h1>
        <p class="text-gray-600 mt-2">
            Kelola daftar jenis hewan yang tersedia di PetHouse
        </p>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-800 rounded-lg">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Tombol Tambah -->
    <div class="mb-6 text-right">
        <a href="{{ route('admin.jenis-hewan.create') }}"
           class="inline-flex items-center gap-2 bg-teal text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
            <i class="fas fa-plus-circle"></i>
            Tambah Jenis Hewan
        </a>
    </div>

    <!-- Tabel -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Jenis Hewan</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">No</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Nama Jenis Hewan</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($jenisHewan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 text-center">{{ $loop->iteration }}</td>

                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $nama = strtolower($item->nama);
                                        $icon = 'fas fa-paw text-gray-500';

                                        if (str_contains($nama, 'kucing')) $icon = 'fas fa-cat text-pink-600';
                                        elseif (str_contains($nama, 'anjing')) $icon = 'fas fa-dog text-teal';
                                        elseif (str_contains($nama, 'kelinci')) $icon = 'fas fa-rabbit text-amber';
                                        elseif (str_contains($nama, 'burung')) $icon = 'fas fa-dove text-sky-600';
                                        elseif (str_contains($nama, 'ikan')) $icon = 'fas fa-fish text-blue-600';
                                    @endphp

                                    <i class="{{ $icon }}"></i>
                                    <span class="font-medium">{{ $item->nama }}</span>
                                </div>
                            </td>

                            <td class="px-4 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $item->aktif === 'ya'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-red-100 text-red-800' }}">
                                    {{ $item->aktif === 'ya' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-center">
                                <div class="flex justify-center gap-4">
                                    <a href="{{ route('admin.jenis-hewan.edit', $item->id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.jenis-hewan.destroy', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus {{ $item->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800 font-medium">
                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2 opacity-30"></i>
                                <p>Belum ada data jenis hewan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Dashboard
        </a>
    </div>

</div>
@endsection
