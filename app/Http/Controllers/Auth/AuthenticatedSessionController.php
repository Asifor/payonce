<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

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
    
                ])->update(['transfer_status'=>'completed']);
                
            }

        }

        return redirect(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
