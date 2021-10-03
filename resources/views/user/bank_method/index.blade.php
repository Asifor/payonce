@extends('layouts.user.header')
@section('title', 'PayOnce | Bank | Method')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content">

<div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
    <div class="mdk-drawer-layout__content page">



        <div class="container-fluid page__heading-container">
            <div class="page__heading">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#"><i class="material-icons icon-20pt">home</i></a></li>
                        <li class="breadcrumb-item">methods</li>
                        <li class="breadcrumb-item active" aria-current="page">Bank</li>
                    </ol>
                </nav>
            </div>
        </div>




        <div class="container-fluid page__container">

            <div class="card card-form d-flex flex-column flex-sm-row">
                <div class="card-form__body card-body-form-group flex">
                    <div class="row">
                        <div class="col-sm-auto">
                            <div class="form-group">
                                <label for="filter_name">Add Beneficiaries</label>
                                <input id="filter_name" type="number" class="form-control numBeneficiaries">
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>

            <div class="row">


                <div class="col-md-5">

                    <div class="card card-form">

                        <div class="card-body card-form__body">
                            <form method="get" id="beneficiaryForm">

                                <div id="form_fields">

                                    <div class="row pb-2 mb-4 border-bottom">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" name="amount[]" class="form-control input" placeholder="Amount" data-attr="de">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" name="account_num[]" class="form-control input" placeholder="Account Number" data-attr="de">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <select class="form-control input" name="bank[]" data-attr="de">
                                                    <option value="">-- Choose Bank --</option>
                                                    @foreach($banks as $bank)
                                                    <option value="{{ $bank->code }}">{{ $bank->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12 delCol"></div>

                                    </div>

                                </div>

                                <div class="form-group">
                                    <button class="btn btn-outline-primary verifyBtn" type="submit">Verify</button>
                                </div>

                            </form>

                        </div>
                        
                    </div>

                </div>

                <div class="col-md-7">

                        <div class="card card-form">
                            <div class="row no-gutters">
                                <div class="col-lg-12 card-form__body"  id="beneficiaries-data">

                                    <p class="text-center pt-3 text-muted">No Resolved Accounts</p>

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

    let options = '';

    @foreach($banks as $bank) 

        options +='<option value="{{ $bank->code }}">{{ $bank->name }}</option>';

    @endforeach



    $('.numBeneficiaries').on('change', ()=>{

        let num = $('.numBeneficiaries').val();

        for(let i=0; i<num; i++) {

            let sel = '<select class="form-control input" name="bank[]"><option value="" >-- Choose Bank --</option>'+options+'</select>';

            let newRow = '<div class="row pb-2 mb-4 border-bottom"><div class="col-md-6"><div class="form-group"><input type="text" name="amount[]" class="form-control input" placeholder="Amount"></div></div><div class="col-md-6"><div class="form-group"><input type="text" name="account_num[]" class="form-control input" placeholder="Account Number"></div></div><div class="col-md-12"><div class="form-group">'+sel+'</div></div><div class="col-md-12 delCol"></div></div>';

            $('#form_fields').append(newRow);
            
        }

        let delCol = document.querySelectorAll('.delCol');

        delCol.forEach((del)=>{
            let     a  = document.createElement('a');
            a.textContent = "Remove";
            a.setAttribute('class', 'trashBtn float-right');
            a.setAttribute('href', 'javascript:void(0)');

            if(del.hasChildNodes() === false) {
                del.appendChild(a)
            }
        })

        let trashBtn = document.querySelectorAll('.trashBtn');

        trashBtn.forEach((btn)=>{
            $(btn).click(()=>{
                $(btn.parentNode.parentNode).remove();
            })
        })


        //auto validate beneficiary account details

       // beneficiary();
        

    })


    //verify beneficiary account details

    $('#beneficiaryForm').on('submit', (e)=>{

        e.preventDefault();

        let form = document.querySelector('#beneficiaryForm');

        let verifyBtn = document.querySelector('.verifyBtn');

        let formData = new  FormData(form);

        $.ajax({
            url : "{{ route('payonce.method.bank.verify') }}",
            method: "POST",
            data  : formData,
            processData : false,
            contentType : false,
            beforeSend  : () => {
                verifyBtn.classList.add('is-loading', 'is-loading-sm')
            },

            success     : (response) => {

                verifyBtn.classList.remove('is-loading', 'is-loading-sm')

                $("#beneficiaries-data").html(response);

            },

            error       : (error) => {

                console.log(error)

            }
        })

    })


    // beneficiary();


    // function beneficiary() {


    //     let inputs = document.querySelectorAll('.input');

    //     let arr    = []; 

    //     inputs.forEach(input=>{

    //         $(input).on('change',  ()=>{

    //             if(input.value !='') {

    //                 let obj = {};

    //                 obj.name = input.name;
    //                 obj.id   = input.dataset.attr;
    //                 obj.value= input.value;

    //                 arr.push(obj)

    //             }

    //             let counts = [];

    //             for(let i=0; i<arr.length; i++) {

    //                 if(counts[arr[i].id]) {

    //                     counts[arr[i].id] +=1;

    //                 } else {

    //                     counts[arr[i].id] =1

    //                 }

    //             }

                

    //             for(pop in counts) {

    //                 if(counts[pop] >= 3) {

    //                     let beneficiary = document.querySelectorAll('[data-attr="'+ pop +'"]');

    //                     let data = [];

    //                     beneficiary.forEach(bn=>{

    //                         let newObj = {};

    //                         newObj.name  = bn.name;
    //                         newObj.value = bn.value;

    //                         data.push(newObj);

    //                     })

    //                     console.log (data);

    //                 }

                   

    //             }


    //         })

    //     })

    // }

    
    
</script>

@endsection