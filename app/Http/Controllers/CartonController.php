<?php

namespace App\Http\Controllers;

use App\Imports\CartonItemImport;
use App\Imports\CartonImport;
use App\Imports\ProductImport;
use App\Models\CartonModel;
use App\Models\CartonItemModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SpreadsheetReader;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class CartonController extends Controller
{
  public function index()
  {
    return view('cartons/create');
  }

  public function store(Request $request)
  {
    $carton = new CartonModel();

    $cartonAlreadyExists = $carton
      ::where('shipping_hu', $request->shipping_hu)
      ->count();

    if ($cartonAlreadyExists < 1) {
      $carton->id = Str::uuid();
      $carton->shipping_hu = $request->shipping_hu;
      $carton->document = $request->document;

      $carton->save();

      return redirect('/cartons')->with('msg', 'Caixa cadastrada');
    } else {
      return redirect('/cartons')
        ->with('msg', 'Caixa já possui cadastro  , favor revisar')
        ->with('status', 1);
    }
  }

  public function index_carton()
  {
    return view('audit/create', ['msg' => 'Carton or Box']);
  }

  public function show()
  {
    $carton = new CartonModel();

    $available_cartons = $carton::all();

    return view('cartons/show', ['cartons' => $available_cartons]);
  }

  public function uploadForm()
  {
    return view('audit/create', ['msg' => 'Auditoria Upload']);
  }

  public function upload(Request $request)
  {
    Excel::import(new CartonImport(), $request->file('importedFile'));
    Excel::import(new ProductImport(), $request->file('importedFile'));
    Excel::import(new CartonItemImport(), $request->file('importedFile'));
    return redirect('/audit')
      ->with('msg', 'Caixa cadastrada')
      ->with('status', 1);
  }

  public function listCartons()
  {
    $cartons = CartonModel::all();

    return view('audit/list', [
      'msg' => 'Auditoria Upload',
      'cartons' => $cartons,
    ]);
  }

  public function showCarton($id)
  {
    $carton = CartonModel::where('id', $id)->get();

    return view('audit/carton', [
      'msg' => 'Auditoria Upload',
      'carton' => $carton[0],
    ]);
  }

  public function auditItem(Request $request)
  {
    // return redirect('/audit/show/'.$request['carton'])->with('msg', 'Item atualizado');
    $carton = new CartonModel();
    $product = new ProductModel();
    $audit = new CartonItemModel();

    $carton = $carton::where('id', $request['carton'])->first();

    $productColletcion = $product
      ::where('partnumber', $request['partnumber'])
      ->first();

    $where = [
      'carton_id' => $carton['id'],
      'product_id' => $productColletcion['id'],
      'line' => $request['line'],
    ];

    $audit = $audit::where($where)->first();

    $sobraFalta = [];

    if ($audit['packed_quantity'] >= $request['audit_quantity']) {
      $sobraFalta['falta'] =
        $audit['packed_quantity'] - $request['audit_quantity'];
      $sobraFalta['sobra'] = 0;
    } else {
      $sobraFalta['falta'] = 0;
      $sobraFalta['sobra'] =
        $request['audit_quantity'] - $audit['packed_quantity'];
    }

    // using attach() for single message
    $audit = $audit::where($where)->update([
      'audit_quantity' => $request['audit_quantity'],
      'items_status' => true,
      'audit_user' => Auth::user()->username,
      'remaining_quantity' => $sobraFalta['falta'],
      'exceed_quantity' => $sobraFalta['sobra'],
    ]);
    // $carton->itemsPacked()->attach($productColletcion['id'], [
    //     'packed_quantity' => $audit['packed_quantity'],
    //     'audit_quantity' => $request['audit_quantity'],
    //     'remaining_quantity' => $sobraFalta['falta'],
    //     'exceed_quantity' => $sobraFalta['sobra'],
    //     'damaged_quantity' => $audit['damaged_quantity'],
    //     'items_status' => true,
    //     'audit_user' => Auth::user()->username
    // ]);
    echo json_encode(['msg' => 'Ok']);
  }

  function closeAuditItem(Request $request)
  {
    $audit = new CartonItemModel();

    $where = [
      'carton_id' => $request['carton'],
      'product_id' => $request['product'],
      'line' => $request['line'],
    ];

    $audit = $audit::where($where)->first();

    if (
      $audit['audit_status'] != 'Completo' &&
      $audit['audit_status'] != 'Corrigido'
    ) {
      $audit = $audit
        ::where($where)
        ->update(['audit_status' => $request['status']]);
      echo json_encode(['msg' => 'Ok']);
    } else {
      echo json_encode(['msg' => 'nOk']);
    }

    // $carton->itemsPacked()->attach($productColletcion['id'], [
    //     'packed_quantity' => $audit['packed_quantity'],
    //     'audit_quantity' => $audit['audit_quantity'],
    //     'remaining_quantity' => $audit['remaining_quantity'],
    //     'exceed_quantity' => $audit['exceed_quantity'],
    //     'damaged_quantity' => $audit['damaged_quantity'],
    //     'items_status' => $audit['items_status'],
    //     'audit_user' => $audit['audit_user'],
    //     'audit_status' => $request['status']
    // ]);
  }

  function closeAuditCarton(Request $request)
  {
    $audit = new CartonItemModel();

    $where = [
      'carton_id' => $request->carton,
    ];

    $audit = $audit::where($where)->get();

    foreach ($audit as $line) {
      
      if ($line->items_status != 1) {
        $sobra = 0;
        $falta = 0;
        if ($line->audit_quantity > $line->packed_quantity) {
          $sobra = $line->audit_quantity - $line->packed_quantity;
        } elseif ($line->audit_quantity < $line->packed_quantity) {
          $falta = $line->packed_quantity - $line->audit_quantity;
        }
        echo $line->line;
        echo '<br>Sobra: ' . $sobra;
        echo '<br>Falta: ' . $falta;
        echo '<br><br>';

        $where_update = [
          'carton_id' => $request->carton,
          'line' => $line->line,
        ];
        $update = CartonItemModel::where($where_update)->update([
          'audit_quantity' => $line->audit_quantity,
          'items_status' => true,
          'audit_user' => Auth::user()->username,
          'remaining_quantity' => $falta,
          'exceed_quantity' => $sobra,
        ]);
      }
      
    }
    $where_carton = [
        'id' => $request->carton,
      ];
    $update_carton = CartonModel::where($where_carton)->update([
        'status' => '1',
      ]);
  }
}
