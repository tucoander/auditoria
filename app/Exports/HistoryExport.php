<?php

// namespace App\Exports;

// use App\Models\CartonItemModel;
// use Maatwebsite\Excel\Concerns\FromQuery;
// use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\FromCollection;

// class HistoryExport implements FromCollection
// {
//     /**
//     * @return \Illuminate\Support\Collection
//     */
//     public function collection()
//     {
//         return CartonItemModel::all();
//     }
// }

namespace App\Exports;

use App\Models\CartonModel;
use App\Models\CartonItemModel;
use App\Models\ProductModel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HistoryExport implements FromCollection, WithHeadings, WithStyles
{
  public function __construct($data)
  {
    $this->data = $data;
  }

  public function headings(): array
    {
        return [
          "Data de auditoria",
          "HU",
          "Documento",
          "Status",
          "Item",
          "Quantidade embalada",
          "Quantidade auditada",
          "Quantidade em falta",
          "Quantidade em sobra",
          "Quantidade avariada",
          "Auditor",
          "Informações"
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    // dd($this->data);

    $export = DB::table('carton_item')
      ->join('cartons', 'cartons.id', '=', 'carton_item.carton_id')
      ->join('products', 'products.id', '=', 'carton_item.product_id')
      ->selectRaw(
        'cartons.updated_at, 
        cartons.shipping_hu, 
        cartons.document, 
        cartons."status", 
        products.partnumber, 
        carton_item.packed_quantity,
        carton_item.audit_quantity, 
        carton_item.remaining_quantity,
        carton_item.exceed_quantity,
        carton_item.damaged_quantity,
        carton_item.audit_user,
        cartons.observations'
      )
      ->where('status', '!=', 0);
    
      if (!!$this->data['shipping_hu']) {
        $export = $export->where(
          'cartons.shipping_hu',
          '=',
          $this->data['shipping_hu']
        );
      }
      if (!!$this->data['dateFrom'] && !!$this->data['dateTo']) {
        $export = $export->whereBetween('cartons.updated_at', [
          date($this->data['dateFrom']),
          date($this->data['dateTo']),
        ]);
      } elseif (!!$this->data['dateFrom'] && !!!$this->data['dateTo']) {
        $export = $export->where(
          'cartons.updated_at',
          '>=',
          date($this->data['dateFrom'])
        );
      } elseif (!!!$this->data['dateFrom'] && !!$this->data['dateTo']) {
        $export = $export->where(
          'cartons.updated_at',
          '>=',
          date($this->data['dateTo'])
        );
      }

    

    return $export->get();
  }
}
