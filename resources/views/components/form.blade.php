<div class="w-full">
    @error('url_origin')
        <div id="error" class="mb-4 px-4 py-2 bg-red-500/10 border border-red-500/20 text-red-500 text-sm rounded-lg">
            {{ $message }}
        </div>
    @enderror
    <form id="shorten-form" action="{{ route('shorts.store') }}" method="POST" class="w-full
                 bg-white/6 backdrop-blur-2xl
                 border border-white/12
                 rounded-2xl sm:rounded-[20px] p-4 sm:p-6">
        @csrf

        <label class="block text-white/60 text-xs sm:text-sm mb-2">URL longa</label>

        <div class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="url_origin" placeholder="https://exemplo.com/pagina/muito/longa?utm_source" required class="flex-1 min-w-0 px-2 sm:px-2 py-2 sm:py-2
                          bg-white/5 border border-white/10 rounded-xl
                          text-white text-sm sm:text-sm placeholder:text-white/25
                          focus:outline-none focus:bg-white/9 focus:border-white/28
                          transition-all duration-200">
            <button type="submit" class="flex items-center justify-center gap-2 px-4 sm:px-5 py-2.5 sm:py-3 shrink-0
                           bg-white text-black font-medium text-sm sm:text-sm
                           rounded-xl
                           hover:opacity-90 hover:-translate-y-px
                           active:scale-[0.98]
                           transition-all duration-150 cursor-pointer">
                Encurtar
            </button>
        </div>
    </form>
</div>
