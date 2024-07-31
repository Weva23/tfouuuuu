<link href="{{ asset('assets/css/material-icons.css') }}" rel="stylesheet">

<div class="container-fluid px-2 px-md-4">
    <div class=" min-height-100 ">
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
        <div class="row gx-4 ">
            <div class="">
                <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1  " data-bs-toggle="tab" 
                                role="tab" aria-selected="true">
                                <!-- <i class="material-icons text-lg position-relative">home</i> -->
                                <h6><span class="ms-1">Profile Information</span></h6>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <img src="{{ asset('assets') }}/img/ipf.jpg" alt="profile_image"
                        class="w-100 border-radius-lg shadow-sm">
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">
                        {{ auth()->user()->name }}
                    </h5>
                    <p class="mb-0 font-weight-normal text-sm">
                        administrateur
                    </p>
                </div>
            </div>
            
            
        </div>
        <div class="card card-plain h-100">
            
            <div class="card-body  p-3">
                <form wire:submit.prevent='update'>
                    <div class="row">

                        <div class="mb-2 col-md-6">

                            <label class="form-label">Email address</label>
                            <input wire:model.lazy="user.email" type="email" class="form-control border border-2 p-2">
                            @error('user.email')
                            <p class='text-danger inputerror'>{{ $message }} </p>
                            @enderror
                        </div>

                        <div class="mb-2 col-md-6">

                            <label class="form-label">Name</label>
                            <input wire:model.lazy="user.name" type="text" class="form-control border border-2 p-2">
                            @error('user.name')
                            <p class='text-danger inputerror'>{{ $message }} </p>
                            @enderror
                        </div>

                        <div class="mb-2 col-md-6">

                            <label class="form-label">Phone</label>
                            <input wire:model.lazy="user.phone" type="number" class="form-control border border-2 p-2">
                            @error('user.phone')
                            <p class='text-danger inputerror'>{{ $message }} </p>
                            @enderror
                        </div>

                        <div class="mb-2 col-md-6">

                            <label class="form-label">Location</label>
                            <input wire:model.lazy="user.location" type="text" class="form-control border border-2 p-2">
                            @error('user.location')
                            <p class='text-danger inputerror'>{{ $message }} </p>
                            @enderror
                        </div>

                        <div class="mb-2 col-md-12">

                            <label for="floatingTextarea2">About</label>
                            <textarea wire:model.lazy="user.about" class="form-control border border-2 p-2"
                                placeholder=" Say something about yourself" id="floatingTextarea2" ></textarea>
                            @error('user.about')
                            <p class='text-danger inputerror'>{{ $message }} </p>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-dark">Submit</button>
                </form>

            </div>
        </div>


    </div>

</div>
