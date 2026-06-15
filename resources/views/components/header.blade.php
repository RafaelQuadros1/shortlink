<header class="w-full flex items-center justify-between px-4 sm:px-6 lg:px-8 py-4">
    @if (Illuminate\Support\Facades\Auth::check())
        @if (Illuminate\Support\Facades\Request::is('shorts/*'))
            <div class="flex items-center gap-2">
                <a href="{{ route('shorts.index') }}" class="text-white/70 hover:text-white transition-colors">
                    @include('icons.back')
                </a>
            </div>
        @elseif (Illuminate\Support\Facades\Request::is('shorts'))
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" class="text-white/70 hover:text-white transition-colors">
                    @include('icons.back')
                </a>
            </div>
        @else
            <div class="flex justify-center items-center gap-3">
                <a href="{{ route('shorts.index') }}" class="text-white/70 hover:text-white transition-colors">
                    @include('icons.menu')
                </a>
            </div>
        @endif

        <div class="flex justify-center items-center gap-3">
            <a href="{{ route('settings.api-keys') }}" class="text-white/70 hover:text-white transition-colors">
                @include('icons.settings')
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-white/70 hover:text-white transition-colors cursor-pointer">
                    @include('icons.logout')
                </button>
            </form>
        </div>
    @endif
</header>
