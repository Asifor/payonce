@extends('layouts.user.header')
@section('title', 'PayOnce | User | Transactions')

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
                            <li class="breadcrumb-item">transactions</li>
                            <li class="breadcrumb-item active" aria-current="page">transfers</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>



        <div class="container-fluid page__container">

            @if($success = session('success'))
            <div class="alert alert-success">{{ $success }}</div>
            @endif

            <form method="get" action="#">
                <div class="card card-form d-flex flex-column flex-sm-row">
                    <div class="card-form__body card-body-form-group flex">
                        <div class="row">

                            <div class="col-sm-auto">
                                <div class="form-group">
                                    <label for="filter_category">Transaction Status</label><br>
                                    <select id="filter_category" class="custom-select" style="width: 200px;" name="status">
                                        <option value="">-- Choose --</option>
                                        <option value="processing">Processing</option>
                                        <option value="pending">Pending</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-auto">
                                <div class="form-group" style="width: 200px;">
                                    <label for="filter_date">Created Date</label>
                                    <input id="filter_date" type="text" name="date" class="form-control" placeholder="Select date ..." value="" data-toggle="flatpickr" data-flatpickr-alt-format="Y-m-d" data-flatpickr-date-format="Y-m-d">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">search</i></button>
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
                                <th>Recipient Email</th>
                                <th style="width: 120px;">Recipient&nbsp;Phone</th>
                                <th class="text-center">Amount</th>
                                <th style="width: 50px;">Transfer&nbsp;Status</th>
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

                        @forelse($transactions as $transaction)

                        @php ++$i; @endphp
                        
                            <tr class="row-{{ $transaction->id }}">

                                <td class="text-center">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input js-check-selected-row checkBox" id="customCheck1_{{ $i }}" value="{{ $transaction->id }}">
                                        <label class="custom-control-label" for="customCheck1_{{ $i }}" ><span class="text-hide">Check</span></label>
                                    </div>
                                </td>

                                <td>
                                    <div class="badge badge-soft-dark">{{ $i }}</div>
                                </td>

                                <td>
                                    {{ ucwords($transaction->email) }}
                                </td>

                                <td style="width: 140px;">{{ ucwords($transaction->phone) }}</td>

                                <td style="width:80px">
                                    {{ $transaction->amount }}
                                </td>
                                
                                @if($transaction->transfer_status !== 'cancelled')
                                <td class="text-center"><span class="badge badge-info">{{ ucfirst($transaction->transfer_status) }}</span></td>
                                @else
                                <td class="text-center"><span class="badge badge-danger">{{ ucfirst($transaction->transfer_status) }}</span></td>
                                @endif

                                <td>{{ date('d/m/Y', strtotime($transaction->created_at)) }}</td>
                                
                                <td><a href="javascript:void(0)" class="btn btn-sm btn-danger delBtn" data-trans-id="{{ $transaction->id }}"><i class="material-icons icon-16pt">delete</i></a> </td>

                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center">No Records Found</td></tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>

            </div>
        </div>



    </div>
    <!-- // END drawer-layout__content -->

@endsection

@section('js')

<script>

    $(document).ready( ()=> {

        let delBtns = document.querySelectorAll('.delBtn');

        delBtns.forEach((btn) => {

            $(btn).click( () => {

                $.ajax({

                    url : "/wallet/transactions/"+ btn.dataset.transId +"/delete",
                    method : "GET",
                    success      : (response) => {

                        if(response.success) {

                            document.querySelector('.row-'+btn.dataset.transId).style.display = 'none';

                        }

                    },

                    error        : (error) => {

                        console.log(error);

                    }

                })

            } )

        })

        handleBulkDel('wallet/transactions')

    });

</script>

@endsection