<?php

namespace App\Http\Controllers;

use App\Models\UserDetails;
use App\Http\Requests\StoreUserDetailsRequest;
use App\Http\Requests\UpdateUserDetailsRequest;

class UserDetailsController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserDetails  $userDetails
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $details = auth()->user()->details;
        return view('pages.account.edit', ['details' => $details]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserDetailsRequest  $request
     * @param  \App\Models\UserDetails  $userDetails
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserDetailsRequest $request)
    {
        $validated = $request->validated();
        $details = auth()->user()->details;
        $details->bio = strip_tags($validated['bio']);
        $details->phone = strip_tags($validated['phone']);
        $details->display_name = strip_tags($validated['display_name']);
        if ($request->file('profile_photo')) {
            $userDir = '/uploads/users/' . md5(auth()->user()->email);
            $uploadDir = storage_path('app/public') . $userDir; 
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir);
            }
            $path = $request->file('profile_photo')->store('public' . $userDir);
            $details->profile_photo = str_replace('public', '/storage', $path);
        }
        if ($details->save()) {
            return redirect()->route('profile', ['user' => auth()->user()]);
        }
        
        return redirect()->back()->withErrors(['Cannot save account details']);
    }
}
