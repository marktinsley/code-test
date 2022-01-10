<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUpdateProduct;
use App\Models\Product;
use App\Money\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $sortable = [
            'name',
            'description',
            'price',
        ];

        return Product::query()
            ->when(
                $request->query('filter'),
                fn(Builder $query) => $query->where('name', 'LIKE', "%{$request->query('filter')}%")
            )
            ->when(
                $request->query('sort_by') && in_array($request->query('sort_by'), $sortable),
                fn(Builder $query) => $query->orderBy($request->query('sort_by'), $request->query('descending') ? 'desc' : 'asc')
            )
            ->paginate(
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
