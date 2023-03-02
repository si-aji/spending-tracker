@section('parentTitle', 'Register')

<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <section>
        <div class="page-header min-vh-100">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-absolute w-40 top-0 start-0 h-100 d-md-block d-none">
                            <div class="oblique-image position-absolute d-flex fixed-top ms-auto h-100 z-index-0 bg-cover me-n8" style="background-image:url('{{ asset('assets/corporate-ui/') }}/img/image-sign-up.jpg')">
                                <div class="my-auto text-start max-width-350 ms-7">
                                    <h1 class="mt-3 text-white font-weight-bolder">Start your <br> new journey.</h1>
                                    <p class="text-white text-lg mt-4 mb-4">Use these awesome forms to login or create new account in your project for free.</p>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-group d-flex">
                                            <a href="javascript:;" class="avatar avatar-sm rounded-circle" data-bs-toggle="tooltip" data-original-title="Jessica Rowland">
                                                <img alt="Image placeholder" src="{{ asset('assets/corporate-ui/') }}/img/team-3.jpg" class="">
                                            </a>
                                            <a href="javascript:;" class="avatar avatar-sm rounded-circle" data-bs-toggle="tooltip" data-original-title="Audrey Love">
                                                <img alt="Image placeholder" src="{{ asset('assets/corporate-ui/') }}/img/team-4.jpg" class="rounded-circle">
                                            </a>
                                            <a href="javascript:;" class="avatar avatar-sm rounded-circle" data-bs-toggle="tooltip" data-original-title="Michael Lewis">
                                                <img alt="Image placeholder" src="{{ asset('assets/corporate-ui/') }}/img/marie.jpg" class="rounded-circle">
                                            </a>
                                            <a href="javascript:;" class="avatar avatar-sm rounded-circle" data-bs-toggle="tooltip" data-original-title="Audrey Love">
                                                <img alt="Image placeholder" src="{{ asset('assets/corporate-ui/') }}/img/team-1.jpg" class="rounded-circle">
                                            </a>
                                        </div>
                                        <p class="font-weight-bold text-white text-sm mb-0 ms-2">Join 2.5M+ users</p>
                                    </div>
                                </div>
                                <div class="text-start position-absolute fixed-bottom ms-7">
                                    <h6 class="text-white text-sm mb-5">Copyright © 2022 Corporate UI Design System by Creative Tim.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex flex-column mx-auto">
                        <div class="card card-plain mt-8">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h3 class="font-weight-black text-dark display-6">Sign up</h3>
                                <p class="mb-0">Nice to meet you! Please enter your details.</p>
                            </div>
                            <div class="card-body">
                                <form role="form" wire:submit.prevent="register">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Enter your name" name="name" wire:model.lazy="name">

                                        @error('name')
                                            <small class="invalid-feedback tw__italic tw__text-xs">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email address" name="email" wire:model.lazy="email">
                                    
                                        @error('email')
                                            <small class="invalid-feedback tw__italic tw__text-xs">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control @error('email') is-invalid @enderror" placeholder="Create a password" name="password" wire:model.lazy="password">
                                        
                                        @error('password')
                                            <small class="invalid-feedback tw__italic tw__text-xs">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="form-check form-check-info text-left mb-0">
                                            <input class="form-check-input" type="checkbox" value="" name="toc" id="input-toc" wire:model.lazy="toc">
                                            <label class="font-weight-normal text-dark mb-0" for="input-toc">
                                                I agree the <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#tocModal" class="text-dark font-weight-bold">Terms and Conditions</a>.
                                            </label>
                                        </div>
                                        @error('toc')
                                            <small class=" tw__block invalid-feedback tw__italic tw__text-xs">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Sign up</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-4 text-xs mx-auto">
                                    Already have an account?
                                    <a href="{{ route('auth.login') }}" class="text-dark font-weight-bold">Sign in</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@section('content_modal')
    <!-- Modal -->
    <div class="modal fade" id="tocModal" tabindex="-1" aria-labelledby="tocModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header tw__border-b-0">
                    <h5 class="modal-title" id="tocModalTitle">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Welcome to <strong>{{ env('APP_NAME') }}</strong>!</p>
                    <p>These terms and conditions outline the rules and regulations for the use of <strong>{{ env('APP_NAME') }}</strong> Website, located at <a href="javascript:void(0)">{{ env('APP_URL') }}</a></p>
                    <p>By accessing this website we assume you accept these terms and conditions. Do not continue to use <strong>{{ env('APP_NAME') }}</strong> if you do not agree to take all of the terms and conditions stated on this page.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn_modal-toc_agree" data-bs-dismiss="modal">I Agree</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_inline')
    <script>
        if(document.getElementById('btn_modal-toc_agree')){
            document.getElementById('btn_modal-toc_agree').addEventListener('click', (e) => {
                document.getElementById('input-toc').checked = true;
            });
        }
    </script>
@endsection