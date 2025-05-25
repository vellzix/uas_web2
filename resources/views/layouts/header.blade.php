<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>SIAKAD Kampus</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
  <div class="min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-800 text-white p-6 space-y-4">
      <h2 class="text-2xl font-bold border-b pb-4">SIAKAD</h2>
      <nav class="flex flex-col space-y-2">
        @if(auth()->user()->role === 'mahasiswa')
          <a href="/mahasiswa/dashboard" class="hover:bg-blue-600 px-4 py-2 rounded">Dashboard</a>
          <a href="/mahasiswa/krs" class="hover:bg-blue-600 px-4 py-2 rounded">KRS</a>
          <a href="/mahasiswa/nilai" class="hover:bg-blue-600 px-4 py-2 rounded">Nilai</a>
          <a href="/mahasiswa/transkrip" class="hover:bg-blue-600 px-4 py-2 rounded">Transkrip</a>
        @elseif(auth()->user()->role === 'dosen')
          <a href="/dosen/dashboard" class="hover:bg-blue-600 px-4 py-2 rounded">Dashboard</a>
          <a href="/dosen/jadwal" class="hover:bg-blue-600 px-4 py-2 rounded">Jadwal</a>
          <a href="/dosen/input-nilai" class="hover:bg-blue-600 px-4 py-2 rounded">Input Nilai</a>
        @elseif(auth()->user()->role === 'admin')
          <a href="/admin/dashboard" class="hover:bg-blue-600 px-4 py-2 rounded">Dashboard</a>
          <a href="/admin/mahasiswa" class="hover:bg-blue-600 px-4 py-2 rounded">Data Mahasiswa</a>
          <a href="/admin/dosen" class="hover:bg-blue-600 px-4 py-2 rounded">Data Dosen</a>
          <a href="/admin/matakuliah" class="hover:bg-blue-600 px-4 py-2 rounded">Mata Kuliah</a>
        @endif
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="text-left hover:bg-red-500 mt-8 bg-red-600 px-4 py-2 rounded">Logout</button>
        </form>
      </nav>
    </aside>

    <!-- Content -->
    <div class="flex-1 p-8">
