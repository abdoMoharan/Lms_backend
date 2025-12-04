<?php
namespace App\Http\Controllers\Api\Websit\GroupRegister;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\GroupRegister;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Group\GroupRegisterResource;
use App\Http\Requests\Api\GroupRegister\GroupRegisterRequest;

class GroupRegisterController extends Controller
{
    public GroupRegister $groupRegister;
    public function __construct(GroupRegister $groupRegister)
    {
        $this->groupRegister = $groupRegister;
    }

    public function store(GroupRegisterRequest $request)
    {
        $data = $request->getData();
        try {
            DB::beginTransaction();
            $groupRegister = $this->groupRegister->create($data);
            $groupRegister->load(['user', 'group']);
            DB::commit();
// return response()->json([
//     'message' => 'You have successfully registered.',
//     'data' =>$groupRegister,
// ], JsonResponse::HTTP_OK);
            return ApiResponse::apiResponse(JsonResponse::HTTP_OK, 'You have successfully registered.', new GroupRegisterResource($groupRegister));
        } catch (\Exception $th) {
            DB::rollBack();
            return ApiResponse::apiResponse(JsonResponse::HTTP_NOT_FOUND, 'You have Error registered.', $th->getMessage());
        }
    }
}
