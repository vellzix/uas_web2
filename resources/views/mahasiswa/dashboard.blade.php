<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard Mahasiswa') }}
            </h2>
            @if($is_periode_krs)
            <a href="{{ route('mahasiswa.krs.form') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Isi KRS
            </a>
            @endif
        </div>
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

            <!-- Pengumuman -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Pengumuman Terbaru</h3>
                    <div class="space-y-4">
                        @forelse($pengumuman as $p)
                        <div class="border-l-4 {{ $p->tipe === 'mahasiswa' ? 'border-blue-500' : 'border-gray-500' }} pl-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-lg font-medium">{{ $p->judul }}</h4>
                                    <p class="text-gray-600 mt-1">{{ $p->isi }}</p>
                                </div>
                                <span class="text-sm text-gray-500">{{ $p->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="mt-2 flex items-center space-x-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $p->tipe === 'mahasiswa' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($p->tipe) }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    Berlaku: {{ $p->tanggal_mulai->format('d M Y') }} - {{ $p->tanggal_selesai->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center">Tidak ada pengumuman terbaru</p>
                        @endforelse
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
                        <div class="text-sm text-gray-500">
                            SKS Lulus: {{ $total_sks }} | Total SKS: {{ $total_sks_diambil }}
                        </div>
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
