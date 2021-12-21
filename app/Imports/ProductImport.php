<?php

namespace App\Imports;

use App\Models\ProductModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
        if ($row['produto'] === '' || is_null(($row['produto']))) {
            next($row);
        } else {

            $product = ProductModel::where('partnumber', $row['produto'])->first();

            if (is_null($product)) {
                return new ProductModel([
                    'id' => Str::uuid(),
                    'partnumber' => $row['produto'],
                    'description' => $row['descricao_breve_do_produto'],
                ]);
            }
        }
    }
}
