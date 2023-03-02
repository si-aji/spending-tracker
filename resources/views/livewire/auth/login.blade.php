@section('parentTitle', 'Login')

<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <section>
        <div class="page-header min-vh-100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-md-6 d-flex flex-column mx-auto">
                        <div class="card card-plain mt-8">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h3 class="font-weight-black text-dark display-6">Welcome back</h3>
                                <p class="mb-0">Fill your credential to enter</p>
                            </div>
                            <div class="card-body">
                                <form role="form" wire:submit.prevent="authenticate">
                                    <div class="form-group">
                                        <label>Email / Username</label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email / username" name="email" wire:model.lazy="email">

                                        @error('email')
                                            <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                                        @enderror
                                    </div>
                                        
                                    <div class="form-group">
                                        <label>Password</label>
                                        <div class=" tw__relative" x-data="{
                                            rawPassowrd: false,
                                            show: false
                                        }">
                                            <input x-bind:type="rawPassowrd ? 'text' : 'password'" class="form-control tw__pr-10 @error('password') is-invalid @enderror" placeholder="Enter password" name="password" wire:model.lazy="password" x-on:keyup="show = $event.target.value.length > 0">
                                        
                                            <a href="javascript:void(0)" class=" tw__absolute tw__top-1/2 tw__transform tw__-translate-y-1/2 tw__right-4" x-on:click="rawPassowrd = !rawPassowrd" x-show="show">
                                                <i class="fa-solid" :class="rawPassowrd ? 'fa-eye-slash' : 'fa-eye'"></i>
                                            </a>
                                        </div>

                                        @error('password')
                                            <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="form-check form-check-info text-left mb-0">
                                            <input class="form-check-input" type="checkbox" value="" id="input-remember" name="remember" wire:model.lazy="remember">
                                            <label class="font-weight-normal text-dark mb-0" for="input-remember">
                                                Remember me
                                            </label>
                                        </div>
                                        <a href="javascript:;" class="text-xs font-weight-bold ms-auto">Forgot password?</a>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">
                                            {{-- <span wire:loading wire:target="authenticate" class=" tw__flex tw__items-center tw__gap-2 tw__justify-center"><span><i class="fas fa-spinner fa-pulse"></i></span> Loading</span> --}}
                                            <span>Sign in</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-4 text-xs mx-auto">
                                    Don't have an account?
                                    <a href="{{ route('auth.register') }}" class="text-dark font-weight-bold">Sign up</a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-absolute w-40 top-0 end-0 h-100 d-md-block d-none">
                            <div class="oblique-image position-absolute fixed-top ms-auto h-100 z-index-0 bg-cover ms-n8" style="background-image:url('{{ asset('assets/corporate-ui/') }}/img/image-sign-in.jpg')">
                                <div class="blur mt-12 p-4 text-center border border-white border-radius-md position-absolute fixed-bottom m-4">
                                    <h2 class="mt-3 text-dark font-weight-bold">Enter our global community of developers.</h2>
                                    <h6 class="text-dark text-sm mt-5">Copyright © 2022 Corporate UI Design System by Creative Tim.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
