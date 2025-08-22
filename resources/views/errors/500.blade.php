<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Internal Server Error</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-4">
    
    <div class="w-full max-w-sm bg-white rounded-2xl border border-slate-200 overflow-hidden">
        <!-- Header -->
        <div class="px-8 pt-8 pb-6 text-center">
            <div class="w-16 h-16 bg-red-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">500 - Server Error</h1>
            <p class="text-slate-500 text-sm">Terjadi kesalahan pada server. Tim kami sedang memperbaiki masalah ini</p>
        </div>

        <!-- Content -->
        <div class="px-8 pb-8">
            <!-- Error Description -->
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl text-center">
                <p class="mb-2">Maaf, server mengalami gangguan sementara.</p>
                <p class="text-red-600">Silakan coba lagi dalam beberapa menit atau hubungi administrator.</p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <!-- Primary Button - Refresh -->
                <button 
                    onclick="window.location.reload()"
                    class="w-full bg-slate-900 text-white py-3 px-4 rounded-xl font-semibold hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 transition-all duration-200 transform hover:translate-y-[-1px] active:translate-y-0"
                >
                    Muat Ulang Halaman
                </button>

                <!-- Secondary Button - Dashboard -->
                <a 
                    href="/dashboard"
                    class="w-full bg-slate-100 text-slate-700 py-3 px-4 rounded-xl font-medium hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200 inline-block text-center"
                >
                    Kembali ke Dashboard
                </a>
            </div>

            <!-- Additional Links -->
            <div class="text-center mt-6 space-y-2">
                <div>
                    <a 
                        href="/" 
                        class="text-sm text-slate-500 hover:text-slate-700 transition-colors duration-200"
                    >
                        Halaman Utama
                    </a>
                </div>
                <div>
                    <a 
                        href="/contact" 
                        class="text-sm text-slate-500 hover:text-slate-700 transition-colors duration-200"
                    >
                        Laporkan masalah ke support
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Fade-in animation matching login page -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const errorPage = document.querySelector('body > div');
            errorPage.style.opacity = '0';
            errorPage.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                errorPage.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                errorPage.style.opacity = '1';
                errorPage.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>

</body>
</html>