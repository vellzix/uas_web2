<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengisian KRS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Info KRS -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Informasi Akademik</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Tahun Akademik:</span> {{ $periode->tahun_akademik }}</p>
                                <p><span class="font-medium">Semester:</span> {{ ucfirst($periode->semester) }}</p>
                                <p><span class="font-medium">IPK:</span> {{ number_format($ipk, 2) }}</p>
                                <p><span class="font-medium">Total SKS yang sudah ditempuh:</span> {{ $total_sks }}</p>
                                <p><span class="font-medium">Maksimal SKS yang bisa diambil:</span> {{ $max_sks }}</p>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Periode Pengisian</h3>
                            <div class="space-y-2">
                                <p><span class="font-medium">Mulai:</span> {{ $periode->krs_mulai->format('d M Y') }}</p>
                                <p><span class="font-medium">Selesai:</span> {{ $periode->krs_selesai->format('d M Y') }}</p>
                                <p class="text-sm text-red-600 mt-4">* Pastikan Anda mengisi KRS sebelum periode berakhir</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form KRS -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-6">Pilih Mata Kuliah</h3>
                    
                    <form method="POST" action="{{ route('mahasiswa.krs.store') }}" id="krsForm">
                        @csrf
                        
                        <!-- Total SKS yang dipilih -->
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <p class="text-blue-800">
                                Total SKS yang dipilih: <span id="totalSKS" class="font-bold">0</span> / {{ $max_sks }}
                            </p>
                        </div>

                        <!-- Daftar Mata Kuliah -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pilih</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKS</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($jadwals as $jadwal)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <input type="checkbox" 
                                                name="jadwal_ids[]" 
                                                value="{{ $jadwal->id }}" 
                                                data-sks="{{ $jadwal->matakuliah->sks }}"
                                                class="matakuliah-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                {{ $jadwal->terisi >= $jadwal->kapasitas ? 'disabled' : '' }}
                                                {{ in_array($jadwal->id, $selected_jadwals) ? 'checked' : '' }}>
                                        </td>
                                        <td class="px-6 py-4">{{ $jadwal->matakuliah->kode }}</td>
                                        <td class="px-6 py-4">{{ $jadwal->matakuliah->nama }}</td>
                                        <td class="px-6 py-4">{{ $jadwal->matakuliah->sks }}</td>
                                        <td class="px-6 py-4">{{ $jadwal->matakuliah->semester }}</td>
                                        <td class="px-6 py-4">
                                            {{ ucfirst($jadwal->hari) }}, {{ $jadwal->jam_mulai->format('H:i') }} - {{ $jadwal->jam_selesai->format('H:i') }}
                                            <br>
                                            <span class="text-sm text-gray-500">{{ $jadwal->ruangan }}</span>
                                        </td>
                                        <td class="px-6 py-4">{{ $jadwal->dosen->nama }}</td>
                                        <td class="px-6 py-4">
                                            <span class="{{ $jadwal->terisi >= $jadwal->kapasitas ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $jadwal->terisi }}/{{ $jadwal->kapasitas }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Simpan KRS
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('krsForm');
            const checkboxes = document.querySelectorAll('.matakuliah-checkbox');
            const totalSKSElement = document.getElementById('totalSKS');
            const maxSKS = {{ $max_sks }};
            
            function updateTotalSKS() {
                let total = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        total += parseInt(checkbox.dataset.sks);
                    }
                });
                totalSKSElement.textContent = total;
                
                // Disable checkboxes if total SKS would exceed max
                checkboxes.forEach(checkbox => {
                    if (!checkbox.checked) {
                        const sks = parseInt(checkbox.dataset.sks);
                        const wouldExceedMax = (total + sks) > maxSKS;
                        const kuotaInfo = checkbox.closest('tr').querySelector('td:last-child').textContent.split('/');
                        const terisi = parseInt(kuotaInfo[0]);
                        const kapasitas = parseInt(kuotaInfo[1]);
                        const kuotaPenuh = terisi >= kapasitas;
                        
                        checkbox.disabled = wouldExceedMax || kuotaPenuh;
                        
                        // Add visual feedback
                        if (wouldExceedMax) {
                            checkbox.title = 'Melebihi batas maksimal SKS';
                        } else if (kuotaPenuh) {
                            checkbox.title = 'Kapasitas kelas penuh';
                        } else {
                            checkbox.title = '';
                        }
                    }
                });
            }
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotalSKS);
            });
            
            // Initial calculation
            updateTotalSKS();
            
            // Form submission validation
            form.addEventListener('submit', function(e) {
                const totalSKS = parseInt(totalSKSElement.textContent);
                if (totalSKS === 0) {
                    e.preventDefault();
                    alert('Pilih minimal satu mata kuliah!');
                } else if (totalSKS > maxSKS) {
                    e.preventDefault();
                    alert(`Total SKS (${totalSKS}) melebihi batas maksimal (${maxSKS})!`);
                }
            });
        });
    </script>
    @endpush
</x-app-layout> 