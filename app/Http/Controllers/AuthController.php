<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\Api;

class AuthController extends Controller
{
    use ApiResponser;

    public function getOtp(Request $request)
    {
        $rules = [
            'country_code' => 'required|min:1',
            'phone_number' => 'required|min:9|max:13'
        ];

        $this->validate($request, $rules);

        $user = User::where('country_code', $request->country_code)
            ->where('phone_number', $request->phone_number)
            ->first();

        if ($user) {
            $user->update([
                'otp' => User::generateOTP(),
            ]);
        }

        return $this->showOne($user);

        
    }
    public function verifyOtp(Request $request)
    {
        $rules = [
            'country_code' => 'required|min:1',
            'phone_number' => 'required|min:9|max:13',
            'otp' => 'required|digits:6',
        ];

        $this->validate($request, $rules);

        $user = User::where('phone_number', $request->phone_number)
        ->where('otp', $request->otp)
        ->first();

        // dd($user);
        if($user) {
            Auth::login($user);
            $authUser = Auth::user();
            $tokenResult = $authUser->createToken('Personal Access Token', []);

            return response()->json([
                'token' => $tokenResult->accessToken,
            ]);
        }

    }
}
