<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganisationRequest;
use App\Models\Organisation;
use App\Http\Controllers\JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Http\Response;

class OrganisationsController extends Controller
{
    use HttpResponses;

    public function index()
    {
        // Retrieve the authenticated user
        $user = auth()->user();

        // Check if the user is authenticated
        if (!$user) {
            return $this->error(status: "error", message: 'Unauthorized', code: Response::HTTP_UNAUTHORIZED,);
        }

        // Retrieve the organizations related to the authenticated user
        $organisations = $user->organisations;


        // Return the organizations as a JSON response
        return $this->success(data: [
            'organisations' => $organisations,
        ], status: "success", message: "organisations retrieved", code: Response::HTTP_OK,);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrganisationRequest $request)
    {
        $request->validated($request->all());

        $organisation = Organisation::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        if (!$organisation) {
            return $this->error(
                status: 'Bad Request',
                message: "Client Error",
                code: Response::HTTP_BAD_REQUEST,
            );
        }

        $request->user()->organisations()->attach($organisation->orgId);


        // Return the organizations as a JSON response
        return $this->success(data: [
            'organisations' => $organisation,
        ], status: "success", message: "organisations created", code: Response::HTTP_CREATED,);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->error(status: "error", message: 'Unauthorized', code: Response::HTTP_UNAUTHORIZED,);
        }
        $organisation = Organisation::where('orgId', $id)->first();

        if (!$organisation) {
            return $this->error(status: "error", message: 'organisations not found', code: Response::HTTP_NOT_FOUND,);
        }
        return $this->success(
            data: $organisation,
            status: "success",
            message: "User found",
            code: Response::HTTP_OK,
        );
    }

    public function addUser(Request $request, $orgId)
    {
        $request->validate([
            'userId' => 'required|uuid|exists:users,userId',
        ]);

        $currentUser = auth()->user();
        $organisation = Organisation::findOrFail($orgId);

        if (!$currentUser->organisations->contains($orgId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have permission to add users to this organisation',
            ], 403);
        }

        $user = User::findOrFail($request->userId);

        if (!$organisation->users->contains($user->userId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User is already in this organisation',
            ], 400);
        }

        $organisation->users()->attach($user->userId);

        return response()->json([
            'status' => 'success',
            'message' => 'User added to organisation successfully',
        ], 200);
    }
}
