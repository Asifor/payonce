<form id="transfer_form" action="{{ route('payonce.method.bank.start-transaction') }}" method="post">
    @csrf
    <div class="table-responsive border-bottom" data-toggle="lists" data-lists-values='["js-lists-values-employee-name"]'>
        <table class="table mb-0 thead-border-top-0">
            <thead>
                <tr>

                    <th style="width:120px">Account&nbsp;Name</th>
                    <th style="width: 30px;">Account&nbsp;Number</th>
                    <th style="width: 51px;">Amount</th>
                    <th style="width: 24px;">Status</th>
                    
                </tr>
            </thead>
            <tbody class="list" id="staff02">

                @forelse($beneficiaries as $beneficiary)

                <tr>

                    <td style="width:220px;">
                        <span class="js-lists-values-employee-name">{{ $beneficiary['name'] }}</span>
                        <input type="hidden" name="account_name[]" value="{{ $beneficiary['name'] }}">
                    </td>

                    <td>{{ $beneficiary['account_num'] }}</td>
                        <input type="hidden" name="account_num[]" value="{{ $beneficiary['account_num'] }}">
                    <td>₦{{ number_format($beneficiary['amount']) }}</td>
                        <input type="hidden" name="amount[]" value="{{ $beneficiary['amount'] }}">
                    @if($beneficiary['status'] == true)
                    <td><span class="badge badge-success">Resolved</span></td>
                    @elseif($beneficiary['status'] == false)
                    <td><span class="badge badge-danger">Unresolved</span></td>
                    @endif
                    <input type="hidden" name="status[]" value="{{ $beneficiary['status'] }}">
                    <input type="hidden" name="bank[]" value="{{ $beneficiary['bank'] }}">
                    
                </tr>

                @empty

                <tr><td colspan="5" class="text-center">No Resolved Accounts</td></tr>

                @endforelse

            </tbody>
        </table>
    </div>

    @if(!empty(auth()->user()->preference->app_usage))

    <div class="form-group mr-3 pb-2 mb-2">

        <div class="row">
            
            <div class="col-md-6">

            @if( !empty(auth()->user()->preference->app_usage) )
                        
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" value="1" id="invalidCheck01" name="save_as" checked="">
                    <label class="custom-control-label" for="invalidCheck01">
                        Save as @if(auth()->user()->preference->app_usage == 'Pay Salaries') employee(s) @endif
                    </label>
                </div>

            @endif

            </div>

            <div class="col-md-6">
                <button class="btn btn-outline-primary float-right" type="submit">Proceed</button>
            </div>

        </div>
      
    </div>

    @else

    <div class="form-group mr-3 pb-4 mb-4">
        <button class="btn btn-primary float-right" type="submit">Proceed</button>
    </div>

    @endif

</form>