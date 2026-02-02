@extends('admin.layout')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Edit Foto Galeri</h1>
        <a href="{{ route('admin.galeri.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.galeri.update', $galeri->id) }}" 
                  method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" 
                                      rows="3">{{ old('keterangan', $galeri->keterangan) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                            
                            <div class="mt-3">
                                <p>Foto saat ini:</p>
                                <img src="{{ asset('storage/' . $galeri->foto) }}" 
                                     alt="{{ $galeri->keterangan }}" 
                                     class="img-fluid" style="max-height: 200px;">
                            </div>
                            
                            <div class="mt-3">
                                <img id="preview" src="#" alt="Preview" 
                                     class="img-fluid d-none" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview image
    document.getElementById('foto').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
        }
    });
</script>
@endpush
@endsection