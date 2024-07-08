<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use HttpResponses;


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {


        $user = User::where('userId', $id)->first();
        if (!$user) {
            return $this->error(status: "error", message: 'User not found', code: Response::HTTP_NOT_FOUND,);
        }
        return $this->success(data: [
            'user' => $user,
        ], status: "success", message: "User found", code: Response::HTTP_OK,);
    }
}
