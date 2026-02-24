@extends('admin.layouts.app')

@section('title', 'Manajemen Kapasitas')

@section('content')
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4" data-aos="fade-down">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Kapasitas Kandang</h1>
        <p class="text-gray-500">Atur batas maksimal kuota penitipan hewan</p>
    </div>
    <button onclick="toggleModal('modalTambah')" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-2xl shadow-lg transition-all flex items-center gap-2">
        <i class="fas fa-plus"></i>
        <span>Tambah Pengaturan</span>
    </button>
</div>

<div class="bg-white rounded-3xl shadow-xl overflow-hidden" data-aos="fade-up">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-gray-600 text-sm font-bold uppercase tracking-wider">
                    <th class="py-5 px-6">Layanan</th>
                    <th class="py-5 px-6">Jenis Hewan</th>
                    <th class="py-5 px-6">Ukuran</th>
                    <th class="py-5 px-6">Max Slot</th>
                    <th class="py-5 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($kapasitas as $row)
                <tr class="hover:bg-teal-50/30 transition-colors">
                    <td class="py-5 px-6 font-medium text-gray-700">{{ $row->nama_layanan }}</td>

                    <td class="py-5 px-6">
                        <span class="px-3 py-1 rounded-lg text-xs font-bold {{ $row->jenis_hewan == 'Kucing' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600' }}">
                            {{ $row->jenis_hewan }}
                        </span>
                    </td>
                    <td class="py-5 px-6 text-gray-600">{{ $row->ukuran_hewan }}</td>
                    <td class="py-5 px-6">
                        <span class="text-lg font-bold text-teal-600">{{ $row->max_kapasitas }}</span>
                        <span class="text-xs text-gray-400"> Kandang</span>
                    </td>
                    <td class="py-5 px-6">
                        <div class="flex justify-center items-center gap-3">
                            <button onclick="openEditModal({{ json_encode($row) }})" class="text-amber-500 hover:bg-amber-50 w-10 h-10 rounded-xl flex items-center justify-center transition-all">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.kapasitas.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Hapus pengaturan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-pink-500 hover:bg-pink-50 w-10 h-10 rounded-xl flex items-center justify-center transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-10 text-center text-gray-400 italic">Belum ada data kapasitas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambah" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" onclick="toggleModal('modalTambah')"></div>
        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('admin.kapasitas.store') }}" method="POST" class="p-8">
                @csrf
                <h3 class="text-2xl font-bold text-gray-800 mb-6">Tambah Kapasitas</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Layanan</label>
                        <select name="layanan_id" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-teal-500 focus:border-teal-500" required>
                            @foreach($layanan as $l)
                            <option value="{{ $l->id }}">{{ $l->nama_layanan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Hewan</label>
                            <select name="jenis_hewan" class="w-full px-4 py-3 rounded-xl border-gray-200" required>
                                <option value="Kucing">Kucing</option>
                                <option value="Anjing">Anjing</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ukuran</label>
                            <select name="ukuran_hewan" class="w-full px-4 py-3 rounded-xl border-gray-200" required>
                                <option value="Kecil">Kecil</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Besar">Besar</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Maksimal Slot Kandang</label>
                        <input type="number" name="max_kapasitas" class="w-full px-4 py-3 rounded-xl border-gray-200" placeholder="Contoh: 10" required min="1">
                    </div>
                </div>
                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="toggleModal('modalTambah')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl font-semibold">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-teal-600 text-white rounded-xl font-semibold shadow-lg shadow-teal-500/30">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75" onclick="toggleModal('modalEdit')"></div>
        <div class="inline-block bg-white rounded-3xl shadow-2xl transform transition-all sm:max-w-md w-full p-8 text-left">
            <form id="formEdit" method="POST">
                @csrf @method('PUT')
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Edit Kapasitas</h3>
                <p id="editDesc" class="text-sm text-gray-500 mb-6"></p>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Maksimal Slot Kandang</label>
                    <input type="number" name="max_kapasitas" id="edit_max" class="w-full px-4 py-3 rounded-xl border-gray-200" required min="1">
                </div>
                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="toggleModal('modalEdit')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl font-semibold">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-amber-500 text-white rounded-xl font-semibold">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        modal.classList.toggle('hidden');
    }

    function openEditModal(data) {
        const form = document.getElementById('formEdit');
        form.action = `/admin/kapasitas/${data.id}`;
        document.getElementById('edit_max').value = data.max_kapasitas;
        document.getElementById('editDesc').innerText = `${data.layanan.nama_layanan} - ${data.jenis_hewan} (${data.ukuran_hewan})`;
        toggleModal('modalEdit');
    }
</script>
@endpush