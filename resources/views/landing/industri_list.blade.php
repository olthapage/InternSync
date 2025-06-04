@if($allIndustries->isNotEmpty())
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8" id="industryGrid">
        @foreach ($allIndustries as $industry)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 group border border-slate-200 flex flex-col">
                <div class="w-full h-48 bg-slate-50 flex items-center justify-center p-6 flex-shrink-0">
                    {{-- Pastikan $industry->logo_url selalu ada atau beri fallback --}}
                    <img src="{{ $industry->logo_url ?? asset('images/default-logo.png') }}" alt="Logo {{ $industry->industri_nama }}" class="max-h-full max-w-full object-contain transition-transform duration-300 group-hover:scale-110">
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
        {{-- Pastikan pagination mempertahankan query 'search' --}}
        {{ $allIndustries->appends(['search' => $searchTerm])->links() }}
    </div>
@else
    <div class="text-center py-12" id="noResultsMessage">
        <svg class="mx-auto h-24 w-24 text-slate-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
        </svg>
        <p class="text-xl text-slate-600 font-semibold mb-2">
            @if($searchTerm)
                Mitra Industri tidak ditemukan
            @else
                Belum Ada Mitra Industri
            @endif
        </p>
        <p class="text-base text-slate-500">
            @if($searchTerm)
                Tidak ada hasil yang cocok dengan pencarian "{{ $searchTerm }}". Coba kata kunci lain.
            @else
                Saat ini belum ada data mitra industri yang terdaftar. Silakan cek kembali nanti.
            @endif
        </p>
        @if($searchTerm)
            {{-- Tombol untuk menghapus filter pencarian bisa ditambahkan di sini jika diperlukan --}}
            {{-- Contoh: <a href="{{ route('industri.landing') }}" class="mt-4 inline-block text-sky-600 hover:text-sky-700 font-medium">Tampilkan semua mitra industri</a> --}}
        @endif
    </div>
@endif
