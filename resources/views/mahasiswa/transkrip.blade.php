<div class="p-6">
  <h1 class="text-2xl font-bold mb-4">Transkrip Nilai</h1>
  <table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-200"><tr><th>Mata Kuliah</th><th>SKS</th><th>Nilai Akhir</th></tr></thead>
    <tbody>
      @foreach($transkrip as $t)
      <tr>
        <td>{{ $t->matakuliah->nama }}</td>
        <td>{{ $t->matakuliah->sks }}</td>
        <td>{{ ($t->uts + $t->uas) / 2 }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
