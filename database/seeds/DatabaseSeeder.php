<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        \App\User::truncate();
        \App\Category::truncate();
        \App\Product::truncate();
        \App\Transaction::truncate();

        \Illuminate\Support\Facades\DB::table('category_product')->truncate();

        $cantidadUsuarios = 1000;
        $cantidadCategorias = 30;
        $cantidadProductos = 1000;
        $cantidadTransacciones = 1000;

        factory(\App\User::class, $cantidadUsuarios)->create();
        factory(\App\Category::class, $cantidadCategorias)->create();
        factory(\App\Product::class, $cantidadProductos)->create()->each(
            function ($producto){
                $categorias = \App\Category::all()->random(mt_rand(1,5))->pluck('id');
                $producto->categories()->attach($categorias);
            }
        );
        factory(\App\Transaction::class, $cantidadTransacciones)->create();


    }
}
