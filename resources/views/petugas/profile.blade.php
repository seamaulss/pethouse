@extends('petugas.layouts.app')

@section('title', 'Profil Petugas')

@section('content')
<div class="max-w-4xl mx-auto p-6" data-aos="fade-up">
    <!-- Header -->
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Profil Petugas
    </h1>

    <!-- Info Petugas -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <h2 class="text-xl font-semibold mb-4">Data Petugas</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Username</p>
                <p class="font-medium">
                    {{ $user->username }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Role</p>
                <p class="font-medium capitalize">
                    {{ $user->role }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium">
                    {{ $user->email ?? '-' }}
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Nomor WhatsApp</p>
                <p class="font-medium">
                    {{ $user->nomor_wa ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Riwayat Perawatan -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Riwayat Perawatan</h2>

        @if($riwayat->count() === 0)
            <div class="text-center py-8 text-gray-500">
                Belum ada riwayat perawatan.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                Kode Booking
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                Hewan
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                Tanggal Masuk
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                Selesai
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200">
                        @foreach($riwayat as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 text-sm font-medium">
                                    {{ $row->booking->kode_booking }}
                                </td>

                                <td class="px-4 py-2 text-sm">
                                    {{ $row->booking->nama_hewan }}<br>
                                    <span class="text-xs text-gray-500">
                                        {{ $row->booking->jenis_hewan }}
                                    </span>
                                </td>

                                <td class="px-4 py-2 text-sm">
                                    {{ \Carbon\Carbon::parse($row->booking->tanggal_masuk)->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-2 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Back -->
    <div class="mt-6">
        <a href="{{ route('petugas.dashboard') }}" class="text-teal-600 hover:underline">
            &larr; Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection