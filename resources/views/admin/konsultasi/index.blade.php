@extends('admin.layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="mb-10" data-aos="fade-down">
        <h1 class="text-4xl font-bold text-gray-800">Manajemen Antrean</h1>
        <p class="text-lg text-gray-600 mt-2 italic">Pantau status kunjungan klinik PetHouse hari ini.</p>
    </div>

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
                            <div class="text-xs text-gray-500">{{ $item->jenis_hewan }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">{{ $item->tanggal_janji->format('d/m/Y') }}</div>
                            <div class="text-xs text-pink-500 font-medium">{{ date('H:i', strtotime($item->jam_janji)) }} WIB</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $item->status_class }}">
                                {{ $item->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-1">
                            <a href="{{ route('admin.konsultasi.show', $item->id) }}" class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.konsultasi.edit', $item->id) }}" class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition" title="Ubah Status">
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
        <div class="p-4 bg-gray-50">
            {{ $konsultasi->links() }}
        </div>
    </div>
</div>
@endsection