@extends('layouts.user.header')
@section('title', 'PayOnce | Dashboard')

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
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                    <div class="mt-2">
                        @if(date('a') == 'am') 
                        <p>Good Morning <strong>{{ auth()->user()->full_name }}</strong>😀</p>
                        @elseif(date('a') == 'pm')
                        <p>Good Evening <strong>{{ auth()->user()->full_name }}</strong>😀</p>
                        @endif
                        
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-outline-primary ml-3">Pay Once</a>
                    <div class="dropdown-menu dropdown-menu-right">

                        <a href="{{ route('payonce.method.bank') }}" class="dropdown-item"><i class="material-icons  mr-1">account_balance</i>Via Bank Transfer</a>

                        <a href="javascript:void(0)" class="dropdown-item" data-toggle="modal" data-target="#walletPayment"><i class="material-icons  mr-1">account_balance_wallet</i>Via Wallet Transfer</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid page__container">
            <div class="row card-group-row">
                <div class="col-lg-4 col-md-6 card-group-row__col">
                    <div class="card card-group-row__card">
                        <div class="card-body-x-lg card-body d-flex flex-row align-items-center">
                            <div class="flex">
                                <div class="card-header__title text-muted mb-2 d-flex">Total Payroll Today</div>
                                <span class="h4 m-0">₦{{ number_format($salarySumForToday) }}<small class="text-muted"></small> </span>
                            </div>
                            <div><i class="material-icons icon-muted icon-40pt ml-3">monetization_on</i></div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 card-group-row__col">
                    <div class="card card-group-row__card">
                        <div class="card-body-x-lg card-body d-flex flex-row align-items-center">
                            <div class="flex">
                                <div class="card-header__title text-muted d-flex mb-2">Wallet Balance</div>
                                <span class="h4 m-0">₦@if(!empty(auth()->user()->wallet->balance)){{ auth()->user()->wallet->balance }}@else 0.00 @endif</span>
                            </div>
                            <div><i class="material-icons icon-muted icon-40pt ml-3">account_balance_wallet</i></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 card-group-row__col">
                    <div class="card card-group-row__card">
                        <div class="card-body-x-lg card-body d-flex flex-row align-items-center">
                            <div class="flex">
                                @if(empty($app_usage))
                                <div class="card-header__title text-muted mb-2">Team/Employees</div>
                                @elseif($app_usage == 'Pay Salaries')
                                <div class="card-header__title text-muted mb-2">Employees</div>
                                @elseif($app_usage == 'Pay Team Members')
                                <div class="card-header__title text-muted mb-2">Team Members</div>
                                @endif

                                <div class="d-flex align-items-center">
                                    <div class="h4 m-0">{{ number_format($count) }}</div>
                                </div>
                            </div>
                            <div><i class="material-icons icon-muted icon-40pt ml-3">group</i></div>
                        </div>
                    </div>
                </div>
            </div>

            

            <div class="card">
                <div class="card-header">
                    <form class="form-inline">
                        <label class="mr-sm-2" for="inlineFormFilterBy">Filter by:</label>
                        <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" name="emp_f" id="inlineFormFilterBy" placeholder="Type a name">
                    </form>
                </div>


                <div class="table-responsive border-bottom" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>

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
                    <h4 class="text-center">Hello 👋!</h4>
                    <p class="mt-3">Please let us know how you want to use the platform</p>
                    <form class="preference_form">
                        <div class="form-group">
                            <select class="form-control el" name="preference">
                                <option value="">-- Choose --</option>
                                <option value="Pay Salaries">Pay Salaries</option>
                                <option value="Pay Team Members">Pay Team members</option>
                            </select>
                            <span class="preference" style="color:red;font-size:12px;"> 
                            </span>
                        </div>
                        <div class="form-group text-right">
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
        @if(empty(auth()->user()->preference->app_usage))

            $("#modal-inf").modal("show")

        @endif

        $(document).ready(function() {

            $(".preference_form").submit( (e)=> {

                e.preventDefault();

                let formData  = new FormData(document.querySelector('.preference_form'))

                let btn = document.querySelector('.subBtn');

                $.ajax({

                    url : "{{ route('user.save.app-usage.setting') }}",
                    method : "POST",
                    data   : formData,
                    processData : false,
                    contentType : false,
                    beforeSend  : () => {

                        btn.classList.add('is-loading')

                    },
                    success : (response) => {

                        btn.classList.remove('is-loading')

                        handleError(response)

                        if(response.success == true) {

                            location.reload();

                        }

                    },
                    error   : (error) =>  {

                        console.log(error)

                    }

                })

            })


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

        })
    </script>
    @endsection