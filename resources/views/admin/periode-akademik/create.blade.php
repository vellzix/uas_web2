<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Periode Akademik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.periode-akademik.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Periode -->
                            <div>
                                <x-input-label for="nama" :value="__('Nama Periode')" />
                                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" 
                                    :value="old('nama')" required />
                                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                            </div>

                            <!-- Tahun Akademik -->
                            <div>
                                <x-input-label for="tahun_akademik" :value="__('Tahun Akademik')" />
                                <x-text-input id="tahun_akademik" name="tahun_akademik" type="text" 
                                    class="mt-1 block w-full" placeholder="2023/2024"
                                    :value="old('tahun_akademik')" required />
                                <x-input-error :messages="$errors->get('tahun_akademik')" class="mt-2" />
                            </div>

                            <!-- Semester -->
                            <div>
                                <x-input-label for="semester" :value="__('Semester')" />
                                <select id="semester" name="semester" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="ganjil" {{ old('semester') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                                </select>
                                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                            </div>

                            <!-- Tanggal Mulai -->
                            <div>
                                <x-input-label for="tanggal_mulai" :value="__('Tanggal Mulai')" />
                                <x-text-input id="tanggal_mulai" name="tanggal_mulai" type="date" 
                                    class="mt-1 block w-full" :value="old('tanggal_mulai')" required />
                                <x-input-error :messages="$errors->get('tanggal_mulai')" class="mt-2" />
                            </div>

                            <!-- Tanggal Selesai -->
                            <div>
                                <x-input-label for="tanggal_selesai" :value="__('Tanggal Selesai')" />
                                <x-text-input id="tanggal_selesai" name="tanggal_selesai" type="date" 
                                    class="mt-1 block w-full" :value="old('tanggal_selesai')" required />
                                <x-input-error :messages="$errors->get('tanggal_selesai')" class="mt-2" />
                            </div>

                            <!-- KRS Mulai -->
                            <div>
                                <x-input-label for="krs_mulai" :value="__('Tanggal Mulai KRS')" />
                                <x-text-input id="krs_mulai" name="krs_mulai" type="date" 
                                    class="mt-1 block w-full" :value="old('krs_mulai')" required />
                                <x-input-error :messages="$errors->get('krs_mulai')" class="mt-2" />
                            </div>

                            <!-- KRS Selesai -->
                            <div>
                                <x-input-label for="krs_selesai" :value="__('Tanggal Selesai KRS')" />
                                <x-text-input id="krs_selesai" name="krs_selesai" type="date" 
                                    class="mt-1 block w-full" :value="old('krs_selesai')" required />
                                <x-input-error :messages="$errors->get('krs_selesai')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="mt-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('is_active') ? 'checked' : '' }}>
                                <span class="ml-2">Aktifkan periode ini</span>
                            </label>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button type="button" onclick="window.history.back()" class="mr-3">
                                {{ __('Batal') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 