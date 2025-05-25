<div class="p-6">
  <h1 class="text-2xl font-bold mb-4">Pengumuman</h1>
  <ul class="space-y-4">
    @foreach($pengumuman as $p)
    <li class="border bg-white p-4 rounded shadow">
      <h2 class="font-bold text-lg">{{ $p->judul }}</h2>
      <p>{{ $p->isi }}</p>
      <small class="text-gray-500">{{ $p->created_at->format('d M Y') }}</small>
    </li>
    @endforeach
  </ul>
</div>
