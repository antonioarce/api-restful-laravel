<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'identificador' => (int)$seller->id,
            'nombre' => (string)$seller->name,
            'correo' => (string)$seller->email,
            'verificado' => (int)$seller->verified,
            'fechaCreacion' => (string)$seller->created_at,
            'fechaActualizacion' => (string)$seller->created_at,
            'fechaEliminacion' => isset($seller->deleted_at) ? (string)$seller->deleted_at : null,
            'links' => [
                ['rel' => 'self' , 'href' => route('sellers.show', $seller->id) ],
                ['rel' => 'sellers.buyers' , 'href' => route('sellers.buyers.index',$seller->id) ],
                ['rel' => 'sellers.categories' , 'href' => route('sellers.categories.index',$seller->id) , ],
                ['rel' => 'sellers.products' , 'href' => route('sellers.products.index',$seller->id) , ],
                ['rel' => 'sellers.transactions' , 'href' => route('sellers.transactions.index',$seller->id)],
                ['rel' => 'user' , 'href' => route('users.show',$seller->id)],
            ],
        ];
    }

    public static function originalAttribute($index){
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
