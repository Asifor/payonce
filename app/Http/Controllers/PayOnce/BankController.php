<?php

namespace App\Http\Controllers\PayOnce;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\Paystack;

use DB;

use Carbon\Carbon;

class BankController extends Controller
{
    protected $view_dir = 'user.bank_method';

    public function index () {

        return view($this->view_dir.'.index', [

            'banks' => Paystack::bankList()
            
        ]);

    }


    public function verify() {

        request()->validate([

            'amount' => 'required',
            'account_num' => 'required',
            'bank'  => 'required'

        ]);

        $banks = request('bank');

        $i=0;

        $beneficiaries = [];

        foreach($banks as $bank) {

            $amount = request('amount')[$i];
            $account_num = request('account_num')[$i];

            $queryString = 'account_number='.$account_num.'&bank_code='.$bank;

            $beneficiary = Paystack::resolveBankAcc($queryString);
            $data = [];
            if($beneficiary->status == true ) {

                $data['name'] = $beneficiary->data->account_name;
                $data['account_num'] = $beneficiary->data->account_number;
                $data['amount']  = $amount;
                $data['status']  = true;
                $data['bank']    = $bank;

            } else if($beneficiary->status == false) {

                $data['name']    = 'Unresolved';
                $data['account_num'] = $account_num;
                $data['amount']  = $amount;
                $data['status']  = false;
                $data['bank']    = $bank;

            }

            $beneficiaries[] = $data;

            ++$i;

        }

        return view($this->view_dir.'.ajax.beneficiaries-data', [

            'beneficiaries' => $beneficiaries

        ]);

    }

    public function startTransaction() {

        $banks = request('bank');

        $i = 0;

        $amount = 0;

        $recipient_ids = [];

        foreach($banks as $bank) {

            $acc_name = request('account_name')[$i];
            $acc_num  = request('account_num')[$i];

            $data = Paystack::recipient_code([
                'type' => "nuban",
                'name' => $acc_name,
                'account_number' => $acc_num,
                'bank_code' => $bank,
                'currency' => "NGN"

            ]);

            $status = '';

            if(request('status')[$i] == true) {
                $status = 'resolved';
            } else {
                $status = 'unresolved';
            }

            $now = Carbon::now();


            DB::table('bank_transfers')->insert([

                'user_id'       => auth()->user()->id,
                'bank_name'     => $data->details->bank_name,
                'account_name'  => $acc_name,
                'account_num'   => $acc_num,
                'amount'        => request('amount')[$i],
                'verification_status' => $status,
                'recipient_code'      => $data->recipient_code,
                'created_at'          => $now,
                'updated_at'          => $now

            ]);


            $recipient_ids[] = DB::getPdo()->lastInsertId();

            $amount += request('amount')[$i];

            ++$i;

        }

        session(['recipient_ids' => $recipient_ids, 'save_as'=>request('save_as')]);

        return redirect( Paystack::gateway($amount, auth()->user()->email, 'payonce.payment.verification') );

    }

    public function paymentVerification() {

        $user = auth()->user();
        $now  = Carbon::now();

        $response = Paystack::verifyPayment(request('reference'));

        if($response->data->status == 'success') {

            $recipient_ids = session('recipient_ids');
            $save_as       = session('save_as');

            session()->forget(['recipient_ids', 'save_as']);

            $recipients = DB::table('bank_transfers')->whereIn('id', $recipient_ids)->get();

            $transfers = [];

            foreach($recipients as $recipient) {

                $transfers[] = [

                    'amount' => str_replace('.00', '', $recipient->amount),
                    'reason' => 'Your Earnings',
                    'recipient' => $recipient->recipient_code

                ];

                if($save_as == 1) {

                    DB::table('employees')->updateOrInsert(['account_num'=>$recipient->account_num, 'user_id'=>$user->id], [

                        'user_id' => $user->id,
                        'full_name' => $recipient->account_name,
                        'account_name' => $recipient->account_name,
                        'account_num'  => $recipient->account_num,
                        'bank_name'    => $recipient->bank_name,
                        'salary'       => $recipient->amount,
                        'recipient_code' => $recipient->recipient_code,
                        'created_at'     => $now,
                        'updated_at'     => $now

                    ]);

                }

            }


            $response = Paystack::disbursePayment($transfers);

            $i = 0;

            foreach($recipient_ids as $id) {

                DB::table('bank_transfers')->where('id', $id)->update([

                    'transfer_code'   => $response->data[$i]->transfer_code,
                    'transfer_status' => 'processing'

                ]);

                ++$i;

            }


        }

        return redirect()->route('user.transactions')->with(['success'=>'Transfer transaction complete, view/track transfer status of each beneficiaries below!']);

    }


    public function fetchTransfer() {

        $response = Paystack::fetchTrans("TRF_hg3tmeeuywqz1jd");

        print_r($response);

        return;

    }


    public function listenToEventWebhook() {

        $response = Paystack::event();

        print_r($response);

        return;
    }
    
}
