<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Village;
use App\Models\District;

class RegionImport implements ToModel, WithHeadingRow
{
    /**
     * Method untuk mendefinisikan bagaimana baris data pada Excel akan dipetakan ke dalam model.
     */
    public function model(array $row)
    {
        $dis = District::where('name',$row['kecamatan'])->first();
        if($dis)
        {
            $desa = new Village;
            $desa->name = $row['desa'];
            $desa->districts_id = $dis->id;
            $desa->save();
        }
        else
        {
            $dis = new District;
            $dis->name = $row['kecamatan'];
            $dis->save();

            $desa = new Village;
            $desa->name = $row['desa'];
            $desa->districts_id = $dis->id;
            $desa->save();
        } 

    }

    // public function headingRow(): int
    // {
    //     return 2;
    // }
}
