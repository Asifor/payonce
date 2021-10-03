<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Helpers\Paystack;

use DB;

use Carbon\Carbon;

class WalletController extends Controller
{
    protected $view_dir = 'user.wallet';

    public function index() {

        return view($this->view_dir. '.index', [

            'banks' => Paystack::bankList()

        ]);

    }

    public function deposit() {

        request()->validate([

            'amount' => 'required'

        ]);

        $amount = request('amount');

        $callback_url = 'user.wallet.deposit.verification';

        session(['amount' => $amount]);

        return redirect( Paystack::gateway($amount, auth()->user()->email, $callback_url) );

    }


    public function depositVerification () {

        $amount = session('amount');

        session()->forget('amount');

        $response = Paystack::verifyPayment( request('reference') );

        if($response->data->status == 'success') {

            $user = auth()->user();

            DB::table('wallets')->updateOrInsert( ['owner_id'=>$user->id], [

                'owner_id' => $user->id,
                'balance'  => DB::raw('balance + '.$amount),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()

            ]);

        }

        return redirect()->route('user.wallet');

    }


    public function bankVerification () {

        $validator = Validator::make(request()->all(), [

            'amount'    => ['required'],
            'bank_name' => ['required'],
            'account_number' => ['required'],
        
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }


        $queryString = 'account_number='.request('account_number').'&bank_code='.request('bank_name');

        $beneficiary = Paystack::resolveBankAcc($queryString);

        if($beneficiary->status == false) {

            return response()->json(['account_number'=>"Could not resolve account number"]);

        }

        return response() -> json($beneficiary);

    }

    public function processWithdrawal() {

        $validator = Validator::make(request()->all(), [
            'amount'    => ['required'],
            'bank_name' => ['required'],
            'account_number' => ['required'],
            'account_name'   => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $data = Paystack::recipient_code([
            'type' => "nuban",
            'name' => request('account_name'),
            'account_number' => request('account_number'),
            'bank_code' => request('bank_name'),
            'currency' => "NGN"

        ]);

        $transfer_fields = [
            [
                'amount' => request('amount'),
                'recipient' => $data->recipient_code,
                'reason' => "PayOnce Wallet Cash Withdrawal"
            ]
        ];

        $response = Paystack::disbursePayment($transfer_fields);

        if($response->status == true) {

            $now = Carbon::now();

            DB::table('bank_transfers')->insert([

                'user_id'       => auth()->user()->id,
                'bank_name'     => $data->details->bank_name,
                'account_name'  => request('account_name'),
                'account_num'   => request('account_number'),
                'amount'        => request('amount'),
                'verification_status' => 'resolved',
                'transfer_code'       => $response->data[0]->transfer_code,
                'transfer_status'     => 'processing',
                'recipient_code'      => $data->recipient_code,
                'created_at'          => $now,
                'updated_at'          => $now

            ]);

        }

        return response() -> json(['success'=>true]);
    }
}
