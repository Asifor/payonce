@extends('layouts.user.header')
@section('title', 'PayOnce | User | Employees - Create')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content">
    
<form method="get" id="create_emp_form">

<div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">

    <div class="mdk-drawer-layout__content page">



        <div class="container-fluid page__heading-container">
            <div class="page__heading">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                        <li class="breadcrumb-item">employees</li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>




        <div class="container-fluid page__container">

            <div class="row">


                <div class="col-md-6">

                    <div class="row">
                        <div class="col-md-12">

                            <div class="card card-form">
                                <div class="card-header">
                                    <h5>Personal Details</h5>
                                </div>
                                <div class="card-body card-form__body">

                                        <div id="form_fields">

                                            <div class="row pb-2 mb-4 border-bottom">

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Full Name</label>
                                                        <input type="text" name="full_name" class="form-control el" placeholder="Enter full name" value="{{ $employee->full_name }}">
                                                        <span class="full_name" style="color:red;font-size:12px;"> 
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control el" placeholder="Enter email" value="{{ $employee->email }}">
                                                        <span class="email" style="color:red;font-size:12px;"> 
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                    <label>Phone</label>
                                                        <input type="text" name="phone" class="form-control el" placeholder="Enter phone number" value="{{ $employee->phone }}">
                                                        <span class="phone" style="color:red;font-size:12px;"> 
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>City</label>
                                                        <input type="text" name="city" class="form-control el" placeholder="Enter city" value="{{ $employee->city }}">
                                                        <span class="city" style="color:red;font-size:12px;"> 
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>State/Province</label>
                                                        <input type="text" name="state" class="form-control el" placeholder="Enter state/province" value="{{ $employee->state }}">
                                                        <span class="state" style="color:red;font-size:12px;"> 
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                    <label>Address</label>
                                                        <input type="text" name="address" class="form-control el" placeholder="Enter address" value="{{ $employee->address }}">
                                                        <span class="address" style="color:red;font-size:12px;"> 
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                </div>
                                
                            </div>

                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="card card-form">
                                <div class="card-header">
                                    <h5>Bank Account Details</h5>
                                </div>
                                <div class="card-body card-form__body">

                                    <div class="row pb-2 mb-4 border-bottom">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Account Name</label>
                                                <input type="text" name="account_name" placeholder="Enter account name" class="form-control el" value="{{ $employee->account_name }}">
                                                <span class="account_name" style="color:red;font-size:12px;"> 
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Account Number</label>
                                                <input type="text" name="account_number" placeholder="Enter account number" class="form-control el" value="{{ $employee->account_num }}">
                                                <span class="account_number" style="color:red;font-size:12px;"> 
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Bank Name</label>
                                            <div class="form-group">
                                                <select class="form-control input el" name="bank_name" data-attr="de">
                                                    <option value="">-- Choose Bank --</option>
                                                    @foreach($banks as $bank)
                                                    <option value="{{ $bank->name.'-'.$bank->code }}" @if($employee->bank_name == $bank->name) selected @endif>{{ $bank->name }}</option>
                                                    @endforeach
                                                </select>
                                                <span class="bank_name" style="color:red;font-size:12px;"> 
                                                </span>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            
                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="card card-form">
                                <div class="card-header">
                                    <h5>Work Details</h5>
                                </div>
                                <div class="card-body card-form__body">

                                    <div class="row pb-2 mb-4 border-bottom">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Role/Position</label>
                                                <input type="text" name="role" placeholder="Enter role or position" class="form-control el" value="{{ $employee->role }}">
                                                <span class="role" style="color:red;font-size:12px;"> 
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Salary</label>
                                                <input type="text" name="salary" placeholder="Enter salary" class="form-control el" value="{{ $employee->salary }}">
                                                <span class="salary" style="color:red;font-size:12px;"> 
                                                </span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label>Date Joined</label>
                                                <input id="filter_date" type="text" name="date_joined" class="form-control el" placeholder="Select date ..." value="{{ $employee->date_joined }}" data-toggle="flatpickr" data-flatpickr-alt-format="Y-m-d" data-flatpickr-date-format="Y-m-d">
                                                <span class="date_joined" style="color:red;font-size:12px;"> 
                                                </span>
                                            </div>

                                        </div>

                                        <div class="col-md-6">
                                            
                                            <div class="form-group">
                                                <label>Pay Day Start Date</label>
                                                <input id="filter_date" type="text" name="pay_day" class="form-control el" placeholder="Select date ..." value="{{ date('Y-m-').$employee->pay_day }}" data-toggle="flatpickr" data-flatpickr-alt-format="Y-m-d" data-flatpickr-date-format="Y-m-d">
                                                <span class="pay_day" style="color:red;font-size:12px;"> 
                                                </span>
                                            </div>

                                        </div>

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

<div class="form-group emp_create_btn">
    <input type="hidden" value="{{ $employee->id }}" name="employee_id">
    <button type="submit" class="btn btn-outline-primary createBtn">Save</button>
</div>

</form>

@endsection

@section('js')

<script>

$("#create_emp_form").submit( (e) => {

    e.preventDefault();

    let form = document.querySelector('#create_emp_form');

    let formData = new FormData(form);

    let btn = document.querySelector('.createBtn');

    $.ajax({

        url : "{{ route('user.employees.update') }}",
        method : "POST",
        data   : formData,
        processData : false,
        contentType : false,
        beforeSend  : () => {

            btn.classList.add('is-loading');

        },

        success: (response) => {

            btn.classList.remove('is-loading');

            handleError(response);

            if(response.success) {

                toastr.success('Changes updated successfully!')

            }

        },

        error : (error) => {

            console.log(error)

        }

    })

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"

    }

} )

</script>

@endsection