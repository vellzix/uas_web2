<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Menu Navigasi -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <a href="{{ route('admin.mahasiswa.index') }}" class="block">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">Manajemen Mahasiswa</div>
                                    <div class="text-sm text-gray-500">Kelola data mahasiswa</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.dosen.index') }}" class="block">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">Manajemen Dosen</div>
                                    <div class="text-sm text-gray-500">Kelola data dosen</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.matakuliah.index') }}" class="block">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">Manajemen Mata Kuliah</div>
                                    <div class="text-sm text-gray-500">Kelola mata kuliah</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.pengumuman.index') }}" class="block">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">Manajemen Pengumuman</div>
                                    <div class="text-sm text-gray-500">Kelola pengumuman</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.jadwal.create') }}" class="block">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="text-lg font-medium text-gray-900">Jadwal Kuliah</div>
                                    <div class="text-sm text-gray-500">Kelola jadwal perkuliahan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Total Mahasiswa</div>
                        <div class="text-3xl font-bold">{{ $total_mahasiswa }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Total Dosen</div>
                        <div class="text-3xl font-bold">{{ $total_dosen }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Total Prodi</div>
                        <div class="text-3xl font-bold">{{ $total_prodi }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 text-sm">Total Matakuliah</div>
                        <div class="text-3xl font-bold">{{ $total_matkul }}</div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- User Terbaru -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">User Terbaru</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($latest_users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($user->role) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Log Sistem -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Log Sistem Terbaru</h3>
                        <div class="space-y-4">
                            @foreach($system_logs as $log)
                            <div class="border-l-4 border-blue-500 pl-4">
                                <div class="text-sm text-gray-600">{{ $log->created_at->diffForHumans() }}</div>
                                <div class="text-gray-900">{{ $log->message }}</div>
                                <div class="text-sm text-gray-500">oleh {{ $log->user->name ?? 'System' }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
