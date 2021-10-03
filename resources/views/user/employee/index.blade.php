@extends('layouts.user.header')
@section('title', 'PayOnce | User | Employees')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content">

<div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">

<div class="mdk-drawer-layout__content page">



    <div class="container-fluid page__heading-container">
        <div class="page__heading d-flex align-items-center">
            <div class="flex">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/dashboard"><i class="material-icons icon-20pt">home</i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">employees</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('user.employees.create') }}" class="btn btn-outline-primary ml-3">Create <i class="material-icons">add</i></a>

            <form enctype="multipart/form-data" id="csv_form">
                <label for="csv_upload" class="btn btn-outline-primary ml-3 mt-2" id="upload_btn">Upload CSV <i class="material-icons">publish</i></label>
                <input type="file" style="display:none;" name="csv_upload" id="csv_upload">
            </form>
            
        </div>
    </div>



    <div class="container-fluid page__container">

        @if($success = session('success'))
            <div class="alert alert-success">{{ $success }}</div>
        @endif

        @if($error = session('error'))
            <div class="alert alert-danger">{{ $error }}</div>
        @endif

        <form method="get" action="">
            <div class="card card-form d-flex flex-column flex-sm-row">
                <div class="card-form__body card-body-form-group flex">
                    <div class="row">
                    <div class="col-sm-auto">
                            <div class="form-group">
                                <label for="filter_category">Role</label><br>
                                <input class="form-control" style="width: 200px;" name="role" placeholder="Search by role">
                            </div>
                        </div>

                        <div class="col-sm-auto">
                            <div class="form-group">
                                <label for="salary">Salary</label><br>
                                <input id="salary" class="custom-select" style="width: 200px;" name="salary">
                            </div>
                        </div>
                        
                        <div class="col-sm-auto">
                            <div class="form-group" style="width: 200px;">
                                <label for="filter_date">Pay Day</label>
                                <input id="filter_date" type="text" name="pay_day" class="form-control" placeholder="Select date ..." data-toggle="flatpickr" data-flatpickr-alt-format="Y-m-d" data-flatpickr-date-format="Y-m-d">
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">search</i></button>
            </div>
        </form>

        <div class="card">


            <div class="table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>

                <table class="table mb-0 thead-border-top-0 table-striped">
                    <thead>
                        <tr>

                            <th style="width: 18px;">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input js-toggle-check-all" data-target="#companies" id="customCheckAll">
                                    <label class="custom-control-label" for="customCheckAll"><span class="text-hide">Toggle all</span></label>
                                </div>
                            </th>

                            <th style="width: 30px;">S/N</th>
                            
                            <th style="width:140px;">Employee</th>

                            

                            <th style="width: 140px;">Account&nbsp;Name</th>
                            <th style="width: 120px;">Bank&nbsp;Name</th>
                            <th>Account&nbsp;Number</th>
                            <th class="text-center">Salary</th>

                            <th style="width:30px;">Pay Day</th>
                           
                            <th >Created</th>
                            <th style="width: 50px;">
                                <div class="dropdown pull-right">
                                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">Bulk</a>
                                    <div class="dropdown-menu dropdown-menu-right">

                                        <a href="javascript:void(0)" class="dropdown-item" id="payonce"><i class="material-icons  mr-1">monetization_on</i>Pay Once</a>

                                        <a href="javascript:void(0)" class="dropdown-item" id="bulkDel"><i class="material-icons  mr-1">delete</i>Delete</a>
                                        
                                    </div>
                                </div>
                            </th>
                        </tr>

                    </thead>
                    <tbody class="list" id="companies">

                    @php $i=0; @endphp

                    @forelse($employees as $employee)

                    @php ++$i @endphp

                        <tr class="row-{{ $employee->id }}">

                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input js-check-selected-row checkBox" id="customCheck1_{{ $i }}" value="{{ $employee->id }}">
                                    <label class="custom-control-label" for="customCheck1_{{ $i }}"><span class="text-hide">Check</span></label>
                                </div>
                            </td>

                            <td>
                                <div class="badge badge-soft-dark">{{ $i }}</div>
                            </td>

                            <td style="width:140px;">@if(empty($employee->full_name)) N/A @else {{ ucwords($employee->full_name) }} @endif</td>

                            

                            <td style="width: 140px;">@if(empty($employee->account_name)) N/A @else {{ ucwords($employee->account_name) }} @endif</td>

                            <td style="width: 140px;">@if(empty($employee->bank_name)) N/A @else {{ ucwords($employee->bank_name) }} @endif</td>

                            <td style="width:80px">@if(empty($employee->account_num)) N/A @else {{ $employee->account_num }} @endif</td>

                            <td class="text-center">₦{{ number_format($employee->salary) }} </td>

                            <td style="width:30px;">@if(empty($employee->pay_day)) N/A @else {{ $employee->pay_day.date('/m/Y') }} @endif</td>

                            <td>{{ date('d/m/Y', strtotime($employee->created_at)) }}</td>
                            
                            <td>
                                <a href="{{ route('user.employees.show', $employee->id) }}" class="btn btn-sm btn-link"><i class="material-icons icon-16pt">edit</i></a> 
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger delBtn" data-emp-id="{{ $employee->id }}"><i class="material-icons icon-16pt">delete</i></a> 
                            </td>

                        </tr>
                        
                    @empty
                        <tr><td colspan="10" class="text-center">No Records Found</td></tr>
                    @endforelse
                    

                    </tbody>
                </table>
            </div>

        </div>
    </div>



