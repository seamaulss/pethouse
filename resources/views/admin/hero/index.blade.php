@extends('admin.layout')

@section('title', 'Hero Slider')

@section('content')
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Hero Slider</h2>
            <p class="text-sm text-gray-600">Kelola slide pada bagian hero website</p>
        </div>
        <a href="{{ route('admin.hero.create') }}"
           class="inline-flex items-center justify-center px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition shadow-sm">
            + Tambah Slide
        </a>
    </header>

    <!-- Table Content -->
    <div class="p-6">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjudul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($slides as $slide)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($slide->gambar)
                                        <img src="{{ asset('storage/hero/' . $slide->gambar) }}"
                                             alt="Slide {{ $loop->iteration }}"
                                             class="w-24 h-14 object-cover rounded border">
                                    @else
                                        <span class="text-gray-400">–</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                    {{ $slide->judul }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $slide->subjudul }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ $slide->urutan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.hero.edit', $slide) }}"
                                       class="text-yellow-600 hover:text-yellow-800 font-medium mr-3">Ubah</a>
                                    <form action="{{ route('admin.hero.destroy', $slide) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus slide ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada slide pada hero.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection