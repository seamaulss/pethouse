{{-- resources/views/admin/testimoni/index.blade.php --}}
@extends('admin.layouts.app')

@section('content')
<div class="flex min-h-screen">
    <!-- Sidebar -->
    @include('admin.layout')

    <!-- Main Content -->
    <main class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-semibold text-gray-800">Manajemen Testimoni</h2>
            <p class="text-sm text-gray-600 mt-1">
                {{ request()->has('edit') ? 'Edit testimoni yang ada' : 'Tambah testimoni baru' }}
            </p>
        </header>

        <!-- Content -->
        <div class="p-6 space-y-6">
            <!-- Alert Messages -->
            @if(session('success'))
            <div class="bg-green-50 text-green-800 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="bg-red-50 text-red-800 px-4 py-3 rounded-md">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form Tambah/Edit -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">
                    {{ request()->has('edit') ? 'Edit Testimoni' : 'Tambah Testimoni Baru' }}
                </h3>

                <form method="POST" action="{{ route('admin.testimoni.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $edit_data->id ?? '' }}">

                    <!-- Nama Pemilik -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Nama Pemilik <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_pemilik"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            required
                            value="{{ old('nama_pemilik', $edit_data->nama_pemilik ?? '') }}">
                    </div>

                    <!-- Nama Hewan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Hewan</label>
                        <input type="text" name="nama_hewan"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            value="{{ old('nama_hewan', $edit_data->nama_hewan ?? '') }}">
                    </div>

                    <!-- Jenis Hewan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Jenis Hewan</label>
                        <select name="jenis_hewan"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="">-- Pilih --</option>
                            @foreach($jenisHewanList as $jenis)
                            <option value="{{ $jenis->nama }}" {{ (old('jenis_hewan', $edit_data->jenis_hewan ?? '') == $jenis->nama) ? 'selected' : '' }}>
                                {{ $jenis->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Isi Testimoni -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">
                            Testimoni <span class="text-red-500">*</span>
                        </label>
                        <textarea name="isi_testimoni" rows="3"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                            required>{{ old('isi_testimoni', $edit_data->isi_testimoni ?? '') }}</textarea>
                    </div>

                    <!-- Rating -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Rating (1–5)</label>
                        <select name="rating"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ (old('rating', $edit_data->rating ?? 5) == $i) ? 'selected' : '' }}>
                                {{ str_repeat('⭐', $i) }}
                            </option>
                            @endfor
                        </select>
                    </div>

                    <!-- Foto Hewan -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Foto Hewan (opsional)</label>
                        <input type="file" name="foto_hewan" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">

                        @if(isset($edit_data) && $edit_data->foto_hewan)
                        <div class="mt-2">
                            <img src="{{ asset('storage/testimoni/' . $edit_data->foto_hewan) }}"
                                alt="Foto hewan"
                                class="w-24 h-24 object-cover rounded">
                        </div>
                        @endif
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            <option value="aktif" {{ (old('status', $edit_data->status ?? 'aktif') == 'aktif') ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ (old('status', $edit_data->status ?? '') == 'nonaktif') ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 text-dark rounded-md hover:bg-primary-700 transition">
                            {{ isset($edit_data) ? 'Perbarui' : 'Simpan' }}
                        </button>

                        @if(isset($edit_data))
                        <a href="{{ route('admin.testimoni.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                            Batal
                        </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Daftar Testimoni -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800">Daftar Testimoni</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hewan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Testimoni</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($testimoni as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $row->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $row->nama_pemilik }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ $row->nama_hewan ?? '-' }} ({{ $row->jenis_hewan ?? '-' }})
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ Str::limit($row->isi_testimoni, 50) }}...
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ str_repeat('⭐', $row->rating) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($row->status === 'aktif')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                    @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Nonaktif
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.testimoni.index', ['edit' => $row->id]) }}"
                                        class="text-blue-600 hover:text-blue-800 mr-3">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.testimoni.destroy', $row->id) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Hapus testimoni ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada testimoni.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection