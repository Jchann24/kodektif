<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserProfile\UserProfileStoreRequest;
use App\Http\Requests\UserProfile\UserProfileUpdateRequest;
use App\Http\Resources\User\UserProfileResource;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UserProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'not.suspended'])
            ->except(['show']);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserProfile\UserProfileStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserProfileStoreRequest $request)
    {
        $validated = $request->validated();

        $user = authUser();

        $userProfile = $user->profile()
            ->create($validated);

        return Response::json(
            new UserProfileResource($userProfile),
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function show(UserProfile $userProfile)
    {
        return new UserProfileResource($userProfile);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserProfile\UserProfileUpdateRequest  $request
     * @param  \App\Models\UserProfile  $userProfile
     * @return \Illuminate\Http\Response
     */
    public function update(UserProfileUpdateRequest $request, UserProfile $userProfile)
    {
        $this->authorize('update', $userProfile);
        $validated = $request->validated();

        $userProfile->update($validated);

        return Response::json(
            new UserProfileResource($userProfile),
            200
        );
    }
}
