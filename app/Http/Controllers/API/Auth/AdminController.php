<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Services\Auth\Admin\Login;
use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['admin_middleware'])->except('login');
    }

    /**
     * @param LoginRequest $request
     * @param Login $login
     * @return AdminResource|Response
     */
    public function login(LoginRequest $request, Login $login): AdminResource|Response
    {
        try {
            $data = $login($request->validated());

            return (new AdminResource(auth()->guard('admin')->user()))->additional([
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
        auth()->logout('admin');

        return response(['success' => true], JsonResponse::HTTP_OK);
    }
}
