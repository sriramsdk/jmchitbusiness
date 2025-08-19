<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomersAddress extends Model
{
    protected $table = "customers_address";
    protected $primaryKey = "address_id";
    protected $allowedFields = ['group_id_no_need','forc','customer_name','address','job_details','contact_no','status'];
    protected $returnType = "array";
}