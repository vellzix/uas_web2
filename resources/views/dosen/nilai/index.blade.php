<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Nilai Mahasiswa') }}
        </h2>
    </x-slot>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jadwals as $jadwal)
                <div class="bg-gray-50 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-semibold mb-2">{{ $jadwal->matakuliah->nama }}</h3>
                    <p class="text-gray-600 mb-2">Kode: {{ $jadwal->matakuliah->kode }}</p>
                    <p class="text-gray-600 mb-2">SKS: {{ $jadwal->matakuliah->sks }}</p>
                    <p class="text-gray-600 mb-4">Jumlah Mahasiswa: {{ $jadwal->krs->count() }}</p>
                    <a href="{{ route('dosen.nilai.show', $jadwal->id) }}" 
                       class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">
                        Input Nilai
                    </a>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-gray-500">
                    Tidak ada kelas yang aktif saat ini.
                </div>
            @endforelse
        </div>
    </div>
</div>
</x-app-layout>
