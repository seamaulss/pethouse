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
        transform: translateX(8px);
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

    /* Table styling */
    .table-container {
        min-width: 1200px;
        overflow-x: auto;
    }

    .table-header {
        background: linear-gradient(to right, rgba(13, 148, 136, 0.05), rgba(244, 63, 94, 0.05));
        backdrop-filter: blur(8px);
    }

    .table-cell {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50/50 to-pink-50/50">
    
    <!-- Header -->
    <div class="bg-white border-b border-teal-100 px-6 py-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Layanan</h1>
                <p class="text-gray-600 mt-1">Kelola harga layanan per jenis hewan</p>
            </div>
            <a href="{{ route('admin.layanan.create') }}" 
               class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-teal-600 to-teal-500 
                      text-white font-semibold rounded-lg shadow-md hover:shadow-lg 
                      hover:scale-[1.02] transition-all duration-200">
                <i class="fas fa-plus"></i>
                Tambah Layanan
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-4">
        <div class="bg-white rounded-xl shadow-lg border border-teal-100 overflow-hidden">
            
            <!-- Table Wrapper -->
            <div class="table-container">
                <table class="w-full">
                    <!-- Table Header -->
                    <thead class="table-header border-b border-teal-200/50">
                        <tr>
                            <th class="table-cell text-left text-xs font-bold text-teal-700 uppercase tracking-wider">No</th>
                            <th class="table-cell text-left text-xs font-bold text-teal-700 uppercase tracking-wider">Gambar</th>
                            <th class="table-cell text-left text-xs font-bold text-teal-700 uppercase tracking-wider">Nama Layanan</th>
                            <th class="table-cell text-left text-xs font-bold text-teal-700 uppercase tracking-wider">Deskripsi</th>
                            
                            <!-- Harga per jenis hewan -->
                            @foreach($jenisHewan as $jh)
                                <th class="px-4 py-4 text-left text-xs font-bold text-pink-600 uppercase tracking-wider whitespace-nowrap min-w-[120px]">
                                    {{ $jh->nama }}
                                </th>
                            @endforeach
                            
                            <th class="table-cell text-left text-xs font-bold text-amber-600 uppercase tracking-wider min-w-[220px]">Aksi</th>
                        </tr>
                    </thead>

                    <!-- Table Body -->
                    <tbody class="divide-y divide-teal-100/30">
                        @foreach($layanan as $item)
                            <tr class="table-row hover:bg-teal-50/50">
                                <td class="table-cell font-semibold text-teal-700">{{ $loop->iteration }}</td>
                                
                                <td class="table-cell">
                                    @if($item->gambar)
                                        <div class="w-14 h-14 rounded-lg overflow-hidden shadow">
                                            <img src="{{ Storage::url('layanan/'.$item->gambar) }}"
                                                 alt="{{ $item->nama_layanan }}"
                                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                        </div>
                                    @else
                                        <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </td>
                                
                                <td class="table-cell">
                                    <div class="font-bold text-gray-800">{{ $item->nama_layanan }}</div>
                                </td>
                                
                                <td class="table-cell">
                                    <div class="text-sm text-gray-600 line-clamp-2 max-w-xs">
                                        {{ $item->deskripsi }}
                                    </div>
                                </td>
                                
                                <!-- Harga per jenis hewan -->
                                @foreach($jenisHewan as $jh)
                                    <td class="px-4 py-5 text-right">
                                        @php
                                            $harga = $item->hargas->where('jenis_hewan_id', $jh->id)->first();
                                        @endphp
                                        @if($harga)
                                            <div class="font-bold text-pink-600">
                                                Rp {{ number_format($harga->harga_per_hari, 0, ',', '.') }}
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-sm">–</span>
                                        @endif
                                    </td>
                                @endforeach
                                
                                <td class="table-cell">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.layanan.edit', $item->id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-amber-500 to-amber-400 
                                                  text-white text-xs font-medium rounded-lg hover:shadow hover:scale-105 
                                                  transition-all duration-200">
                                            <i class="fas fa-edit mr-1.5 text-xs"></i> Ubah
                                        </a>
                                        
                                        <a href="{{ route('admin.layanan.atur-harga', $item->id) }}" 
                                           class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-teal-600 to-teal-500 
                                                  text-white text-xs font-medium rounded-lg hover:shadow hover:scale-105 
                                                  transition-all duration-200">
                                            <i class="fas fa-coins mr-1.5 text-xs"></i> Harga
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.layanan.destroy', $item->id) }}" 
                                              class="inline-block" onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-500 to-pink-500 
                                                           text-white text-xs font-medium rounded-lg hover:shadow hover:scale-105 
                                                           transition-all duration-200">
                                                <i class="fas fa-trash mr-1.5 text-xs"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Empty State -->
            @if($layanan->isEmpty())
                <div class="py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-teal-100 to-pink-100 rounded-2xl 
                                flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-paw text-3xl text-teal-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Belum ada layanan</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">
                        Mulai tambahkan layanan pertama untuk PetHouse Anda.
                    </p>
                    <a href="{{ route('admin.layanan.create') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-500 
                              text-white font-medium rounded-lg shadow-md hover:shadow-lg hover:scale-[1.02] 
                              transition-all duration-200">
                        <i class="fas fa-plus"></i>
                        Tambah Layanan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add hover effects for table rows
    document.querySelectorAll('.table-row').forEach(row => {
        row.addEventListener('mouseenter', () => {
            row.style.transform = 'translateX(4px)';
        });
        row.addEventListener('mouseleave', () => {
            row.style.transform = 'translateX(0)';
        });
    });
</script>
@endsection