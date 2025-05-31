<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Presensi') }} - {{ $jadwal->matakuliah->nama }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold">Detail Mata Kuliah</h3>
                        <div class="mt-2 text-sm text-gray-600">
                            <p>Kode: {{ $jadwal->matakuliah->kode }}</p>
                            <p>Jadwal: {{ ucfirst($jadwal->hari) }}, {{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}</p>
                            <p>Ruangan: {{ $jadwal->ruangan }}</p>
                        </div>
                    </div>

                    <form action="{{ route('dosen.presensi.store', $jadwal->id) }}" method="POST">
                        @csrf
                        
                        @if(session('error'))
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif
                        
                        <input type="hidden" name="matakuliah_id" value="{{ $jadwal->matakuliah_id }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="pertemuan_ke" class="block text-sm font-medium text-gray-700">Pertemuan Ke</label>
                                <input type="number" name="pertemuan_ke" id="pertemuan_ke" 
                                    value="{{ old('pertemuan_ke', $latest_pertemuan + 1) }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('pertemuan_ke') border-red-500 @enderror">
                                @error('pertemuan_ke')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" 
                                    value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('tanggal') border-red-500 @enderror">
                                @error('tanggal')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($mahasiswas as $mahasiswa)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $mahasiswa->nim }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $mahasiswa->nama }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <select name="presensi[{{ $mahasiswa->id }}][status]" 
                                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('presensi.' . $mahasiswa->id . '.status') border-red-500 @enderror">
                                                    <option value="hadir" {{ old('presensi.' . $mahasiswa->id . '.status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                    <option value="izin" {{ old('presensi.' . $mahasiswa->id . '.status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                                    <option value="sakit" {{ old('presensi.' . $mahasiswa->id . '.status') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                                    <option value="alpha" {{ old('presensi.' . $mahasiswa->id . '.status') == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                                </select>
                                                @error('presensi.' . $mahasiswa->id . '.status')
                                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                                @enderror
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text" name="presensi[{{ $mahasiswa->id }}][keterangan]" 
                                                    value="{{ old('presensi.' . $mahasiswa->id . '.keterangan') }}"
                                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md @error('presensi.' . $mahasiswa->id . '.keterangan') border-red-500 @enderror"
                                                    placeholder="Keterangan (opsional)">
                                                @error('presensi.' . $mahasiswa->id . '.keterangan')
                                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan Presensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 