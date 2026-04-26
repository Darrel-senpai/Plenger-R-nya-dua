<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AirLayak — Dashboard Warga</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto p-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-bold">Halo, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600 mt-2">Dashboard warga akan dibangun oleh tim frontend.</p>
            
            <div class="mt-6 flex gap-3">
                <a href="#" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700">
                    Lapor Masalah Air
                </a>
                <a href="#" class="px-4 py-2 border rounded hover:bg-gray-50">
                    Lihat Heatmap
                </a>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>