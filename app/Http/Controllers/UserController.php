<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{

    public function __construct()
    {
        
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users|max:255',
            'password' => 'required:max:255'
        ]);

        $user = new User($request->all());
        $user->save();
        return $user;
    }

    public function update(Request $request, $id){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users|max:255',
            'password' => 'required:max:255'
        ]);

        $user = User::find($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->update();

        return $user;
    }


    public function view($id){
        return User::find($id);
    }

}

