<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
    <div class=" tw__min-h-screen tw__w-screen tw__flex tw__justify-center tw__items-center">
        <div class="">
            <h5 class=" tw__text-3xl tw__mb-2">{{ env('APP_NAME') }}</h5>

            <div class=" tw__flex tw__items-center tw__justify-center tw__gap-4">
                <a href="{{ route('auth.login') }}" class=" tw__px-4 tw__py-2 tw__bg-purple-500 hover:tw__bg-purple-700 tw__text-white tw__rounded">Login</a>
                @if (\Route::has('auth.register'))
                    <a href="{{ route('auth.register') }}" class=" tw__px-4 tw__py-2 tw__bg-slate-400 hover:tw__bg-slate-700 tw__text-white tw__rounded">Register</a>
                @endif
            </div>
        </div>
    </div>
</div>
