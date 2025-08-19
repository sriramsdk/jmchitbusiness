<?php

namespace App\Models;

use CodeIgniter\Model;

class Customers extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";

    protected $allowedFields = ['group_id','group_name','month_si','starts_with_months','book_name','care_of_name','is_group_address','address_id','real_doj','guessed_doj','aprox_doj','due_amount','months','amount_need_on','total_intrest_paid','intrest_paid_details','pending_trust_percent','intrest_trust_percent','collection_by','current_status','status','closed_on','closed_date_by_us','closed_with_pending','closed_with_intrest'];
    
    protected $returnType = "array";
}