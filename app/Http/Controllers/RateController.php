<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        return view('rates.create', ['user' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @param  \App\Models\User  $user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'value' => ['required', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        $rate = new Rate();
        $rate->user_id = $request->user_id;
        $rate->name = $request->name;
        $rate->is_active = $request->is_active;
        $rate->value = $request->value;
        $rate->save();

        Session::flash('message', 'Rate was created!');
        Session::flash('type', 'info');

        return redirect()->route('users.detail', ['user' => $rate->user]);
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Rate $rate)
    {
        return view('rates.edit', ['user' => $user, 'rate' => $rate]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rate $rate)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'value' => ['required', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
        }

        Rate::where('id', $rate->id)
                    ->update([
                        'name' => $request->name,
                        'is_active' => $request->is_active,
                        'value' => $request->value,
                    ]);

        Session::flash('message', 'Rate was updated!');
        Session::flash('type', 'info');

        return redirect()->route('users.detail', ['user' => $rate->user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rate  $rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rate $rate)
    {
        //
    }
}
