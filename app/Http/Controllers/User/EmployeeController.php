<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ProcessWalletTransfer;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use App\Helpers\Paystack;

use Carbon\Carbon;

use DB;

class EmployeeController extends Controller
{
    protected $view_dir = 'user.employee';

    public function index() {

        $employees = DB::table('employees')->where('user_id', auth()->user()->id)->orderBy('id', 'DESC')->get();

        if($this->filter() !== false) {

            $employees = $this->filter();

        }
        

        return view($this->view_dir.'.index', [

            'employees' => $employees

        ]);

    }

    public function create() {

        return view($this->view_dir.'.create', [

            'banks' => Paystack::bankList()

        ]);

    }

    public function store() {

        $user = auth()->user();

        $validator = Validator::make(request()->all(), [

            'full_name' => ['required'],
            'bank_name' => ['required'],
            'account_number' => ['required'],
            'account_name'   => ['required'],
            'salary'         => ['required']
        
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $bankDataArr = explode('-', request('bank_name'));

        $queryString = 'account_number='.request('account_number').'&bank_code='.$bankDataArr[1];

        $recipient_code = $this->getRecipientCode($queryString,  $bankDataArr[1]);

        DB::table('employees')->insert($this->record($user->id, $bankDataArr[0], $recipient_code));

        return response()->json(['success' => true]);


    }

    public function uploadCSV() {

        $validator = Validator::make(request()->all(), [

            'csv_upload' => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $pathName = request('csv_upload')->getPathName();

        $file = fopen($pathName, "r");

        $now = Carbon::now();

        $bankList = Paystack::bankList();


        while(($column = fgetcsv($file, 10000, ",")) !== FALSE) {

            array_filter($column);

            $bank_code = '';

            foreach($bankList as $bank) {

                if($column[4] == $bank->name) {

                    $bank_code = $bank->code;

                }

            }

            $queryString = 'account_number='.$column[3].'&bank_code='.$bank_code;

            $recipient_code = $this->getRecipientCode($queryString,  $bank_code);

            DB::table('employees')->insert([

                'user_id'   => auth()->user()->id,
                'full_name' => $column[0],
                'email'     => $column[1],
                'phone'     => $column[2],
                'city'      => $column[7],
                'state'     => $column[8],
                'address'   => $column[9],
                'account_num' => $column[3],
                'bank_name'   => $column[4],
                'salary'      => $column[5],
                'recipient_code' => $recipient_code,
                'role'        => $column[6],
                'created_at'  => $now,
                'updated_at'  => $now

            ]);

        }
        

        return response()->json(['success' => true ]);

    }

    public function show($id) {

        $employee = DB::table('employees')->where('id', $id)->first();

        return view($this->view_dir.'.edit', [

            'banks' => Paystack::bankList(),

            'employee' => $employee

        ]);

    }


    public function update() {

        $user = auth()->user();

        $validator = Validator::make(request()->all(), [

            'full_name' => ['required'],
            'bank_name' => ['required'],
            'account_number' => ['required'],
            'account_name'   => ['required'],
            'salary'         => ['required']
        
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $bankDataArr = explode('-', request('bank_name'));

        $queryString = 'account_number='.request('account_number').'&bank_code='.$bankDataArr[1];

        $recipient_code = $this->getRecipientCode($queryString,  $bankDataArr[1]);

        $record = $this->record($user->id, $bankDataArr[0], $recipient_code);
        
        unset($record['created_at']);

        DB::table('employees')->where([['id', request('employee_id')], ['user_id', $user->id]])->update($record);

        return response()->json(['success'=>true]);

    }

    public function delete ($id) {

        DB::table('employees')->where('id', $id)->delete();

        return response()->json(['success'=>true]);

    }

    public function bulkDelete() {

        $ids = explode(',', request('ids'));

        DB::table('employees')->whereIn('id', $ids)->delete();

        return response()->json(['success'=>true]);

    }


    public function getRecipientCode($queryString, $bank_code) {

        $employee = Paystack::resolveBankAcc($queryString);

        $recipient_code = '';

        if($employee->status == true ) {

            $data = Paystack::recipient_code([

                'type' => "nuban",
                'name' => $employee->data->account_name,
                'account_number' => $employee->data->account_number,
                'bank_code' =>  $bank_code,
                'currency' => "NGN"

            ]);

            $recipient_code = $data->recipient_code;

        }

        return $recipient_code;

    }

    public function record($id, $bank_name, $recipient_code) {

        return [

            'user_id'   => $id,
            'full_name' => request('full_name'), 
            'email'     => request('email'),
            'phone'     => request('phone'),
            'city'      => request('city'),
            'state'     => request('state'),
            'address'   => request('address'),
            'account_name' => request('account_name'),
            'account_num'  => request('account_number'),
            'bank_name'    => $bank_name,
            'salary'       => request('salary'),
            'pay_day'      => date('d', strtotime( request('pay_day') ) ),
            'recipient_code' => $recipient_code,
            'date_joined'  => request('date_joined'),
            'role'         => request('role'),
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now()

        ];

    }


    public function filter() {

        if( !empty(request('role')) || !empty(request('salary')) || !empty(request('pay_day')) ) {

            return DB::table('employees')->where('role', request('role'))->orWhere('salary', request('salary'))->orWhere('pay_day', date('d', strtotime( request('pay_day') ) ) )->get();

        }

        return false;
    }


    public function payOnce() {

        $ids = explode(',', request('ids'));

        $salarySum = DB::table('employees')->whereIn('id', $ids)->sum('employees.salary');

        if( request('payment_method') == 'Pay Stack' ) {

            session(['recipient_ids' => $ids]);

            $sum = str_replace('.00', '', $salarySum);

            return redirect( Paystack::gateway($sum, auth()->user()->email, 'user.employees.payonce.verification') );

        } else if( request('payment_method') == 'My Wallet' ) {

            $wallet = auth()->user()->wallet;

            if( !empty($wallet) && $salarySum !== 0 && $wallet->balance > $salarySum ) { 

                if( $this->handleTrans($ids) ) {

                    $newWalletBalance = $wallet->balance - $salarySum;

                    DB::table('wallets')->where('owner_id', auth()->user()->id)->update([

                        'balance' => $newWalletBalance

                    ]);

                    return redirect()->route('user.employees')->with(['success'=>'Transfer transaction complete. Ensure to view/track your transfer status from the transfer history tab!']);

                }

            } else {

                return redirect()->route('user.employees')->with(['error'=>'Your wallet Balance is not enough for this transfer!']);

            }


        }


    }


    public function processBulkTransfer() {

        $response = Paystack::verifyPayment( request('reference') );

        $recipient_ids = session('recipient_ids');

        session()->forget(['recipient_ids']);

        if($response->data->status == 'success') { 

            $this->handleTrans($recipient_ids);

        }

        return redirect()->route('user.employees')->with(['success'=>'Transfer transaction complete. Ensure view/track transfer status from the transfer history tab!']);
    }

    public function handleTrans($ids) {

        $recipients = DB::table('employees')->whereIn('id', $ids)->get();

        $transfers = [];
        $lastIds = [];

        foreach($recipients as $recipient) {


            if(empty($recipient->recipient_code)) {

                $this->doWalletTrans($recipient);

            } else {

                $transfers[] = $this->doBankTrans($recipient, $lastIds);

            }

        }

        if( !empty($transfers) ) {

            $this->processTrans($transfers, $lastIds);
    
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
