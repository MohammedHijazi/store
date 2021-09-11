<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RolesController extends Controller
{

    public function index()
    {
        Gate::authorize('roles.view-any');

        $roles = Role::paginate();
        return view('admin.roles.index', compact('roles'));
    }


    public function create()
    {
        Gate::authorize('roles.create');
        return view('admin.roles.create', [
            'role' => new Role(),
        ]);
    }


    public function store(Request $request)
    {
        Gate::authorize('roles.create');
        $request->validate([
            'name' => 'required',
            'abilities' => 'required|array',
        ]);

        //dd($request->all());

        $role = Role::create($request->all());

        return redirect()->route('roles.index')->with('success', 'Role added');
    }


    public function show($id)
    {
        $role = Role::find($id);
        return $role->users;
    }


    public function edit($id)
    {
        Gate::authorize('roles.update');
        $role = Role::findOrFail($id);

        return view('admin.roles.edit', compact('role'));
    }


    public function update(Request $request, $id)
    {
        Gate::authorize('roles.update');
        $request->validate([
            'name' => 'required',
            'abilities' => 'required|array',
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->all());

        return redirect()->route('roles.index')->with('success', 'Role updated');
    }


    public function destroy($id)
    {
        Gate::authorize('roles.delete');

        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted');
    }
}
