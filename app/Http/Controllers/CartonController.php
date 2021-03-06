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
    $cartons = CartonModel::where('status', '!=', '1')
      ->orWhereNull('status')
      ->orderBy('document')
      ->get();

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

    $update_carton = $carton->update([
      'status' => '2',
    ]);

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

    echo json_encode(['msg' => 'Ok']);
  }

  public function auditItemAddQuantity(Request $request)
  {
    $carton = CartonModel::where('id', $request['carton'])->first();

    $update_carton = $carton->update([
      'status' => '2',
    ]);

    $productColletcion = ProductModel::where(
      'partnumber',
      $request['partnumber']
    )->first();
    $where = [
      'carton_id' => $request['carton'],
      'product_id' => $productColletcion['id'],
      'line' => $request['line'],
    ];

    $auditUpdate = CartonItemModel::where($where)->first();

    $auditUpdate = $auditUpdate::where($where)->update([
      'audit_quantity' =>
        $auditUpdate->audit_quantity + $request['audit_quantity'],
      'items_status' => true,
      'audit_user' => Auth::user()->username,
    ]);

    $auditSobraEFalta = CartonItemModel::where($where)->first();

    $sobraFalta = [];

    if (
      $auditSobraEFalta['packed_quantity'] >=
      $auditSobraEFalta['audit_quantity']
    ) {
      $sobraFalta['falta'] =
        $auditSobraEFalta['packed_quantity'] -
        $auditSobraEFalta['audit_quantity'];
      $sobraFalta['sobra'] = 0;
    } else {
      $sobraFalta['falta'] = 0;
      $sobraFalta['sobra'] =
        $auditSobraEFalta['audit_quantity'] -
        $auditSobraEFalta['packed_quantity'];
    }

    $auditSobraEFalta = CartonItemModel::where($where)->update([
      'remaining_quantity' => $sobraFalta['falta'],
      'exceed_quantity' => $sobraFalta['sobra'],
    ]);
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
    $response = [];
    $response['msg'] = 'Ok';
    echo json_encode($response);
  }

  public function addInfoCarton(Request $request)
  {
    $carton = CartonModel::where('id', $request['carton'])
      ->first()
      ->update([
        'observations' => $request['info'],
      ]);

    echo json_encode(['msg' => 'Ok']);
  }

  public function addExceedItem(Request $request)
  {
    try {
      $product = ProductModel::where(
        'partnumber',
        $request['partnumber']
      )->first();

      if (is_null($product)) {
        $product = new ProductModel([
          'id' => Str::uuid(),
          'partnumber' => $request['partnumber'],
          'description' => $request['description'],
        ]);

        $product->save();
      }

      $line = CartonItemModel::where('carton_id', $request['carton'])->max('line');

      $newCarton = new CartonItemModel([
        'carton_id' => $request['carton'],
        'product_id' => $product->id,
        'packed_quantity' => 0,
        'audit_quantity' => $request['quantity'],
        'remaining_quantity' => 0,
        'exceed_quantity' => $request['quantity'],
        'damaged_quantity' => 0,
        'items_status' => false,
        'line' => intval($line)+1,
      ]);

      $newCarton->save();

      return response()->json(
        [
          'message' => 'Prduct inserted',
          'response' => $newCarton,
          'error_code' => 0
        ],
        201
      );
    } catch (\Throwable $th) {

      if ($th instanceof \PDOException) {
        return response()->json(
          [
            'message' => 'SQL error',
            'error_code' => $th->getCode()
          ],
          400
        );
      }
      return response()->json(
        [
          'message' => $th->getMessage(),
          'error_code' => $th->getCode()
        ],
        400
      );
    }

    $newCarton->save();
  }
}
