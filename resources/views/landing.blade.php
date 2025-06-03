<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternSync - Temukan Magang Impianmu!</title> {{-- Judul disesuaikan --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    {{-- Montserrat Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: "Montserrat", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400 background-color: #F1F5F9;
            color: #1E293B;
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
            {{-- Dark (Slate 900) --}} color: #FFFFFF;
            {{-- White --}}
        }

        .btn-primary:hover {
            background-color: #1E293B;
            {{-- Slightly lighter dark (Slate 800) --}}
        }

        .btn-secondary {
            background-color: #E2E8F0;
            {{-- Light Gray (Slate 200) --}} color: #0F172A;
            {{-- Dark (Slate 900) --}} border: 1px solid #CBD5E1;
            {{-- Slate 300 --}}
        }

        .btn-secondary:hover {
            background-color: #CBD5E1;
            {{-- Darker Gray (Slate 300) --}}
        }

        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        {{-- Custom animation delays --}} .animation-delay-100 {
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

        {{-- Modal Styles --}} .modal {
            display: none;
            {{-- Hidden by default --}} position: fixed;
            z-index: 1000;
            {{-- Sit on top --}} left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            {{-- Enable scroll if needed --}} background-color: rgba(0, 0, 0, 0.5);
            {{-- Black w/ opacity --}} justify-content: center;
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
                transform: translate3d(100%, 0, 0);
            }

            100% {
                transform: translate3d(-100%, 0, 0);
            }
        }

        @keyframes marquee2 {
            0% {
                transform: translate3d(200%, 0, 0);
            }

            100% {
                transform: translate3d(0%, 0, 0);
            }
        }

        .animate-marquee {
            animation: marquee 25s linear infinite;
        }

        .animate-marquee2 {
            animation: marquee2 25s linear infinite;
        }

        .group:hover .pause-animation {
            animation-play-state: paused;
        }

        .glassmorphism-light {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .carousel-dot.active {
            background-color: #0ea5e9;
            transform: scale(1.2);
        }

        @media (max-width: 640px) {

            .animate-marquee,
            .animate-marquee2 {
                animation-duration: 20s;
            }
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body class="antialiased">

    {{-- Header --}}
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md shadow-sm">
        <nav class="container mx-auto px-6 py-2 flex justify-between items-center text-sm">
            <a href="#" class="text-2xl font-bold text-sky-600"> {{-- Warna brand InternSync (contoh) --}}
                <img src="{{ asset('softTemplate/assets/img/LogoInternSync.png') }}" alt="InternSync Logo"
                    class="h-8">
                {{-- Logo InternSync --}}
            </a>
            <div class="hidden md:flex items-center justify-center space-x-6">
                {{-- Navigasi utama --}}
                <a href="#hero" class="text-slate-600 hover:text-blue-600 transition duration-300">Beranda</a>
                <a href="#about" class="text-slate-600 hover:text-blue-600 transition duration-300">Tentang Kami</a>
                <a href="#features" class="text-slate-600 hover:text-blue-600 transition duration-300">Fitur</a>
                <a href="#team" class="text-slate-600 hover:text-blue-600 transition duration-300">Tim Kami</a>
                {{-- TAMBAHKAN NAVIGASI TIM --}}
                <a href="#testimonials" class="text-slate-600 hover:text-sky-600 transition duration-300">Testimoni</a>
                <a href="{{ route('login') }}"
                    class="py-2 px-4 text-slate-600 hover:text-sky-600 transition duration-300">Masuk</a>
                <a href="{{ route('signup') }}"
                    class="btn-primary py-2 px-4 rounded-md font-semibold shadow-sm hover:shadow-md transition duration-300 text-sm">Daftar
                    Sekarang</a>
            </div>
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-slate-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </nav>
        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden bg-white px-6 pb-4 space-y-2 shadow-lg text-sm">
            <a href="#hero" class="block text-slate-600 hover:text-sky-600 transition duration-300 py-2">Beranda</a>
            <a href="#about" class="block text-slate-600 hover:text-sky-600 transition duration-300 py-2">Tentang
                Kami</a>
            <a href="#features" class="block text-slate-600 hover:text-sky-600 transition duration-300 py-2">Fitur</a>
            <a href="#team" class="block text-slate-600 hover:text-sky-600 transition duration-300 py-2">Tim Kami</a>
            {{-- TAMBAHKAN NAVIGASI TIM (MOBILE) --}}
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
        {{-- Hero Section --}}
        <section id="hero"
            class="relative min-h-screen flex flex-col items-center justify-center text-center overflow-hidden pt-20 md:pt-0 bg-white">
            {{-- Floating Icons - Disesuaikan untuk tema magang --}}
            <div
                class="absolute top-[15%] left-[10%] md:top-[30%] md:left-[15%] w-16 h-16 md:w-20 md:h-20 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform -rotate-6 opacity-80">
                <i class="fas fa-briefcase text-3xl md:text-4xl text-sky-600"></i> {{-- Ikon magang --}}
            </div>
            <div
                class="absolute top-[25%] right-[8%] md:top-[30%] md:right-[12%] w-14 h-14 md:w-16 md:h-16 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform rotate-3 opacity-70 animation-delay-200">
                <i class="fas fa-user-graduate text-3xl md:text-4xl text-purple-600"></i> {{-- Ikon mahasiswa --}}
            </div>
            <div
                class="absolute bottom-[20%] left-[5%] md:bottom-[25%] md:left-[10%] w-12 h-12 md:w-14 md:h-14 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform rotate-12 opacity-60 animation-delay-400">
                <i class="fas fa-building text-2xl md:text-3xl text-indigo-600"></i> {{-- Ikon perusahaan --}}
            </div>
            <div
                class="absolute top-[45%] left-[25%] md:top-[50%] md:left-[30%] w-10 h-10 md:w-12 md:h-12 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform rotate-2 opacity-70 animation-delay-300 hidden lg:flex">
                <i class="fas fa-search-location text-xl md:text-2xl text-teal-600"></i> {{-- Ikon pencarian --}}
            </div>
            <div
                class="absolute top-[60%] right-[22%] md:top-[65%] md:right-[8%] w-12 h-12 md:w-14 md:h-14 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform -rotate-3 opacity-65 animation-delay-500 hidden lg:flex">
                <i class="fas fa-link text-xl md:text-2xl text-amber-600"></i> {{-- Ikon koneksi --}}
            </div>
            <div
                class="absolute top-[5%] left-[30%] md:top-[10%] md:left-[10%] w-16 h-16 md:w-20 md:h-20 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform rotate-3 opacity-90 z-20">
                <i class="fas fa-rocket text-3xl md:text-4xl text-rose-600"></i> {{-- Ikon inovasi/karir --}}
            </div>
            <div
                class="absolute top-[7%] right-[25%] md:top-[10%] md:right-[10%] w-14 h-14 md:w-16 md:h-16 glassmorphism-light rounded-lg flex items-center justify-center animate-pulse transform -rotate-6 opacity-85 z-20">
                <i class="fas fa-network-wired text-3xl md:text-4xl text-green-600"></i> {{-- Ikon jaringan/relasi --}}
            </div>

            <div class="container mx-auto px-6 py-16 relative z-10 flex flex-col items-center">
                <a href="#features"
                    class="inline-block bg-sky-500/20 text-sky-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-sky-500/30 transition duration-300 mb-6 reveal">
                    Lihat Mitra yang terafiliasi <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <h1
                    class="text-4xl sm:text-5xl md:text-6xl lg:text-4xl font-bold text-slate-900 mb-6 reveal animation-delay-100 text-center">
                    <em>Intern</em>
                    <span
                        style="
background: linear-gradient(to right, #3b82f6, #06b6d4);
-webkit-background-clip: text;
-webkit-text-fill-color: transparent;
display: inline-block;">
                    <em>Sync</em>
                    </span>
                    <br class="hidden md:block">
                    Jembatan Menuju Karir Impian
                </h1>

                <p class="text-sm text-slate-700 max-w-2xl mx-auto mb-10 reveal animation-delay-200 text-center">
                    Dapatkan rekomendasi magang atau kerja praktek yang sesuai dengan bidang studi, keterampilan, dan
                    preferensi Anda. Hubungkan diri dengan perusahaan mitra terpercaya.
                </p>
                <div
                    class="space-y-4 sm:space-y-0 sm:space-x-4 flex flex-col sm:flex-row justify-center items-center reveal animation-delay-300 mb-12">
                    <a href="{{ route('signup') }}"
                        class="w-full sm:w-auto btn-primary text-sm font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300">Cari
                        Magang Sekarang</a>
                    <a href="#about"
                        class="w-full sm:w-auto btn-secondary text-sm font-semibold py-3 px-8 rounded-lg shadow-sm hover:shadow-md transition duration-300">Pelajari
                        Lebih Lanjut</a>
                </div>

                {{-- Enhanced Image Carousel --}}
                <div class="mt-8 max-w-5xl w-full mx-auto reveal animation-delay-400 relative mb-20"
                    id="imageCarouselContainer">
                    <div class="bg-white p-6 sm:p-8 rounded-xl border border-sm relative overflow-hidden">
                        {{-- Carousel Wrapper --}}
                        <div class="carousel-wrapper flex transition-transform duration-700 ease-in-out"
                            id="carouselWrapper">
                            {{-- Slide 1 --}}
                            <div class="carousel-slide min-w-full">
                                <img src="{{ asset('images/corausel/corausel1.png') }}"
                                    alt="InternSync Platform Mockup 1"
                                    class="rounded-xl w-full h-auto object-cover shadow-lg"
                                    style="max-height: 500px;">
                            </div>
                            {{-- Slide 2 --}}
                            <div class="carousel-slide min-w-full">
                                <img src="{{ asset('images/corausel/corausel2.png') }}"
                                    alt="InternSync Platform Mockup 2"
                                    class="rounded-xl w-full h-auto object-cover shadow-lg"
                                    style="max-height: 500px;">
                            </div>
                        </div>

                        {{-- Enhanced Carousel Navigation --}}
                        <button id="prevBtn"
                            class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-white/90 text-slate-700 p-3 rounded-full hover:bg-white hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-sky-500 z-10">
                            <i class="fas fa-chevron-left text-lg"></i>
                        </button>
                        <button id="nextBtn"
                            class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-white/90 text-slate-700 p-3 rounded-full hover:bg-white hover:shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-sky-500 z-10">
                            <i class="fas fa-chevron-right text-lg"></i>
                        </button>

                        {{-- Enhanced Carousel Dots --}}
                        <div id="carouselDots"
                            class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3">
                            <button
                                class="carousel-dot w-3 h-3 rounded-full bg-sky-500 opacity-100 transition-all duration-300"
                                data-slide="0"></button>
                            <button
                                class="carousel-dot w-3 h-3 rounded-full bg-slate-300 opacity-70 hover:opacity-100 transition-all duration-300"
                                data-slide="1"></button>
                        </div>

                        {{-- Auto-play indicator --}}
                        <div class="absolute top-4 right-4 bg-white/90 rounded-full px-3 py-1 text-xs text-slate-600">
                            <i class="fas fa-play mr-1"></i> Auto-play
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Enhanced Trusted Industries Marquee Section --}}
        <section class="w-full bg-gradient-to-r from-slate-50 via-white to-slate-50 py-16">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12">
                    <h3 class="text-slate-800 font-bold text-2xl sm:text-3xl mb-4">
                        Dipercaya oleh Industri Terkemuka
                    </h3>
                    <p class="text-slate-600 text-lg max-w-2xl mx-auto">
                        Bergabung dengan ribuan mahasiswa yang telah menemukan peluang magang terbaik di
                        perusahaan-perusahaan ternama
                    </p>
                </div>

                <div class="relative flex overflow-x-hidden group">
                    {{-- First marquee row --}}
                    <div class="py-8 animate-marquee whitespace-nowrap flex group-hover:pause-animation">
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-sky-600 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-microsoft"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-red-500 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-google"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-orange-500 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-amazon"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-gray-800 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-apple"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-blue-600 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-meta"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-blue-700 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-linkedin"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-gray-900 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-github"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-purple-600 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-slack"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-indigo-600 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-atlassian"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-green-600 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-spotify"></i></span>
                    </div>

                    {{-- Second marquee row (duplicate for seamless loop) --}}
                    <div
                        class="absolute top-0 py-8 animate-marquee2 whitespace-nowrap flex group-hover:pause-animation">
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-sky-600 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-microsoft"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-red-500 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-google"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-orange-500 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-amazon"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-gray-800 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-apple"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-blue-600 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-meta"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-blue-700 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-linkedin"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-gray-900 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-github"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-purple-600 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-slack"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-indigo-600 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-atlassian"></i></span>
                        <span
                            class="mx-8 text-6xl sm:text-7xl md:text-8xl text-slate-400 hover:text-green-600 transition-colors duration-300 transform hover:scale-110"><i
                                class="fab fa-spotify"></i></span>
                    </div>
                </div>

                {{-- Stats section --}}
                <div class="mt-16 grid grid-cols-2 md:grid-cols-3 gap-8 text-center">
                    <div class="p-6 bg-white rounded-xl border border-sm hover:shadow-xl transition-shadow duration-300">
                        <div class="text-3xl font-bold text-sky-600 mb-2">20+</div>
                        <div class="text-slate-600 text-sm">Perusahaan Partner</div>
                    </div>
                    <div class="p-6 bg-white rounded-xl border border-sm hover:shadow-xl transition-shadow duration-300">
                        <div class="text-3xl font-bold text-purple-600 mb-2">2K+</div>
                        <div class="text-slate-600 text-sm">Mahasiswa JTI Kompeten</div>
                    </div>
                    <div class="p-6 bg-white rounded-xl border border-sm hover:shadow-xl transition-shadow duration-300">
                        <div class="text-3xl font-bold text-amber-600 mb-2">24/7</div>
                        <div class="text-slate-600 text-sm">Dukungan Aktif</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- About Section (Tentang InternSync) --}}
        <section id="about" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12 md:mb-16 reveal">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Kenapa Memilih InternSync?</h2>
                    <p class="text-sm text-slate-600 max-w-3xl mx-auto">
                        InternSync hadir untuk merevolusi cara mahasiswa menemukan peluang magang dan perusahaan
                        mendapatkan talenta terbaik. Kami bertujuan untuk menciptakan ekosistem magang yang efektif,
                        efisien, dan bermanfaat bagi semua pihak.
                    </p>
                </div>
                <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
                    <div class="reveal">
                        <img src="{{ asset('assets/kolaborasi.jpg') }}" alt="Kolaborasi InternSync"
                            class="rounded-lg shadow-lg w-full">
                    </div>
                    <div class="text-slate-700 space-y-6 reveal animation-delay-200">
                        <h3 class="text-2xl font-semibold text-slate-800">Misi Kami</h3>
                        <p class="text-sm">
                            Mempermudah mahasiswa dalam menemukan kesempatan magang atau kerja praktek yang relevan dan
                            berkualitas, sekaligus membantu perusahaan mitra menemukan kandidat yang sesuai dengan
                            kebutuhan mereka.
                        </p>
                        <ul class="space-y-3 text-sm">
                            <li class="flex">
                                <i class="fas fa-check-circle text-sky-500 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Memberikan rekomendasi tempat magang yang <strong class="font-semibold">tepat
                                        sasaran</strong> sesuai profil Anda.</span>
                            </li>
                            <li class="flex">
                                <i class="fas fa-check-circle text-sky-500 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Meningkatkan <strong class="font-semibold">kecocokan</strong> antara potensi
                                    mahasiswa dengan kebutuhan industri.</span>
                            </li>
                            <li class="flex">
                                <i class="fas fa-check-circle text-sky-500 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Menyediakan platform <strong class="font-semibold">terintegrasi</strong> untuk
                                    pencarian dan pengelolaan aplikasi magang.</span>
                            </li>
                            <li class="flex">
                                <i class="fas fa-check-circle text-sky-500 mr-3 mt-1 flex-shrink-0"></i>
                                <span>Meningkatkan <strong class="font-semibold">kesiapan mahasiswa</strong> dalam
                                    memasuki dunia kerja melalui pengalaman magang yang bermakna.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- Features Section --}}
        <section id="features" class="py-16 md:py-24 bg-slate-50">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12 md:mb-16 reveal">
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Solusi Tepat untuk Kebutuhan Magang
                        Anda</h2>
                    <p class="text-sm text-slate-600 max-w-2xl mx-auto">
                        InternSync dilengkapi dengan berbagai fitur canggih untuk memaksimalkan pengalaman magang Anda,
                        baik sebagai mahasiswa maupun perwakilan perusahaan.
                    </p>
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-10">
                    <div
                        class="glassmorphism-light p-6 rounded-lg hover:shadow-lg transition-shadow duration-300 reveal">
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-sky-500 text-white rounded-full mb-6 text-2xl">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-3">Rekomendasi Cerdas</h3>
                        <p class="text-sm text-slate-600">
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
                        <p class="text-sm text-slate-600">
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
                        <p class="text-sm text-slate-600">
                            Semua kebutuhan magang Anda, mulai dari pencarian, pelamaran, hingga komunikasi dengan
                            perusahaan, ada dalam satu platform.
                        </p>
                    </div>
                    <div
                        class="glassmorphism-light p-6 rounded-lg hover:shadow-lg transition-shadow duration-300 reveal animation-delay-300">
                        <div
                            class="flex items-center justify-center w-16 h-16 bg-rose-500 text-white rounded-full mb-6 text-2xl">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800 mb-3">Informasi Transparan</h3>
                        <p class="text-sm text-slate-600">
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
                        <p class="text-sm text-slate-600">
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
                        <p class="text-sm text-slate-600">
                            Dapatkan pengalaman magang yang relevan untuk mengembangkan keterampilan praktis dan
                            meningkatkan kesiapan Anda di dunia kerja.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Team Section --}}
        <section id="team" class="py-16 md:py-24 bg-white">
            <div class="container mx-auto px-6">
                <div class="text-center mb-12 md:mb-16 reveal">
                    <h2 class="text-3xl md:text-3xl font-bold text-slate-900 mb-4">
                        Tim Kami
                    </h2>
                    <p class="text-sm text-slate-600 max-w-2xl mx-auto">
                        Bertemu dengan para profesional di balik InternSync yang berdedikasi untuk kesuksesan Anda.
                    </p>
                </div>
                {{-- Modifikasi grid di sini untuk 5 card.
             Untuk 5 item, kita bisa gunakan sm:grid-cols-2 (2, 2, 1), md:grid-cols-3 (3, 2), lg:grid-cols-5 (5).
             Jika lg:grid-cols-5 terlalu sempit, Anda bisa mempertimbangkan lg:grid-cols-3 sehingga menjadi 3 di baris pertama dan 2 di baris kedua.
             Atau untuk 5 item agar lebih seimbang di berbagai ukuran, Anda bisa menggunakan kombinasi atau bahkan flexbox dengan wrap jika perlu.
             Kita coba dengan: grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5
        --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
                    {{-- Card 1 --}}
                    <div class="team-card bg-slate-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer reveal animation-delay-300 flex flex-col h-full overflow-hidden"
                        data-modal-target="modal-1">
                        <div class="w-full aspect-square">
                            <img src="{{ asset('assets/tim/darma.jpg') }}"
                                alt="Firdaus Yuli Darmawan - Project Manager, Quality Assurance, Fullstack Developer"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex flex-col flex-grow text-center">
                            <div class="flex-grow mb-3">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Firdaus Yuli Darmawan</h3>
                                <p class="text-xs text-slate-600 min-h-[2.4em]">
                                    Project Manager - Quality Assurance - Fullstack Developer
                                </p>
                            </div>
                            <p class="text-sm text-sky-600 font-medium">Pelajari Lebih Lanjut</p>
                        </div>
                    </div>

                    {{-- Card 2 --}}
                    <div class="team-card bg-slate-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer reveal animation-delay-300 flex flex-col h-full overflow-hidden"
                        data-modal-target="modal-2">
                        <div class="w-full aspect-square">
                            <img src="{{ asset('assets/tim/shamil.jpg') }}"
                                alt="Abdullah Shamil Basayev - Data Engineer, Fullstack Developer"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex flex-col flex-grow text-center">
                            <div class="flex-grow mb-3">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Abdullah Shamil Basayev</h3>
                                <p class="text-xs text-slate-600 min-h-[2.4em]">
                                    Data Engineer - Fullstack Developer
                                </p>
                            </div>
                            <p class="text-sm text-sky-600 font-medium">Pelajari Lebih Lanjut</p>
                        </div>
                    </div>

                    {{-- Card 3 --}}
                    <div class="team-card bg-slate-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer reveal animation-delay-300 flex flex-col h-full overflow-hidden"
                        data-modal-target="modal-3">
                        <div class="w-full aspect-square">
                            <img src="{{ asset('assets/tim/oltha.jpg') }}"
                                alt="Oltha Rosyeda Al'Haq - Content Writer, UI/UX Designer"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex flex-col flex-grow text-center">
                            <div class="flex-grow mb-3">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Oltha Rosyeda Al'Haq</h3>
                                <p class="text-xs text-slate-600 min-h-[2.4em]">
                                    Content Writer - UI / UX Designer
                                </p>
                            </div>
                            <p class="text-sm text-sky-600 font-medium">Pelajari Lebih Lanjut</p>
                        </div>
                    </div>

                    {{-- Card 4 --}}
                    <div class="team-card bg-slate-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer reveal animation-delay-300 flex flex-col h-full overflow-hidden"
                        data-modal-target="modal-4">
                        <div class="w-full aspect-square">
                            <img src="{{ asset('assets/tim/dimas.jpg') }}"
                                alt="Muhammad Dimas Ajie Nugroho - UI/UX Designer, Quality Assurance"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex flex-col flex-grow text-center">
                            <div class="flex-grow mb-3">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Muhammad Dimas Ajie Nugroho</h3>
                                <p class="text-xs text-slate-600 min-h-[2.4em]">
                                    UI/UX Designer - Quality Assurance
                                </p>
                            </div>
                            <p class="text-sm text-sky-600 font-medium">Pelajari Lebih Lanjut</p>
                        </div>
                    </div>

                    {{-- Card 5 (BARU) - Ganti dengan data sebenarnya --}}
                    <div class="team-card bg-slate-50 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 cursor-pointer reveal animation-delay-300 flex flex-col h-full overflow-hidden"
                        data-modal-target="modal-5"> {{-- Pastikan data-modal-target unik --}}
                        <div class="w-full aspect-square">
                            <img src="{{ asset('assets/tim/nanda.jpg') }}" {{-- Ganti path gambar --}}
                                alt="Nama Anggota Baru - Jabatan Anggota Baru" {{-- Ganti alt text --}}
                                class="w-full h-full object-cover">
                        </div>
                        <div class="p-4 flex flex-col flex-grow text-center">
                            <div class="flex-grow mb-3">
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Ananda Satria Putra Nugraha</h3>
                                {{-- Ganti nama --}}
                                <p class="text-xs text-slate-600 min-h-[2.4em]">
                                    Jabatan Anggota Baru {{-- Ganti jabatan --}}
                                </p>
                            </div>
                            <p class="text-sm text-sky-600 font-medium">Pelajari Lebih Lanjut</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- Modals for Team Members --}}
        <div id="modal-1" class="modal">
            <div
                class="modal-content bg-slate-50 rounded-lg shadow-xl overflow-hidden flex flex-col md:flex-row max-w-4xl w-11/12 mx-auto relative max-h-[95vh]">
                <span
                    class="modal-close-button absolute top-2 right-3 text-3xl font-semibold text-slate-500 hover:text-slate-700 cursor-pointer z-20"
                    data-modal-close="modal-1">&times;</span>
                <div class="md:w-1/2 w-full max-h-[400px] md:max-h-none overflow-hidden">
                    <img src="{{ asset('assets/tim/darma.jpg') }}" alt="Firdaus Yuli Darmawan"
                        class="w-full h-full object-cover object-center rounded-lg">
                </div>
                <div class="md:w-1/2 w-full p-5 md:p-6 overflow-y-auto">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">Firdaus Yuli Darmawan</h3>
                    <p class="text-md text-sky-600 font-medium mb-4">Project Manager - Quality Assurance - Fullstack
                        Developer</p>
                    <hr class="my-4 border-slate-200">
                    <p class="text-sm text-slate-700 leading-relaxed text-left">
                        Firdaus memimpin InternSync dengan visi strategis dan analisis tajam. Ia memastikan setiap
                        proyek berjalan sesuai arah dan tujuan. Sebagai Quality Assurance, ia menjaga kualitas produk
                        melalui proses pengujian yang terstruktur. Di sisi teknis, perannya sebagai Fullstack Developer
                        memungkinkannya membangun sistem secara menyeluruh, dari frontend hingga backend.
                    </p>
                </div>
            </div>
        </div>

        <div id="modal-2" class="modal">
            <div
                class="modal-content bg-slate-50 rounded-lg shadow-xl overflow-hidden flex flex-col md:flex-row max-w-4xl w-11/12 mx-auto relative max-h-[95vh]">
                <span
                    class="modal-close-button absolute top-2 right-3 text-3xl font-semibold text-slate-500 hover:text-slate-700 cursor-pointer z-20"
                    data-modal-close="modal-2">&times;</span>
                <div class="md:w-1/2 w-full max-h-[400px] md:max-h-none overflow-hidden">
                    <img src="{{ asset('assets/tim/shamil.jpg') }}" alt="Abdullah Shamil Basayev"
                        class="w-full h-full object-cover object-center rounded-lg">
                </div>
                <div class="md:w-1/2 w-full p-5 md:p-6 overflow-y-auto">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">Abdullah Shamil Basayev</h3>
                    <p class="text-md text-sky-600 font-medium mb-4">Data Engineer - Fullstack Developer</p>
                    <hr class="my-4 border-slate-200">
                    <p class="text-sm text-slate-700 leading-relaxed text-left">
                        Shamil adalah penggerak utama di sisi teknis InternSync. Sebagai Data Engineer, ia merancang
                        dan mengelola alur data yang efisien dan handal. Di sisi lain, perannya sebagai Fullstack
                        Developer memungkinkan dia menjembatani frontend dan backend dengan solusi yang terintegrasi dan
                        inovatif. Kombinasi kedalaman teknis dan fleksibilitas membuatnya menjadi aset penting dalam
                        pengembangan platform.
                    </p>
                </div>
            </div>
        </div>

        <div id="modal-3" class="modal">
            <div
                class="modal-content bg-slate-50 rounded-lg shadow-xl overflow-hidden flex flex-col md:flex-row max-w-4xl w-11/12 mx-auto relative max-h-[95vh]">
                <span
                    class="modal-close-button absolute top-2 right-3 text-3xl font-semibold text-slate-500 hover:text-slate-700 cursor-pointer z-20"
                    data-modal-close="modal-3">&times;</span>
                <div class="md:w-1/2 w-full max-h-[400px] md:max-h-none overflow-hidden">
                    <img src="{{ asset('assets/tim/oltha.jpg') }}" alt="Oltha Rosyeda Al'Haq"
                        class="w-full h-full object-cover object-center rounded-lg">
                </div>
                <div class="md:w-1/2 w-full p-5 md:p-6 overflow-y-auto">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">Oltha Rosyeda Al'Haq</h3>
                    <p class="text-md text-sky-600 font-medium mb-4">Content Writer - UI/UX Designer</p>
                    <hr class="my-4 border-slate-200">
                    <p class="text-sm text-slate-700 leading-relaxed text-left">
                        Oltha adalah kreator di balik wajah dan kata-kata InternSync. Ia mengemas informasi menjadi
                        visual dan konten yang engaging, serta menghadirkan pengalaman pengguna yang intuitif dan
                        menyenangkan. Kreativitas dan empatinya membuat platform ini terasa hidup dan dekat.
                    </p>
                </div>
            </div>
        </div>

        <div id="modal-4" class="modal">
            <div
                class="modal-content bg-slate-50 rounded-lg shadow-xl overflow-hidden flex flex-col md:flex-row max-w-4xl w-11/12 mx-auto relative max-h-[95vh]">
                <span
                    class="modal-close-button absolute top-2 right-3 text-3xl font-semibold text-slate-500 hover:text-slate-700 cursor-pointer z-20"
                    data-modal-close="modal-4">&times;</span>
                <div class="md:w-1/2 w-full max-h-[400px] md:max-h-none overflow-hidden">
                    <img src="{{ asset('assets/tim/dimas.jpg') }}" alt="Muhammad Dimas Ajie Nugroho"
                        class="w-full h-full object-cover object-center rounded-lg">
                </div>
                <div class="md:w-1/2 w-full p-5 md:p-6 overflow-y-auto">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">Muhammad Dimas Ajie Nugroho</h3>
                    <p class="text-md text-sky-600 font-medium mb-4">UI/UX Designer - Quality Assurance</p>
                    <hr class="my-4 border-slate-200">
                    <p class="text-sm text-slate-700 leading-relaxed text-left">
                        Dimas adalah penjaga kualitas dan kenyamanan pengguna. Ia memastikan desain tampil
                        sempurna dan fungsional, serta setiap fitur berjalan tanpa cela. Fokusnya pada detail dan
                        pengalaman pengguna menjadi kunci kelancaran operasional InternSync.
                    </p>
                </div>
            </div>
        </div>

        {{-- Modal 5 (BARU) - Ganti dengan data sebenarnya --}}
        <div id="modal-5" class="modal"> {{-- Pastikan ID unik dan sesuai dengan data-modal-target di card 5 --}}
            <div
                class="modal-content bg-slate-50 rounded-lg shadow-xl overflow-hidden flex flex-col md:flex-row max-w-4xl w-11/12 mx-auto relative max-h-[95vh]">
                <span
                    class="modal-close-button absolute top-2 right-3 text-3xl font-semibold text-slate-500 hover:text-slate-700 cursor-pointer z-20"
                    data-modal-close="modal-5">&times;</span> {{-- Pastikan data-modal-close sesuai ID modal --}}
                <div class="md:w-1/2 w-full max-h-[400px] md:max-h-none overflow-hidden">
                    <img src="{{ asset('assets/tim/nanda.jpg') }}" alt="ANANDA SATRIA PUTRA NUGRAHA"
                        {{-- Ganti path gambar dan alt text --}} class="w-full h-full object-cover object-center rounded-lg">
                </div>
                <div class="md:w-1/2 w-full p-5 md:p-6 overflow-y-auto">
                    <h3 class="text-2xl font-bold text-slate-900 mb-1">Ananda Satria Putra Nugroho</h3>
                    {{-- Ganti nama --}}
                    <p class="text-md text-sky-600 font-medium mb-4">Jabatan Anggota Baru</p> {{-- Ganti jabatan --}}
                    <hr class="my-4 border-slate-200">
                    <p class="text-sm text-slate-700 leading-relaxed text-left">
                        Deskripsi mengenai anggota tim baru. Ceritakan peran dan kontribusi mereka dalam InternSync.
                        Gantilah teks ini dengan deskripsi yang relevan untuk anggota tim kelima Anda.
                    </p>
                </div>
            </div>
        </div>



        {{-- Testimonials Section --}}
        <section id="testimonials" class="py-16 md:py-24 bg-slate-50">
            {{-- Mengubah background ke slate-50 agar ada kontras dengan section tim --}}
            <div class="container mx-auto px-6">
                <div class="text-center mb-12 md:mb-16 reveal">
                    <h2 class="text-3xl md:text-3xl font-bold text-slate-900 mb-4">
                        Apa Kata Mereka Tentang InternSync?
                    </h2>
                    <p class="text-sm text-slate-600 max-w-2xl mx-auto">
                        Pengalaman positif dari mahasiswa dan perusahaan yang telah menggunakan InternSync.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {{-- Loop Testimoni --}}
                    @foreach ($evaluasi as $item)
                        <div
                            class="glassmorphism-light p-8 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 reveal">
                            <div class="flex items-center mb-4">
                                <img src="{{ $item->mahasiswa->foto ? asset('storage/foto/' . $item->mahasiswa->foto) : asset('assets/default-profile.png') }}"
                                    alt="User Avatar" class="w-12 h-12 rounded-full mr-4 bg-slate-200">

                                <div>
                                    <h4 class="font-semibold text-slate-800">
                                        {{ $item->mahasiswa->nama_lengkap }}
                                    </h4>
                                    <p class="text-sm text-slate-500">Mahasiswa Teknik Informatika</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-700">
                                {{ $item->evaluasi }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>


        {{-- Call to Action Section --}}
        <section id="contact" class="py-16 md:py-24 text-center bg-white"> {{-- Mengubah bg ke white agar ada kontras --}}
            <div class="container mx-auto px-6 reveal">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6">Siap Memulai Perjalanan Magangmu?</h2>
                <p class="text-sm text-slate-700 max-w-xl mx-auto mb-10">
                    Bergabunglah dengan ribuan mahasiswa dan ratusan perusahaan yang telah merasakan manfaat InternSync.
                    Daftar sekarang dan buka peluang karirmu!
                </p>
                <div class="space-y-4 sm:space-y-0 sm:space-x-4 flex flex-col sm:flex-row justify-center items-center">
                    <a href="{{ route('signup') }}"
                        class="w-full sm:w-auto btn-primary text-sm font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition duration-300">Gabung
                        Sekarang</a>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-800 text-slate-300 border-t border-slate-700 py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                <div>
                    <h5 class="text-xl font-semibold text-white mb-4">InternSync</h5>
                    <p class="text-sm text-slate-400">
                        Menghubungkan talenta muda dengan peluang karir terbaik.
                    </p>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-white mb-4">Navigasi</h5>
                    <ul class="space-y-2">
                        <li><a href="#about"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Tentang
                                Kami</a></li>
                        <li><a href="#features"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Fitur</a>
                        </li>
                        <li><a href="#team"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Tim</a>
                        </li>
                        <li><a href="#testimonials"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Testimoni</a>
                        </li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Blog</a></li>
                        {{-- Contoh --}}
                    </ul>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-white mb-4">Untuk Pengguna</h5>
                    <ul class="space-y-2">
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Mahasiswa</a>
                        </li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Perusahaan</a>
                        </li>
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Institusi
                                Pendidikan</a></li> {{-- Contoh --}}
                        <li><a href="#"
                                class="text-sm text-slate-400 hover:text-sky-300 transition duration-300">Pusat
                                Bantuan</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="text-sm font-semibold text-white mb-4">Ikuti Kami</h5>
                    <div class="flex space-x-4">
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
                <a href="#" class="ml-4 hover:text-sky-300 transition duration-300">Kebijakan Privasi</a>
                <a href="#" class="ml-4 hover:text-sky-300 transition duration-300">Ketentuan Layanan</a>
            </div>
        </div>
    </footer>

    <script>
    {{-- Mobile Menu Toggle --}}
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    {{-- Set Current Year in Footer --}}
    const currentYearElement = document.getElementById('currentYear');
    if (currentYearElement) {
        currentYearElement.textContent = new Date().getFullYear();
    }

    {{-- Simple Scroll Reveal Animation --}}
    const revealElements = document.querySelectorAll('.reveal');
    const revealOnScroll = () => {
        const windowHeight = window.innerHeight;
        revealElements.forEach(el => {
            const elementTop = el.getBoundingClientRect().top;
            if (elementTop < windowHeight - 80) { // Adjusted threshold for earlier reveal
                el.classList.add('visible');
            }
        });
    }
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll(); // Initial check

    {{-- Smooth scroll for anchor links --}}
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            {{-- Pastikan targetId bukan hanya '#' (link kosong) --}}
            if (targetId.length > 1 && document.querySelector(targetId)) {
                const targetElement = document.querySelector(targetId);
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
                {{-- Close mobile menu if open after click --}}
                if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                }
            } else if (targetId === '#') {
                {{-- Untuk link seperti #getstarted-header yang mungkin belum ada sectionnya --}}
                console.warn(`Anchor link "${targetId}" points to a non-existent or empty target.`);
            }
        });
    });

    {{-- Modal Functionality --}}
    const teamCards = document.querySelectorAll('.team-card');
    const modals = document.querySelectorAll('.modal');
    const closeButtons = document.querySelectorAll('.modal-close-button');

    teamCards.forEach(card => {
        card.addEventListener('click', () => {
            const modalId = card.getAttribute('data-modal-target');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('show');
            }
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.getAttribute('data-modal-close');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('show');
            }
        });
    });

    // Close modal when clicking outside of the modal content
    modals.forEach(modal => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) { // If the click is on the modal backdrop itself
                modal.classList.remove('show');
            }
        });
    });

    {{-- Enhanced Carousel Functionality - Completely Reorganized --}}
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('carouselWrapper');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const dots = document.querySelectorAll('.carousel-dot');

        // Check if carousel elements exist before proceeding
        if (!wrapper || !prevBtn || !nextBtn || dots.length === 0) {
            console.log('Carousel elements not found, skipping carousel initialization');
            return;
        }

        let currentSlide = 0;
        const totalSlides = dots.length; // Dynamic slide count based on dots
        let autoPlayInterval;

        function updateCarousel() {
            const translateX = -currentSlide * 100;
            wrapper.style.transform = `translateX(${translateX}%)`;

            // Update dots with enhanced animation
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.add('active');
                    dot.style.backgroundColor = '#0ea5e9';
                    dot.style.transform = 'scale(1.2)';
                    dot.style.opacity = '1';
                } else {
                    dot.classList.remove('active');
                    dot.style.backgroundColor = '#cbd5e1';
                    dot.style.transform = 'scale(1)';
                    dot.style.opacity = '0.7';
                }
            });
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
            stopAutoPlay(); // Clear any existing interval
            autoPlayInterval = setInterval(nextSlide, 5000);
        }

        function stopAutoPlay() {
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
                autoPlayInterval = null;
            }
        }

        // Enhanced Event listeners with error handling
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                nextSlide();
                stopAutoPlay();
                setTimeout(startAutoPlay, 3000);
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                prevSlide();
                stopAutoPlay();
                setTimeout(startAutoPlay, 3000);
            });
        }

        // Enhanced Dot navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateCarousel();
                stopAutoPlay();
                setTimeout(startAutoPlay, 3000);
            });
        });

        // Pause auto-play on hover
        const carouselContainer = document.getElementById('imageCarouselContainer');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', stopAutoPlay);
            carouselContainer.addEventListener('mouseleave', startAutoPlay);
        }

        // Enhanced Touch/swipe support for mobile
        let startX = 0;
        let endX = 0;
        let startTime = 0;
        let isPointerDown = false;

        function handleStart(e) {
            isPointerDown = true;
            startX = e.touches ? e.touches[0].clientX : e.clientX;
            startTime = Date.now();
        }

        function handleEnd(e) {
            if (!isPointerDown) return;
            isPointerDown = false;
            endX = e.changedTouches ? e.changedTouches[0].clientX : e.clientX;
            const timeDiff = Date.now() - startTime;

            // Only trigger swipe if it's quick enough (< 300ms) and long enough
            if (timeDiff < 300) {
                handleSwipe();
            }
        }

        function handleSwipe() {
            const threshold = 50;
            const diff = startX - endX;

            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
                stopAutoPlay();
                setTimeout(startAutoPlay, 3000);
            }
        }

        // Add touch event listeners
        if (wrapper) {
            wrapper.addEventListener('touchstart', handleStart, { passive: true });
            wrapper.addEventListener('touchend', handleEnd, { passive: true });
            wrapper.addEventListener('mousedown', handleStart);
            wrapper.addEventListener('mouseup', handleEnd);
        }

        // Enhanced Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                prevSlide();
                stopAutoPlay();
                setTimeout(startAutoPlay, 3000);
            } else if (e.key === 'ArrowRight') {
                nextSlide();
                stopAutoPlay();
                setTimeout(startAutoPlay, 3000);
            } else if (e.key === 'Escape') {
                stopAutoPlay();
            } else if (e.key === ' ' || e.key === 'Enter') {
                if (autoPlayInterval) {
                    stopAutoPlay();
                } else {
                    startAutoPlay();
                }
            }
        });

        // Initialize carousel
        updateCarousel();
        startAutoPlay();

        // Visibility API for better performance
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoPlay();
            } else {
                startAutoPlay();
            }
        });
    });

    {{-- Enhanced Marquee Functionality --}}
    document.addEventListener('DOMContentLoaded', function() {
        const marqueeElements = document.querySelectorAll('.animate-marquee, .animate-marquee2');
        const marqueeContainer = document.querySelector('.group');

        if (marqueeElements.length === 0) {
            console.log('Marquee elements not found, skipping marquee initialization');
            return;
        }

        // Enhanced marquee hover effects
        marqueeElements.forEach(marquee => {
            const icons = marquee.querySelectorAll('span');

            icons.forEach(icon => {
                // Add enhanced hover effects
                icon.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.15) rotate(5deg)';
                    this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                    this.style.filter = 'drop-shadow(0 10px 25px rgba(0,0,0,0.15))';
                });

                icon.addEventListener('mouseleave', function() {
                    this.style.transform = 'scale(1) rotate(0deg)';
                    this.style.filter = 'none';
                });

                // Add click effects for better interactivity
                icon.addEventListener('click', function() {
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1.1)';
                        setTimeout(() => {
                            this.style.transform = 'scale(1)';
                        }, 150);
                    }, 100);
                });
            });
        });

        // Pause marquee on hover with smooth transition
        if (marqueeContainer) {
            marqueeContainer.addEventListener('mouseenter', function() {
                marqueeElements.forEach(marquee => {
                    marquee.style.animationPlayState = 'paused';
                });
            });

            marqueeContainer.addEventListener('mouseleave', function() {
                marqueeElements.forEach(marquee => {
                    marquee.style.animationPlayState = 'running';
                });
            });
        }

        // Reduce motion for users who prefer it
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            marqueeElements.forEach(marquee => {
                marquee.style.animation = 'none';
            });
        }

        // Performance optimization: pause animations when not visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const marquees = entry.target.querySelectorAll('.animate-marquee, .animate-marquee2');
                if (entry.isIntersecting) {
                    marquees.forEach(marquee => {
                        marquee.style.animationPlayState = 'running';
                    });
                } else {
                    marquees.forEach(marquee => {
                        marquee.style.animationPlayState = 'paused';
                    });
                }
            });
        }, { threshold: 0.1 });

        if (marqueeContainer) {
            observer.observe(marqueeContainer);
        }
    });

    {{-- Additional Utility Functions --}}

    // Smooth performance monitoring
    let lastFrameTime = Date.now();
    function checkPerformance() {
        const currentTime = Date.now();
        const deltaTime = currentTime - lastFrameTime;
        lastFrameTime = currentTime;

        // If frame time is too high (> 33ms = < 30fps), reduce animations
        if (deltaTime > 33) {
            console.log('Performance issue detected, consider reducing animations');
        }
    }

    // Call performance check occasionally
    setInterval(checkPerformance, 5000);

    // Cleanup function for better memory management
    window.addEventListener('beforeunload', function() {
        // Clear any remaining intervals
        const wrapper = document.getElementById('carouselWrapper');
        if (wrapper && wrapper.autoPlayInterval) {
            clearInterval(wrapper.autoPlayInterval);
        }
    });

    {{-- Debug mode for development --}}
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        console.log('🎠 Enhanced Carousel & Marquee Script Loaded Successfully');
        console.log('📱 Features: Auto-play, Touch/Swipe, Keyboard Navigation, Performance Optimization');
    }
</script>
</body>

</html>
