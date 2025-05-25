<div class="p-6">
  <h1 class="text-2xl font-bold mb-4">Jadwal Kuliah</h1>
  <table class="w-full bg-white rounded shadow">
    <thead class="bg-gray-200"><tr><th>Hari</th><th>Jam</th><th>Mata Kuliah</th><th>Ruangan</th></tr></thead>
    <tbody>
      @foreach($jadwal as $j)
      <tr>
        <td>{{ $j->hari }}</td>
        <td>{{ $j->jam }}</td>
        <td>{{ $j->matakuliah->nama }}</td>
        <td>{{ $j->ruangan->nama }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
