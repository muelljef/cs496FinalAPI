<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use MongoDB\Driver\Exception\BulkWriteException;

class UserController extends Controller
{
    //

    public function index()
    {
        $users = User::all();
        return $users;
    }

    public function store($username=null, $password=null)
    {

        $username = request()->get('username');
        $password = request()->get('password');
        if ( ! $username || ! $password )
        {
            return "error missing credentials";
        }

        $user = new User;
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $user->save();
        } catch (BulkWriteException $e) {
            return "Sorry it looks like that users already exists";
        }

        return $user;
    }

    public function verifyUser($username=null, $password=null)
    {
        $username = request()->get('username');
        $password = request()->get('password');
        if ( ! $username || ! $password )
        {
            return "error missing credentials";
        }

        $user = User::where('username', '=', $username)->first();

        if(password_verify($password, $user->password)) {
            return "true";
        }
        return 'false';

    }
}