</div>
<!-- // END drawer-layout__content -->

@endsection

@section('modal')

<!-- Info Alert Modal -->
<div id="modal-inf" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
        <div class="modal-body p-4">
            <p class="text-center"><i class="material-icons icon-40pt text-info mb-2">info_outline</i></p>
            <h4 class="text-center">Well done 👋!</h4>
            <p class="mt-3">How would you like to pay ?</p>
            <form method="post" action="{{ route('user.employees.payonce') }}">
            @csrf
                <div class="form-group">
                    <select class="form-control el" name="payment_method">
                        <option value="">-- Choose --</option>
                        <option value="My Wallet">My Wallet</option>
                        <option value="Pay Stack">Pay Stack</option>
                    </select>
                </div>
                <div class="form-group text-right">
                    <input type="hidden" name="ids" id="ids">
                    <button class="btn btn-primary subBtn">Submit</button>
                </div>
            </form>
        </div> <!-- // END .modal-body -->
    </div> <!-- // END .modal-content -->
</div> <!-- // END .modal-dialog -->
</div> <!-- // END .modal -->

@endsection


@section('js')

<script>

    $(document).ready( ()=> {

        let delBtns = document.querySelectorAll('.delBtn');

        delBtns.forEach((btn) => {

            $(btn).click( () => {

                $.ajax({

                    url : "/employees/"+ btn.dataset.empId +"/delete",
                    method : "GET",
                    success      : (response) => {

                        if(response.success) {

                            document.querySelector('.row-'+btn.dataset.empId).style.display = 'none';

                        }

                    },

                    error        : (error) => {

                        console.log(error);

                    }

                })

            } )

        })

        handleBulkDel('employees')


        $("#csv_upload").change( () => {

            let form = document.querySelector('#csv_form');

            let btn  = document.querySelector('#upload_btn');

            let formData = new FormData(form);

            $.ajax({

                url : '/employees/create/via/csv',
                method: "POST",
                data : formData,
                processData : false,
                contentType : false,
                beforeSend  : () => {

                    btn.classList.add('is-loading');

                }, 
                success  : (response) => {


                    if(response.success) {

                        location.reload();

                    } else {

                        handleError(response);

                    }

                },

                error : (error) => {

                    console.log(error)

                }

            })

        } );



        let bulkPayBtn = document.querySelector('#payonce');

        $(bulkPayBtn).click( ()=>{

            let checkBoxes = document.querySelectorAll('.checkBox');

            let ids = [];
            checkBoxes.forEach( (checkBox) => {

                

                if(checkBox.checked) {

                    ids.push(checkBox.value)

                }
                

            } )


            if(ids.length > 0) {

                let idsString = ids.join(',');

                $("#ids").val(idsString)

                $("#modal-inf").modal('toggle')


            } else  {
                
                toastr.warning('No Employee has been selected for this action!')

            }


        } )

    });

</script>

@endsection