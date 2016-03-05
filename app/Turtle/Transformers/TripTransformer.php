<?php

namespace App\Turtle\Transformers;


class TripTransformer extends Transformer
{

    public function transform($trip)
    {
        return [
            'id' => $trip['_id'],
            'title' => $trip['title'],
            'description' => $trip['description'],
            'userId' => $trip['userId']
        ];
    }
}