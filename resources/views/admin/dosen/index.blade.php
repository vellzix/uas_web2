<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Dosen') }}
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
                        <h2 class="text-2xl font-semibold">Data Dosen</h2>
                        <button type="button" 
                                onclick="openModal()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Dosen
                        </button>
                    </div>

                    <div class="relative overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Studi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($dosens as $dosen)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $dosen->nip }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $dosen->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $dosen->user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $dosen->prodi->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $dosen->bidang }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $dosen->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($dosen->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editDosen({{ $dosen->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                        <button onclick="deleteDosen({{ $dosen->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $dosens->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.dosen-modal')

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get modal instance
            const dosenModal = window.getModal('dosenModal');
            if (!dosenModal) {
                console.error('Failed to initialize dosen modal');
                return;
            }

            // Add form submit handler
            const form = document.getElementById('dosenForm');
            form.addEventListener('submit', handleFormSubmit);

            // Add modal hide handler
            const modalElement = document.getElementById('dosenModal');
            modalElement.addEventListener('hide.bs.modal', function () {
                form.reset();
                document.getElementById('methodField').innerHTML = '';
                form.action = "{{ route('admin.dosen.store') }}";
                document.getElementById('modalTitle').textContent = 'Tambah Dosen';
                document.getElementById('password').required = true;
            });
        });

        function handleFormSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
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
                    // Hide modal
                    window.getModal('dosenModal').hide();
                    
                    // Show success message
                    alert(data.message);
                    
                    // Reload page to show new data
                    window.location.reload();
                } else {
                    // Show error message
                    alert(data.message || 'Terjadi kesalahan saat menyimpan data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                if (error.errors) {
                    errorMessage = Object.values(error.errors).flat().join('\n');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                alert(errorMessage);
            });
        }

        function openModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Dosen';
            document.getElementById('dosenForm').reset();
            document.getElementById('dosenForm').action = "{{ route('admin.dosen.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('password').required = true;
            window.getModal('dosenModal').show();
        }

        function closeModal() {
            window.getModal('dosenModal').hide();
        }

        function editDosen(id) {
            fetch(`/admin/dosen/${id}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('modalTitle').textContent = 'Edit Dosen';
                document.getElementById('nip').value = data.nip;
                document.getElementById('nama').value = data.nama;
                document.getElementById('email').value = data.user.email;
                document.getElementById('no_hp').value = data.no_hp;
                document.getElementById('tempat_lahir').value = data.tempat_lahir;
                document.getElementById('tanggal_lahir').value = data.tanggal_lahir;
                document.getElementById('jenis_kelamin').value = data.jenis_kelamin;
                document.getElementById('agama').value = data.agama;
                document.getElementById('prodi_id').value = data.prodi_id;
                document.getElementById('bidang').value = data.bidang;
                
                // Make password optional for edit
                document.getElementById('password').required = false;
                
                // Update form for edit
                const form = document.getElementById('dosenForm');
                form.action = `/admin/dosen/${id}`;
                document.getElementById('methodField').innerHTML = '@method("PUT")';
                
                // Show modal
                window.getModal('dosenModal').show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data dosen');
            });
        }

        // Delete confirmation with AJAX
        function deleteDosen(id) {
            if (confirm('Apakah Anda yakin ingin menghapus dosen ini?')) {
                fetch(`/admin/dosen/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
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
    </script>
    @endpush
</x-app-layout> 