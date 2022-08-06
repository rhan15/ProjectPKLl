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

    // public function __construct()
    // {
    //     $this->middleware('client.credentials')->only(['store','update']);   
    //     $this->middleware('auth:api')->except(['store','login','update']);
    // }
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
            'country_code' => 'required|min:1',
            'phone_number' => 'required|min:9|max:13',
            'pin' => 'required|digits:6|confirmed'
        ];

        $this->validate($request, $rules);
        $data = $request->all();

        $user = User::where('phone_number', request()->phone_number)->first();


        // $checks = User::where($request->phone_number);
        if ($user == null) {
            $data['pin'] = bcrypt($request->pin);
            $data['admin'] = User::REGULAR_USER;



            $user = User::create($data);

            return $this->showOne($user, 201);
        }
        return $this->errorResponse('Nomor telephone telah terdaftar', 409);


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
            'country_code' => 'required|min:1',
            'phone_number' => 'unique:users|min:9|max:12',
            'pin' => 'digits:6|confirmed'
        ];

        $this->validate($request, $rules);

        if ($request->has('country_code')) {
            $user->country_code = $request->country_code;
        }

        if ($request->has('phone_number') && $user->phone_number != $request->phone_number) {
            $user->otp = User::generateOTP();
            $user->phone_number = $request->phone_number;
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


    // $user = auth()->user()

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
        dd('a');
        $rules = [
            'phone_number' => 'required|min:9|max:13',
            'otp' => 'required|digits:6',
        ];

        $this->validate($request, $rules);

        if (Auth::attempt(['phone_number' => $request->phone_number, 'otp' => $request->otp])) {
            $user = Auth::user();

            dd($user);
            // if ($user->verified !=1) {
            //     return response()->json([
            //         'messege' => 'Akun ini belum terferifikasi.'
            //     ], 401);
            // }

            //$token = $user->createToken($user->phone_number);


        }
        return response()->json([
            'error' => "nomor belum terdaftar"
        ], 401);
    }

    public function information()
    {
        $user = auth()->user();
        
        return $this->showOne($user);

        // $user = auth()->user();
        
        // return $this->showOne($user);

        // ! Ambil dari access token, cari si User,
        // ! return informasi dari si pemilik token
    }

    public function updatePin(Request $request)
    {
        $rules = [
            'pin' => 'required|digits:6|confirmed',
            
            
        ];
        $this->validate($request, $rules);
        $user = auth()->user();
        
        
        $user->update([
            'pin' => bcrypt($request->pin),
        ]);
        return $this->showOne($user);
        // dd($usera);
        //return $this->showOne($user);

        // $user = auth()->user();
        
        // return $this->showOne($user);

        // ! Ambil dari access token, cari si User,
        // ! return informasi dari si pemilik token
    }
}
