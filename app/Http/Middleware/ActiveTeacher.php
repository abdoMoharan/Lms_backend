<?php
namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // جلب المستخدم الذي من نوع "معلم" (teacher)
        $user = auth()->user();

        // التحقق إذا كان المستخدم من نوع "معلم" وإذا كانت حالته نشطة
        if (! $user || $user->user_type !== 'teacher') {
            return ApiResponse::apiResponse(JsonResponse::HTTP_UNAUTHORIZED,  [
                 'User is not a teacher',
            ]);
        }

        // التحقق من حالة المعلم
        if ($user->status == 0) {
            return ApiResponse::apiResponse(JsonResponse::HTTP_UNAUTHORIZED, [
                'Teacher is not active',
            ]);
        }

        return $next($request);
    }
}
