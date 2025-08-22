<?php

namespace App\Http\Controllers\Api\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\ProfileInterface;
use App\Http\Requests\Api\Profile\ProfileRequest;

class ProfileUserController extends Controller
{
  public ProfileInterface $profile;
    public function __construct(ProfileInterface $profile)
    {
        $this->profile = $profile;
    }

    public function index()
    {
        return $this->profile->index();
    }
    public function update(ProfileRequest $request)
    {
        return $this->profile->update($request);
    }
    public function changePassword(Request $request)
    {
        return $this->profile->changePassword($request);
    }
}
