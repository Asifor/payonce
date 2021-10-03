<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Models\User;
use Carbon\Carbon;

class AccountController extends Controller
{
    protected $view_dir = 'user.account';

    public function index() {

        return view($this->view_dir.'.profile');

    }


    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'email'          => ['required', 'email', 'unique:users,email,'.Auth::user()->id],
            'full_name'      => ['required'],
            'profile_pic'    => ['image', 'mimes:jpeg,png,jpg,gif,svg']
        ]);

        if($validator->fails()) {

            return response()->json($validator->errors());

        }

        $user = User::find(Auth::user()->id);

        if($file = $request->file('profile_pic')) {

            $imagePath = str_replace('public', '', $request->profile_pic->store('public/avatar'));
            $image     = asset('storage'.$imagePath);
            
            $user->profile_pic = $image;
            $user->full_name   = $request->full_name;
            $user->email       = $request->email; 

        } else {

            $user->full_name   = $request->full_name;
            $user->email       = $request->email; 

        }

        $user->save();

        return response()->json(['success'=>true]);

    }

    public function passwordReset(Request $request) {

        $validator = Validator::make($request->all(), [

            'password'              => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'old_password'          => ['required']

        ]);

        if($validator->fails()) {

            return response()->json($validator->errors());
            
        }

        $user = User::find(Auth::user()->id);

        if(!password_verify($request->old_password, $user->password)) {

            $errors = new MessageBag(['old_password' => ['Old password entered is invalid']]); 

            return response()->json($errors);

        }

        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json(['success'=>true]);
    }


}
