<div class="p-6">
  <h1 class="text-2xl font-bold mb-4">Kartu Rencana Studi (KRS)</h1>
  <form method="POST" action="{{ route('mahasiswa.krs.store') }}">@csrf
    <div class="grid grid-cols-2 gap-4">
      @foreach($matakuliah as $mk)
      <div class="flex items-center">
        <input type="checkbox" name="matakuliah_id[]" value="{{ $mk->id }}" class="mr-2">
        <label>{{ $mk->nama }} ({{ $mk->sks }} SKS)</label>
      </div>
      @endforeach
    </div>
    <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">Simpan KRS</button>
  </form>
</div>
