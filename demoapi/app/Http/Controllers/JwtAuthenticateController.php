<?php

namespace App\Http\Controllers;
use App\Signin;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Hash;
class JwtAuthenticateController extends Controller
{
    public function index()
    {
        echo "tapan";
    }
    ////////////////////////////////////////////////////////////
    public function register(Request $request)
    {
        $user=new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->password= Hash::make($user->password);
        $user->save();
        $count = User::where('email', $user->email)->first();

        $loginuser=new Signin();
        $loginuser->user_id=$count->id;
        $loginuser->email = $user->email;
        $loginuser->password = $user->password;
        $loginuser->save();
        //return redirect()->route('profile');
        return response()->json("id created");
    }

    ////////////////////////////////////////////////////////////
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!($token = JWTAuth::attempt($credentials))) {
                return "invalid user";
            }
        } catch (JWTException $e) {
            return "JWT Exception".$e;
        }
        $user=new User();
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->password= Hash::make($user->password);
        $name = User::where('email', $user->email)->first();
        $nm=$name->name;
        return "user ".$nm." is valid";
    }

    ////////////////////////////////////////////////////////////
    public function createRole(Request $request)
    {
        $role = new Role();
        $role->name = $request->input('name');
        $role->save();
        return "role created";
    }

    ////////////////////////////////////////////////////////////
    public function createPermission(Request $request)
    {

        $viewUsers = new Permission();
        $viewUsers->name = $request->input('name');
        $viewUsers->save();

        return "permission created";

    }
    ///////////////////////////////////////////////////////////
    public function assignRole(Request $request)
    {
        $user = User::where('email', '=', $request->input('email'))->first();


        $role = Role::where('name', '=', $request->input('role'))->first();
        //$user->attachRole($request->input('role'));
        $user->roles()->attach($role->id);

        return "role assigned";
    }
    ///////////////////////////////////////////////////////////
    public function attachPermission(Request $request)
    {
        $role = Role::where('name', '=', $request->input('role'))->first();
        $permission = Permission::where('name', '=', $request->input('name'))->first();
        $role->attachPermission($permission);

        return "permission given";
    }
}
