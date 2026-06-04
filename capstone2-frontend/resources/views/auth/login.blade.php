<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lab Aset E-Procurement</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Lab Aset</h1>
            <p class="text-gray-500 mt-2">Sistem Pengadaan & Inventaris</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" type="email" name="email" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" id="password" type="password" name="password" required>
            </div>

            <div class="flex items-center justify-between mb-6">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full focus:outline-none focus:shadow-outline transition duration-200" type="submit">
                    Masuk
                </button>
            </div>
        </form>
    </div>

    <script>
        window.addEventListener('pageshow', function(event) {
            // Deteksi jika halaman diakses dari tombol "Back"
            var isBackNavigation = event.persisted || 
                                   (typeof window.performance !== "undefined" && 
                                    window.performance.navigation.type === 2);
            
            if (isBackNavigation) {
                // Paksa reload halaman
                window.location.reload();
            }
        });
    </script>
</body>
</html>