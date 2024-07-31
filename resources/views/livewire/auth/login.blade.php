
            <div class="container ">
                <div class="row signin-margin">
                    <div class="col-lg-4 col-md-8 col-12 mx-auto">
                        <div class="card z-index-0 fadeIn3 fadeInBottom">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                                <div class="bg-gradient-info shadow-info border-radius-lg py-3 pe-1">
                                    <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Se connecter</h4>
                                    <div class="row mt-3">
                                        <h6 class='text-white text-center'>
                                            <!-- <span class="font-weight-normal">Email:</span> admin@material.com
                                            <br>
                                            <span class="font-weight-normal">Password:</span> secret</h6>
                                        <div class="col-2 text-center ms-auto">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-facebook text-white text-lg"></i>
                                            </a>
                                        </div> -->
                                        <!-- <div class="col-2 text-center px-1">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-github text-white text-lg"></i>
                                            </a>
                                        </div>
                                        <div class="col-2 text-center me-auto">
                                            <a class="btn btn-link px-3" href="javascript:;">
                                                <i class="fa fa-google text-white text-lg"></i>
                                            </a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form wire:submit.prevent='store'>
                                    @if (Session::has('status'))
                                    <div class="alert alert-success alert-dismissible text-white" role="alert">
                                        <span class="text-sm">{{ Session::get('status') }}</span>
                                        <button type="button" class="btn-close text-lg py-3 opacity-10"
                                            data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @endif
                                    <div class="input-group input-group-outline mt-3 @if(strlen($email ?? '') > 0) is-filled @endif">
                                        <label class="form-label">Email</label>
                                        <input wire:model='email' type="email" class="form-control">
                                    </div>
                                    @error('email')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror

                                    <div class="input-group input-group-outline mt-3 @if(strlen($password ?? '') > 0) is-filled @endif">
                                        <label class="form-label">Mot de passe </label>
                                        <input wire:model="password" type="password" class="form-control"
                                             >
                                    </div>
                                    @error('password')
                                    <p class='text-danger inputerror'>{{ $message }} </p>
                                    @enderror
                                    <div class="form-check form-switch d-flex align-items-center my-3">
                                        <input class="form-check-input" type="checkbox" id="rememberMe">
                                        <label class="form-check-label mb-0 ms-2" for="rememberMe">
                                        rappelle moi</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-info w-100 my-4 mb-2">Se connecter</button>
                                    </div>
                                    <p class="mt-4 text-sm text-center">
                                    Vous n'avez pas de compte ?
                                        <a href="{{ route('register') }}"
                                            class="text-info text-gradient font-weight-bold">S'inscrire</a>
                                    </p>
                                    <!-- <p class="text-sm text-center">
                                    Mot de passe oublié? Réinitialisez votre mot de passe ici
                                        <a href="{{ route('password.forgot') }}"
                                            class="text-info text-gradient font-weight-bold">ici</a>
                                    </p> -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>