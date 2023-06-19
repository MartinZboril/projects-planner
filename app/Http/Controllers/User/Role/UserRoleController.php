<?php

namespace App\Http\Controllers\User\Role;

use App\DataTables\RolesDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Role;
use App\Services\Data\RoleService;
use App\Traits\FlashTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class UserRoleController extends Controller
{
    use FlashTrait;

    public function __construct(
        private RoleService $roleService
    ) {
    }

    /**
     * Display a listing of the roles.
     */
    public function index(RolesDataTable $ratesDataTable): JsonResponse|View
    {
        return $ratesDataTable->render('users.roles.index');
    }

    /**
     * Show the form for creating a new eole.
     */
    public function create(): View
    {
        return view('users.roles.create');
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        try {
            $this->roleService->handleSave(new Role, $request->validated());
            $this->flash(__('messages.role.create'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('users.roles.index');
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role): View
    {
        return view('users.roles.edit', ['role' => $role]);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            $this->roleService->handleSave($role, $request->validated());
            $this->flash(__('messages.role.update'), 'info');
        } catch (Exception $exception) {
            Log::error($exception);

            return redirect()->back()->with(['error' => __('messages.error')]);
        }

        return redirect()->route('users.roles.index');
    }
}
