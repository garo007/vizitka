<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Client\CreatePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\Client\JoinRequest;
use App\Http\Resources\Client\ClientResource;
use App\Services\Auth\Client\Login;
use App\Services\FileService;
use App\Services\Client\CreatePassword;
use App\Services\Client\Join;
use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:client'])->only('logout');
    }

    /**
     * @param JoinRequest $request
     * @param Join $join
     * @param FileService $fileService
     * @return Response
     */
    public function join(JoinRequest $request, Join $join, FileService $fileService): Response
    {
        try {
            $message = $join($request->validated(), $fileService);

            return response([
                'success' => true,
                'message' => $message
            ]);
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param CreatePasswordRequest $request
     * @param CreatePassword $createPassword
     * @return Response|ClientResource
     */
    public function createPassword(CreatePasswordRequest $request, CreatePassword $createPassword): Response|ClientResource
    {
        try {
            $data = $createPassword($request->validated());

            return (new ClientResource(auth()->guard('client')->user()))->additional([
                'success' => true,
                'token' => $data['token'],
                'expired_at' => $data['expired_at']
            ]);
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage(), 'success' => false], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param LoginRequest $request
     * @param Login $login
     * @return Resource|ClientResource
     */
    public function login(LoginRequest $request, Login $login): ClientResource|Response
    {
        try {
            $data = $login($request->validated());

            return (new ClientResource(auth()->guard('client')->user()))->additional([
                'success' => true,
                'token' => $data['token'],
                'expired_at' => $data['expired_at']
            ]);
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage(), 'success' => false], JsonResponse::HTTP_OK);
        }
    }

    /**
     * @return Response
     */
    public function logout(): Response
    {
        auth()->logout('client');

        return response(['success' => true], JsonResponse::HTTP_OK);
    }
}
