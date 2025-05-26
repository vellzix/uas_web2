<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Mata Kuliah') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Data Mata Kuliah</h2>
                        <button type="button" 
                                data-modal-target="matakuliahModal" 
                                data-modal-toggle="matakuliahModal" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Mata Kuliah
                        </button>
                    </div>

                    <div class="relative overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($matakuliahs as $matakuliah)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $matakuliah->kode }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $matakuliah->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $matakuliah->sks }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $matakuliah->semester }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $matakuliah->prodi->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $matakuliah->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($matakuliah->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editMatakuliah({{ $matakuliah->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                        <form class="inline-block" method="POST" action="{{ route('admin.matakuliah.destroy', $matakuliah->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $matakuliahs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.matakuliah-modal')

    @push('scripts')
    <script>
        function openModal() {
            document.getElementById('matakuliahModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Tambah Mata Kuliah';
            document.getElementById('matakuliahForm').reset();
            document.getElementById('matakuliahForm').action = "{{ route('admin.matakuliah.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('statusField').style.display = 'none';
        }

        function closeModal() {
            document.getElementById('matakuliahModal').classList.add('hidden');
        }

        function editMatakuliah(id) {
            fetch(`/admin/matakuliah/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Edit Mata Kuliah';
                    document.getElementById('kode').value = data.kode;
                    document.getElementById('nama').value = data.nama;
                    document.getElementById('sks').value = data.sks;
                    document.getElementById('semester').value = data.semester;
                    document.getElementById('prodi_id').value = data.prodi_id;
                    document.getElementById('deskripsi').value = data.deskripsi;
                    document.getElementById('status').value = data.status;
                    document.getElementById('statusField').style.display = 'block';
                    
                    document.getElementById('matakuliahForm').action = `/admin/matakuliah/${id}`;
                    document.getElementById('methodField').innerHTML = '@method("PUT")';
                    
                    document.getElementById('matakuliahModal').classList.remove('hidden');
                });
        }
    </script>
    @endpush
</x-app-layout> 