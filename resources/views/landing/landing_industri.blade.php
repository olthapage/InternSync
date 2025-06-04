<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitra Industri Terdaftar - InternSync</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: "Montserrat", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            background-color: #F1F5F9; /* Mengubah kembali ke slate-100 untuk konsistensi */
            color: #1E293B; /* slate-800 */
        }
        .btn-primary {
            background-color: #0F172A; /* Dark (Slate 900) */
            color: #FFFFFF; /* White */
        }
        .btn-primary:hover {
            background-color: #1E293B; /* Slightly lighter dark (Slate 800) */
        }
        /* Styling untuk pagination (Tailwind-like) */
        .pagination { display: flex; justify-content: center; align-items: center; list-style: none; padding: 0; margin-top: 2rem; }
        .pagination li { margin: 0 0.25rem; }
        .pagination li a, .pagination li span {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 0.5rem 0.75rem; min-width: 2.25rem; height: 2.25rem;
            border: 1px solid #e2e8f0; color: #0ea5e9; text-decoration: none;
            border-radius: 0.375rem; transition: background-color 0.3s, color 0.3s;
            font-size: 0.875rem; line-height: 1.25rem;
        }
        .pagination li a:hover { background-color: #f0f9ff; }
        .pagination li.active span { background-color: #0ea5e9; color: white; border-color: #0ea5e9; }
        .pagination li.disabled span { color: #9ca3af; cursor: not-allowed; background-color: #f8fafc; }
        .pagination li.disabled span { border-color: #e2e8f0; }

        /* Optional: loading indicator styles */
        .loading-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px; /* Adjust as needed */
            font-size: 1.2rem;
            color: #64748B; /* slate-500 */
        }
        .loading-indicator .fa-spinner {
            margin-right: 10px;
        }
    </style>
</head>
<body class="antialiased">
    <main>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="text-center mb-8">
                <a href="{{ route('landing') }}"
                    class="inline-block bg-sky-100 text-sky-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-sky-200 transition duration-300 mb-6">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
                </a>
                <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">
                    Mitra Industri Kami
                </h1>
                <p class="text-base text-slate-600 max-w-2xl mx-auto">
                    Temukan perusahaan-perusahaan terkemuka yang telah bekerja sama dan menjadi mitra InternSync untuk peluang magang terbaik.
                </p>
            </div>

            {{-- FORM PENCARIAN --}}
            <div class="mb-8 md:mb-10 max-w-2xl mx-auto">
                {{-- Kita tidak lagi membutuhkan form untuk submit tradisional, tapi tetap bisa digunakan untuk fallback jika JS mati --}}
                <form action="{{ route('industri.landing') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3" id="searchForm">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-slate-400"></i>
                        </div>
                        <input type="text" name="search" id="searchInput"
                                class="block w-full pl-12 pr-3 py-3 text-base border border-slate-300 rounded-lg shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 transition duration-150 ease-in-out"
                                placeholder="Cari nama industri atau kategori..."
                                value="{{ request('search') }}">
                    </div>
                    {{-- Tombol submit bisa dihilangkan atau di-disable jika Anda murni mengandalkan real-time search --}}
                    <button type="submit" style="display: none;" {{-- Sembunyikan tombol submit jika tidak diperlukan untuk submit manual --}}
                            class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-sky-600 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500 transition duration-150 ease-in-out">
                        <i class="fas fa-search mr-2 sm:mr-0"></i> <span class="sm:hidden">Cari</span>
                    </button>
                </form>
            </div>
            {{-- AKHIR FORM PENCARIAN --}}

            {{-- Kontainer untuk hasil pencarian dan pesan --}}
            <div id="industryResultsContainer">
                {{-- Konten awal dari server (akan digantikan oleh AJAX) --}}
                @if($allIndustries->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8" id="industryGrid">
                        @foreach ($allIndustries as $industry)
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 group border border-slate-200 flex flex-col">
                                <div class="w-full h-48 bg-slate-50 flex items-center justify-center p-6 flex-shrink-0">
                                    <img src="{{ $industry->logo_url }}" alt="Logo {{ $industry->industri_nama }}" class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-110">
                                </div>
                                <div class="p-5 text-left flex-grow flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-lg md:text-xl font-semibold text-slate-800 mb-2 group-hover:text-sky-600 transition-colors duration-300 truncate" title="{{ $industry->industri_nama }}">
                                            {{ $industry->industri_nama }}
                                        </h3>
                                        @if($industry->kota)
                                            <p class="text-sm text-slate-500 mb-1 flex items-center">
                                                <i class="fas fa-map-marker-alt mr-2 text-slate-400 w-4 text-center"></i>{{ $industry->kota->kota_nama }}
                                            </p>
                                        @endif
                                        @if($industry->kategori_industri)
                                            <p class="text-sm text-slate-500 flex items-center">
                                                <i class="fas fa-briefcase mr-2 text-slate-400 w-4 text-center"></i>{{ $industry->kategori_industri->kategori_nama }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12" id="paginationContainer">
                        {{ $allIndustries->appends(['search' => request('search')])->links() }}
                    </div>
                @else
                     <div class="text-center py-12" id="noResultsMessage">
                        <svg class="mx-auto h-24 w-24 text-slate-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                        <p class="text-xl text-slate-600 font-semibold mb-2">
                            @if(request('search'))
                                Mitra Industri tidak ditemukan
                            @else
                                Belum Ada Mitra Industri
                            @endif
                        </p>
                        <p class="text-base text-slate-500">
                            @if(request('search'))
                                Tidak ada hasil yang cocok dengan pencarian "{{ request('search') }}". Coba kata kunci lain.
                            @else
                                Saat ini belum ada data mitra industri yang terdaftar. Silakan cek kembali nanti.
                            @endif
                        </p>
                        @if(request('search'))
                            <a href="{{ route('industri.index') }}" class="mt-4 inline-block text-sky-600 hover:text-sky-700 font-medium">
                                Tampilkan semua mitra industri
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </main>

    {{-- Skrip JavaScript untuk Real-time Search --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const resultsContainer = document.getElementById('industryResultsContainer');
            // const industryGrid = document.getElementById('industryGrid'); // Jika Anda ingin mengupdate grid secara spesifik
            // const paginationContainer = document.getElementById('paginationContainer'); // Jika Anda ingin mengupdate pagination secara spesifik
            // const noResultsMessage = document.getElementById('noResultsMessage'); // Jika ingin kontrol pesan no-result
            const searchForm = document.getElementById('searchForm');
            let debounceTimer;

            // Fungsi untuk menampilkan loading indicator
            function showLoading() {
                resultsContainer.innerHTML = `<div class="loading-indicator col-span-full">
                                                  <i class="fas fa-spinner fa-spin"></i> Mencari...
                                              </div>`;
            }

            // Fungsi untuk mengambil dan menampilkan data
            async function fetchAndRenderIndustries(searchTerm, page = 1) {
                showLoading(); // Tampilkan loading sebelum fetch

                // Buat URL dengan parameter search dan page
                // Pastikan route 'industri.search.ajax' terdefinisi di Laravel Anda
                // dan mengembalikan HTML partial untuk daftar industri dan pagination
                const searchUrl = new URL("{{ route('industri.landing') }}"); // Gunakan route yang sudah ada, kita akan deteksi AJAX di controller
                searchUrl.searchParams.set('search', searchTerm);
                searchUrl.searchParams.set('page', page);
                // Anda bisa menambahkan parameter khusus untuk menandakan ini request AJAX jika diperlukan
                // searchUrl.searchParams.set('ajax', '1');

                try {
                    const response = await fetch(searchUrl.toString(), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest', // Header standar untuk menandakan request AJAX
                            'Accept': 'application/json' // Atau 'text/html' jika controller mengembalikan HTML partial langsung
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    // Asumsi controller mengembalikan JSON dengan HTML partials
                    const data = await response.json();

                    if (data.html) {
                         resultsContainer.innerHTML = data.html;
                    } else {
                        // Fallback jika struktur data tidak sesuai atau error parsing
                        resultsContainer.innerHTML = '<p class="text-center col-span-full py-10 text-red-500">Gagal memuat data. Silakan coba lagi.</p>';
                        console.error("Received data is not in expected format:", data);
                    }

                    // Update URL di browser tanpa reload halaman (opsional, untuk UX)
                    const newUrl = new URL(window.location.href);
                    newUrl.searchParams.set('search', searchTerm);
                    if (page > 1) {
                        newUrl.searchParams.set('page', page);
                    } else {
                        newUrl.searchParams.delete('page');
                    }
                    window.history.pushState({path: newUrl.toString()}, '', newUrl.toString());


                } catch (error) {
                    console.error('Error fetching industries:', error);
                    resultsContainer.innerHTML = `<div class="text-center py-12">
                                                      <p class="text-xl text-red-500 font-semibold mb-2">Terjadi kesalahan saat mengambil data.</p>
                                                      <p class="text-base text-slate-500">Silakan coba lagi nanti atau hubungi administrator.</p>
                                                  </div>`;
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const searchTerm = this.value.trim();
                    clearTimeout(debounceTimer);

                    debounceTimer = setTimeout(() => {
                        fetchAndRenderIndustries(searchTerm, 1); // Selalu ke halaman 1 saat search baru
                    }, 500); // Delay 500ms sebelum mengirim request (debounce)
                });
            }

            // Mencegah submit form tradisional jika JavaScript aktif
            if (searchForm) {
                searchForm.addEventListener('submit', function(event) {
                    event.preventDefault(); // Mencegah submit form standar
                    const searchTerm = searchInput.value.trim();
                    fetchAndRenderIndustries(searchTerm, 1);
                });
            }

            // Menangani klik pada link pagination via AJAX
            // Kita perlu event delegation karena link pagination dinamis
            document.body.addEventListener('click', function(event) {
                // Cek apakah yang diklik adalah link di dalam paginationContainer
                const paginationLink = event.target.closest('#paginationContainer a');

                if (paginationLink && paginationLink.href) {
                    event.preventDefault(); // Mencegah navigasi standar
                    const url = new URL(paginationLink.href);
                    const page = url.searchParams.get('page') || 1;
                    const searchTerm = searchInput.value.trim(); // atau ambil dari URL jika lebih reliable
                    fetchAndRenderIndustries(searchTerm, page);
                }
            });

             // Handle back/forward browser buttons
            window.addEventListener('popstate', function(event) {
                const urlParams = new URLSearchParams(window.location.search);
                const searchTerm = urlParams.get('search') || '';
                const page = urlParams.get('page') || 1;
                searchInput.value = searchTerm; // Update input field
                fetchAndRenderIndustries(searchTerm, page);
            });

            // Skrip Global lainnya (jika ada, letakkan di sini atau pastikan sudah termuat)
            // ... (kode skrip global Anda sebelumnya) ...
            // Contoh:
            // const mobileMenuButtonGlobal = document.getElementById('mobile-menu-button-global');
            // // ... dan seterusnya
        });
    </script>
</body>
</html>
