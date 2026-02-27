<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Product\ProductRepository;


class ProductController extends Controller
{
    //khởi tạo repository
    protected $productRepo;

    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }
    public function index()
    {
        //
        $products=  $this->productRepo->getProducts();


        return view('products.index', compact('products'));
    }
}
