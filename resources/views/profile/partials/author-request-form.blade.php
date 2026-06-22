<section>
    <header class="mb-6">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Become an Author') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ajukan permohonan untuk menjadi penulis (Author) di platform Aksara dan mulailah mempublikasikan cerita Anda.') }}
        </p>
    </header>

    @if (auth()->user()->author_request_status === 'pending')
        <div class="p-6 bg-amber-50 border border-amber-200 rounded-2xl text-amber-900">
            <div class="flex items-center space-x-3 mb-2">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="font-bold text-sm">Permintaan Sedang Ditinjau</h3>
            </div>
            <p class="text-xs opacity-90 leading-relaxed">
                Terima kasih! Permintaan Anda untuk naik akses menjadi **Author** telah terkirim pada {{ auth()->user()->author_request_at ? \Carbon\Carbon::parse(auth()->user()->author_request_at)->translatedFormat('d F Y, H:i') : '' }}. Admin akan meninjau pengajuan Anda dalam waktu dekat.
            </p>
            
            <div class="mt-4 pt-4 border-t border-amber-200/50 space-y-2 text-xs">
                <div>
                    <span class="font-semibold block text-amber-800">Bio Menulis Anda:</span>
                    <p class="italic text-amber-950 mt-1">"{{ auth()->user()->author_request_bio }}"</p>
                </div>
                @if(auth()->user()->author_request_portfolio)
                <div class="mt-2">
                    <span class="font-semibold block text-amber-800">Portfolio:</span>
                    <a href="{{ auth()->user()->author_request_portfolio }}" target="_blank" class="underline hover:text-amber-950 break-all">{{ auth()->user()->author_request_portfolio }}</a>
                </div>
                @endif
            </div>
        </div>
    @elseif (auth()->user()->author_request_status === 'approved')
        <div class="p-6 bg-green-50 border border-green-200 rounded-2xl text-green-900">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="font-bold text-sm">Permintaan Disetujui!</h3>
            </div>
            <p class="text-xs mt-2 leading-relaxed">
                Selamat! Anda sekarang sudah menjadi **Author**. Silakan klik tombol **Write** di atas untuk mulai menulis artikel baru!
            </p>
        </div>
    @else
        @if (auth()->user()->author_request_status === 'rejected')
            <div class="p-4 mb-6 bg-red-50 border border-red-200 rounded-xl text-red-800 text-xs">
                <span class="font-bold">Pengajuan Sebelumnya Ditolak</span>. Anda dapat mengajukan kembali dengan memperbarui bio menulis atau melampirkan portofolio yang relevan.
            </div>
        @endif

        <form method="post" action="{{ route('profile.request-author') }}" class="space-y-6">
            @csrf

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                    Bio Menulis / Alasan Mengajukan <span class="text-red-500">*</span>
                </label>
                <textarea id="bio" name="bio" rows="4" required 
                    placeholder="Ceritakan tentang diri Anda, bidang tulisan yang Anda sukai (misal: Teknologi, Keuangan, Kesehatan), atau pengalaman menulis Anda..." 
                    class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                >{{ old('bio') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">Maksimum 1000 karakter. Jelaskan alasan Anda agar admin menyetujui pengajuan.</p>
                @error('bio')
                    <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Portfolio Link -->
            <div>
                <label for="portfolio" class="block text-sm font-medium text-gray-700 mb-1">
                    Link Portofolio / Blog Pribadi (Opsional)
                </label>
                <input id="portfolio" name="portfolio" type="url" value="{{ old('portfolio') }}"
                    placeholder="https://medium.com/@username atau link blog Anda"
                    class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                />
                <p class="mt-1 text-xs text-gray-500">Tautkan karya tulis Anda sebelumnya (opsional) untuk mendukung persetujuan admin.</p>
                @error('portfolio')
                    <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gray-900 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-600 focus:bg-indigo-600 active:bg-gray-950 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Kirim Pengajuan') }}
                </button>
            </div>
        </form>
    @endif
</section>
