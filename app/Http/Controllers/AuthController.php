<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Organisation;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use HttpResponses;


    /**
     * login a user
     *
     * @param LoginUserRequest $request
     * @return response
     */
    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->error(
                status: 'Bad Request',
                message: "Registration unsuccessful",
                code: Response::HTTP_BAD_REQUEST,
            );
        }

        $user = User::where("email", $request->email)->first();

        return $this->success(data: [
            'accessToken' => $token,
            'user' => $user,
        ], status: "success", message: "Login successful",  code: Response::HTTP_OK,);
    }

    /**
     * register a user
     *
     * @param StoreUserRequest $request
     * @return response
     */
    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());

        try {
            DB::beginTransaction();

            $user = User::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
            ]);

            $organisation = Organisation::create([
                'name' => $user->firstName . "'s Organisation",
            ]);

            $user->organisations()->attach($organisation->orgId);

            $token = JWTAuth::fromUser($user);
            DB::commit();

            return $this->success(data: [
                'accessToken' => $token,
                'user' => $user,
            ], status: "success", message: "Registration successful", code: Response::HTTP_CREATED,);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(
                status: 'Bad Request',
                message: "Registration unsuccessful",
                code: Response::HTTP_BAD_REQUEST,
            );
        }
    }
}
