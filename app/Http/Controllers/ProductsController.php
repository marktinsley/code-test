<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUpdateProduct;
use App\Models\Product;
use App\Money\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Product
     */
    public function store(AddUpdateProduct $request): Product
    {
        $data = $request->validated();
        $data['price'] = Money::toInt($data['price']);

        return Product::query()->create($data);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return Product
     */
    public function update(AddUpdateProduct $request, Product $product): Product
    {
        $data = $request->validated();
        $data['price'] = Money::toInt($data['price']);
        $product->fill($data);
        $product->save();

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
