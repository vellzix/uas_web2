<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Mahasiswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Info Mahasiswa -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center">
                        @if($mahasiswa->foto)
                            <img src="{{ asset('storage/' . $mahasiswa->foto) }}" alt="Foto Mahasiswa" class="w-24 h-24 rounded-full object-cover">
                        @else
                            <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                        <div class="ml-6">
                            <h3 class="text-2xl font-bold">{{ $mahasiswa->nama }}</h3>
                            <p class="text-gray-600">NIM: {{ $mahasiswa->nim }}</p>
                            <p class="text-gray-600">Program Studi: {{ $mahasiswa->prodi->nama }}</p>
                            <p class="text-gray-600">Semester {{ $mahasiswa->semester }} - Angkatan {{ $mahasiswa->angkatan }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Akademik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Status KRS</div>
                        <div class="text-3xl font-bold">
                            @if($krs_status)
                                <span class="text-green-600">Disetujui</span>
                            @else
                                <span class="text-yellow-600">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">IPK</div>
                        <div class="text-3xl font-bold">{{ number_format($ipk, 2) }}</div>
                        <div class="text-sm text-gray-500">Total SKS: {{ $total_sks }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Kehadiran</div>
                        <div class="text-3xl font-bold">{{ $kehadiran_percentage }}%</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Jadwal Hari Ini -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Jadwal Hari Ini</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($jadwal_hari_ini as $jadwal)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->matakuliah->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->dosen->nama }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $jadwal->ruangan }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada jadwal hari ini</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tugas yang Belum Dikumpulkan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Tugas yang Belum Dikumpulkan</h3>
                        <div class="space-y-4">
                            @forelse($tugas as $t)
                            <div class="border-l-4 border-red-500 pl-4">
                                <div class="text-lg font-medium">{{ $t->tugas->judul }}</div>
                                <div class="text-sm text-gray-600">{{ $t->tugas->jadwal->matakuliah->nama }}</div>
                                <div class="text-sm text-gray-500">Deadline: {{ $t->tugas->deadline->format('d M Y H:i') }}</div>
                            </div>
                            @empty
                            <p class="text-gray-500 text-center">Tidak ada tugas yang belum dikumpulkan</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
