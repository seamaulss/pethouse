@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Galeri</h1>
        <a href="{{ route('admin.galeri.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Foto
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($galeri->count() > 0)
                <div class="row">
                    @foreach($galeri as $item)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card">
                            <img src="{{ asset('storage/' . $item->foto) }}" 
                                 class="card-img-top" 
                                 alt="{{ $item->keterangan }}"
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <p class="card-text">{{ Str::limit($item->keterangan, 50) }}</p>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.galeri.edit', $item) }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.galeri.destroy', $item) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Hapus foto ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-images fa-3x text-muted"></i>
                    <p class="mt-3">Belum ada foto di galeri</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection