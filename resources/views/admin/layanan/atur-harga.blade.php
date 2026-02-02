@extends('admin.layout')

@section('title', 'Atur Harga Layanan')

@section('styles')
<style>
    .price-card {
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s;
    }
    
    .price-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .price-input {
        width: 100%;
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    
    .price-input:focus {
        outline: none;
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
    }
    
    .currency-label {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .price-field {
        position: relative;
    }
    
    .price-field input {
        padding-left: 2.5rem;
    }
    
    .animal-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    
    .dog-icon {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .cat-icon {
        background-color: #fce7f3;
        color: #9d174d;
    }
    
    .bird-icon {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .rabbit-icon {
        background-color: #dcfce7;
        color: #166534;
    }
    
    .reptile-icon {
        background-color: #f3e8ff;
        color: #6b21a8;
    }
</style>
@endsection

@section('content')
<div class="flex min-h-screen">
    @include('admin.layouts.navbar')

    <!-- Main Content -->
    <main class="flex-1 bg-gray-50">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">Atur Harga Layanan</h2>
                    <p class="text-gray-600 mt-1">{{ $layanan->nama_layanan }}</p>
                </div>
                <a href="{{ route('admin.layanan.index') }}" 
                   class="flex items-center text-teal-600 hover:text-teal-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i> 
                    Kembali
                </a>
            </div>
        </header>

        <!-- Form Area -->
        <div class="p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-lg text-sm border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-lg text-sm border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Layanan Info -->
            <div class="mb-8 p-6 bg-white rounded-xl shadow border border-gray-200">
                <div class="flex items-start space-x-4">
                    @if($layanan->gambar)
                        <img src="{{ Storage::url('layanan/' . $layanan->gambar) }}" 
                             alt="{{ $layanan->nama_layanan }}"
                             class="w-24 h-24 rounded-lg object-cover">
                    @else
                        <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-2xl"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $layanan->nama_layanan }}</h3>
                        @if($layanan->deskripsi)
                            <p class="text-gray-600 mt-2">{{ $layanan->deskripsi }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Harga Form -->
            <div class="bg-white rounded-xl shadow border border-gray-200 p-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Harga per Hari per Jenis Hewan</h3>
                
                <form method="post" action="{{ route('admin.layanan.simpan-harga', $layanan->id) }}" class="space-y-8">
                    @csrf
                    
                    @foreach($jenisHewan as $jenis)
                        <div class="price-card p-6">
                            <div class="flex items-center mb-4">
                                <div class="animal-icon {{ strtolower($jenis->nama) }}-icon">
                                    @if(strtolower($jenis->nama) == 'anjing')
                                        <i class="fas fa-dog text-xl"></i>
                                    @elseif(strtolower($jenis->nama) == 'kucing')
                                        <i class="fas fa-cat text-xl"></i>
                                    @elseif(strtolower($jenis->nama) == 'burung')
                                        <i class="fas fa-dove text-xl"></i>
                                    @elseif(strtolower($jenis->nama) == 'kelinci')
                                        <i class="fas fa-paw text-xl"></i>
                                    @else
                                        <i class="fas fa-paw text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $jenis->nama }}</h4>
                                    <p class="text-sm text-gray-500">Harga per hari</p>
                                </div>
                            </div>
                            
                            @php
                                // Ambil harga dari collection $hargas yang sudah di-keyBy
                                $harga = $hargas->get($jenis->id);
                                $value = $harga ? $harga->harga_per_hari : old('harga.' . $jenis->id);
                            @endphp
                            
                            <div class="price-field">
                                <span class="currency-label">Rp</span>
                                <input type="number" 
                                       name="harga[{{ $jenis->id }}]" 
                                       value="{{ $value }}"
                                       min="0"
                                       step="1000"
                                       placeholder="Masukkan harga"
                                       class="price-input">
                            </div>
                            
                            @error('harga.' . $jenis->id)
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.layanan.index') }}" 
                           class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 bg-teal-600 text-white font-medium rounded-lg hover:bg-teal-700 transition-colors">
                            Simpan Harga
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection