<!-- Modal Pengumuman -->
<div id="pengumumanModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-start justify-between p-4 border-b rounded-t">
                <h3 class="text-xl font-semibold text-gray-900">
                    Tambah Pengumuman
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center" data-modal-hide="pengumumanModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="{{ route('admin.pengumuman.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="judul" class="block mb-2 text-sm font-medium text-gray-900">Judul Pengumuman</label>
                            <input type="text" name="judul" id="judul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label for="isi" class="block mb-2 text-sm font-medium text-gray-900">Isi Pengumuman</label>
                            <textarea name="isi" id="isi" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="tanggal_mulai" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Mulai</label>
                                <input type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                            <div>
                                <label for="tanggal_selesai" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Selesai</label>
                                <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="tipe" class="block mb-2 text-sm font-medium text-gray-900">Tipe Pengumuman</label>
                                <select name="tipe" id="tipe" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="akademik">Akademik</option>
                                    <option value="non-akademik">Non-Akademik</option>
                                    <option value="beasiswa">Beasiswa</option>
                                    <option value="kegiatan">Kegiatan</option>
                                </select>
                            </div>
                            <div>
                                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                <select name="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan</button>
                    <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10" data-modal-hide="pengumumanModal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openPengumumanModal() {
        document.getElementById('pengumumanModal').classList.remove('hidden');
        document.getElementById('pengumumanModalTitle').textContent = 'Tambah Pengumuman';
        document.getElementById('pengumumanForm').reset();
        document.getElementById('pengumumanForm').action = "{{ route('admin.pengumuman.store') }}";
        document.getElementById('pengumumanMethodField').innerHTML = '';
    }

    function closePengumumanModal() {
        document.getElementById('pengumumanModal').classList.add('hidden');
    }

    function editPengumuman(id) {
        fetch(`/admin/pengumuman/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('pengumumanModalTitle').textContent = 'Edit Pengumuman';
                document.getElementById('judul').value = data.judul;
                document.getElementById('isi').value = data.isi;
                document.getElementById('tanggal_mulai').value = data.tanggal_mulai.split('T')[0];
                document.getElementById('tanggal_selesai').value = data.tanggal_selesai.split('T')[0];
                document.getElementById('tipe').value = data.tipe;
                document.getElementById('status').value = data.status;
                
                document.getElementById('pengumumanForm').action = `/admin/pengumuman/${id}`;
                document.getElementById('pengumumanMethodField').innerHTML = '@method("PUT")';
                
                document.getElementById('pengumumanModal').classList.remove('hidden');
            });
    }
</script>
@endpush 