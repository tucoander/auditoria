<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    public function index()
    {
        return view('products\create');
    }

    public function store(Request $request)
    {
        $product = new ProductModel();

        $productAlreadyExists = ProductModel::where('partnumber', $request->partnumber)->count();

        if ($productAlreadyExists < 1) {
            $product->id = Str::uuid();
            $product->partnumber = $request->partnumber;
            $product->description = $request->description;

            $product->save();

            return redirect('/products')->with('msg', 'Produto cadastrado');
        } else {
            return redirect('/products')->with('msg', 'Produto já possui cadastro, favor revisar');
        }
    }
}
