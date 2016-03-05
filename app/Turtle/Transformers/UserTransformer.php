<?php

namespace App\Turtle\Transformers;


class UserTransformer extends Transformer
{
    public function transform($user)
    {
        return [
            'id' => $user['_id'],
            'username' => $user['username'],
            'tripIds' => $user['tripIds']
        ];
    }
}