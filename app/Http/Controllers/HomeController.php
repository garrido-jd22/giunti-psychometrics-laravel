<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Home: hero, productos destacados y contacto.
     */
    public function __invoke(ProductService $products): View
    {
        return view('pages.home', [
            'featuredProducts' => $products->featured(),
        ]);
    }
}
