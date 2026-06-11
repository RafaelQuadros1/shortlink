<div class="w-full flex flex-col">

    <div class="flex flex-row items-center gap-4">
        <span class="grow border-t border-white/10"></span>
        <h2 class="text-white/40 text-xs sm:text-sm font-medium">ou</h2>
        <span class="grow border-t border-white/10"></span>
    </div>
    <div class="flex flex-row justify-center items-center">
        <h3 class="text-white/40 text-xs sm:text-sm font-medium">Faça login para prolongar a duração do link</h3>
    </div>
    <div class="flex justify-center mt-4">
        <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center gap-2 px-4 py-2
                   bg-white/6 backdrop-blur-2xl
                   border border-white/12
                   rounded-xl
                   text-white text-sm font-medium
                   hover:bg-white/10 hover:-translate-y-px
                   active:scale-[0.98]
                   transition-all duration-150">
            @include('icons.google')
            Google
        </a>
    </div>
</div>
