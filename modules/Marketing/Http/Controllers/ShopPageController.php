<?php

namespace Modules\Marketing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Product\Models\Product;
use Modules\Product\Models\ProductCategory;
use Modules\Product\Http\Resources\ProductHomePageResource;

class ShopPageController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->search($request->search)
            ->where('is_active', true)
            ->with('images')
            ->orderBy('name')
            ->paginate(50);
        
        $product_categories = ProductCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->select('id', 'name', 'slug')
            ->get();

        return Inertia::render('marketing/shop/Index', [
            'products' => ProductHomePageResource::collection($products),
            'product_categories' => $product_categories,
            'filters' => [
                'search' => $request->search,
            ]
        ]);
    }
}