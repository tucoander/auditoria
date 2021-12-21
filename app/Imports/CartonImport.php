<?php

namespace App\Imports;

use App\Models\CartonModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CartonImport implements ToModel, WithHeadingRow
{
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
            $carton = CartonModel::where('shipping_hu', $row['unidade_comercial'])->first();
            
            if (is_null($carton)) {
                return new CartonModel([
                    'id' => Str::uuid(),
                    'shipping_hu' => $row['unidade_comercial'],
                    'document' => $row['documento'],
                ]);
            }
            $carton = CartonModel::where('shipping_hu', $row['unidade_comercial'])->first();
        }

    }
}
