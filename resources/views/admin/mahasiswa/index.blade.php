<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Mahasiswa') }}
            </h2>
            <button type="button" 
                    data-modal-target="mahasiswaModal" 
                    data-modal-toggle="mahasiswaModal" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Tambah Mahasiswa
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex justify-between items-center">
                        <div class="flex-1 pr-4">
                            <div class="relative">
                                <input type="search" 
                                    id="searchInput"
                                    class="w-full pl-10 pr-4 py-2 rounded-lg border focus:border-blue-300 focus:ring focus:ring-blue-200" 
                                    placeholder="Cari mahasiswa...">
                                <div class="absolute left-3 top-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="flex">
                            <select id="prodiFilter" class="rounded-lg border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 mr-2">
                                <option value="">Semua Prodi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                                @endforeach
                            </select>
                            <select id="angkatanFilter" class="rounded-lg border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <option value="">Semua Angkatan</option>
                                @foreach($angkatans as $angkatan)
                                    <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Angkatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. HP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($mahasiswas as $mahasiswa)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->nim }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($mahasiswa->foto)
                                                <img class="h-10 w-10 rounded-full object-cover mr-3" src="{{ Storage::url($mahasiswa->foto) }}" alt="{{ $mahasiswa->nama }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $mahasiswa->nama }}</div>
                                                <div class="text-sm text-gray-500">{{ $mahasiswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->prodi->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->angkatan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $mahasiswa->no_hp }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $mahasiswa->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($mahasiswa->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editMahasiswa({{ $mahasiswa->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                        <button onclick="deleteMahasiswa({{ $mahasiswa->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $mahasiswas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.mahasiswa-modal')

    @push('scripts')
    <script>
        // Delete confirmation
        function deleteMahasiswa(id) {
            if (confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')) {
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                fetch(`/admin/mahasiswa/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(json => Promise.reject(json));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Terjadi kesalahan saat menghapus data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'Terjadi kesalahan saat menghapus data';
                    if (error.errors) {
                        errorMessage = Object.values(error.errors).flat().join('\n');
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    alert(errorMessage);
                });
            }
        }

        // Search and filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const prodiFilter = document.getElementById('prodiFilter');
            const angkatanFilter = document.getElementById('angkatanFilter');
            const tableBody = document.querySelector('tbody');

            function filterTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const prodiId = prodiFilter.value;
                const angkatan = angkatanFilter.value;

                const rows = tableBody.getElementsByTagName('tr');
                Array.from(rows).forEach(row => {
                    const nim = row.cells[0].textContent.toLowerCase();
                    const nama = row.cells[1].querySelector('.text-gray-900').textContent.toLowerCase();
                    const prodi = row.cells[2].textContent;
                    const rowAngkatan = row.cells[3].textContent;

                    const matchesSearch = nim.includes(searchTerm) || nama.includes(searchTerm);
                    const matchesProdi = !prodiId || prodi.includes(prodiFilter.options[prodiFilter.selectedIndex].text);
                    const matchesAngkatan = !angkatan || rowAngkatan === angkatan;

                    row.style.display = matchesSearch && matchesProdi && matchesAngkatan ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterTable);
            prodiFilter.addEventListener('change', filterTable);
            angkatanFilter.addEventListener('change', filterTable);
        });
    </script>
    @endpush
</x-app-layout> 