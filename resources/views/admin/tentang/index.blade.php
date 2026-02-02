

@extends('admin.layout')

@section('title', 'Tentang Kami - PetHouse Admin')

@section('content')

<!-- Include Navbar -->
@include('admin.layouts.navbar')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tentang Kami</h1>
        <p class="text-gray-600 mt-2">Kelola konten untuk halaman "Tentang Kami" di website publik</p>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-700">Daftar Konten</h2>
            <p class="text-sm text-gray-500">Total: {{ $tentang->count() }} konten</p>
        </div>
        <a href="{{ route('admin.tentang.create') }}"
            class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition duration-300 flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Konten
        </a>
    </div>

    <!-- Konten -->
    @if($tentang->count() > 0)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Isi (Preview)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tentang as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->judul }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 max-w-xs truncate">
                                {{ Str::limit(strip_tags($item->isi), 80) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <!-- Di bagian tabel, perbaiki URL gambar -->
                            @if($item->gambar)
                            <img src="{{ url('storage/tentang/' . $item->gambar) }}"
                                alt="Gambar tentang"
                                class="w-16 h-12 object-cover rounded border">
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.tentang.edit', $item->id) }}"
                                    class="text-yellow-600 hover:text-yellow-900 flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.tentang.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus konten ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-900 flex items-center">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
        <div class="mx-auto w-24 h-24 mb-6 text-gray-300">
            <i class="fas fa-info-circle text-6xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada konten tentang kami</h3>
        <p class="text-gray-500 mb-6 max-w-md mx-auto">
            Mulai dengan menambahkan konten pertama Anda untuk ditampilkan di halaman "Tentang Kami"
        </p>
        <a href="{{ route('admin.tentang.create') }}"
            class="inline-flex items-center justify-center px-6 py-3 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition shadow-sm">
            <i class="fas fa-plus mr-2"></i> Tambah Konten Pertama
        </a>
    </div>
    @endif
</div>
@endsection