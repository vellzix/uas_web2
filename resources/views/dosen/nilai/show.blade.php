@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Input Nilai: {{ $jadwal->matakuliah->nama }}</h1>
                <p class="text-gray-600">Kode: {{ $jadwal->matakuliah->kode }} | SKS: {{ $jadwal->matakuliah->sks }}</p>
            </div>
            <a href="{{ route('dosen.nilai.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Kembali
            </a>
        </div>

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

        <form id="nilaiForm" action="{{ route('dosen.nilai.store', $jadwal->id) }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">NIM</th>
                            <th class="px-4 py-2 text-left">Nama Mahasiswa</th>
                            <th class="px-4 py-2">Nilai UTS</th>
                            <th class="px-4 py-2">Nilai UAS</th>
                            <th class="px-4 py-2">Nilai Tugas</th>
                            <th class="px-4 py-2">Nilai Akhir</th>
                            <th class="px-4 py-2">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswas as $krs)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $krs->mahasiswa->nim }}</td>
                                <td class="px-4 py-2">{{ $krs->mahasiswa->nama }}</td>
                                <td class="px-4 py-2">
                                    <input type="hidden" name="nilai[{{ $krs->id }}][krs_id]" value="{{ $krs->id }}">
                                    <input type="number" 
                                           name="nilai[{{ $krs->id }}][uts]" 
                                           value="{{ $krs->nilai->nilai_uts ?? '' }}"
                                           class="w-20 px-2 py-1 border rounded"
                                           min="0"
                                           max="100"
                                           required>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" 
                                           name="nilai[{{ $krs->id }}][uas]" 
                                           value="{{ $krs->nilai->nilai_uas ?? '' }}"
                                           class="w-20 px-2 py-1 border rounded"
                                           min="0"
                                           max="100"
                                           required>
                                </td>
                                <td class="px-4 py-2">
                                    <input type="number" 
                                           name="nilai[{{ $krs->id }}][tugas]" 
                                           value="{{ $krs->nilai->nilai_tugas ?? '' }}"
                                           class="w-20 px-2 py-1 border rounded"
                                           min="0"
                                           max="100"
                                           required>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    {{ $krs->nilai->nilai_angka ?? '-' }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    {{ $krs->nilai->nilai_huruf ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Simpan Nilai
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('nilaiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const nilai = {};
    
    // Group form data by KRS ID
    for (let [key, value] of formData.entries()) {
        const matches = key.match(/nilai\[(\d+)\]\[(\w+)\]/);
        if (matches) {
            const [, krsId, field] = matches;
            if (!nilai[krsId]) {
                nilai[krsId] = {};
            }
            nilai[krsId][field] = value;
        }
    }
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ nilai })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan saat menyimpan nilai');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan nilai');
    });
});
</script>
@endpush

@endsection 