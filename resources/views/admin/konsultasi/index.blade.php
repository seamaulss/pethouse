@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-10" data-aos="fade-down">
        <h1 class="text-4xl font-bold text-gray-800">Manajemen Antrean</h1>
        <p class="text-lg text-gray-600 mt-2 italic">Pantau status kunjungan klinik LARAPetHouse hari ini.</p>
    </div>

    {{-- Alert Success & WA Notif --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-2xl flex justify-between items-center" data-aos="fade-up">
        <span><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
        @if(session('wa_link'))
        <a href="{{ session('wa_link') }}" target="_blank" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-emerald-700 transition">
            <i class="fab fa-whatsapp mr-1"></i> Kirim Notifikasi WA
        </a>
        @endif
    </div>
    @endif

    {{-- Filter & Search Section (Sinkron dengan Logika Controller Baru) --}}
    <div class="mb-8 bg-white p-5 rounded-3xl border border-gray-100 shadow-sm" data-aos="fade-up">
        <form method="GET" action="{{ route('admin.konsultasi.index') }}" class="flex flex-wrap lg:flex-nowrap gap-4 items-center">
            {{-- Keep status filter if active --}}
            @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="relative flex-grow">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari kode, nama pemilik, atau hewan..."
                    class="w-full pl-10 pr-10 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 text-sm transition-all">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                @if(request('search'))
                <a href="{{ route('admin.konsultasi.index', request()->except('search')) }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                    <i class="fas fa-times-circle"></i>
                </a>
                @endif
            </div>

            <div class="flex items-center gap-2 bg-gray-50 px-4 py-3 rounded-2xl border border-gray-100">
                <i class="fas fa-calendar-alt text-gray-400"></i>
                <input type="date" name="date" value="{{ request('date') }}"
                    class="bg-transparent border-none focus:ring-0 text-sm text-gray-600 font-medium p-0">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-teal-600 text-white px-6 py-3 rounded-2xl hover:bg-teal-700 font-bold transition shadow-lg shadow-teal-200 active:scale-95">
                    Filter
                </button>
                <a href="{{ route('admin.konsultasi.export-pdf', request()->all()) }}"
                    target="_blank"
                    class="bg-white text-red-600 border border-red-200 px-5 py-2.5 rounded-lg hover:bg-red-50 text-sm font-bold transition flex items-center gap-2 shadow-sm">
                    <i class="fas fa-file-pdf"></i> PDF
                </a>
            </div>
        </form>
    </div>

    {{-- Status Tabs (Pills) --}}
    <div class="mb-6 flex flex-wrap gap-2" data-aos="fade-up">
        <a href="{{ route('admin.konsultasi.index', request()->except('status')) }}"
            class="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition {{ !request('status') ? 'bg-gray-800 text-white shadow-lg' : 'bg-white text-gray-500 border border-gray-100 hover:bg-gray-50' }}">
            Semua
        </a>
        @foreach(['pending' => 'Menunggu', 'diterima' => 'Diterima', 'selesai' => 'Selesai'] as $key => $label)
        <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}"
            class="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-wider transition {{ request('status') == $key ? 'bg-teal-600 text-white shadow-lg' : 'bg-white text-gray-500 border border-gray-100 hover:text-teal-600' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    {{-- Table Section --}}
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100" data-aos="fade-up">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Kode</th>
                        <th class="px-6 py-4">Pemilik & Hewan</th>
                        <th class="px-6 py-4">Jadwal</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($konsultasi as $item)
                    <tr class="hover:bg-teal-50/30 transition">
                        <td class="px-6 py-4 font-mono text-teal-600 font-bold">#{{ $item->kode_konsultasi }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800">{{ $item->nama_pemilik }}</div>
                            <div class="text-xs text-gray-500">{{ $item->nama_hewan }} ({{ $item->jenis_hewan }})</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-700">{{ $item->tanggal_janji->format('d/m/Y') }}</div>
                            <div class="text-xs text-pink-500 font-bold">{{ date('H:i', strtotime($item->jam_janji)) }} WIB</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $item->status_class }}">
                                {{ $item->status_label ?? $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <a href="{{ route('admin.konsultasi.show', $item->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition inline-block" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.konsultasi.edit', $item->id) }}" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition inline-block" title="Ubah Status">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.konsultasi.destroy', $item->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Hapus antrean ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-gray-400 italic text-lg">
                            <i class="fas fa-inbox text-4xl mb-4 block"></i> Belum ada data konsultasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-6 bg-gray-50 border-t border-gray-100">
            {{ $konsultasi->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection