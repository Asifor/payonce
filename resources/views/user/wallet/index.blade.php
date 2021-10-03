@extends('layouts.user.header')
@section('title', 'PayOnce | '.auth()->user()->full_name.' | Wallet')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content">

<div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
    <div class="mdk-drawer-layout__content page">



        <div class="container-fluid  page__heading-container">
            <div class="page__heading">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="/dashboard"><i class="material-icons icon-20pt">home</i></a></li>
                        <li class="breadcrumb-item">Wallet</li>
                    </ol>
                </nav>
            </div>
        </div>






        <div class="container-fluid page__container">
            <div class="alert alert-success alert-dismissible alertBox" style="display:none;">Your withdrawal transaction has been completed, Kindly wait for sometime for it to reflect on your bank account. Click <a href="{{ route('user.transactions') }}">here</a> to view progress.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>
            <div class="row card-group-row  pt-2">

                <div class="col-md-6 col-lg-6 card-group-row__col">
                    <div class="card card-group-row__card pricing__card">

                        <div class="card-body d-flex flex-column">
                            <div class="text-center">
                                <h2 class="pricing__title mb-0 mt-2">Wallet Balance</h4>
                                <div class="d-flex align-items-center justify-content-center border-bottom-2 flex pb-3 mt-2">
                                    <h4 class="headings-color">₦@if(!empty(auth()->user()->wallet->balance)){{ auth()->user()->wallet->balance }}@else 0.00 @endif</h4>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column mt-3">
                                <form autocomplete='off' method="post" action="{{ route('user.wallet.deposit') }}">
                                @csrf
                                    <div class="form-group">
                                        <label>Deposit More ?</label>
                                        <input type="text" name="amount" class="form-control" placeholder="Enter amount">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-outline-primary mt-auto">Deposit</button>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 card-group-row__col">
                    <div class="card card-group-row__card pricing__card">

                        <div class="card-body d-flex flex-column">
                            <div class="text-left border-bottom">
                                <h2 class="pricing__title mb-3 mt-2">Cash Withdrawal</h4>
                            </div>
                            <div class="card-body d-flex flex-column mt-3">
                                <form autocomplete='off' method="post" action="#" id="withdrawForm">
                                @csrf
                                    <div class="form-group">
                                        <label>Amount ?</label>
                                        <input type="text" name="amount" class="form-control el" placeholder="Enter amount">
                                        <span class="amount text-danger" style="font-size:12px;"></span>
                                    </div>

                                    <div class="form-group">
                                        <select class="form-control input el" name="bank_name" data-attr="de">
                                            <option value="">-- Choose Bank --</option>
                                            @foreach($banks as $bank)
                                            <option value="{{ $bank->code }}">{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="bank_name text-danger" style="font-size:12px;"></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Account Number ?</label>
                                        <input type="text" name="account_number" class="form-control el" placeholder="Enter account number">
                                        <span class="account_number text-danger" style="font-size:12px;"></span>
                                       
                                    </div>

                                    <div class="form-group account_name" style="display:none;">
                                        <label>Account Name</label>
                                        <input type="hidden" name="account_name">
                                        <input type="hidden" name="path">
                                        <p id="account_name">PAUL IGWEZE</p>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-outline-primary mt-auto confirmBtn">Confirm</button>
                                    </div>
                                </form>
                                
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

    $("#withdrawForm").submit( (e) => {
        e.preventDefault()

        let form = document.querySelector("#withdrawForm");
        let cBtn = document.querySelector(".confirmBtn");

        let formData = new FormData(form);

        let url = (formData.get('path') == 'process') ? "/wallet/process/withdrawal" : "/wallet/withdrawal/bank/verification";

        $.ajax({

            url   : url,
            method: "POST",
            data  : formData,
            contentType : false,
            processData : false,
            beforeSend  : () => {

                cBtn.classList.add('is-loading');

            },
            success     : (response) => {

                cBtn.classList.remove('is-loading');

                handleError(response);

                if(response.status == true) {

                    document.querySelector('.account_name').style.display = 'block';
                    document.querySelector('input[name="account_name"]').value = response.data.account_name
                    document.querySelector('input[name="path"]').value = 'process'
                    document.querySelector('#account_name').textContent = response.data.account_name

                    cBtn.textContent = 'Withdraw';

                } else if(response.success == true) {

                    document.querySelector('.account_name').style.display = 'none';
                    document.querySelector('#account_name').textContent  = '';
                    cBtn.textContent = 'Confirm';

                    form.reset();

                   $(".alertBox").show();

                }

            },

            error       : (error) => {

                console.log(error);

            }

        })

    } )


</script>

@endsection