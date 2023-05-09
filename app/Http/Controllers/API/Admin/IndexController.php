<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Services\Admin\ChangePassword;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IndexController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin_middleware']);
    }

    /**
     * @param ChangePasswordRequest $request
     * @param ChangePassword $changePassword
     * @return Response
     */
    public function changePassword(ChangePasswordRequest $request, ChangePassword $changePassword): Response
    {
        try {
            $changePassword($request->validated());
            $message = __('response.change_password');
            $success = true;

            return response(compact('message', 'success'));
        } catch (Exception $exception) {
            return response(['errors' => json_decode($exception->getMessage()), 'success' => false]);
        }
    }

    /**
     * @param Request $request
     * @return AdminResource
     */
    public function me(Request $request): AdminResource
    {
        $admin = auth()->guard('admin')->user();

        return (new AdminResource($admin))->additional([
            'success' => true,
            'token' => $request->bearerToken(),
            'expired_at' => $admin->currentAccessToken()->expires_at
        ]);
    }
}
