<div class="p-6">
  <h1 class="text-2xl font-bold mb-4">Nilai Anda</h1>
  <table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-200"><tr><th>Mata Kuliah</th><th>UTS</th><th>UAS</th><th>Rata-rata</th></tr></thead>
    <tbody>
      @foreach($nilai as $n)
      <tr>
        <td>{{ $n->matakuliah->nama }}</td>
        <td>{{ $n->uts }}</td>
        <td>{{ $n->uas }}</td>
        <td>{{ ($n->uts + $n->uas) / 2 }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
