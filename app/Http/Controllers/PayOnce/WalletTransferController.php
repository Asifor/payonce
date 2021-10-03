<?php

namespace App\Http\Controllers\PayOnce;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Helpers\Paystack;
use App\Jobs\ProcessWalletTransfer;

use Carbon\Carbon;


use DB;

class WalletTransferController extends Controller
{

    public function index() {

        $transactions = DB::table('wallet_transfers')->where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();

        if($this->filter() !== false) {

            $transactions = $this->filter();

        }


        return view('user.transaction.wallet-transfer', [

            'transactions' => $transactions

        ]);

    }

    public function store() {

        $validator = Validator::make(request()->all(), [

            'recipient_email' => ['required'],
            'recipient_phone' => ['required'],
            'amount'          => ['required']
        
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }


        $emails = explode(',', request('recipient_email'));

        $phones  = explode(',', request('recipient_phone'));

        $amounts = explode(',', request('amount'));

        $user = auth()->user();

        $now = Carbon::now();

        $i = 0;

        $transfers = [];

        foreach($emails as $email) {

            DB::table('wallet_transfers')->insert([

                'user_id' => $user->id,
                'email'   => $email,
                'phone'   => $phones[$i],
                'amount'  => $amounts[$i],
                'transfer_code' => Str::random(25),
                'transfer_status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,

            ]);

            $transfers[] = DB::getPdo()->lastInsertId();

            ++$i;

        }

        session(['transfers'=>$transfers, 'amounts'=>$amounts, 'save_as' => request('save_as')]);

        return response()->json(['success'=>true]);

    }


    public function delete ($id) {

        DB::table('wallet_transfers')->where('id', $id)->delete();

        return response()->json(['success'=>true]);

    }

    public function bulkDelete() {

        $ids = explode(',', request('ids'));

        DB::table('wallet_transfers')->whereIn('id', $ids)->delete();

        return response()->json(['success'=>true]);

    }


    public function startTransaction() {

        $amounts = session('amounts');

        session()->forget('amounts');

        $amount = 0;

        foreach($amounts as $amt) {
            $amount +=$amt;
        }

        $callback_url = 'payonce.wallet.transfer.verify';

        return redirect( Paystack::gateway($amount, auth()->user()->email, $callback_url) );

    }


    public function verifyTransaction() {

        $user = auth()->user();

        $now = Carbon::now();
        
        $transfers = session('transfers');

        $save_as   = session('save_as');

        session()->forget(['transfers', 'save_As']);

        $response = Paystack::verifyPayment(request('reference'));

        if($response->data->status == 'success') {

            foreach($transfers as $trans_id) {

                $transfer = DB::table('wallet_transfers')->where('id', $trans_id)->first();

                $user = DB::table('users')->where('email', $transfer->email)->first();

                $status = 'processing';

                $trans_code = $transfer->transfer_code;

                if(!empty($user)) {

                    $status = 'completed';
                    $trans_code = '';

                    DB::table('wallets')->updateOrInsert( ['owner_id'=>$user->id], [

                        'owner_id'   => $user->id,
                        'balance'    => DB::raw('balance + '.$transfer->amount),
                        'created_at' => $now,
                        'updated_at' => $now
        
                    ]);
                    
                }

                DB::table('wallet_transfers')->where('id', $trans_id)->update([

                    'transfer_status' => $status,
                    'updated_at'      => $now

                ]);

                $data = [

                    'token'    => $trans_code,
                    'amount'   => $transfer->amount,
                    'sender'   => $user->full_name,
                    'created_at' => date('F j, Y H:i:s', strtotime($now))

                ];

                $transfer_data = [
                    'transfer_email' => trim($transfer->email),
                    'data'           => $data
                ];

                ProcessWalletTransfer::dispatch($transfer_data);
                
                if( $save_as == 1 ) {


                    DB::table('employees')->insert([

                        'user_id' => $user->id,
                        'email'   => $transfer->email,
                        'phone'   => $transfer->phone,
                        'salary'  => $transfer->amount,
                        'created_at' => $now,
                        'updated_at' => $now

                    ]);
                }

            }

        }


        return redirect()->route('user.wallet.transfer.transactions')->with(['success'=>'Transfer transaction complete, view/track transfer status of each beneficiaries below!']);

    }




    public function filter() {

        if(( request('status') || request('date') )) {

           return  DB::table('wallet_transfers')->where([

               ['transfer_status', request('status')], 
               ['user_id', auth()->user()->id]

               ])->orWhere([
                   
                   ['created_at', request('date')], 
                   ['user_id', auth()->user()->id]
                
               ])->orderBy('created_at', 'DESC')->get();

        }

        return false;

    }
}
