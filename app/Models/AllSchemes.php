<?php

namespace App\Models;

use CodeIgniter\Model;

class AllSchemes extends Model
{
    protected $table = "allschemes";
    protected $primaryKey = "Month";
    protected $allowedFields = ['10_months','11_months','15_months','20_months','21_months','22_months','25_months'];
    protected $returnType = "array";
}