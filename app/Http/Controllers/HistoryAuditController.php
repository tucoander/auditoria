<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartonModel;
use App\Models\CartonItemModel;
use App\Models\ProductModel;
use App\Exports\HistoryExport;
use Maatwebsite\Excel\Facades\Excel;

class HistoryAuditController extends Controller
{
  public function index(Request $request)
  {
    $carton = new CartonModel();
    if (!!$request) {
      $finished_cartons = $carton::where('status', '!=', 0)->get();

      if (!!$request->inputHu) {
        $finished_cartons = $finished_cartons->where(
          'shipping_hu',
          '=',
          $request->inputHu
        );
      }
      if (!!$request->inputDateFrom && !!$request->inputDateTo) {
        $finished_cartons = $finished_cartons->whereBetween('updated_at', [
          date($request->inputDateFrom),
          date($request->inputDateTo),
        ]);
      } elseif (!!$request->inputDateFrom && !!!$request->inputDateTo) {
        $finished_cartons = $finished_cartons->where(
          'updated_at',
          '>=',
          date($request->inputDateFrom)
        );
      } elseif (!!!$request->inputDateFrom && !!$request->inputDateTo) {
        $finished_cartons = $finished_cartons->where(
          'updated_at',
          '>=',
          date($request->inputDateTo)
        );
      }
    } else {
      $finished_cartons = $carton::where('status', '!=', 0)->get();
    }

    return view('history/list', ['cartons' => $finished_cartons]);
  }
  public function export(Request $request)
  {
    $data = array();
    if (!!$request) {
        $data['shipping_hu'] = $request->inputHu;
        $data['dateFrom'] = $request->inputDateFrom;
        $data['dateTo'] = $request->inputDateTo;
    } 

    $dateNow = new \DateTime('now');
    $uniquePrefix = $dateNow->format('Y-m-d_His');
    return Excel::download(new HistoryExport($data), $uniquePrefix . '_audit.xlsx');
    // return (new HistoryExport($data))->download($uniquePrefix . '_audit.xlsx');

  }
}
