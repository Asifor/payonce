<?php

namespace App\Http\Controllers\PayOnce;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWalletTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use DB;

use Carbon\Carbon;
use App\Helpers\Paystack;

class PayRollController extends Controller
{
    public function startProcessing () {

        $user_preferences = DB::table('preferences')->where('enable_payroll', true)->get();

        foreach($user_preferences as $preference) {

            if( $preference->enable_payroll == true ) {

                $salarySumForToday = DB::table('employees')->where([

                    ['user_id', $preference->user_id],

                    ['pay_day', Carbon::now()->day]

                ])->sum('employees.salary');

                $wallet = DB::table('wallets')->where('owner_id', $preference->user_id)->first();

                $this->handleTransfer($wallet, $salarySumForToday, $preference);

            }

        }

        return;

    }


    public function handleTransfer($wallet, $salarySumForToday, $preference) {

        if( !empty($wallet) && $salarySumForToday !== 0 && $wallet->balance > $salarySumForToday ) {

            $walletBalance = $wallet->balance;

            $transfers = $this->handleEmployeeTrans($walletBalance, $preference);

            DB::table('wallets')->where('owner_id', $preference->user_id)->update([

                'balance' => $walletBalance

            ]);

            return true;

        }

    }


    public function handleEmployeeTrans(&$walletBalance, $preference) {

        $employees = DB::table('employees')->where([

            ['user_id', $preference->user_id],

            ['pay_day', Carbon::now()->day]

        ])->get();

        $bank_transfers = [];

        $lastIds = [];

        foreach($employees  as $employee) {


            if( empty($employee->account_num) ) {

                $this->doWalletTrans($employee);

            } else {

                $bank_transfers[] = $this->doBankTrans($employee, $lastIds);

            }

            $walletBalance -= $employee->salary;

        }

        if( !empty($bank_transfers) ) {

           $this->processTrans($bank_transfers, $lastIds);

        }

        return true;

    }

    public function processTrans($bank_transfers, $lastIds) {

        $response = Paystack::disbursePayment($bank_transfers);

        $i = 0;

        foreach($lastIds as $id) {

            DB::table('bank_transfers')->where('id', $id)->update([

                'transfer_code'   => $response->data[$i]->transfer_code,
                'transfer_status' => 'processing'

            ]);

            ++$i;
        }

        return true;

    }


    public function doBankTrans($employee, &$lastIds) {

        $now = Carbon::now();

        DB::table('bank_transfers')->insert([

            'user_id'       => $employee->user_id,
            'bank_name'     => $employee->bank_name,
            'account_name'  => $employee->account_name,
            'account_num'   => $employee->account_num,
            'amount'        => $employee->salary,
            'verification_status' => 'resolved',
            'recipient_code'      => $employee->recipient_code,
            'created_at'          => $now,
            'updated_at'          => $now

        ]);

        $lastIds[] = DB::getPdo()->lastInsertId();

        return  [

            'amount' => str_replace('.00', '', $employee->salary),
            'reason' => 'Your Earnings',
            'recipient' => $employee->recipient_code

        ];

    }


    public function doWalletTrans($employee) {

        $transfer_code = Str::random(25);

        $now = Carbon::now();

        $employer = DB::table('users')->where('id', $employee->user_id)->first();

        DB::table('wallet_transfers')->insert([

            'user_id' => $employee->user_id,
            'email'   => $employee->email,
            'phone'   => $employee->phone,
            'amount'  => $employee->salary,
            'transfer_code' => $transfer_code,
            'transfer_status' => 'processing',
            'created_at' => $now,
            'updated_at' => $now,

        ]);

        $data = [

            'token'    => $transfer_code,
            'amount'   => $employee->salary,
            'sender'   => $employer->full_name,
            'created_at' => date('F j, Y H:i:s', strtotime($now))

        ];

        $transfer_data = [

            'transfer_email' => $employee->email,
            'data'           => $data

        ];

        ProcessWalletTransfer::dispatch($transfer_data);

        return true;

    }

}
