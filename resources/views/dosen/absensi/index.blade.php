<div class="p-6">
  <h1 class="text-2xl font-bold mb-4">Absensi Mahasiswa</h1>
  @foreach($matakuliah as $mk)
    <div class="mb-4">
      <h2 class="font-semibold">{{ $mk->nama }}</h2>
      <form method="POST" action="{{ route('dosen.absensi.store') }}">@csrf
        <input type="hidden" name="matakuliah_id" value="{{ $mk->id }}">
        @foreach($mk->mahasiswa as $m)
        <div class="flex items-center gap-2 mb-2">
          <input type="checkbox" name="absen[{{ $m->id }}]" value="1">
          <label>{{ $m->nama }}</label>
        </div>
        @endforeach
        <button class="bg-green-600 text-white px-3 py-1 rounded">Simpan</button>
      </form>
    </div>
  @endforeach
</div>
