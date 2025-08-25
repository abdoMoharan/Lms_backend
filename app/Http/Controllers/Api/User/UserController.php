<?php
namespace App\Http\Controllers\Api\User;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\User\UserInterface;
use App\Http\Requests\Api\User\UserRequest;

class UserController extends Controller
{
    public UserInterface $user;
    public function __construct(UserInterface $user)
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

    public function update(UserRequest $request,User $user)
    {
        return $this->user->update($request,$user);
    }
    public function delete(User $user)
    {

        return $this->user->delete($user);
    }
    public function show(User $user)
    {
        return $this->user->show($user);
    }
    public function showDeleted()
    {
        return $this->user->showDeleted();
    }

    public function restore($id)
    {
        return $this->user->restore($id);
    }
    public function forceDelete($id)
    {
        return $this->user->forceDelete($id);
    }
    public function multi_actions(Request $request)
    {
        return $this->user->multi_actions($request);
    }
}
