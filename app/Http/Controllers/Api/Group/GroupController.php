<?php
namespace App\Http\Controllers\Api\Group;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Group\GroupRepository;
use App\Http\Requests\Api\Group\GroupRequest;

class GroupController extends Controller
{
    public GroupRepository $repository;
    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request)
    {
        return $this->repository->index($request);
    }
    public function store(GroupRequest $request)
    {
        return $this->repository->store($request);
    }
    public function update($local, GroupRequest $request, Group $model)
    {
        return $this->repository->update($local, $request, $model);
    }
    public function delete($local, Group $model)
    {
        return $this->repository->delete($local, $model);
    }
    public function show($local, Group $model)
    {
        return $this->repository->show($local, $model);
    }
}
