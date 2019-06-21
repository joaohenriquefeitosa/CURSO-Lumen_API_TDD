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
            'password' => 'required|max:255|confirmed'
        ]);

        $user = new User($request->all());
        $user->save();
        return $user;
    }

    public function update(Request $request, $id){
        $dadosValidacao = [
            'name' => 'required',
            'email' => 'required|unique:users|max:255'
        ];

        if(isset($request->all()['password'])){
            if($request->all()['password'] == '' || $request->all()['password'] == null){
                unset($request->all()['password']);
            }else{
                $dadosValidacao['password'] = 'required|max:255|confirmed';
            }
            
        }

        $this->validate($request, $dadosValidacao);

        $user = User::find($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if(isset($request->all()['password'])){
            $user->password = $request->input('password');
        }
        $user->update();

        return $user;
    }


    public function view($id){
        return User::find($id);
    }

    public function list(){
        return User::all();
    }

    public function delete($id){
        if(User::destroy($id)){
            return response('Removido com sucesso.', 200);
        }else{
            return response('Erro ao remover.', 401);
        }
    }

}

