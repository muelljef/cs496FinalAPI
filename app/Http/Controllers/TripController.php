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
        $trips = Trip::all();

        return $this->respond([
            'data' => $this->tripTransformer->transformCollection($trips->all())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if  ( ! Input::get('title') or ! Input::get('description'))
        {
            //return error, 422 (un-processable entity)
            return $this->setStatusCode(422)->respondWithError('Parameters failed validation for a trip');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $trip = Trip::find($id);

        if ( ! $trip) {
            return $this->respondNotFound('Trip does not exist');
        }

        return $this->respond([
            'data' => $this->tripTransformer->transform($trip)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    }


}
