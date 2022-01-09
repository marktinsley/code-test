<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUpdateProduct;
use App\Models\Product;
use App\Money\Money;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Paginator
     */
    public function index(Request $request)
    {
        return Product::query()
            ->simplePaginate(
                $request->query('per_page') > 0 ? $request->query('per_page') : 25
            );
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
        return $product;
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
     * @return array{success: bool}
     */
    public function destroy(Product $product): array
    {
        return ['success' => $product->delete()];
    }
}
