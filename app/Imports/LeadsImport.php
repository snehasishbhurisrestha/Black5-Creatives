<?php

namespace App\Imports;

use App\Models\Lead;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Lead([
            'first_name'           => $row['first_name'], 
            'last_name'            => $row['last_name'], 
            'business_name'        => $row['business_name'], 
            'business_category_id' => $row['business_category_id'], 
            'email'                => $row['email'], 
            'gender'               => $row['gender'], 
            'phone'                => $row['phone'], 
            'opt_mobile_no'        => $row['opt_mobile_no'], 
            'contact_person'       => $row['contact_person'], 
            'address'              => $row['address'], 
            'business_address'     => $row['business_address'], 
            'status'               => 'new',
        ]);
    }
}
