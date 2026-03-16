@extends('admin.layout')

@section('title', 'Data Layanan - Admin')

@section('styles')
<style>
    body {
        background: linear-gradient(135deg, #faf9f6 0%, #f5f3ef 100%) !important;
    }

    .table-row {
        transition: all 0.3s ease;
    }

    .table-row:hover {
        background-color: rgba(13, 148, 136, 0.05);
    }

    .gradient-text {
        background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .table-container {
        min-width: 1000px;
        overflow-x: auto;
    }

    .table-header {
        background: linear-gradient(to right, rgba(13, 148, 136, 0.05), rgba(244, 63, 94, 0.05));
        backdrop-filter: blur(8px);
    }

    .table-cell {
        padding: 1rem 1.5rem;
    }

    /* Custom Scrollbar for Table */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #0d9488;
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50/50 to-pink-50/50">
    
    <div class="bg-white border-b border-teal-100 px-6 py-4 shadow-sm sticky top-0 z-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Layanan</h1>
                <p class="text-gray-600 mt-1">Kelola harga layanan per jenis hewan</p>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="relative hidden sm:block">
                    <input type="text" id="searchInput" placeholder="Cari layanan..." 
                           class="pl-10 pr-4 py-2 bg-gray-50 border border-teal-100 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none transition-all w-64">
                    <i class="fas fa-search absolute left-3 top-3 text-teal-400"></i>
                </div>

                <a href="{{ route('admin.layanan.create') }}" 
                   class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-600 to-teal-500 
                          text-white font-semibold rounded-lg shadow-md hover:shadow-lg 
                          hover:scale-[1.02] transition-all duration-200">
                    <i class="fas fa-plus"></i>
                    <span class="hidden sm:inline">Tambah Layanan</span>
                </a>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6">
        <div class="bg-white rounded-xl shadow-lg border border-teal-100 overflow-hidden">
            
            <div class="overflow-x-auto">
                <div class="table-container">
                    <table class="w-full" id="layananTable">
                        <thead class="table-header border-b border-teal-200/50">
                            <tr>
                                <th class="table-cell text-left text-xs font-bold text-teal-700 uppercase tracking-wider">No</th>
                                <th class="table-cell text-left text-xs font-bold text-teal-700 uppercase tracking-wider">Gambar</th>
                                <th class="table-cell text-left text-xs font-bold text-teal-700 uppercase tracking-wider">Nama Layanan</th>
                                <th class="table-cell text-left text-xs font-bold text-teal-700 uppercase tracking-wider">Deskripsi</th>
                                
                                @foreach($jenisHewan as $jh)
                                    <th class="px-4 py-4 text-center text-xs font-bold text-pink-600 uppercase tracking-wider whitespace-nowrap">
                                        {{ $jh->nama }}
                                    </th>
                                @endforeach
                                
                                <th class="table-cell text-center text-xs font-bold text-amber-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-teal-100/30">
                            @forelse($layanan as $item)
                                <tr class="table-row">
                                    <td class="table-cell font-semibold text-teal-700">{{ ($layanan->currentPage() - 1) * $layanan->perPage() + $loop->iteration }}</td>
                                    
                                    <td class="table-cell">
                                        @if($item->gambar)
                                            <div class="w-14 h-14 rounded-lg overflow-hidden shadow-sm border border-gray-100">
                                                <img src="{{ Storage::url('layanan/'.$item->gambar) }}"
                                                     alt="{{ $item->nama_layanan }}"
                                                     class="w-full h-full object-cover hover:scale-125 transition-transform duration-500">
                                            </div>
                                        @else
                                            <div class="w-14 h-14 bg-gray-50 rounded-lg flex items-center justify-center border border-dashed border-gray-200">
                                                <i class="fas fa-image text-gray-300"></i>
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <td class="table-cell">
                                        <div class="font-bold text-gray-800 service-name">{{ $item->nama_layanan }}</div>
                                    </td>
                                    
                                    <td class="table-cell">
                                        <div class="text-sm text-gray-600 line-clamp-2 max-w-xs">
                                            {{ $item->deskripsi }}
                                        </div>
                                    </td>
                                    
                                    @foreach($jenisHewan as $jh)
                                        <td class="px-4 py-5 text-center">
                                            @php
                                                $harga = $item->hargas->where('jenis_hewan_id', $jh->id)->first();
                                            @endphp
                                            @if($harga)
                                                <span class="inline-block px-3 py-1 bg-pink-50 text-pink-700 rounded-full text-xs font-bold ring-1 ring-inset ring-pink-600/20">
                                                    Rp {{ number_format($harga->harga_per_hari, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="text-gray-300 text-xs italic">N/A</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    
                                    <td class="table-cell">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('admin.layanan.edit', $item->id) }}" 
                                               class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                               title="Ubah Data">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <a href="{{ route('admin.layanan.atur-harga', $item->id) }}" 
                                               class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition-colors"
                                               title="Atur Harga">
                                                <i class="fas fa-tags"></i>
                                            </a>
                                            
                                            <form method="POST" action="{{ route('admin.layanan.destroy', $item->id) }}" 
                                                  class="inline-block delete-form">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this)"
                                                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 5 + count($jenisHewan) }}" class="py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-teal-50 text-teal-500 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-search text-3xl"></i>
                                            </div>
                                            <h3 class="text-lg font-bold text-gray-700">Tidak ada layanan ditemukan</h3>
                                            <p class="text-gray-500">Silakan tambah layanan baru atau ubah kata kunci pencarian Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($layanan->hasPages())
                <div class="bg-gray-50 px-6 py-4 border-t border-teal-100">
                    {{ $layanan->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fitur Pencarian Sederhana (Client-side)
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#layananTable tbody tr:not(:last-child)');
        
        rows.forEach(row => {
            let name = row.querySelector('.service-name').textContent.toLowerCase();
            if (name.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    // SweetAlert untuk Konfirmasi Hapus
    function confirmDelete(button) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data layanan yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0d9488',
            cancelButtonColor: '#f43f5e',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        })
    }
</script>
@endsection