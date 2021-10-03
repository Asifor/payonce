<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
<!-- App Settings FAB -->
    <div id="app-settings" style="display: none;">
        <app-settings layout-active="default" :layout-location="{
      'default': 'staff.html',
      'fixed': 'fixed-staff.html',
      'fluid': 'fluid-staff.html',
      'mini': 'mini-staff.html'
    }"></app-settings>
    </div>

    <!-- jQuery -->
    <script src="{{asset('assets/vendor/jquery.min.js')}}"></script>

    <!-- Bootstrap -->
    <script src="{{asset('assets/vendor/popper.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap.min.js')}}"></script>

    <!-- Perfect Scrollbar -->
    <script src="{{asset('assets/vendor/perfect-scrollbar.min.js')}}"></script>

    <!-- DOM Factory -->
    <script src="{{asset('assets/vendor/dom-factory.js')}}"></script>

    <!-- MDK -->
    <script src="{{asset('assets/vendor/material-design-kit.js')}}"></script>

    <!-- App -->
    <script src="{{asset('assets/js/toggle-check-all.js')}}"></script>
    <script src="{{asset('assets/js/check-selected-row.js')}}"></script>
    <script src="{{asset('assets/js/dropdown.js')}}"></script>
    <script src="{{asset('assets/js/sidebar-mini.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>

    <!-- App Settings (safe to remove) -->
    <script src="{{asset('assets/js/app-settings.js')}}"></script>



    <!-- Flatpickr -->
    <script src="{{asset('assets/vendor/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset('assets/js/flatpickr.js')}}"></script>

    <!-- Global Settings -->
    <script src="{{asset('assets/js/settings.js')}}"></script>


    <!-- Chart.js -->
    <script src="{{asset('assets/vendor/Chart.min.js')}}"></script>

    <!-- UI Charts Page JS -->
    <script src="{{asset('assets/js/chartjs-rounded-bar.js')}}"></script>
    <script src="{{asset('assets/js/charts.js')}}"></script>

    <!-- Chart.js Samples -->
    <script src="{{asset('assets/js/page.staff.js')}}"></script>

     <!-- Toastr -->
     <script src="{{asset('assets/vendor/toastr.min.js')}}"></script>
    <script src="{{asset('assets/js/toastr.js')}}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        function handleError(response) {

            let input = document.querySelectorAll('.el'); // get all the input fields with the class `.el`

            input.forEach( (field) => {

                let errorElement = document.querySelector('.'+field.name); // this will get any element (in our case span tag) that the class attribute value is same with the input tag name attribute value

                // check if the input field has an error

                if(response.hasOwnProperty(field.name)) {

                        errorElement.innerHTML = response[field.name]; // replace the text content of the span tag with the error response message

                    } else {

                        errorElement.innerHTML = ''; // else leave it empty or set it to display none.

                    }

            } );

        }


        function handleBulkDel(items) {

            let bulkDelBtn = document.querySelector('#bulkDel');

            $(bulkDelBtn).click( ()=>{

                let checkBoxes = document.querySelectorAll('.checkBox');
                let ids = [];
                checkBoxes.forEach( (checkBox) => {

                    

                    if(checkBox.checked) {

                        ids.push(checkBox.value)

                    }
                    

                } )

                let newItm = items.replace('/', ' ');

                if(ids.length > 0) {

                    let idsString = ids.join(',');

                    $.ajax({

                        url : '/'+items+'/delete?ids='+idsString,
                        method : 'GET',
                        success : (response) => {

                            if(response.success) {

                                for(let i = 0; i<ids.length; i++) {

                                    document.querySelector('.row-'+ids[i]).remove();

                                }

                                toastr.success(jsUcFirst(newItm)+' deleted successfully!');

                            }

                        },
                        error   : (error) => {

                            console.log('error')

                        }

                    })


                } else  {
                   
                    toastr.warning('No '+jsUcFirst(newItm)+' has been selected for this action!')

                }


            } )


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

        }

        function jsUcFirst(string) {

            return string.charAt(0).toUpperCase() + string.slice(1);

        }
    </script>

    
    @yield('modal')
    @yield('js')


    <!-- Info Alert Modal -->
    <div id="walletPayment" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-4">
                    <h4>Wallet Transfer Method</h4>
                </div>
                <form autocomplete="OFF" method="post" action="" id="walletTransForm">
                    <div class="modal-body p-4">
                        <div class="form-group">
                            <label>Recipient Email</label>
                            <textarea name="recipient_email" class="form-control el" placeholder="Enter multiple email addresses, separating with a comma"></textarea>
                            <span class="recipient_email text-danger" style="font-size:12px;"></span>
                        </div>
                        <div class="form-group">
                            <label>Recipient Phone</label>
                            <textarea name="recipient_phone"  class="form-control el" placeholder="Enter multiple phone numbers, separate each with a comma"></textarea>
                            <span class="recipient_phone text-danger" style="font-size:12px;"></span>
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <textarea name="amount"  class="form-control el" placeholder="Enter multiple amounts, separate each with a comma"></textarea>
                            <span class="amount text-danger" style="font-size:12px;"></span>
                        </div>

                        @if( !empty(auth()->user()->preference->app_usage) )
                        
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox" value="1" id="invalidCheck01" name="save_as" checked="">
                            <label class="custom-control-label" for="invalidCheck01">
                                Save as @if(auth()->user()->preference->app_usage == 'Pay Salaries') employee(s) @endif
                            </label>
                        </div>

                        @endif
                    </div> <!-- // END .modal-body -->
                    <div class="modal-footer p-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-outline-primary proceedBtn">Proceed</button>
                        </div>
                    </div>
                </form>
            </div> <!-- // END .modal-content -->
        </div> <!-- // END .modal-dialog -->
    </div> <!-- // END .modal -->

    <script>

        $("#walletTransForm").submit((e)=>{

            e.preventDefault();

            let form = document.querySelector("#walletTransForm");

            let formData = new FormData(form);

            let btn = document.querySelector('.proceedBtn')

            $.ajax({

                url : '/payonce/wallet/transfer',
                method : 'POST',
                contentType : false,
                processData : false,
                data        : formData,
                beforeSend  : () => {

                    btn.classList.add('is-loading');

                }, 

                success : (response) => {

                    btn.classList.remove('is-loading');
                    handleError(response)
                   
                    if(response.success) {

                        window.location = '/payonce/wallet/transfer/start-transaction';

                    }

                },
                error   : (error) => {

                    console.log(error)
                }

            })

        })

    </script>

</body>

</html>