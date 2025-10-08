<?php
namespace App\Repositories\User;

use Exception;
use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Abstract\BaseRepository;
use App\Interfaces\User\UserInterface;
use App\Http\Resources\User\UserResource;

class UserRepository extends BaseRepository
{
    public User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }
    public function index($request)
    {
        try {
            $users = $this->model->query()->with('roles')->filter($request->query())->get();
            if ($users->isEmpty()) {
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No users found', []);
            }
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'users retrieved successfully', UserResource::collection($users)->response()->getData(true));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No users found', $e->getMessage());
        }
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $data = $request->getData();
            $user = $this->model->create($data);
            $user->syncRoles($data['roles']);
            // dd($user);
            DB::commit();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'User created successfully', new UserResource($user));
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No users found', $e->getMessage());
        }
    }

    public function update($local, $request, $user)
    {

        try {
            $data = $request->getData();
            $user->update($data);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'User updated successfully', new UserResource($user));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No users found', []);
        }
    }
    public function delete($local, $user)
    {
        try {
            $user->delete();
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'User deleted successfully', []);
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No users found', []);
        }
    }

    public function show($local, $user)
    {
        try {
            $user->load('roles');
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'User retrieved successfully', new UserResource($user));
        } catch (\Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No users found', []);
        }
    }
    public function showDeleted()
    {
        $users = $this->model->getAllDeleted();
        if ($users->isEmpty()) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'No deleted users found', []);
        }
        return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Deleted users retrieved successfully', UserResource::collection($users));
    }
    public function restore($local, $id)
    {
        try {
            $this->model->restoreSoft($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'User restored successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No users found', []);
        }
    }

    public function forceDelete($local, $id)
    {
        try {
            $this->model->forceDeleteById($id);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'User force deleted successfully');
        } catch (Exception $e) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No users found', []);
        }
    }
    public function multi_actions($local, $request)
    {
        $data = $request->validate([
            'type'    => 'required',
            'records' => 'required|array',
        ]);

        switch ($data['type']) {
            case 'delete':
                $models = $this->model->findMany($request['records']);
                foreach ($models as $item) {
                    $item->delete();
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'deleted successfully');
                break;
            case 'status_active':
                $models = $this->model->findMany($request['records']);
                foreach ($models as $item) {
                    $item->update(['status' => 1]);
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'update status  successfully');
                break;
            case 'status_inactive':
                $models = $this->model->findMany($request['records']);
                foreach ($models as $item) {
                    $item->update(['status' => 0]);
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'update status  successfully');
                break;
            case 'restore':
                $models = $this->model->onlyTrashed()->findMany($request['records']);
                foreach ($models as $item) {
                    $item->restore();
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Records restored successfully');
                break;
            case 'force-delete':
                $models = $this->model->onlyTrashed()->findMany($request['records']);
                foreach ($models as $item) {
                    $item->forceDelete();
                }
                return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'Records permanently deleted');
                break;
            default:
                return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'No  found', []);
                break;
        }
    }
}
