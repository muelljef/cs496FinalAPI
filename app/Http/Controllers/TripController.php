<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Trip;
use App\Http\Requests;
use Illuminate\Http\Response;
use App\Turtle\Transformers\TripTransformer;

class TripController extends ApiController
{
    /**
     * @var App\Turtle\Transformers\TripTransformer
     */
    protected $tripTransformer;

    function __construct(TripTransformer $tripTransformer)
    {
        $this->tripTransformer = $tripTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = UserController::verifyUser(request()->get('username'), request()->get('password'));
        if (!$user) {
            return $this->respondFailedUserAuthentication();
        }

        $trips = Trip::where('userId', '=', $user->_id)->get();

        return $this->respond([
            'data' => $this->tripTransformer->transformCollection($trips->all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        $user = UserController::verifyUser(request()->get('username'), request()->get('password'));
        if (!$user) {
            return $this->respondFailedUserAuthentication();
        }

        if (!request()->get('title') or !request()->get('description')) {
            return $this->respondMissingFields('The title or description were missing');
        }

        // Create trip
        $trip = Trip::create(request()->all());
        $trip->locations = array();

        // Associate user with trip
        $trip->userId = $user->_id;
        $trip->save();

        // Add trip id to user
        $user->addTrip($trip->_id);

        return $this->setStatusCode(201)->respond([
            'data' => $this->tripTransformer->transform($trip)
        ]);

    }

    public function storeLocation($id)
    {
        $user = UserController::verifyUser(request()->get('username'), request()->get('password'));
        if (!$user) {
            return $this->respondFailedUserAuthentication();
        }

        if (!request()->get('location')) {
            return $this->respondMissingFields('The location was missing');
        }

        $trip = Trip::where('_id', '=', $id)->where('userId', '=', $user->_id)->first();
        $trip->addLocation(request()->get('location'));

        if (!$trip) {
            return $this->respondNotFound('Trip does not exist or you do not have authorization to see this trip');
        }

        return $this->respond([
            'data' => $this->tripTransformer->transform($trip)
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = UserController::verifyUser(request()->get('username'), request()->get('password'));
        if (!$user) {
            return $this->respondFailedUserAuthentication();
        }

        $trip = Trip::where('_id', '=', $id)->where('userId', '=', $user->_id)->first();

        if (!$trip) {
            return $this->respondNotFound('Trip does not exist or you do not have authorization to see this trip');
        }

        return $this->respond([
            'data' => $this->tripTransformer->transform($trip)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $user = UserController::verifyUser(request()->get('username'), request()->get('password'));
        if (!$user) {
            return $this->respondFailedUserAuthentication();
        }

        $trip = Trip::where('_id', '=', $id)->where('userId', '=', $user->_id)->first();

        if (!$trip) {
            return $this->respondNotFound('Trip does not exist or you do not have authorization to see this trip');
        }

        $trip->title = request()->get('title');
        $trip->description = request()->get('description');
        $trip->save();

        return $this->respond([
            'data' => $this->tripTransformer->transform($trip)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = UserController::verifyUser(request()->get('username'), request()->get('password'));
        if (!$user) {
            return $this->respondFailedUserAuthentication();
        }

        $trip = Trip::where('_id', '=', $id)->where('userId', '=', $user->_id)->first();

        if (!$trip) {
            return $this->respondNotFound('Trip does not exist or you do not have authorization to see this trip');
        }

        //Remove the trip id from the user
        $updatedTrips = array_diff($user->tripIds, array($trip->_id));
        $user->tripIds = $updatedTrips;
        $user->save();

        $trip->delete();

        return $this->respond([
            'message' => "The trip was successfully deleted"
        ]);

    }


}
