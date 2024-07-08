<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
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
    public function store(Request $request)
    {
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
