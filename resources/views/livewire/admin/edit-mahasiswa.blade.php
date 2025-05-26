<div class="p-6">
    <h2 class="text-lg font-medium text-gray-900 mb-4">
        {{ __('Edit Data Mahasiswa') }}
    </h2>

    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- NIM -->
            <div>
                <x-input-label for="nim" :value="__('NIM')" />
                <x-text-input wire:model="nim" id="nim" type="text" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('nim')" class="mt-2" />
            </div>

            <!-- Nama -->
            <div>
                <x-input-label for="nama" :value="__('Nama Lengkap')" />
                <x-text-input wire:model="nama" id="nama" type="text" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="email" id="email" type="email" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Program Studi -->
            <div>
                <x-input-label for="prodi_id" :value="__('Program Studi')" />
                <select wire:model="prodi_id" id="prodi_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Pilih Program Studi</option>
                    @foreach($prodis as $prodi)
                        <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('prodi_id')" class="mt-2" />
            </div>

            <!-- Semester -->
            <div>
                <x-input-label for="semester" :value="__('Semester')" />
                <select wire:model="semester" id="semester" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Pilih Semester</option>
                    @for($i = 1; $i <= 14; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
            </div>

            <!-- Angkatan -->
            <div>
                <x-input-label for="angkatan" :value="__('Angkatan')" />
                <select wire:model="angkatan" id="angkatan" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Pilih Angkatan</option>
                    @foreach($angkatans as $angkatan)
                        <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('angkatan')" class="mt-2" />
            </div>

            <!-- Status -->
            <div>
                <x-input-label for="status" :value="__('Status')" />
                <select wire:model="status" id="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Non-Aktif</option>
                </select>
                <x-input-error :messages="$errors->get('status')" class="mt-2" />
            </div>

            <!-- Foto -->
            <div class="col-span-2">
                <x-input-label for="temp_foto" :value="__('Foto')" />
                <input wire:model="temp_foto" type="file" id="temp_foto" class="mt-1 block w-full" accept="image/*">
                <div class="mt-2 text-sm text-gray-500">
                    Format yang didukung: JPG, PNG. Maksimal 2MB.
                </div>
                <x-input-error :messages="$errors->get('temp_foto')" class="mt-2" />

                <div class="mt-2 flex items-center space-x-4">
                    @if ($temp_foto)
                        <img src="{{ $temp_foto->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg">
                    @elseif ($foto)
                        <img src="{{ Storage::url($foto) }}" class="w-32 h-32 object-cover rounded-lg">
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button wire:click="$emit('closeModal')" class="mr-3">
                {{ __('Batal') }}
            </x-secondary-button>

            <x-primary-button type="submit">
                {{ __('Simpan Perubahan') }}
            </x-primary-button>
        </div>
    </form>
</div> 