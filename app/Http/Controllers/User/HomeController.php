<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index() {

        $user = auth()->user();

        $count = DB::table('employees')->where('user_id', $user->id)->count();

        $employees = DB::table('employees')->where('user_id', $user->id)->orderBy('id', 'DESC')->limit(20)->get();

        $salarySumForToday = DB::table('employees')->where([

            ['user_id', $user->id],

            ['pay_day', Carbon::now()->day]

        ])->sum('employees.salary');


        return view('user.index', [

            'count'     => $count,

            'employees' => $employees,

            'salarySumForToday' => $salarySumForToday

        ]);

    }
}
