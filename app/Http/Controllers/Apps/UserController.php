<?php

namespace App\Http\Controllers\Apps;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::when(request()->q, function($users){
            $users = $users->where('name', 'like', '%'.request()->q.'%');
        })->with('roles')->latest()->paginate(5);

        // dd($users);

        return inertia('Apps/Users/Index', ['users' => $users]);
    }

    public function create()
    {
        $roles = Role::all();
        return Inertia::render('Apps/Users/Create', [
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'roles' => 'required'
        ],

        [
        'name.required' => 'Nama harus diisi',
        'email.required' => 'Email harus diisi',
        'email.unique' => 'Email sudah digunakan',
        'password.required' => 'Password harus diisi',
        'password.confirmed' => 'Konfirmasi password tidak sesuai',
        'roles.required' => 'Role harus diisi'
        ]);

        //create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        //assign roles to user
        $user->assignRole($request->roles);

        //redirect
        return redirect()->route('apps.users.index');
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);

        $roles = Role::all();

        return Inertia::render('Apps/Users/Edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'password' => 'nullable|confirmed'
        ],

        [
        'name.required' => 'Nama harus diisi',
        'email.required' => 'Email harus diisi',
        'email.unique' => 'Email sudah digunakan',
        'password.confirmed' => 'Konfirmasi password tidak sesuai'
        ]);

        if(!$request->password)
        {
            $user->update([
                'name' => $request->name,
                'email' => $request->email
            ]);
        }
        else{
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
        }

        $user->syncRoles($request->roles);

        return redirect()->route('apps.users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return redirect()->route('apps.users.index');
    }
}
