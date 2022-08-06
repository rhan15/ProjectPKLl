<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GodsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
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
            'country_code' => 'required',
            'admin' => 'required',

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

        if ($request->has('admin')) {
            $user->admin = $request->admin;
        }
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
}
