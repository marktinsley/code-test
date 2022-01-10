<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class UserProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $userProduct
     * @return array{success: true}
     */
    public function update(Request $request, Product $userProduct): array
    {
        /** @var User $user */
        $user = $request->user();

        $user->products()->attach($userProduct);

        return ['success' => true];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param \App\Models\Product $userProduct
     * @return array{success: true}
     */
    public function destroy(Request $request, Product $userProduct): array
    {
        /** @var User $user */
        $user = $request->user();

        $user->products()->detach($userProduct);

        return ['success' => true];
    }
}
