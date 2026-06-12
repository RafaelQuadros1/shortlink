<footer class="w-full max-w-90 sm:max-w-112.5 lg:w-150 mx-auto px-4 sm:px-6 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-white/10">
    <div class="flex items-center gap-4 text-xs text-white/40">
        <a href="{{ route('legal.privacy') }}" class="hover:text-white/70 transition-colors">Privacidade</a>
        <a href="{{ route('legal.cookies') }}" class="hover:text-white/70 transition-colors">Cookies</a>
        <a href="{{ route('legal.terms') }}" class="hover:text-white/70 transition-colors">Termos</a>
    </div>
    <button type="button" data-cc="show-preferencesModal"
        class="text-xs text-white/40 hover:text-white/70 transition-colors cursor-pointer">
        Gerenciar cookies
    </button>
</footer>
