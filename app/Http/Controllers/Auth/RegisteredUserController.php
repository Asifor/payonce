<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use DB;

use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        Auth::login($user = User::create([
            'full_name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]));

        event(new Registered($user));

        if( !empty( request('token') ) ) {

            $user = auth()->user();

            $transfer = DB::table('wallet_transfers')->where([

                ['transfer_code', request('token')],

                ['email', $user->email]

            ])->first();
            

            if( !empty($transfer) ) {

                DB::table('wallets')->updateOrInsert( ['owner_id'=>$user->id], [

                    'owner_id' => $user->id,
                    'balance'  => DB::raw('balance + '.$transfer->amount),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
    
                ]);
    
                DB::table('wallet_transfers')->where([
    
                    ['transfer_code', request('token')],

                    ['email', $user->email]
    
                ])->update(['transfer_status'=>'completed']);
                
            }

        }

        return redirect(RouteServiceProvider::HOME);
    }
}
