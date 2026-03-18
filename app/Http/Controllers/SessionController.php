<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSessionRequest;
use App\Http\Requests\UpdateSessionRequest;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Session $session)
    {
        return  response()->json($session);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreSessionRequest $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSessionRequest $request)
    {
        $validate = $request->validated();
        Session::create($validate);
    }

    /**
     * Display the specified resource.
     */
    public function show(Session $session)
    {
        return response()->json($session);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Session $session)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSessionRequest $request, Session $session)
    {

        $validate = $request->validated();
        $session->update($validate);

        return response()->json([
            "session" => $session,
            "massage" => "Session created successfully"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Session $session)
    {
        $session->delete();

        return response()->json([
            "message" => "Session deleted successfully",
        ]);
    }
}
