<?php

namespace App\Providers;

use App\Mail\UserMailChanged;
use App\Product;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use App\Mail\UserCreated;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Product::updated(function($product){
            if($product->quantity == 0 && $product->estaDisponible()){
                $product->status = Product::PRODUCTO_NO_DISPONIBLE;
                $product->save();
            }
        });

        Product::created(function($user){
            retry(5, function() use ($user){
                Mail::to($user)->send(new UserCreated($user));
            },100);
        });

        Product::updated(function($user){
            retry(5, function() use ($user){
                if($user->isDirty('email')){
                    Mail::to($user)->send(new UserMailChanged($user));
                }
            },100);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
