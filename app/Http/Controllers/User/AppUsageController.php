<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Preference;
use Carbon\Carbon;
use DB;

class AppUsageController extends Controller
{
    public function store() {

        $validator = Validator::make(request()->all(), [

            'preference' => ['required'],
            
         ]);
 
         if($validator->fails()) {
             return response()->json($validator->errors());
         }


        $user = auth()->user();
        $now  = Carbon::now();

        DB::table('preferences')->updateOrInsert(['user_id'=>$user->id], [

            'user_id' => $user->id,
            'app_usage' => request('preference'),
            'created_at' => $now,
            'updated_at' => $now
            
        ]);

        return response()->json(['success' => true]);

    }
}
