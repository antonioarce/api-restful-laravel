<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use function Composer\Autoload\includeFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }

    public function store(Request $request, User $seller){
        $rules = [
            'name' => 'required' ,
            'description' => 'required' ,
            'quantity' => 'required|integer|min:1' ,
            'image' => 'required|image' ,
        ];
        $this->validate($request, $rules);
        $data = $request->all();
        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $data['image'] = $request->image->store('');
        $data['seller_id'] = $seller->id;
        $product = Product::create($data);
        return $this->showAll($product);
    }

    public function update(Request $request, Seller $seller, Product $product){
        $rules = [
            'quantity' => 'required|min:1' ,
            'status' => 'in:' . Product::PRODUCTO_NO_DISPONIBLE . ',' . Product::PRODUCTO_DISPONIBLE ,
            'image' => 'image' ,
        ];
        $this->validate($request, $rules);
        $this->verificarVendedor($seller,$product);

        $product->fill($request->intersect([
            'name', 'description', 'quantity'
        ]));

        if($request->has('status')){
            $product->status = $request->status;
            if($product->estaDisponible() && $product->categories()->count() ==0){
                return $this->errorResponse('Un producto activo debe tener al menos una categoria', 409);
            }
        }

        if($request->has('image')){
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }

        if($product->isClean()){
            return $this->errorResponse('Se debe especificar almenos una valor diferente para actualizar',422);
        }

        $product->save();

        return $this->showOne($product);
    }

    public function delete(Seller $seller, Product $product){

        $this->verificarVendedor($seller,$product);
        Storage::delete($product->image);
        $product->delete();

        return $this->showOne($product);
    }

    protected function verificarVendedor(Seller $seller, Product $product){
        if($seller->id != $product->seller_id){
            throw new HttpException(422, 'El vendedor especificado no es el vendedor ereal del producto');
        }
    }
}
