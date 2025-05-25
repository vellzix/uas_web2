<div class="p-6">
  <h1 class="text-2xl font-bold mb-4">Input Nilai Mahasiswa</h1>
  <form method="POST" action="{{ route('dosen.nilai.store') }}">@csrf
    <select name="matakuliah_id" class="w-full border p-2 mb-2">
      @foreach($matakuliah as $mk)
        <option value="{{ $mk->id }}">{{ $mk->nama }}</option>
      @endforeach
    </select>
    <select name="mahasiswa_id" class="w-full border p-2 mb-2">
      @foreach($mahasiswa as $m)
        <option value="{{ $m->id }}">{{ $m->nama }}</option>
      @endforeach
    </select>
    <input name="uts" type="number" placeholder="Nilai UTS" class="w-full border p-2 mb-2">
    <input name="uas" type="number" placeholder="Nilai UAS" class="w-full border p-2 mb-2">
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
  </form>
</div>
