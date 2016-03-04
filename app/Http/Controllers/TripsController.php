<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Trip;
use App\Http\Requests;
use Illuminate\Http\Response;
use App\Turtle\Transformers\TripTransformer;

class TripsController extends Controller
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

        return response()->json([
            'data' => $this->tripTransformer->transformCollection($trips->all())
        ], 200);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json([
                'error' => [
                    'message' => 'Trip does not exist'
                ]
            ], 404);
        }

        return response()->json([
            'data' => $this->tripTransformer->transform($trip)
        ], 200);
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
