<?php

namespace App\Http\Controllers;

use App\Models\CartonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartonController extends Controller
{
    public function index()
    {
        return view('cartons/create');
    }

    public function store(Request $request){
        $carton = new CartonModel();

        $cartonAlreadyExists = $carton::where('shipping_hu', $request->shipping_hu)->count();

        if ($cartonAlreadyExists < 1) {
            $carton->id = Str::uuid();
            $carton->shipping_hu = $request->shipping_hu;
            $carton->document = $request->document;

            $carton->save();

            return redirect('/cartons')->with('msg', 'Caixa cadastrada');
        } else {
            return redirect('/cartons')->with('msg', 'Caixa já possui cadastro  , favor revisar')->with('status', 1);
        }
    }

    public function index_carton(){
        return view('audit/create', ['msg'=> 'Carton or Box']);
    }

    public function show()
    {
        $carton = new CartonModel();

        $available_cartons = $carton::all();

        return view('cartons/show', ['cartons' => $available_cartons]);
    }
}
