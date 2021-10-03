@extends('layouts.user.header')
@section('title', 'PayOnce | '.auth()->user()->full_name.' | Profile')

@section('content')


<!-- Header Layout Content -->
<div class="mdk-header-layout__content">

    <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
        <div class="mdk-drawer-layout__content page">




            <div style="padding-bottom: calc(5.125rem / 2); position: relative; margin-bottom: 1.5rem;">
                <div class="bg-primary" style="min-height: 150px;">
                    <div class="d-flex align-items-end container-fluid page__container" style="position: absolute; left: 0; right: 0; bottom: 0;">
                        <div class="avatar avatar-xl">
                        @if(Auth::user()->profile_pic)
                            <img src="{{ Auth::user()->profile_pic }}" alt="avatar" class="avatar-img rounded" style="border: 2px solid white;">
                        @else
                            <img src="{{asset('assets/images/avatar/placeholder.jpg')}}" alt="avatar" class="avatar-img rounded" style="border: 2px solid white;">
                        @endif
                        </div>
                        <div class="card-header card-header-tabs-basic nav flex" role="tablist">
                            <a href="#activity" class="active show" data-toggle="tab" role="tab" aria-selected="true">Profile</a>
                            <a href="#password" data-toggle="tab" role="tab" aria-selected="false">Password</a>
                        </div>
                    </div>
                </div>
            </div>



            <div class="container-fluid page__container">
                <div class="row">
                    <div class="col-lg-3">
                        <h1 class="h4 mb-1">{{ ucwords(auth()->user()->full_name) }}</h1>
                        <div class="text-muted d-flex align-items-center">
                            <div class="flex"><a href="mailto:{{ auth()->user()->email }}">{{ auth()->user()->email }}</a></div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="tab-content">
                                <div class="tab-pane active" id="activity">

                                    <div class="card card-form">
                                        <form method="post" action="#" enctype="multipart/form-data" id="accountUpdate">
                                            @csrf
                                            <div class="row no-gutters">
                                                <div class="col-lg-4 card-body">
                                                    <div class="form-group">
                                                    @if(Auth::user()->profile_pic)
                                                        <img src="{{ Auth::user()->profile_pic }}" class="w-100 img-thumbnail" id="img-placeholder">
                                                    @else
                                                        <img src="{{asset('assets/images/avatar/placeholder.jpg')}}" alt="avatar" class="w-100 img-thumbnail" id="img-placeholder">
                                                    @endif
                                                        <input type="file" name="profile_pic" class="el" style="display:none;" id="profile_upload">
                                                        <span class="text-danger font-size-11 profile_pic" style="font-size:10px;"></span>
                                                    </div>
                                                    <label for="profile_upload" class="btn btn-primary">
                                                        Choose Image    
                                                    </label>
                                                </div>
                                                <div class="col-lg-6 card-form__body card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="fname">Full Name</label>
                                                                <input id="fname" type="text" class="form-control el" placeholder="Full name" value="{{ Auth::user()->full_name }}" name="full_name">
                                                                <span class="text-danger font-size-11 full_name" style="font-size:10px;"></span>
                                                            </div>
                                                            
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="lname">Email</label>
                                                                <input id="email" type="email" name="email" class="form-control el" placeholder="Email address" value="{{ Auth::user()->email }}">
                                                                <span class="text-danger font-size-11 email"></span>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary float-right" id="saveChange">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>

                                <div class="tab-pane" id="password">
                                    <div class="card card-form">
                                        <div class="row no-gutters">
                                            <div class="col-lg-4 card-body">
                                                <p><strong class="headings-color">Update Your Password</strong></p>
                                                <p class="text-muted">Change your password.</p>
                                            </div>
                                            <div class="col-lg-8 card-form__body card-body">
                                                <form action="" method="post" id="changePassword">
                                                @csrf
                                                    <div class="form-group">
                                                        <label for="opass">Old Password</label>
                                                        <input  id="opass" type="password" name="old_password" class="form-control el" placeholder="Old password">
                                                        <span class="text-danger font-size-11 old_password" style="font-size:10px;"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="npass">New Password</label>
                                                        <input  id="npass" type="password" name="password" class="form-control el">
                                                        <span class="text-danger font-size-11 password" style="font-size:10px;"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cpass">Confirm Password</label>
                                                        <input  id="cpass" name="password_confirmation" type="password" class="form-control el" placeholder="Confirm password">
                                                            <span class="text-danger font-size-11 password_confirmation" style="font-size:10px;"></span>
                                                    </div>
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary" id="changePasswordBtn">Change Password</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- // END drawer-layout__content -->

@endsection

@section('js')

<script>

    $("#profile_upload").on('change', ()=>{
       let files  = $("#profile_upload")[0].files;

       fileReader = new FileReader();
       fileReader.onload = (event)=>{
           let img = document.querySelector("#img-placeholder")
           img.src = event.target.result
       }

       fileReader.readAsDataURL(files[0])
    });


    $("#accountUpdate").on("submit", (e)=>{


        e.preventDefault()

        let formData = new FormData(document.querySelector("#accountUpdate"))

        let saveChangesBtn = document.querySelector("#saveChange");

        $.ajax({
            url    : "{{ route('user.account.update') }}",
            method : "POST",
            processData : false,
            contentType : false,
            data        : formData,
            beforeSend  : () => {

                saveChangesBtn.classList.add("is-loading")

            },
            success     : (response) => {

                handleError(response)

                saveChangesBtn.classList.remove("is-loading")

                if(response.success) {
                    toastr.success('Your changes has been saved successfully!');
                }

                
                
            },
            error       : (error) => {

                saveChangesBtn.classList.remove("is-loading")

                console.log(error)
            }
        })

    })

    $("#changePassword").on("submit", (e)=> {

        e.preventDefault()

        let form = document.querySelector("#changePassword")

        let formData = new FormData(form)

        let saveChangesBtn = document.querySelector("#changePasswordBtn");

        $.ajax({

            url    : "{{ route('user.account.password.reset') }}",

            method : "POST",

            processData : false,
            contentType : false,
            
            data        : formData,

            beforeSend  : () => {

                saveChangesBtn.classList.add("is-loading")

            },

            success     : (response) => {

                handleError(response)

                form.reset();

                saveChangesBtn.classList.remove("is-loading")

                if(response.success) {

                    toastr.success('You have successfully changed your password');

                }
                
            },
            error       : (error) => {

                saveChangesBtn.classList.remove("is-loading")

                console.log(error)

            }
        })

    })



</script>


@endsection