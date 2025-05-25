<div class="p-6">
  <h1 class="text-2xl font-bold mb-4">Daftar Mahasiswa per Mata Kuliah</h1>
  @foreach($matakuliah as $mk)
  <div class="mb-6">
    <h2 class="text-lg font-semibold">{{ $mk->nama }}</h2>
    <ul class="list-disc ml-5">
      @foreach($mk->mahasiswa as $m)
        <li>{{ $m->nama }} ({{ $m->nim }})</li>
      @endforeach
    </ul>
  </div>
  @endforeach
</div>
