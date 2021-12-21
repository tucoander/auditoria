<?php

namespace App\Http\Controllers;

use App\Imports\CartonItemImport;
use App\Imports\CartonImport;
use App\Imports\ProductImport;
use App\Models\CartonModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SpreadsheetReader;
use Maatwebsite\Excel\Facades\Excel;

class CartonController extends Controller
{
    public function index()
    {
        return view('cartons/create');
    }

    public function store(Request $request)
    {
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

    public function index_audit()
    {
        $products = ProductModel::all();
        return view(
            'audit/create',
            [
                'msg' => 'Auditoria Upload',
                'products' => $products
            ]
        );
    }

    public function store_audit(Request $request)
    {
        $inputFileName = $request->importedFile;

        $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

        switch ($inputFileType) {
            case 'Xlsx':
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                break;
            case 'Xls':
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                break;
            case 'Csv':
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
                break;
        }

        $spreadsheet = $reader->load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        $indexArray = array(
            0 => 'Unidade comercial',
            2 => 'Produto',
            3 => 'Descrição',
            4 => 'Quantidade',
            6 => 'Tipo de Estoque',
            9 => 'Documento',
            13 => 'Centro',
            14 => 'Posição no depósito'
        );

        $cartonsArray = array();
        $cartonData = array(0);
        $productsArray = array();
        $productData = array(2);

        foreach ($rows as $key => $excelRow) {
            if ($key > 1) {
                foreach ($excelRow as $col => $cell) {
                    if (in_array($col, $cartonData) and !empty($cell)) {
                        if (!in_array($cell, $cartonsArray)) {
                            $cartonsArray[] = $cell;
                        }
                    }
                    if (in_array($col, $productData) and !empty($cell)) {
                        if (!in_array($cell, $productsArray)) {
                            $productsArray[] = $cell;
                        }
                    }
                }
            }
        }

        $shipping_hu = new CartonModel();
        $material = new ProductModel();

        foreach ($cartonsArray as $k => $carton) {

            $cartonAlreadyExists = $shipping_hu::where('shipping_hu', $carton)->count();
            if ($cartonAlreadyExists < 1) {
                foreach ($rows as $key => $excelRow) {
                    if ($key > 1) {
                        if ($excelRow[0] === $carton) {
                            $shipping_hu->id = Str::uuid();
                            $shipping_hu->shipping_hu = $excelRow[0];
                            $shipping_hu->document = $excelRow[9];
                            $shipping_hu->save();
                        }
                    }
                }
            }
        }

        foreach ($productsArray as $k => $product) {
            
            $productAlreadyExists = $material::where('partnumber', $product)->count();
            if ($productAlreadyExists < 1) {
                foreach ($rows as $key => $excelRow) {
                    if ($key > 1) {
                        if ($excelRow[2] === $product) {
                            $material->id = Str::uuid();
                            $material->partnumber = $excelRow[2];
                            $material->description = $excelRow[3];
                            $material->save();
                        }
                    }
                }
            }
        }

        // $cartonInput = $carton::where('shipping_hu', $request->shipping_hu)->get();

        // $products_inputs = collect($request->input('packed_quantity', []))->map(
        //     function($product){
        //         return [
        //             'packed_quantity' => $product,
        //             'audit_quantity' => 0,
        //             'remaining_quantity' => 0,
        //             'exceed_quantity' => 0,
        //             'damaged_quantity' => 0,
        //             'items_status' => false
        //         ];
        //     }
        // );
        $log =array();
        foreach ($cartonsArray as $k => $carton) {
            $i = 0;
            foreach ($rows as $key => $excelRow) {
                if ($key > 1) {
                    
                    if ($excelRow[0] === $carton) {
                        $i++;
                        // print_r($excelRow[0]);
                        // echo ' - - - '.$i.' - - - ';
                        // print_r($carton);
                        // echo '<br><br>';
                        $productsPacked = [
                            'packed_quantity' => $excelRow[4],
                            'audit_quantity' => 0,
                            'remaining_quantity' => 0,
                            'exceed_quantity' => 0,
                            'damaged_quantity' => 0,
                            'items_status' => false
                        ];
                        $temp = array();

                        $product = $material::where('partnumber', $excelRow[2])->get();

                        $temp[$product[0]->id][$i] = $productsPacked;

                        $products_inputs[$product[0]->id][] = $productsPacked;

                        $carton = $shipping_hu::where('shipping_hu', $excelRow[0])->get();

                        $log[$carton[0]->shipping_hu][$i] = $temp;

                        $temp = array();

                        $carton = $shipping_hu::findOrFail($carton[0]->id);

                        // $carton->itemsPacked()->attach($products_inputs);
                        // $carton->save();

                        

                    }
                    
                }
            }
            $products_inputs = array();
        }

        dd($log);
        





        // $carton = $carton::findOrFail($cartonInput[0]->id);
        // // $products_inputs = $request->input('products', []);
        // // dd($request->all(), $products_inputs, $cartonInput[0]->id);
        // $carton->itemsPacked()->attach($products_inputs);

        // return redirect('/cartons')->with('msg', 'Caixa cadastrada');
    }

    public function store_excel(Request $request){
        Excel::import(new CartonImport(), $request->file('importedFile'));
        Excel::import(new ProductImport(), $request->file('importedFile'));
        Excel::import(new CartonItemImport(), $request->file('importedFile'));
        return redirect('/audit')->with('msg', 'Caixa cadastrada')->with('status', 1);
    }
}
