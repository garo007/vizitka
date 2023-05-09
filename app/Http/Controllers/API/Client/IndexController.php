<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ChangePasswordRequest;
use App\Http\Requests\Client\ForgotPasswordRequest;
use App\Http\Requests\Client\ResetPasswordRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Http\Resources\Client\ClientResource;
use App\Services\Client\ChangePassword;
use App\Services\Client\ForgotPassword;
use App\Services\Client\ResetPassword;
use App\Services\Client\Update;
use App\Services\FileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware(['auth:client'])->except(['resetPassword', 'forgotPassword']);
//    }

    /**
     * @param Request $request
     * @return ClientResource
     */
    public function me(Request $request): ClientResource
    {
        $client = auth()->guard('client')->user();

        return (new ClientResource($client))->additional([
            'success' => true,
            'token' => $request->bearerToken(),
            'expired_at' => $client->currentAccessToken()->expires_at
        ]);
    }

    /**
     * @param UpdateRequest $request
     * @param FileService $fileService
     * @param Update $update
     * @return Response|ClientResource
     */
    public function update(UpdateRequest $request, FileService $fileService, Update $update): Response|ClientResource
    {
        try {
            $data = $update($request->validated(), $fileService);

            return (new ClientResource($data))->additional([
                'success' => true,
                'message' => __('response.client.details')
            ]);
        } catch (Exception $exception) {
            return response(['errors' => json_decode($exception->getMessage()), 'success' => false], JsonResponse::HTTP_OK);
        }
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
     * @param ForgotPasswordRequest $request
     * @param ForgotPassword $forgotPassword
     * @return Response
     */
    public function forgotPassword(ForgotPasswordRequest $request, ForgotPassword $forgotPassword): Response
    {
        try {
            $forgotPassword($request->validated());
            $message = __('passwords.sent');
            $success = true;

            return response(compact('message', 'success'));
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage(), 'success' => false]);
        }
    }

    /**
     * @param ResetPasswordRequest $request
     * @param ResetPassword $forgotPassword
     * @return ClientResource|Response
     */
    public function resetPassword(ResetPasswordRequest $request, ResetPassword $forgotPassword): ClientResource|Response
    {
        try {
            $data = $forgotPassword($request->validated());

            return (new ClientResource(auth()->guard('client')->user()))->additional([
                'success' => true,
                'token' => $data['token'],
                'expired_at' => $data['expired_at']
            ]);
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage(), 'success' => false]);
        }
    }
}
