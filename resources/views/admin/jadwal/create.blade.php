<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Jadwal Kuliah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.jadwal.store') }}" method="POST">
                        @csrf
                        
                        <!-- Periode Info -->
                        <div class="mb-6">
                            <div class="text-gray-700 mb-2">
                                <strong>Periode Aktif:</strong> 
                                @if($periode)
                                    {{ $periode->tahun_akademik }} Semester {{ $periode->semester }}
                                @else
                                    <span class="text-red-600">Tidak ada periode aktif</span>
                                @endif
                            </div>
                        </div>

                        <!-- Mata Kuliah -->
                        <div class="mb-6">
                            <label for="matakuliah_id" class="block mb-2 text-sm font-medium text-gray-900">
                                Mata Kuliah
                            </label>
                            <select name="matakuliah_id" id="matakuliah_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach($matakuliahs as $matakuliah)
                                    <option value="{{ $matakuliah->id }}" {{ old('matakuliah_id') == $matakuliah->id ? 'selected' : '' }}>
                                        {{ $matakuliah->kode }} - {{ $matakuliah->nama }} ({{ $matakuliah->sks }} SKS)
                                    </option>
                                @endforeach
                            </select>
                            @error('matakuliah_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dosen -->
                        <div class="mb-6">
                            <label for="dosen_id" class="block mb-2 text-sm font-medium text-gray-900">
                                Dosen Pengajar
                            </label>
                            <select name="dosen_id" id="dosen_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="">Pilih Dosen</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->nama }} ({{ $dosen->nip }})
                                    </option>
                                @endforeach
                            </select>
                            @error('dosen_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hari -->
                        <div class="mb-6">
                            <label for="hari" class="block mb-2 text-sm font-medium text-gray-900">
                                Hari
                            </label>
                            <select name="hari" id="hari" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <option value="">Pilih Hari</option>
                                @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $day)
                                    <option value="{{ $day }}" {{ old('hari') == $day ? 'selected' : '' }}>
                                        {{ ucfirst($day) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('hari')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jam -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="jam_mulai" class="block mb-2 text-sm font-medium text-gray-900">
                                    Jam Mulai
                                </label>
                                <input type="time" name="jam_mulai" id="jam_mulai" 
                                    value="{{ old('jam_mulai') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                @error('jam_mulai')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="jam_selesai" class="block mb-2 text-sm font-medium text-gray-900">
                                    Jam Selesai
                                </label>
                                <input type="time" name="jam_selesai" id="jam_selesai" 
                                    value="{{ old('jam_selesai') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                @error('jam_selesai')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Ruangan -->
                        <div class="mb-6">
                            <label for="ruangan" class="block mb-2 text-sm font-medium text-gray-900">
                                Ruangan
                            </label>
                            <input type="text" name="ruangan" id="ruangan" 
                                value="{{ old('ruangan') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                placeholder="Masukkan nama ruangan" required>
                            @error('ruangan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kapasitas -->
                        <div class="mb-6">
                            <label for="kapasitas" class="block mb-2 text-sm font-medium text-gray-900">
                                Kapasitas
                            </label>
                            <input type="number" name="kapasitas" id="kapasitas" 
                                value="{{ old('kapasitas') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                min="1" placeholder="Masukkan jumlah kapasitas" required>
                            @error('kapasitas')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 