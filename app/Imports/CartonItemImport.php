<?php

namespace App\Imports;

use App\Models\CartonItemModel;
use App\Models\CartonModel;
use App\Models\ProductModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class CartonItemImport implements ToModel, WithHeadingRow
{
    private $cartons;
    private $items;
    private $cartonItem;

    public function __construct()
    {
        $this->cartons = CartonModel::select('id', 'shipping_hu', 'document')->get();
        $this->items = ProductModel::select('id', 'partnumber', 'description')->get();
        $this->cartonItem = CartonItemModel::select('carton_id', 'product_id', 'packed_quantity')->get();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {

        if ($row['unidade_comercial'] === '' || is_null(($row['unidade_comercial']))) {
            next($row);
        } else {
            $carton = $this->cartons = CartonModel::where('shipping_hu', $row['unidade_comercial'])->first();
            $product = $this->product = ProductModel::where('partnumber', $row['produto'])->first();

           
            return new CartonItemModel([
                'carton_id' => $carton->id,
                'product_id' => $product->id,
                'packed_quantity' => $row['quantidade'],
                'audit_quantity' => 0,
                'remaining_quantity' => 0,
                'exceed_quantity' => 0,
                'damaged_quantity' => 0,
                'items_status' => false
            ]);
            
                
            
        }
    }
}
