<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;

class TransactionController extends Controller
{
    public function index() {

        $transactions = DB::table('bank_transfers')->where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();

        if($this->filter() !== false) {

            $transactions = $this->filter();

        }


        return view('user.transaction.index', [

            'transactions' => $transactions

        ]);

    }

    public function delete ($id) {

        DB::table('bank_transfers')->where('id', $id)->delete();

        return response()->json(['success'=>true]);

    }

    public function bulkDelete() {

        $ids = explode(',', request('ids'));

        DB::table('bank_transfers')->whereIn('id', $ids)->delete();

        return response()->json(['success'=>true]);

    }


    public function filter() {

        if(( request('status') || request('date') )) {

           return  DB::table('bank_transfers')->where([

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
