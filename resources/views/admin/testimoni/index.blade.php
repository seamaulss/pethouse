@extends('admin.layout')

@section('title', 'Manajemen Testimoni')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-10">

    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-comment-dots text-teal"></i>
            Manajemen Testimoni
        </h1>
        <p class="text-gray-600 mt-2">
            Kelola testimoni pelanggan PetHouse
        </p>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="p-4 bg-green-100 border border-green-400 text-green-800 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-red-100 border border-red-400 text-red-800 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b">
            <h2 class="text-xl font-semibold text-gray-800">
                {{ request()->has('edit') ? 'Edit Testimoni' : 'Tambah Testimoni Baru' }}
            </h2>
        </div>

        <form class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6"
              method="POST"
              action="{{ route('admin.testimoni.store') }}"
              enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $edit_data->id ?? '' }}">

            <!-- Nama Pemilik -->
            <div>
                <label class="font-medium text-gray-700">Nama Pemilik *</label>
                <input type="text" name="nama_pemilik" required
                       value="{{ old('nama_pemilik', $edit_data->nama_pemilik ?? '') }}"
                       class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-teal focus:border-teal">
            </div>

            <!-- Nama Hewan -->
            <div>
                <label class="font-medium text-gray-700">Nama Hewan</label>
                <input type="text" name="nama_hewan"
                       value="{{ old('nama_hewan', $edit_data->nama_hewan ?? '') }}"
                       class="mt-1 w-full border rounded-lg px-3 py-2">
            </div>

            <!-- Jenis Hewan -->
            <div>
                <label class="font-medium text-gray-700">Jenis Hewan</label>
                <select name="jenis_hewan"
                        class="mt-1 w-full border rounded-lg px-3 py-2">
                    <option value="">-- Pilih --</option>
                    @foreach($jenisHewanList as $jenis)
                        <option value="{{ $jenis->nama }}"
                            {{ old('jenis_hewan', $edit_data->jenis_hewan ?? '') == $jenis->nama ? 'selected' : '' }}>
                            {{ $jenis->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Rating -->
            <div>
                <label class="font-medium text-gray-700">Rating</label>
                <select name="rating"
                        class="mt-1 w-full border rounded-lg px-3 py-2">
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}"
                            {{ old('rating', $edit_data->rating ?? 5) == $i ? 'selected' : '' }}>
                            {{ str_repeat('⭐', $i) }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Testimoni -->
            <div class="md:col-span-2">
                <label class="font-medium text-gray-700">Testimoni *</label>
                <textarea name="isi_testimoni" rows="3" required
                          class="mt-1 w-full border rounded-lg px-3 py-2">{{ old('isi_testimoni', $edit_data->isi_testimoni ?? '') }}</textarea>
            </div>

            <!-- Foto -->
            <div>
                <label class="font-medium text-gray-700">Foto Hewan</label>
                <input type="file" name="foto_hewan"
                       class="mt-1 w-full text-sm text-gray-600">
                @if(isset($edit_data) && $edit_data->foto_hewan)
                    <img src="{{ asset('storage/testimoni/'.$edit_data->foto_hewan) }}"
                         class="mt-2 w-24 h-24 object-cover rounded">
                @endif
            </div>

            <!-- Status -->
            <div>
                <label class="font-medium text-gray-700">Status</label>
                <select name="status"
                        class="mt-1 w-full border rounded-lg px-3 py-2">
                    <option value="aktif" {{ old('status', $edit_data->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $edit_data->status ?? '') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <!-- Button -->
            <div class="md:col-span-2 flex gap-3">
                <button class="bg-teal text-white px-6 py-2 rounded-lg hover:bg-teal-700">
                    {{ isset($edit_data) ? 'Perbarui' : 'Simpan' }}
                </button>

                @if(isset($edit_data))
                    <a href="{{ route('admin.testimoni.index') }}"
                       class="px-6 py-2 rounded-lg bg-gray-200 hover:bg-gray-300">
                        Batal
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b">
            <h2 class="text-xl font-semibold text-gray-800">Daftar Testimoni</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3">Pemilik</th>
                        <th class="px-4 py-3">Hewan</th>
                        <th class="px-4 py-3">Testimoni</th>
                        <th class="px-4 py-3 text-center">Rating</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($testimoni as $row)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $row->nama_pemilik }}</td>
                            <td class="px-4 py-3">{{ $row->nama_hewan ?? '-' }} ({{ $row->jenis_hewan ?? '-' }})</td>
                            <td class="px-4 py-3">{{ Str::limit($row->isi_testimoni, 40) }}</td>
                            <td class="px-4 py-3 text-center">{{ str_repeat('⭐', $row->rating) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $row->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($row->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-3">
                                    <a href="{{ route('admin.testimoni.index', ['edit'=>$row->id]) }}"
                                       class="text-blue-600 hover:text-blue-800">Edit</a>
                                    <form method="POST"
                                          action="{{ route('admin.testimoni.destroy',$row->id) }}"
                                          onsubmit="return confirm('Hapus testimoni ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                Belum ada testimoni
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
