<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $roles = DB::table('roles')->get()->pluck('name');

        return view('users', compact('roles'));
    }

    public function table()
    {
        return DataTables::of(User::query())->make(true);
    }

    public function create()
    {
        $user = collect([
            'name'  => '',
            'email' => '',
        ]);

        return view('user_form', compact('user'));
    }

    public function show($id)
    {
        $user = User::find($id);

        return view('user_form', compact('user'));
    }

    public function store(Request $request)
    {
        User::query()->insert([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make('password'),
        ]);

        return ['success' => true];
    }

    public function update(Request $request)
    {
        User::query()->where('id', $request->id)->update($request->input());

        return ['success' => true];
    }

    public function destroy(Request $request)
    {
        User::query()->where('id', $request->id)->delete();

        return ['success' => true];
    }

    public function changePass(Request $request)
    {
        User::query()->where('id', $request->id)->update(['password' => Hash::make($request->password)]);

        return ['success' => true];
    }

    public function logoUpload(Request $request)
    {
        $request->logo->storeAs('logo', 'logo.jpg');

        return redirect('/users');
    }

    public function getUserRole(Request $request)
    {
        $role = User::find($request->id)->getRoles();
        if (count($role) > 0) {
            return $role[0];
        }

        return '';
    }

    public function assignUserRole(Request $request)
    {
        User::find($request->id)->roles()->detach();
        Bouncer::assign($request->role)->to([$request->id]);

        return ['success' => true];
    }
}