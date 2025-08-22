<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-sm bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-lg flex flex-col items-center justify-center py-16 px-6">
        <div class="w-20 h-20 bg-yellow-400 rounded-xl flex items-center justify-center mb-6">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">403 - Akses Ditolak</h1>
        <p class="text-slate-500 mb-6 text-center max-w-md">Anda tidak memiliki izin untuk mengakses halaman ini.</p>
        <a href="/dashboard" class="inline-block bg-slate-900 text-white px-6 py-3 rounded-xl font-semibold hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 transition-all duration-200">Kembali ke Dashboard</a>
    </div>
</body>
</html> 