<?php
namespace App\Http\Controllers\Api\User;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use App\Http\Requests\Api\User\UserRequest;

class UserController extends Controller
{
    public UserRepository $user;
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        return $this->user->index($request);
    }

    public function store(UserRequest $request)
    {
        return $this->user->store($request);
    }

    public function update($local,UserRequest $request,User $user)
    {
        return $this->user->update($local,$request,$user);
    }
    public function delete($local,User $user)
    {

        return $this->user->delete($local,$user);
    }
    public function show($local,User $user)
    {
        return $this->user->show($local,$user);
    }
    public function showDeleted()
    {
        return $this->user->showDeleted();
    }

    public function restore($local,$id)
    {
        return $this->user->restore($local,$id);
    }
    public function forceDelete($local,$id)
    {
        return $this->user->forceDelete($local,$id);
    }
    public function multi_actions($local,Request $request)
    {
        return $this->user->multi_actions($local,$request);
    }
}
