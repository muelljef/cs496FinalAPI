<?php

namespace App\Http\Controllers;

use App\Turtle\Transformers\UserTransformer;
use Illuminate\Http\Request;

use App\User;
use App\Http\Requests;
use MongoDB\Driver\Exception\BulkWriteException;

class UserController extends ApiController
{
    /**
     * @var App\Turtle\Transformers\UserTransformer
     */
    protected $userTransformer;

    /**
     * UserController constructor.
     * @param $userTransformer
     */
    public function __construct(UserTransformer $userTransformer)
    {
        $this->userTransformer = $userTransformer;
    }

    public function index()
    {
        $users = User::all();

        return $this->respond([
            'data' => $this->userTransformer->transformCollection($users->all())
        ]);
    }

    public function store($username = null, $password = null)
    {

        $username = request()->get('username');
        $password = request()->get('password');
        if (!$username or !$password) {
            return $this->respondMissingFields('Both the username and password fields are required');
        }

        $user = new User;
        $user->username = $username;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->tripIds = array();

        try {
            $user->save();
        } catch (BulkWriteException $e) {
            return $this->respondNotProcessable("Sorry it looks like that users already exists");
        }

        return $this->setStatusCode(201)->respond([
            'data' => $this->userTransformer->transform($user)
        ]);
    }

    public function show($username)
    {
        $password = request()->get('password');
        if (!$username or !$password) {
            return $this->respondMissingFields('Both the username and password fields are required');
        }

        $user = UserController::verifyUser($username, $password);
        if (!$user) {
            return $this->respondFailedUserAuthentication();
        }

        return $this->respond([
            'data' => $this->userTransformer->transform($user)
        ]);

    }

    public static function verifyUser($username = null, $password = null)
    {
        if (!$username or !$password) {
            return false;
        }

        $user = User::where('username', '=', $username)->first();

        if(!$user) {
            return false;
        }

        if (password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

}
