<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Service\RoleService;
use App\Http\Service\UserService;
use App\Models\User;

class UserController extends Controller
{
    protected UserService $userService;

    protected RoleService $roleService;

    public function __construct(
        UserService $userService,
        RoleService $roleService
    ) {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->paginate(perPage: 10, with: ['roles']);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->roleService->pluckRoles(keyColumn: 'name');

        return view('users.create-edit-form', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = $this->userService->createUserWithRole(
            userData: $request->validated(),
            roleName: $request->get('role'),
        );

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = $this->roleService->pluckRoles(keyColumn: 'name');

        return view('users.create-edit-form', compact('roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $updatedUser = $this->userService->updateUserWithRole(
            user: $user,
            userData: $request->validated(),
            roleName: $request->input('role')
        );

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
