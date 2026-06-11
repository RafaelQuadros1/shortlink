<header class="w-full flex items-center justify-between px-4 sm:px-6 lg:px-8 py-4">
    @if (Illuminate\Support\Facades\Auth::check())
        <div class="flex items-center gap-3">
            <button class="text-white/50 hover:text-white transition-colors cursor-pointer">
                @include('icons.menu')
            </button>
            <button class="text-white/50 hover:text-white transition-colors cursor-pointer">
                @include('icons.settings')
            </button>
        </div>


        <div class="flex items-center gap-3">
            <button class="text-white/50 hover:text-white transition-colors cursor-pointer">
                @include('icons.logout')
            </button>
        </div>
    @endif

</header>
