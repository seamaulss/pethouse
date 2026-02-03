@extends('admin.layout')

@section('title', 'Master Kegiatan')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-tasks text-teal"></i>
            Master Kegiatan
        </h1>
        <p class="text-gray-600 mt-2">
            Kelola daftar kegiatan layanan yang tersedia di PetHouse
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
        <a href="{{ route('admin.master-kegiatan.create') }}"
           class="inline-flex items-center gap-2 bg-teal text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
            <i class="fas fa-plus-circle"></i>
            Tambah Kegiatan
        </a>
    </div>

    <!-- Tabel -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Kegiatan</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">No</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Nama Kegiatan</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Deskripsi</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Icon</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Warna</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Urutan</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse($kegiatan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 text-center">{{ $loop->iteration }}</td>

                            <td class="px-4 py-4 font-medium text-gray-800">
                                {{ $item->nama_kegiatan }}
                            </td>

                            <td class="px-4 py-4 text-gray-600">
                                {{ $item->deskripsi }}
                            </td>

                            <td class="px-4 py-4 text-center">
                                @if($item->icon)
                                    <i class="fas fa-{{ $item->icon }} text-lg text-gray-700"></i>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-4 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-{{ $item->warna }}-100 text-{{ $item->warna }}-800">
                                    {{ ucfirst($item->warna) }}
                                </span>
                            </td>

                            <td class="px-4 py-4 text-center">
                                {{ $item->urutan }}
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
                                    <a href="{{ route('admin.master-kegiatan.edit', $item->id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.master-kegiatan.destroy', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
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
                            <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2 opacity-30"></i>
                                <p>Belum ada data kegiatan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back -->
    <div class="mt-6">
        <a href="{{ route('admin.dashboard') }}"
           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Dashboard
        </a>
    </div>

</div>
@endsection
