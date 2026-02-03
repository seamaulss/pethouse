@extends('admin.layout')

@section('title', 'Galeri')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <i class="fas fa-images text-teal"></i>
                Galeri
            </h1>
            <p class="text-gray-600 mt-2">
                Kelola foto-foto galeri PetHouse
            </p>
        </div>

        <a href="{{ route('admin.galeri.create') }}"
           class="inline-flex items-center gap-2 bg-teal text-white px-6 py-3 rounded-lg font-semibold hover:bg-teal-700 transition">
            <i class="fas fa-plus-circle"></i>
            Tambah Foto
        </a>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Galeri -->
    <div class="bg-white rounded-lg shadow p-6">
        @if($galeri->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($galeri as $item)
                    <div class="rounded-lg overflow-hidden border hover:shadow-lg transition">
                        <img src="{{ asset('storage/'.$item->foto) }}"
                             alt="{{ $item->keterangan }}"
                             class="w-full h-48 object-cover">

                        <div class="p-4 space-y-3">
                            <p class="text-gray-700 text-sm">
                                {{ Str::limit($item->keterangan, 60) }}
                            </p>

                            <div class="flex justify-between items-center">
                                <a href="{{ route('admin.galeri.edit', $item) }}"
                                   class="text-blue-600 hover:text-blue-800 font-medium">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>

                                <form action="{{ route('admin.galeri.destroy', $item) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus foto ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:text-red-800 font-medium">
                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 text-gray-500">
                <i class="fas fa-images text-4xl mb-4 opacity-30"></i>
                <p>Belum ada foto di galeri</p>
            </div>
        @endif
    </div>

</div>
@endsection
