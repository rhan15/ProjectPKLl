<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Contracts\Service\Attribute\Required;

class UserController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credentials')->only(['store','update']);
        $this->middleware('auth:api')->except(['store','update']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $users = User::with('profile')->get();

        return $this->showAll($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $rules = [
            'phone_num' => 'required|unique:users|min:9|max:13',
            'pin' => 'required|digits:6|confirmed'
        ];

        $this->validate($request, $rules);
        $data = $request->all();

        $user = User::where('phone_num',request()->phone_num)->first();


        // $checks = User::where($request->phone_num);
        if ($user == null ) {
            $data['verified'] = User::UNVERIFIED_USER;
            $data['pin'] = bcrypt($request->pin);
            $data['admin'] = User::REGULAR_USER;
            $data['access_token'] = User::generateAccessToken();
            $data['otp'] = User::generateOTP();
        

            $user = User::create($data);

            return $this->showOne($user, 201);
        }
        // return $this->errorResponse('Nomor telephone telah terdaftar', 409);
        

        // dd($user);
        
        


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'phone_num' => 'unique:users|min:9|max:12',
            'pin' => 'digits:6|confirmed'
        ];

        $this->validate($request, $rules);

        if ($request->has('phone_num') && $user->phone_num != $request->phone_num) {
            $user->verified = User::UNVERIFIED_USER;
            $user->access_token = User::generateAccessToken();
            $user->otp = User::generateOTP();
            $user->phone_num = $request->phone_num;
        }

        if ($request->has('pin')) {
            $user->pin = bcrypt($request->pin);
        }

        // if ($request->has('admin')) {
        //     if (!$user->isVerified()) {
        //         return $this->errorResponse('Hanya user yang terverifikasi dapat memodifikasi kolom admin', '409');
        //     }
        //     $user->admin = $request->admin;
        // }

        if (!$user->isDirty()) {
            return $this->errorResponse('Kamu harus menspesifikasi nilai yang berbeda untuk di perbarui', '422');
        }

        $user->save();

        return $this->showOne($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }


    // $user = auth(->user()

    // public function verify(Request $request, $token)
    // {
    //     $user = User::Where('access_token', $token)->firstOrFail();

    //     $rules =[
    //         'name' => 'required',
    //         'gender' => 'required',
    //         'birth_date' => 'required',
    //     ];

    //     $this->validate($request, $rules);

    //     $data = $request->all();

    //     $data['user_id'] = $user->id;

    //     $profile = Profile::create($data);

    //     return $this->showOne($profile);
    //     return $this->showMessage('Profile berhasil di tambahkan');
    // }

    public function login(Request $request, User $user)
    {
        $rules = [
            'phone_num' => 'required|min:9|max:13',
            'pin' => 'required|digits:6',
        ];

        $this->validate($request,$rules);

        if (Auth::attempt(['phone_num' => $request->phone_num, 'pin' => $request->pin])) {
            $user = Auth::user();

            if ($user->verified !=1) {
                return response()->json([
                    'messege' => 'Akun ini belum terferifikasi.'
                ], 401);
            }

            $token = $user->createToken($user->phone_num);
        }

        return response()->json([
            'error' => "Credential salah"
        ], 401);

    }

}
