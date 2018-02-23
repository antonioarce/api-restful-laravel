<?php

namespace App\Transformers;

use App\Buyer;
use League\Fractal\TransformerAbstract;

class BuyerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Buyer $buyer)
    {
        return [
            'identificador' => (int)$buyer->id,
            'nombre' => (string)$buyer->name,
            'correo' => (string)$buyer->email,
            'verificado' => (int)$buyer->verified,
            'fechaCreacion' => (string)$buyer->created_at,
            'fechaActualizacion' => (string)$buyer->created_at,
            'fechaEliminacion' => isset($buyer->deleted_at) ? (string)$buyer->deleted_at : null,
            'links' => [
                ['rel' => 'self' , 'href' => route('buyers.show', $buyer->id) ],
                ['rel' => 'buyers.categories' , 'href' => route('buyers.categories.index',$buyer->id) , ],
                ['rel' => 'buyers.products' , 'href' => route('buyers.products.index',$buyer->id) , ],
                ['rel' => 'buyers.sellers' , 'href' => route('buyers.sellers.index',$buyer->id) ],
                ['rel' => 'buyers.transactions' , 'href' => route('buyers.transactions.index',$buyer->id)],
                ['rel' => 'user' , 'href' => route('users.show',$buyer->id)],
            ],

        ];
    }

    public static function originalAttribute($index){
        $attributes = [
            'identificador' => 'id',
            'nombre' => 'name',
            'correo' => 'email',
            'verificado' => 'verified',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index){
        $attributes = [
             'id' => 'identificador',
             'name' => 'nombre',
             'email' => 'correo',
             'verified' => 'verificado',
             'created_at' => 'fechaCreacion',
             'updated_at' => 'fechaActualizacion',
             'deleted_at' => 'fechaEliminacion',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
