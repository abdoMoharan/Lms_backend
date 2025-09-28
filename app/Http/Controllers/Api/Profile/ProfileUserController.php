<?php
namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\ProfileRequest;
use App\Interfaces\ProfileInterface;
use Illuminate\Http\Request;

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
