<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternSync - Temukan Magang Impianmu!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: "Inter", "Montserrat", sans-serif;
            background-color: #F1F5F9;
            color: #1E293B;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .glassmorphism-light {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 10px;
            border: 1px solid rgba(203, 213, 225, 0.5);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background-color: #0F172A;
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background-color: #1E293B;
        }

        .btn-secondary {
            background-color: #E2E8F0;
            color: #0F172A;
            border: 1px solid #CBD5E1;
        }

        .btn-secondary:hover {
            background-color: #CBD5E1;
        }

        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .animation-delay-100 {
            animation-delay: 0.1s;
        }

        .animation-delay-200 {
            animation-delay: 0.2s;
        }

        .animation-delay-300 {
            animation-delay: 0.3s;
        }

        .animation-delay-400 {
            animation-delay: 0.4s;
        }

        .animation-delay-500 {
            animation-delay: 0.5s;
        }

        .animation-delay-600 {
            animation-delay: 0.6s;
        }

        .animation-delay-800 {
            animation-delay: 0.8s;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        .modal-close-button {
            color: #aaa;
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-close-button:hover,
        .modal-close-button:focus {
            color: #333;
            text-decoration: none;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        @keyframes marquee2 {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(0%);
            }
        }

        .animate-marquee {
            animation: marquee 35s linear infinite;
        }

        .animate-marquee2 {
            animation: marquee2 35s linear infinite;
        }

        .group:hover .pause-animation {
            animation-play-state: paused;
        }

        .carousel-dot.active {
            background-color: #0ea5e9;
            transform: scale(1.2);
        }

        @media (max-width: 768px) {

            .animate-marquee,
            .animate-marquee2 {
                animation-duration: 25s;
            }
        }
    </style>
</head>

<body class="antialiased">


    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md shadow-sm">
        <nav class="container mx-auto px-4 sm:px-6 py-3 flex justify-between items-center text-sm">
            <a href="#" class="text-2xl font-bold text-sky-600">

                <img src="{{ asset('softTemplate/assets/img/LogoInternSync.png') }}" alt="InternSync Logo"
                    class="h-9">
            </a>

            <div class="hidden md:flex items-center space-x-6">
                <a href="#hero" class="text-slate-600 hover:text-sky-600 transition duration-300">Beranda</a>
                <a href="#about" class="text-slate-600 hover:text-sky-600 transition duration-300">Tentang Kami</a>
                <a href="#features" class="text-slate-600 hover:text-sky-600 transition duration-300">Fitur</a>
                <a href="#team" class="text-slate-600 hover:text-sky-600 transition duration-300">Tim Kami</a>
                <a href="#testimonials" class="text-slate-600 hover:text-sky-600 transition duration-300">Testimoni</a>
                <a href="{{ route('login') }}"
                    class="py-2 px-4 text-slate-600 hover:text-sky-600 transition duration-300">Masuk</a>
                <a href="{{ route('signup') }}"
                    class="btn-primary py-2 px-4 rounded-md font-semibold shadow-sm hover:shadow-md transition duration-300 text-sm">Daftar
                    Sekarang</a>
            </div>

            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-slate-700 focus:outline-none p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </nav>

        <div id="mobile-menu" class="hidden md:hidden bg-white px-4 pb-4 space-y-2 shadow-lg text-sm">
            <a href="#hero" class="block text-slate-600 hover:text-sky-600 transition duration-300 py-2">Beranda</a>
            <a href="#about" class="block text-slate-600 hover:text-sky-600 transition duration-300 py-2">Tentang
                Kami</a>
            <a href="#features" class="block text-slate-600 hover:text-sky-600 transition duration-300 py-2">Fitur</a>
            <a href="#team" class="block text-slate-600 hover:text-sky-600 transition duration-300 py-2">Tim Kami</a>
            <a href="#testimonials"
                class="block text-slate-600 hover:text-sky-600 transition duration-300 py-2">Testimoni</a>
            <hr class="border-slate-200 my-2">
            <a href="{{ route('login') }}"
                class="block text-slate-600 hover:text-sky-600 transition duration-300 py-2">Masuk</a>
            <a href="{{ route('signup') }}"
                class="block btn-primary text-center py-2 px-4 rounded-md font-semibold shadow-sm hover:shadow-md transition duration-300 mt-2 text-sm">Daftar
                Sekarang</a>
        </div>
    </header>

    <main>

        <section id="hero"
            class="relative min-h-screen flex flex-col items-center justify-center text-center overflow-hidden pt-20 pb-10 md:pt-0 bg-white">

            <div
                class="absolute top-[10%] left-[5%] sm:top-[30%] sm:left-[10%] w-16 h-16 md:w-20 md:h-20 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform -rotate-6 opacity-80">
                <i class="fas fa-briefcase text-3xl md:text-4xl text-sky-600"></i>
            </div>
            <div
                class="absolute top-[12%] right-[8%] sm:top-[25%] sm:right-[12%] w-14 h-14 md:w-16 md:h-16 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform rotate-3 opacity-70 animation-delay-200">
                <i class="fas fa-user-graduate text-3xl md:text-4xl text-purple-600"></i>
            </div>
            <div
                class="absolute bottom-[15%] left-[8%] md:bottom-[25%] md:left-[10%] w-12 h-12 md:w-14 md:h-14 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform rotate-12 opacity-60 animation-delay-400">
                <i class="fas fa-building text-2xl md:text-3xl text-indigo-600"></i>
            </div>
            <div
                class="absolute bottom-[12%] right-[10%] md:top-[60%] md:right-[22%] w-12 h-12 md:w-14 md:h-14 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform -rotate-3 opacity-65 animation-delay-500">
                <i class="fas fa-link text-xl md:text-2xl text-amber-600"></i>
            </div>
            <div
                class="absolute top-[5%] left-[30%] md:top-[10%] md:left-[10%] w-16 h-16 md:w-20 md:h-20 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform rotate-3 opacity-90 z-20">
                <i class="fas fa-rocket text-3xl md:text-4xl text-rose-600"></i>
            </div>
            <div
                class="absolute top-[7%] right-[25%] md:top-[10%] md:right-[10%] w-14 h-14 md:w-16 md:h-16 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform -rotate-6 opacity-85 z-20">
                <i class="fas fa-network-wired text-3xl md:text-4xl text-green-600"></i>
            </div>

            <div class="container mx-auto px-4 sm:px-6 py-16 relative z-10 flex flex-col items-center">
                <a href="{{ route('industri.landing') }}"
                    class="inline-block bg-sky-100 text-sky-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-sky-200 transition duration-300 mb-6 reveal">
                    Lihat Mitra Terafiliasi <i class="fas fa-arrow-right ml-2"></i>
                </a>

                <h1
                    class="text-4xl sm:text-5xl md:text-6xl font-bold text-slate-900 mb-6 reveal animation-delay-100 text-center">
                    <em>Intern
                    <span class="bg-gradient-to-r from-blue-500 to-cyan-400 bg-clip-text text-transparent inline-block">
                        Sync 
                    </span>
                    </em>
                    <br class="hidden md:block">
                    Jembatan Menuju Karir Impian
                </h1>

                <p
                    class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto mb-10 reveal animation-delay-200 text-center">
                    Dapatkan rekomendasi magang atau kerja praktek yang sesuai dengan bidang studi, keterampilan, dan
                    preferensi Anda. Hubungkan diri dengan perusahaan mitra terpercaya.
                </p>

                <div
                    class="space-y-4 sm:space-y-0 sm:space-x-4 flex flex-col sm:flex-row justify-center items-center reveal animation-delay-300 mb-12 w-full sm:w-auto">
                    <a href="{{ route('signup') }}"
                        class="w-full sm:w-auto btn-primary text-base font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300">Cari
                        Magang Sekarang</a>
                    <a href="#about"
                        class="w-full sm:w-auto btn-secondary text-base font-semibold py-3 px-8 rounded-lg shadow-sm hover:shadow-md transition duration-300">Pelajari
                        Lebih Lanjut</a>
                </div>


                <div class="mt-8 max-w-5xl w-full mx-auto reveal animation-delay-400 relative"
                    id="imageCarouselContainer">
                    <div
                        class="bg-white p-4 sm:p-6 rounded-xl border border-slate-200 relative overflow-hidden shadow-lg">

                        <div class="carousel-wrapper flex transition-transform duration-700 ease-in-out"
                            id="carouselWrapper">

                            <div class="carousel-slide min-w-full">
                                <img src="{{ asset('images/corausel/corausel1.png') }}"
                                    alt="InternSync Platform Mockup 1" class="rounded-xl w-full h-auto object-cover"
                                    style="max-height: 500px;">
                            </div>

                            <div class="carousel-slide min-w-full">
                                <img src="{{ asset('images/corausel/corausel2.png') }}"
                                    alt="InternSync Platform Mockup 2" class="rounded-xl w-full h-auto object-cover"
                                    style="max-height: 500px;">
                            </div>
                        </div>


                        <button id="prevBtn"
                            class="absolute top-1/2 left-2 sm:left-4 transform -translate-y-1/2 bg-white/80 text-slate-700 p-2 sm:p-3 rounded-full hover:bg-white hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-sky-500 z-10">
                            <i class="fas fa-chevron-left text-base sm:text-lg"></i>
                        </button>
                        <button id="nextBtn"
                            class="absolute top-1/2 right-2 sm:right-4 transform -translate-y-1/2 bg-white/80 text-slate-700 p-2 sm:p-3 rounded-full hover:bg-white hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-sky-500 z-10">
                            <i class="fas fa-chevron-right text-base sm:text-lg"></i>
                        </button>


                        <div id="carouselDots"
                            class="absolute bottom-4 sm:bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3">
                            <button
                                class="carousel-dot w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-slate-300 transition-all duration-300"
                                data-slide="0"></button>
                            <button
                                class="carousel-dot w-2 h-2 sm:w-3 sm:h-3 rounded-full bg-slate-300 transition-all duration-300"
                                data-slide="1"></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="w-full bg-slate-50 py-16">
            <div class="container mx-auto px-4 sm:px-6">
                <div class="text-center mb-12">
                    <h3 class="text-slate-800 font-bold text-2xl sm:text-3xl mb-4">
                        Dipercaya oleh Industri Terkemuka
                    </h3>
                    <p class="text-slate-600 text-base sm:text-lg max-w-2xl mx-auto">
                        Bergabung dengan ribuan mahasiswa yang telah menemukan peluang magang terbaik di
                        perusahaan-perusahaan ternama.
                    </p>
                </div>

                @if (isset($industriesForMarquee) && $industriesForMarquee->isNotEmpty())
                    <div class="relative flex overflow-x-hidden group">

                        <div
                            class="py-8 animate-marquee whitespace-nowrap flex items-center group-hover:pause-animation">
                            @foreach ($industriesForMarquee as $industry)
                                <div class="mx-6 flex-shrink-0 flex items-center justify-center h-16 w-auto sm:h-20"
                                    title="{{ $industry->industri_nama }}">
                                    <img src="{{ $industry->logo_url }}" alt="{{ $industry->industri_nama }} Logo"
                                        class="max-h-full max-w-full object-contain filter grayscale hover:grayscale-0 transition-all duration-300 transform hover:scale-110 cursor-pointer">
                                </div>
                            @endforeach
                        </div>


                        <div
                            class="absolute top-0 py-8 animate-marquee2 whitespace-nowrap flex items-center group-hover:pause-animation">
                            @foreach ($industriesForMarquee as $industry)
                                <div class="mx-6 flex-shrink-0 flex items-center justify-center h-16 w-auto sm:h-20"
                                    title="{{ $industry->industri_nama }}">
                                    <img src="{{ $industry->logo_url }}" alt="{{ $industry->industri_nama }} Logo"
                                        class="max-h-full max-w-full object-contain filter grayscale hover:grayscale-0 transition-all duration-300 transform hover:scale-110 cursor-pointer">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-slate-500 text-sm">Mitra industri akan segera ditampilkan di sini.</p>
                    </div>
                @endif


                <div class="mt-16 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 text-center">
                    <div
                        class="p-6 bg-white rounded-xl border border-slate-200 hover:shadow-xl transition-shadow duration-300">
                        <div class="text-4xl font-bold text-sky-600 mb-2">20+</div>
                        <div class="text-slate-600 text-sm font-medium">Perusahaan Partner</div>
                    </div>
                    <div
                        class="p-6 bg-white rounded-xl border border-slate-200 hover:shadow-xl transition-shadow duration-300">
                        <div class="text-4xl font-bold text-purple-600 mb-2">2K+</div>
                        <div class="text-slate-600 text-sm font-medium">Mahasiswa JTI Kompeten</div>
                    </div>

                    <div
                        class="p-6 bg-white rounded-xl border border-slate-200 hover:shadow-xl transition-shadow duration-300 sm:col-span-2 md:col-span-1">
                        <div class="text-4xl font-bold text-amber-600 mb-2">24/7</div>
                        <div class="text-slate-600 text-sm font-medium">Dukungan Aktif</div>
                    </div>
                </div>
            </div>
        </section>


        <section id="about" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6">
                <div class="text-center mb-12 md:mb-16 reveal">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Kenapa Memilih InternSync?</h2>
                    <p class="text-base sm:text-lg text-slate-600 max-w-3xl mx-auto">
                        InternSync hadir untuk merevolusi cara mahasiswa menemukan peluang magang dan perusahaan
                        mendapatkan talenta terbaik. Kami bertujuan menciptakan ekosistem yang efektif, efisien, dan
                        bermanfaat.
                    </p>
                </div>
                <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
                    <div class="reveal">
                        <img src="{{ asset('images/slide2.jpg') }}" alt="Kolaborasi InternSync"
                            class="rounded-lg shadow-lg w-full">
                    </div>
                    <div class="text-slate-700 space-y-6 reveal animation-delay-200">
                        <h3 class="text-2xl font-semibold text-slate-800">Misi Kami</h3>
                        <p class="text-base">
                            Mempermudah mahasiswa dalam menemukan kesempatan magang yang relevan dan berkualitas,
                            sekaligus membantu perusahaan mitra menemukan kandidat yang sesuai kebutuhan mereka.
                        </p>
                        <ul class="space-y-4 text-base">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-sky-500 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Memberikan rekomendasi tempat magang yang <strong class="font-semibold">tepat
                                        sasaran</strong> sesuai profil Anda.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-sky-500 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Meningkatkan <strong class="font-semibold">kecocokan</strong> antara potensi
                                    mahasiswa dengan kebutuhan industri.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-sky-500 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Menyediakan platform <strong class="font-semibold">terintegrasi</strong> untuk
                                    pencarian dan pengelolaan aplikasi magang.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-sky-500 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Meningkatkan <strong class="font-semibold">kesiapan mahasiswa</strong> memasuki
                                    dunia kerja melalui pengalaman bermakna.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>


        <section id="features" class="py-16 md:py-24 bg-slate-50">
            <div class="container mx-auto px-4 sm:px-6">
                <div class="text-center mb-12 md:mb-16 reveal">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Solusi Tepat untuk Kebutuhan Magang
                        Anda</h2>
                    <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto">
                        InternSync dilengkapi dengan berbagai fitur canggih untuk memaksimalkan pengalaman magang Anda.
                    </p>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        class="glassmorphism-light p-6 rounded-lg hover:shadow-lg transition-shadow duration-300 reveal">
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-sky-500 text-white rounded-full mb-6 text-2xl">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-3">Rekomendasi Cerdas</h3>
                        <p class="text-base text-slate-600">
                            Dapatkan rekomendasi magang yang dipersonalisasi berdasarkan bidang studi, keahlian, dan
                            preferensi Anda secara otomatis.
                        </p>
                    </div>
                    <div
                        class="glassmorphism-light p-6 rounded-lg hover:shadow-lg transition-shadow duration-300 reveal animation-delay-100">
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-emerald-500 text-white rounded-full mb-6 text-2xl">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-3">Pencarian Fleksibel</h3>
                        <p class="text-base text-slate-600">
                            Cari peluang magang dengan mudah menggunakan filter berdasarkan lokasi, industri, durasi,
                            dan jenis pekerjaan.
                        </p>
                    </div>
                    <div
                        class="glassmorphism-light p-6 rounded-lg hover:shadow-lg transition-shadow duration-300 reveal animation-delay-200">
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-purple-500 text-white rounded-full mb-6 text-2xl">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-3">Platform Terpusat</h3>
                        <p class="text-base text-slate-600">
                            Semua kebutuhan magang Anda, mulai dari pencarian hingga komunikasi dengan perusahaan, ada
                            dalam satu platform.
                        </p>
                    </div>
                    <div
                        class="glassmorphism-light p-6 rounded-lg hover:shadow-lg transition-shadow duration-300 reveal animation-delay-300">
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-rose-500 text-white rounded-full mb-6 text-2xl">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-3">Informasi Transparan</h3>
                        <p class="text-base text-slate-600">
                            Akses detail lengkap mengenai posisi magang, persyaratan, profil perusahaan, dan lokasi
                            secara jelas dan mudah.
                        </p>
                    </div>
                    <div
                        class="glassmorphism-light p-6 rounded-lg hover:shadow-lg transition-shadow duration-300 reveal animation-delay-400">
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-amber-500 text-white rounded-full mb-6 text-2xl">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-3">Manajemen Aplikasi Mudah</h3>
                        <p class="text-base text-slate-600">
                            Lacak status lamaran magang Anda dan kelola semua aplikasi Anda dengan antarmuka yang
                            intuitif.
                        </p>
                    </div>
                    <div
                        class="glassmorphism-light p-6 rounded-lg hover:shadow-lg transition-shadow duration-300 reveal animation-delay-500">
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-teal-500 text-white rounded-full mb-6 text-2xl">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-3">Persiapan Karir Optimal</h3>
                        <p class="text-base text-slate-600">
                            Dapatkan pengalaman magang yang relevan untuk mengembangkan keterampilan praktis dan
                            meningkatkan kesiapan Anda di dunia kerja.
                        </p>
                    </div>
                </div>
            </div>
        </section>


        <section id="team" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-4 sm:px-6">
                <div class="text-center mb-12 md:mb-16 reveal">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Tim Kami</h2>
                    <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto">
                        Bertemu dengan para profesional di balik InternSync yang berdedikasi untuk kesuksesan Anda.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 justify-center">

                    <div class="team-card bg-slate-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer reveal animation-delay-100 flex flex-col h-full overflow-hidden"
                        data-modal-target="modal-1">
                        <div class="w-full aspect-square"><img src="{{ asset('assets/tim/darma.jpg') }}"
                                alt="Firdaus Yuli Darmawan" class="w-full h-full object-cover"></div>
                        <div class="p-4 flex flex-col flex-grow text-center">
                            <div class="flex-grow mb-3">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Firdaus Yuli Darmawan</h3>
                                <p class="text-sm text-slate-600 min-h-[3em]">Project Manager & Fullstack</p>
                            </div>
                            <p class="text-sm text-sky-600 font-medium mt-auto">Detail</p>
                        </div>
                    </div>

                    <div class="team-card bg-slate-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer reveal animation-delay-200 flex flex-col h-full overflow-hidden"
                        data-modal-target="modal-2">
                        <div class="w-full aspect-square"><img src="{{ asset('assets/tim/shamil.jpg') }}"
                                alt="Abdullah Shamil Basayev" class="w-full h-full object-cover"></div>
                        <div class="p-4 flex flex-col flex-grow text-center">
                            <div class="flex-grow mb-3">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Abdullah Shamil B.</h3>
                                <p class="text-sm text-slate-600 min-h-[3em]">Data Engineer & Fullstack</p>
                            </div>
                            <p class="text-sm text-sky-600 font-medium mt-auto">Detail</p>
                        </div>
                    </div>

                    <div class="team-card bg-slate-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer reveal animation-delay-300 flex flex-col h-full overflow-hidden"
                        data-modal-target="modal-3">
                        <div class="w-full aspect-square"><img src="{{ asset('assets/tim/oltha.jpg') }}"
                                alt="Oltha Rosyeda Al'Haq" class="w-full h-full object-cover"></div>
                        <div class="p-4 flex flex-col flex-grow text-center">
                            <div class="flex-grow mb-3">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Oltha Rosyeda Al'Haq</h3>
                                <p class="text-sm text-slate-600 min-h-[3em]">Content Writer & UI/UX</p>
                            </div>
                            <p class="text-sm text-sky-600 font-medium mt-auto">Detail</p>
                        </div>
                    </div>

                    <div class="team-card bg-slate-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer reveal animation-delay-400 flex flex-col h-full overflow-hidden"
                        data-modal-target="modal-4">
                        <div class="w-full aspect-square"><img src="{{ asset('assets/tim/dimas.jpg') }}"
                                alt="Muhammad Dimas Ajie Nugroho" class="w-full h-full object-cover"></div>
                        <div class="p-4 flex flex-col flex-grow text-center">
                            <div class="flex-grow mb-3">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Muhammad Dimas Ajie N.</h3>
                                <p class="text-sm text-slate-600 min-h-[3em]">UI/UX Designer & QA</p>
                            </div>
                            <p class="text-sm text-sky-600 font-medium mt-auto">Detail</p>
                        </div>
                    </div>

                    <div class="team-card bg-slate-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer reveal animation-delay-500 flex flex-col h-full overflow-hidden sm:col-start-auto md:col-start-auto lg:col-start-auto"
                        data-modal-target="modal-5">
                        <div class="w-full aspect-square"><img src="{{ asset('assets/tim/nanda.jpg') }}"
                                alt="Ananda Satria Putra Nugraha" class="w-full h-full object-cover"></div>
                        <div class="p-4 flex flex-col flex-grow text-center">
                            <div class="flex-grow mb-3">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Ananda Satria Putra N.</h3>
                                <p class="text-sm text-slate-600 min-h-[3em]">Technical Writer</p>
                            </div>
                            <p class="text-sm text-sky-600 font-medium mt-auto">Detail</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <div id="modal-1" class="modal">
            <div
                class="modal-content bg-slate-50 rounded-lg shadow-xl overflow-hidden flex flex-col md:flex-row max-w-4xl w-11/12 mx-auto relative max-h-[90vh]">
                <span
                    class="modal-close-button absolute top-2 right-3 text-3xl font-semibold text-slate-500 hover:text-slate-700 cursor-pointer z-20"
                    data-modal-close="modal-1">&times;</span>
                <div class="md:w-1/2 w-full h-64 md:h-auto overflow-hidden flex-shrink-0"><img
                        src="{{ asset('assets/tim/darma.jpg') }}" alt="Firdaus Yuli Darmawan"
                        class="w-full h-full object-cover object-center"></div>
                <div class="md:w-1/2 w-full p-6 md:p-8 overflow-y-auto">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">Firdaus Yuli Darmawan</h3>
                    <p class="text-md text-sky-600 font-medium mb-4">Project Manager, QA, & Fullstack Developer</p>
                    <hr class="my-4 border-slate-200">
                    <p class="text-base text-slate-700 leading-relaxed text-left">Firdaus memimpin InternSync dengan
                        visi strategis. Ia memastikan setiap proyek berjalan sesuai arah dan tujuan. Sebagai QA, ia
                        menjaga kualitas produk. Di sisi teknis, perannya sebagai Fullstack Developer memungkinkannya
                        membangun sistem secara menyeluruh.</p>
                </div>
            </div>
        </div>

        <div id="modal-2" class="modal">
            <div
                class="modal-content bg-slate-50 rounded-lg shadow-xl overflow-hidden flex flex-col md:flex-row max-w-4xl w-11/12 mx-auto relative max-h-[90vh]">
                <span
                    class="modal-close-button absolute top-2 right-3 text-3xl font-semibold text-slate-500 hover:text-slate-700 cursor-pointer z-20"
                    data-modal-close="modal-2">&times;</span>
                <div class="md:w-1/2 w-full h-64 md:h-auto overflow-hidden flex-shrink-0"><img
                        src="{{ asset('assets/tim/shamil.jpg') }}" alt="Abdullah Shamil Basayev"
                        class="w-full h-full object-cover object-center"></div>
                <div class="md:w-1/2 w-full p-6 md:p-8 overflow-y-auto">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">Abdullah Shamil Basayev</h3>
                    <p class="text-md text-sky-600 font-medium mb-4">Data Engineer & Fullstack Developer</p>
                    <hr class="my-4 border-slate-200">
                    <p class="text-base text-slate-700 leading-relaxed text-left">Shamil adalah penggerak utama di sisi
                        teknis. Sebagai Data Engineer, ia merancang alur data yang efisien. Perannya sebagai Fullstack
                        Developer memungkinkan dia menjembatani frontend dan backend dengan solusi terintegrasi dan
                        inovatif.</p>
                </div>
            </div>
        </div>
        <div id="modal-3" class="modal">
            <div
                class="modal-content bg-slate-50 rounded-lg shadow-xl overflow-hidden flex flex-col md:flex-row max-w-4xl w-11/12 mx-auto relative max-h-[90vh]">
                <span
                    class="modal-close-button absolute top-2 right-3 text-3xl font-semibold text-slate-500 hover:text-slate-700 cursor-pointer z-20"
                    data-modal-close="modal-3">&times;</span>
                <div class="md:w-1/2 w-full h-64 md:h-auto overflow-hidden flex-shrink-0"><img
                        src="{{ asset('assets/tim/oltha.jpg') }}" alt="Oltha Rosyeda Al'Haq"
                        class="w-full h-full object-cover object-center"></div>
                <div class="md:w-1/2 w-full p-6 md:p-8 overflow-y-auto">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">Oltha Rosyeda Al'Haq</h3>
                    <p class="text-md text-sky-600 font-medium mb-4">Content Writer & UI/UX Designer</p>
                    <hr class="my-4 border-slate-200">
                    <p class="text-base text-slate-700 leading-relaxed text-left">Oltha adalah kreator di balik wajah
                        dan kata-kata InternSync. Ia mengemas informasi menjadi visual dan konten yang menarik, serta
                        menghadirkan pengalaman pengguna yang intuitif dan menyenangkan. Kreativitas dan empatinya
                        membuat platform ini terasa hidup.</p>
                </div>
            </div>
        </div>
        <div id="modal-4" class="modal">
            <div
                class="modal-content bg-slate-50 rounded-lg shadow-xl overflow-hidden flex flex-col md:flex-row max-w-4xl w-11/12 mx-auto relative max-h-[90vh]">
                <span
                    class="modal-close-button absolute top-2 right-3 text-3xl font-semibold text-slate-500 hover:text-slate-700 cursor-pointer z-20"
                    data-modal-close="modal-4">&times;</span>
                <div class="md:w-1/2 w-full h-64 md:h-auto overflow-hidden flex-shrink-0"><img
                        src="{{ asset('assets/tim/dimas.jpg') }}" alt="Muhammad Dimas Ajie Nugroho"
                        class="w-full h-full object-cover object-center"></div>
                <div class="md:w-1/2 w-full p-6 md:p-8 overflow-y-auto">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">Muhammad Dimas Ajie Nugroho</h3>
                    <p class="text-md text-sky-600 font-medium mb-4">UI/UX Designer & Quality Assurance</p>
                    <hr class="my-4 border-slate-200">
                    <p class="text-base text-slate-700 leading-relaxed text-left">Dimas adalah penjaga kualitas dan
                        kenyamanan pengguna. Ia memastikan desain tampil sempurna dan fungsional, serta setiap fitur
                        berjalan tanpa cela. Fokusnya pada detail dan pengalaman pengguna menjadi kunci kelancaran
                        operasional InternSync.</p>
                </div>
            </div>
        </div>
        <div id="modal-5" class="modal">
            <div
                class="modal-content bg-slate-50 rounded-lg shadow-xl overflow-hidden flex flex-col md:flex-row max-w-4xl w-11/12 mx-auto relative max-h-[90vh]">
                <span
                    class="modal-close-button absolute top-2 right-3 text-3xl font-semibold text-slate-500 hover:text-slate-700 cursor-pointer z-20"
                    data-modal-close="modal-5">&times;</span>
                <div class="md:w-1/2 w-full h-64 md:h-auto overflow-hidden flex-shrink-0"><img
                        src="{{ asset('assets/tim/nanda.jpg') }}" alt="Ananda Satria Putra Nugraha"
                        class="w-full h-full object-cover object-center"></div>
                <div class="md:w-1/2 w-full p-6 md:p-8 overflow-y-auto">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">Ananda Satria Putra Nugroho</h3>
                    <p class="text-md text-sky-600 font-medium mb-4">Technical Writer & Documentation Specialist </p>
                    <hr class="my-4 border-slate-200">
                    <p class="text-base text-slate-700 leading-relaxed text-left">Ananda adalah arsitek pengetahuan di balik InternSync, 
                        yang mengubah kompleksitas teknis menjadi panduan yang mudah dicerna. Dengan ketelitiannya, 
                        ia menciptakan dokumentasi proyek yang komprehensif dan panduan pengguna yang intuitif, 
                        menjembatani kesenjangan antara teknologi dengan pengguna akhir maupun developer.</p>
                </div>
            </div>
        </div>


        <section id="testimonials" class="py-16 md:py-24 bg-slate-50">
            <div class="container mx-auto px-4 sm:px-6">
                <div class="text-center mb-12 md:mb-16 reveal">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Apa Kata Mereka?</h2>
                    <p class="text-base sm:text-lg text-slate-600 max-w-2xl mx-auto">
                        Pengalaman positif dari mahasiswa dan perusahaan yang telah menggunakan InternSync.
                    </p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 reveal flex flex-col">
                        <div class="flex items-center mb-4">
                            <img src="{{ asset('assets/default-profile.png') }}" alt="Adinda Putri" class="w-12 h-12 rounded-full mr-4 bg-slate-200 object-cover">
                            <div>
                                <h4 class="font-semibold text-slate-800">Adinda Putri</h4>
                                <p class="text-sm text-slate-500">Mahasiswa Desain Komunikasi Visual</p>
                            </div>
                        </div>
                        <p class="text-base text-slate-700 italic flex-grow">"InternSync sangat membantu saya menemukan tempat magang yang sesuai dengan passion saya di bidang UI/UX. Prosesnya cepat dan platformnya sangat mudah digunakan!"</p>
                    </div>


                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 reveal flex flex-col animation-delay-200">
                        <div class="flex items-center mb-4">
                            <img src="{{ asset('assets/default-profile.png') }}" alt="Bima Sanjaya" class="w-12 h-12 rounded-full mr-4 bg-slate-200 object-cover">
                            <div>
                                <h4 class="font-semibold text-slate-800">Bima Sanjaya</h4>
                                <p class="text-sm text-slate-500">Mahasiswa Teknik Informatika</p>
                            </div>
                        </div>
                        <p class="text-base text-slate-700 italic flex-grow">"Berkat rekomendasi cerdas dari InternSync, saya diterima magang di salah satu startup teknologi terkemuka. Pengalaman yang luar biasa untuk portofolio saya."</p>
                    </div>


                    <div class="bg-white p-8 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 reveal flex flex-col animation-delay-400">
                        <div class="flex items-center mb-4">
                            <img src="{{ asset('assets/default-profile.png') }}" alt="Citra Lestari" class="w-12 h-12 rounded-full mr-4 bg-slate-200 object-cover">
                            <div>
                                <h4 class="font-semibold text-slate-800">Citra Lestari</h4>
                                <p class="text-sm text-slate-500">Mahasiswa Sistem Informasi</p>
                            </div>
                        </div>
                        <p class="text-base text-slate-700 italic flex-grow">"Fitur pelacakan lamaran sangat transparan. Saya tidak lagi bingung dengan status aplikasi saya. Sangat direkomendasikan untuk mahasiswa tingkat akhir!"</p>
                    </div>

                </div>
            </div>
        </section>


        <section id="contact" class="py-16 md:py-24 text-center bg-white">
            <div class="container mx-auto px-4 sm:px-6 reveal">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Siap Memulai Perjalanan Magangmu?</h2>
                <p class="text-base sm:text-lg text-slate-700 max-w-xl mx-auto mb-10">
                    Bergabunglah dengan ribuan mahasiswa dan ratusan perusahaan. Daftar sekarang dan buka peluang
                    karirmu!
                </p>
                <div class="flex justify-center">
                    <a href="{{ route('signup') }}"
                        class="w-full sm:w-auto btn-primary text-base font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300">Gabung
                        Sekarang</a>
                </div>
            </div>
        </section>
    </main>


    <footer class="bg-slate-800 text-slate-300 border-t border-slate-700 py-12">
        <div class="container mx-auto px-4 sm:px-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8 text-center sm:text-left">
                <div>
                    <h5 class="text-lg font-semibold text-white mb-4">InternSync</h5>
                    <p class="text-sm text-slate-400">Menghubungkan talenta muda dengan peluang karir terbaik.</p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Navigasi</h5>
                    <ul class="space-y-2">
                        <li><a href="#about"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Tentang
                                Kami</a></li>
                        <li><a href="#features"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Fitur</a>
                        </li>
                        <li><a href="#team"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Tim</a></li>
                        <li><a href="#testimonials"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Testimoni</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Untuk Pengguna</h5>
                    <ul class="space-y-2">
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Mahasiswa</a>
                        </li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Perusahaan</a>
                        </li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Institusi
                                Pendidikan</a></li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Pusat
                                Bantuan</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Ikuti Kami</h5>
                    <div class="flex space-x-4 justify-center sm:justify-start">
                        <a href="#" class="text-slate-400 hover:text-sky-300 transition duration-300 text-xl"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#" class="text-slate-400 hover:text-sky-300 transition duration-300 text-xl"><i
                                class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-slate-400 hover:text-sky-300 transition duration-300 text-xl"><i
                                class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-slate-700 my-8">
            <div class="text-center text-slate-500 text-sm">
                &copy; <span id="currentYear"></span> InternSync. Hak Cipta Dilindungi.
                <div class="mt-2 sm:mt-0 sm:inline-block">
                    <a href="#"
                        class="ml-0 sm:ml-4 mt-2 sm:mt-0 inline-block hover:text-sky-300 transition duration-300">Kebijakan
                        Privasi</a>
                    <a href="#" class="ml-4 hover:text-sky-300 transition duration-300">Ketentuan Layanan</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            const currentYearElement = document.getElementById('currentYear');
            if (currentYearElement) {
                currentYearElement.textContent = new Date().getFullYear();
            }

            const revealElements = document.querySelectorAll('.reveal');
            const revealOnScroll = () => {
                const windowHeight = window.innerHeight;
                revealElements.forEach(el => {
                    const elementTop = el.getBoundingClientRect().top;
                    if (elementTop < windowHeight - 80) {
                        el.classList.add('visible');
                    }
                });
            };
            window.addEventListener('scroll', revealOnScroll);
            revealOnScroll();

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (targetId.length > 1 && document.querySelector(targetId)) {
                        e.preventDefault();
                        const targetElement = document.querySelector(targetId);
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                            mobileMenu.classList.add('hidden');
                        }
                    }
                });
            });

            const teamCards = document.querySelectorAll('.team-card');
            const modals = document.querySelectorAll('.modal');
            const closeButtons = document.querySelectorAll('.modal-close-button');

            teamCards.forEach(card => {
                card.addEventListener('click', () => {
                    const modalId = card.getAttribute('data-modal-target');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.classList.add('show');
                        document.body.style.overflow = 'hidden';
                    }
                });
            });

            const closeModal = (modal) => {
                if (modal) {
                    modal.classList.remove('show');
                    document.body.style.overflow = '';
                }
            };

            closeButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const modal = button.closest('.modal');
                    closeModal(modal);
                });
            });

            modals.forEach(modal => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    modals.forEach(modal => {
                        if (modal.classList.contains('show')) {
                            closeModal(modal);
                        }
                    });
                }
            });

            const wrapper = document.getElementById('carouselWrapper');
            if (wrapper) {
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const dotsContainer = document.getElementById('carouselDots');
                const slides = wrapper.querySelectorAll('.carousel-slide');
                const totalSlides = slides.length;
                let currentSlide = 0;
                let autoPlayInterval;

                dotsContainer.innerHTML = '';
                for (let i = 0; i < totalSlides; i++) {
                    const button = document.createElement('button');
                    button.classList.add('carousel-dot', 'w-2.5', 'h-2.5', 'sm:w-3', 'sm:h-3', 'rounded-full',
                        'bg-slate-300', 'hover:bg-sky-400', 'transition-all', 'duration-300');
                    button.dataset.slide = i;
                    dotsContainer.appendChild(button);
                }
                const dots = dotsContainer.querySelectorAll('.carousel-dot');

                function updateCarousel() {
                    wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
                    dots.forEach((dot, index) => {
                        if (index === currentSlide) {
                            dot.classList.add('active', 'bg-sky-500', 'scale-125');
                        } else {
                            dot.classList.remove('active', 'bg-sky-500', 'scale-125');
                        }
                    });
                }

                function slideTo(slideIndex) {
                    currentSlide = slideIndex;
                    updateCarousel();
                }

                function nextSlide() {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    updateCarousel();
                }

                function prevSlide() {
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                    updateCarousel();
                }

                function startAutoPlay() {
                    stopAutoPlay();
                    autoPlayInterval = setInterval(nextSlide, 5000);
                }

                function stopAutoPlay() {
                    clearInterval(autoPlayInterval);
                }

                nextBtn.addEventListener('click', () => {
                    nextSlide();
                    stopAutoPlay();
                });
                prevBtn.addEventListener('click', () => {
                    prevSlide();
                    stopAutoPlay();
                });

                dots.forEach(dot => {
                    dot.addEventListener('click', (e) => {
                        slideTo(parseInt(e.target.dataset.slide));
                        stopAutoPlay();
                    });
                });

                const carouselContainer = document.getElementById('imageCarouselContainer');
                carouselContainer.addEventListener('mouseenter', stopAutoPlay);
                carouselContainer.addEventListener('mouseleave', startAutoPlay);

                let touchStartX = 0;
                wrapper.addEventListener('touchstart', (e) => touchStartX = e.touches[0].clientX);
                wrapper.addEventListener('touchend', (e) => {
                    const touchEndX = e.changedTouches[0].clientX;
                    if (touchStartX - touchEndX > 50) nextSlide();
                    if (touchStartX - touchEndX < -50) prevSlide();
                    stopAutoPlay();
                });

                updateCarousel();
                startAutoPlay();
            }
        });
    </script>
</body>

</html>
