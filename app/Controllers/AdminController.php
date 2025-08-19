<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Customers;
use App\Models\Amount_Entries;
use App\Models\AmountGivenDetails;
use App\Models\CustomersAddress;
use App\Models\AllSchemes;
use App\Models\Groups;
use App\Models\CollectionBy;
use CodeIgniter\Database;

class AdminController extends BaseController{

    public function __construct()
    {
        if(!session()->get('isLoggedIn')){
            return redirect()->to('/login');
        }else{
            if(session()->get('is_admin')){
                $this->loggedin = true;
            }else{
                $this->loggedin = false;
                return redirect()->to('/login');
            }
        }

        $this->customers = new Customers();
        $this->customers_address = new CustomersAddress();
        $this->admin = new Admin();
        $this->amount_entries = new Amount_Entries();
        $this->amount_given_details = new AmountGivenDetails();
        $this->all_schemes = new AllSchemes();
        $this->groups = new Groups();
        $this->collectionby = new CollectionBy();
    }

    public function index(){
        if($this->loggedin){
            return view('layout/header').view('admin/dashboard').view('layout/footer');
        }else{
            return redirect()->to('/login');
        }
    }

    public function Customers_list(){
        if($this->loggedin){

            $request = service('request');

            $search = $request->getPost('search')['value'];
            $start = $request->getPost('start');
            $length = $request->getPost('length');
            $order = $request->getPost('order');
            $columns = $request->getPost('columns');

            $orderColumnIndex = $order[0]['column'];
            $orderColumnName  = $columns[$orderColumnIndex]['data'];
            $orderDir         = $order[0]['dir'];

            $columnMap = [
                'customer_id'    => 'c.customer_id',
                'book_name'      => 'c.book_name',
                'group_name'     => 'g.group_name',
                'collector_name' => 'cb.name'
            ];

            $orderByColumn = $columnMap[$orderColumnName] ?? 'c.customer_id';

            $builder = $this->db->table('customers c')
                ->select('
                    c.customer_id,c.group_id,c.month_si,c.starts_with_months,c.book_name,c.care_of_name,c.is_group_address,c.address_id,c.real_doj,c.guessed_doj,c.aprox_doj,c.due_amount,c.months,c.amount_need_on,c.total_intrest_paid,c.intrest_paid_details,c.pending_trust_percent,c.intrest_trust_percent,c.collection_by,c.current_status,c.status,c.closed_on,c.closed_date_by_us,c.closed_with_pending,c.closed_with_intrest, 
                    ca.group_id_no_need,ca.forc,ca.customer_name,ca.address,ca.job_details,ca.contact_no, 
                    agd.amount_given_id,agd.amount_given_date,agd.actual_amount_given,agd.given_amount,agd.deduction_amount,agd.balance_given_amount,agd.amount_given_method, agd.book_entry, agd.given_by, agd.cheque_ft_transfer_details, agd.received_documents, agd.remarks_amt_calc,
                    g.group_name, 
                    cb.name as name
                ')
                ->join('customers_address ca', 'c.address_id = ca.address_id', 'left')
                ->join('amount_given_details agd', 'c.customer_id = agd.customer_id', 'left outer')
                ->join('groups g', 'c.group_id = g.group_id', 'left')
                ->join('colection_by cb', 'c.collection_by = cb.id', 'left')
                ->where('c.status', 1);

            $totalBuilder = $this->db->table('customers c')->select('COUNT(DISTINCT c.customer_id) as total');
            $recordsTotal = $totalBuilder->where('c.status', 1)->get()->getRow()->total;


            if (!empty($search)) {
                $builder->groupStart()
                    ->Like('c.book_name', $search)
                    ->orLike('ca.forc', $search)->orLike('c.care_of_name', $search)
                    ->orLike('g.group_name', $search)
                    ->orLike('cb.name', $search)
                    ->groupEnd();
            }

            $filteredBuilder = $this->db->table('customers c')->select('COUNT(DISTINCT c.customer_id) as filtered,ca.*')
            ->join('customers_address ca', 'c.address_id = ca.address_id', 'left')
                ->join('groups g', 'c.group_id = g.group_id', 'left')
                ->join('colection_by cb', 'c.collection_by = cb.id', 'left')
                ->where('c.status', 1);

            if (!empty($search)) {
                $filteredBuilder->groupStart()
                    ->like('c.book_name', $search)
                    ->orLike('ca.forc', $search)->orLike('c.care_of_name', $search)
                    ->orLike('g.group_name', $search)
                    ->orLike('cb.name', $search)
                    ->groupEnd();
            }

            $recordsFiltered = $filteredBuilder->get()->getRow()->filtered;

            $builder->orderBy('DATE_FORMAT(c.guessed_doj,"%Y-%m-%d") ASC', false)->orderBy($orderByColumn, $orderDir)->groupBy('c.customer_id')->limit($length, $start);
            // echo $builder->getCompiledSelect(); exit; 

            $customer_details = $builder->get()->getResultArray();
            $data = [];

            $paid_m = (isset($_POST['paid_given_det']) && $_POST['paid_given_det'] == 'lm') 
                ? date('Y-m', strtotime('last month')) 
                : date('Y-m');

            $today_month_start = strtotime(date('Y-m-01'));

            foreach ($customer_details as $key => $csdetails) {
                $customer_id = $csdetails['customer_id'];
                $due_amount = $csdetails['due_amount'];
                $months = $csdetails['months'];
                $guessed_doj = strtotime(date('Y-m-01', strtotime($csdetails['guessed_doj'])));

                $cm = 0;
                $date_ptr = $guessed_doj;
                while ($date_ptr <= $today_month_start) {
                    
                    $date_ptr = strtotime('+1 month', $date_ptr);
                    $cm++;
                }
                $pending_interest = [];
                // $pending_interest = $this->return_chit_pending_int($csdetails['customer_id'], $cm, $csdetails['guessed_doj'], $csdetails['months'], $csdetails['due_amount']);
                $pending_payment = 0;
                $need_paid = 0;
                // if(($cm-1)<=$csdetails['months'])
                // {
                //     // $need_paid=($cm-1)*$csdetails['due_amount'];
                //     // $pending_payment = $need_paid-$pending_interest['paid_amount'];
                
                // }
                // else 
                // {
                //     // $need_paid = $csdetails['due_amount']*$csdetails['months'];
                //     // $pending_payment = $need_paid-$pending_interest['paid_amount'];
                // }

                $cm_paid = 0;
                $cm_paid = $this->cm_paid($customer_id, $paid_m);
                $upto_last_month_paid_month = 0;
                // $upto_last_month_paid_month = $pending_interest['paid_amount']/$csdetails['due_amount'];

                $amt_giv_date = trim($csdetails['amount_given_date']);
                $day_of_given = '';
                if(strlen($amt_giv_date)==10)
				{
                    if(date('M-Y',strtotime($amt_giv_date)) == date('M-Y')){
						$day_of_given=date('d',strtotime($amt_giv_date));
                    }
                    $amt_giv_date=date('M-y',strtotime($amt_giv_date));
                }else{
                    $amt_giv_date = $csdetails['amount_given_date'];
                }

                $p_month = '';
                $have_to_give = '';
                // if($csdetails['amount_need_on'] != '' || date('M',strtotime($csdetails['amount_given_date'])) == date('M') )
                // {
                //     $p_month = $csdetails['amount_need_on'];
                //     $have_to_give = $this->given_amount($csdetails['due_amount'],$csdetails['months'],$cm);
                // }
                // else
                // {
                //     $have_to_give='';
                // }

                // if(strlen($p_month)==10)
                // {
                //     $p_month=date('M',strtotime($p_month));
                // }

                // if($day_of_given>0){
	            //     $p_month=ucwords(date('M'));
                // }

                // if(ucwords($p_month) != ucwords(date('M'))){
                //     $have_to_give=0;
                // }
                $given_amount = '';
                // if($csdetails['given_amount'] == 0){
				// 	$given_amount = '';
                // }else{
				// 	$given_amount = $csdetails['given_amount'];
                // }

                // if($have_to_give>0 and ($given_amount<$have_to_give or $given_amount>$have_to_give))
                // {
                //     if($given_amount!=''){
                //         // $given_amount = $given_amount;
                //         // $have_to_give = $have_to_give;
                //     }else{
                //         $given_amount = '';
                //         $have_to_give = '';
                //     }
                    
                // }
                // else if($given_amount==$have_to_give)
                // {
                //     if($have_to_give==0){
                //         // $given_amount = '';
                //         $have_to_give = '';
                //     }else{
                //         // $given_amount = '';
                //         $have_to_give = $have_to_give;
                //     }
                // }
                // else
                // {
                //     if($have_to_give==0){
                //         // $given_amount = '';
                //         $have_to_give = '';
                //     }else{
                //         // $given_amount = '';
                //         $have_to_give = $have_to_give;
                //     }
                // }

                if($csdetails['guessed_doj'] != '0000-00-00'){
					$code_no = date('my',strtotime($csdetails['guessed_doj'])).'-'.$csdetails['month_si'];
                }else{
					$code_no = $csdetails['month_si'];
                }

                $data[$key] = [
                    'customer_id'    => $csdetails['customer_id'],
                    'customer_name'  => $csdetails['book_name'],
                    'forc'           => !empty($csdetails['care_of_name']) ? $csdetails['care_of_name'] : $csdetails['forc'],
                    // 'cm_paid_dates'  => ($pending_interest['cm_paid_dates'])?$pending_interest['cm_paid_dates']:'',
                    'cm_paid_dates'  => '',
                    'cm_paid'        => $cm_paid,
                    'due_amount'     => $due_amount,
                    'pending'        => ($pending_payment != 0 || $pending_payment != '') 
                                        ? $pending_payment 
                                        : $pending_payment,
                    // 'interest'       => ($pending_interest['interest'])?(($pending_interest['interest']>0)?round($pending_interest['interest'],2):0):0,
                    'interest'       => '',
                    'cm'             => $cm,
                    'tm'             => $csdetails['months'],
                    'pm'             => round($upto_last_month_paid_month,2),
                    'tpaid'          => !empty($pending_interest['paid_amount'])?$pending_interest['paid_amount']:"",
                    'tnt'            => $amt_giv_date,
                    'dt'             => '',
                    'togiv'          => $have_to_give,
                    'ask'            => $p_month,
                    'code'           => $code_no,
                    'group'          => $csdetails['group_name'],
                    'cby'            => substr($csdetails['name'],0,1),
                    'action'         => '<a href="'.base_url('customer_view/' . $csdetails['customer_id']).'" target="_blanck" class="btn         btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                        <a href="'.base_url('customer_edit/' . $csdetails['customer_id']).'" target="_blanck" class="btn btn-sm
                                        btn-primary" title="Edit"><i class="fas fa-edit"></i></a>'
                ];
            }

            return $this->response->setJSON([
                'draw' => intval($request->getPost('draw')),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]);
        }else{
            return redirect()->to('/login');
        }
    }

    public function cm_paid($customer_id,$paid_m){
        $data = $this->amount_entries->select('SUM(paid_amount) as cm_paid')->where("DATE_FORMAT(paid_date,'%Y-%m')",$paid_m)->where('customer_id',$customer_id)->first();
        return $data['cm_paid'];
    }

    public function return_chit_pending_int($customer_id, $cm, $guessed_doj, $tm, $due_amount)
    {
        $total_int = 0;
        $total_paid_amount = 0;

        $jm = (int)date('m', strtotime($guessed_doj));
        $jy = (int)date('Y', strtotime($guessed_doj));

        for ($m = 0; $m < $cm - 1; $m++) {
            $month_time = mktime(0, 0, 0, $jm + $m, 1, $jy);
            $month_year = date('m-Y', $month_time);
            $month_end_date = date('Y-m-t', $month_time);

            // Sum of paid amount for that customer for the month
            $due_paid = $this->amount_entries
                ->select("COALESCE(SUM(paid_amount), 0) AS paid_amnt")
                ->where('customer_id', $customer_id)
                ->where("DATE_FORMAT(paid_date, '%m-%Y')", $month_year)
                ->first();

            // $paid_amount = (float)($due_paid['paid_amnt'] ?? 0);
            $total_paid_amount = $total_paid_amount+$due_paid['paid_amnt'];

            // Expected payment up to this month
            if($tm>($m+1)){
			    $have_to_paid=($m+1)*$due_amount;
            }else{
				$have_to_paid=$due_amount*$tm;
            }

            // Calculate pending amount
            $pending_amount = $have_to_paid - $total_paid_amount;
            // $pending_amount1=$pending_amount;
            // $intrest = 0;

            // // Interest rules
            // if($month_end_date<'2020-05-01')
			// 	{
			// 		$intrest=0;
			// 	}
			// 	else if($month_end_date>'2020-06-01')
			// 	{
			// 		$intrest=($pending_amount*2)/100;
			// 	}
			// 	else
			// 	{
	
			// 		while($pending_amount1>0)
			// 		{
			// 			//echo $pending_amount1;
			// 			$intrest=$intrest+($pending_amount1*2)/100;
			// 			$pending_amount1=$pending_amount1-$due_amount;
	
			// 		}
	
			// 	}

            // $total_int += $intrest;
        }

        // Get current month's paid date
        // $paid_date_row = $this->amount_entries
        //     ->select('paid_date')
        //     ->where('customer_id', $customer_id)
        //     ->where("MONTH(paid_date)", date('m'))
        //     ->where("YEAR(paid_date)", date('Y'))
        //     ->first();

        // $dates = '';

        // if (!empty($paid_dates)) {
        //     $date_array = [];
        //     foreach ($paid_dates as $row) {
        //         $date_array[] = date('d', strtotime($row['paid_date']));
        //     }
        //     $dates = implode(',', $date_array);
        // }

        return [
            'paid_amount'    => $total_paid_amount,
            // 'interest'        => $total_int,
            // 'cm_paid_dates'  => $dates,
            // 'pending_amnt'   => $pending_amount ?? 0
        ];
    }

    public function given_amount($due_amount,$months,$cm){
        if($cm > $months){
            $fetch_month = $months;
        }else{
            $fetch_month = $cm;
        }

        $amnt_have_to_given = '';
        $all_schemes = $this->all_schemes->where('Month',$fetch_month)->get()->getResultArray();
        if(!empty($all_schemes)){
            $scheme_months = $months."_months";
            $amount=$all_schemes[0][$scheme_months];

            $total_amount = ($amount/1000)*$due_amount;
            if($cm>$months){
                $diff = $cm-$months;
                $exceed = $cm-$months;
                $intrest_amnt = ( $total_amount*1.5*$exceed)/100;
                return $amnt_have_to_given = (int)( $total_amount + $intrest_amnt);
            }else{ 
                return $amnt_have_to_given = $total_amount;
            }
        }else{
            return $amnt_have_to_given;
        }
    }

    // public function return_chit_pending_int($customer_id,$cm,$guessed_doj,$tm,$due_amount){
    //     $total_int=0;
    //     $total_paid_amount=0;
    //     $cm=$cm;
    //     $guessed_doj=$guessed_doj;
    //     $jm=date('m',strtotime($guessed_doj));
    //     $jy=date('Y',strtotime($guessed_doj));
    //     $tm=$tm;

    //     for($m=0; $m<$cm-1; $m++){
    //         $date_month_year= date('t-m-Y', mktime(0, 0, 0, $m+$jm, 1,$jy));
    //         $month_year= date('m-Y', mktime(0, 0, 0, $m+$jm, 1,$jy));
    //         $month_year1=date('Y-m-t', mktime(0, 0, 0, $m+$jm, 1,$jy));

    //         $due_paid = $this->amount_entries->select("COALESCE(sum(paid_amount),0) as paid_amnt,DATE_FORMAT(paid_date, '%m-%Y') paiddate")->where(['customer_id' => $customer_id, "DATE_FORMAT(paid_date, '%m-%Y')" => $month_year])->first();

    //         if($tm>($m+1)){
	// 		    $have_to_paid=($m+1)*$due_amount;
    //         }else{
	// 			$have_to_paid=$due_amount*$tm;
    //         }

    //         $total_paid_amount = $total_paid_amount+$due_paid['paid_amnt'];

    //         $intrest=0;
    //         $pending_amount = $have_to_paid-$total_paid_amount;
    //         $pending_amount1 = $pending_amount;

    //         if($month_year1 < '2020-05-01')
    //         {
    //             $intrest=0;
    //         }
    //         else if($month_year1 > '2020-06-01')
    //         {
    //             $intrest=($pending_amount*2)/100;
    //         }
    //         else
    //         {
    //             while($pending_amount1>0)
    //             {
    //                 $intrest = $intrest+($pending_amount1*2)/100;
    //                 $pending_amount1 = $pending_amount1-$due_amount;

    //             }

    //         }

    //         $total_int = $total_int+$intrest;
    //     }

    //     $paid_dates = $this->amount_entries->select('customer_id,paid_date')->where("MONTH(paid_date) = MONTH(CURRENT_DATE())")->where("YEAR(paid_date) = YEAR(CURRENT_DATE())")->where('customer_id',$customer_id)->first();
    //     $dates = '';

    //     if(!empty($paid_dates)){
    //         $dates=date('d',strtotime($row_paid_date['paid_date']));
    //     }

    //     if(!isset($pending_amount)){
	// 	    $pending_amount=0;
    //     }

    //     $paid_amnt_intrest=array('paid_amount'=>$total_paid_amount,'intrest'=>$total_int,'cm_paid_dates'=>$dates,'pending_amnt'=>$pending_amount);

	//     return $paid_amnt_intrest;

    // }

    public function customer_view($id){
        if($this->loggedin){
            $customer_details = $this->customers->find($id);
            $customer_address = $this->customers_address->find($customer_details['address_id']);
            $amount_given_details = $this->amount_given_details->where(['customer_id' => $customer_details['customer_id']])->get()->getRowArray();
            $group = $this->groups->find($customer_details['group_id']);
            $collectionby = $this->collectionby->find($customer_details['collection_by']);
            $pending_details = $this->pending_details($id,$customer_details,$amount_given_details);
            $pending_details_lm = $this->pending_details_lm($id,$customer_details,$amount_given_details);
            $amount_entries = $this->amount_entries->where('amount_entries.customer_id',$customer_details['customer_id'])->get()->getResultArray();
            $collection_by = $this->collectionby->get()->getResultArray();
            // echo "<pre>";print_r($collection_by);exit();
            $data = [
                'customer_details' => $customer_details,
                'customer_address' => $customer_address,
                'amount_given_details' => $amount_given_details,
                'groups' => $group,
                'collectionby' => $collectionby,
                'pending_details' => $pending_details,
                'pending_details_lm' => $pending_details_lm,
                'amount_entries' => $amount_entries,
                'collection_by' => $collection_by
            ];
            // echo "<pre>";print_r($data);exit();
            return view('layout/header').view('admin/customer_view',$data).view('layout/footer');
        }else{
            return redirect()->to('/login');
        }
    }

    public function pending_details($customer_id,$customer_details,$amount_given_details){
        $amount_entries = $this->amount_entries->select('SUM(paid_amount) as total_paid')->where("DATE_FORMAT(paid_date,'%Y-%m')<=","date_format(current_date,'%Y-%m')")->where('customer_id =',$customer_id)->get()->getRowArray();

        if($amount_entries['total_paid'] == NULL || $amount_entries['total_paid'] == 0){
            $amount_entries['total_paid'] = 0;
            if(isset($amount_given_details['given_amount'])){
                $amount_given = $amount_given_details['given_amount'];
            }else{
                $amount_given = 0;
            }
        }

        $guessed_doj = strtotime ($customer_details['guessed_doj'] )  ;
		$guessed_doj = strtotime(date('Y-m-01',$guessed_doj));
		$today_date = strtotime(date('Y-m-01'));
		$cm=0;

        while($guessed_doj <= $today_date)
        {
            $guessed_doj = strtotime ( '+1 month' , $guessed_doj ) ;
            $cm++;
        }

        $total_paid = $amount_entries['total_paid'];
		$total_amount_had_paid = $cm * $customer_details['due_amount'];
		$pending_amount = $total_amount_had_paid-$total_paid;

        if($pending_amount < 0){
			$excess_amount = abs($pending_amount);
			$pending_amount = 0;			
		}else {	
			$excess_amount = 0;
		}
		
		if(!empty($amount_given_details['given_amount']) && $amount_given_details['given_amount'] == 0){
			$profit_this_month = 0;
		}else{
            $profit_this_month = 0;
		}

        $res['total_paid'] = $total_paid;
		$res['had_paid'] = $total_amount_had_paid;
		$res['upto_today_pending'] = $pending_amount;
		$res['excess_amount'] = $excess_amount;
		$res['profit_this_month'] = $profit_this_month;
        // echo "<pre>";print_r($res);exit();
        return $res;
    }

    public function pending_details_lm($customer_id,$customer_details,$amount_given_details){
        $last_month_date= date('Y-m-d',strtotime('last day of previous month'));
        $amount_entries = $this->amount_entries->select('SUM(paid_amount) as total_paid')->where("DATE_FORMAT(paid_date,'%Y-%m')<=","date_format(date_sub(current_date, INTERVAL 1 MONTH),'%Y-%m')")->where('customer_id =',$customer_id)->get()->getRowArray();
        if($amount_entries['total_paid'] == NULL || $amount_entries['total_paid'] == 0){
            $amount_entries['total_paid'] = 0;
            if(isset($amount_given_details['given_amount'])){
                $amount_given = $amount_given_details['given_amount'];
            }else{
                $amount_given = 0;
            }
        }

        $guessed_doj = strtotime ($customer_details['guessed_doj'] )  ;
		$guessed_doj = strtotime(date('Y-m-01',$guessed_doj));
		$today_date = strtotime(date('Y-m-01'));
		$cm=0;

        while($guessed_doj <= $today_date)
        {
            $guessed_doj = strtotime ( '+1 month' , $guessed_doj ) ;
            $cm++;
        }

        $total_paid = $amount_entries['total_paid'];
		$total_amount_had_paid = $cm * $customer_details['due_amount'];
		$pending_amount = $total_amount_had_paid-$total_paid;

        if($pending_amount < 0){
			$excess_amount = abs($pending_amount);
			$pending_amount = 0;			
		}else {	
			$excess_amount = 0;
		}
		
		if(!empty($amount_given_details['given_amount']) && $amount_given_details['given_amount'] == 0){
			$profit_this_month = 0;
		}else{
            $profit_this_month = 0;
		}

        $res['total_paid'] = 0;
		$res['had_paid'] = $total_amount_had_paid;
		$res['end_pending'] = $pending_amount;
		$res['excess_amount'] = $excess_amount;
        $res['last_month_date'] = $last_month_date;
		$res['profit_last_month'] = 0;
        // echo "<pre>";print_r($res);exit();
        return $res;
    }

    public function months(){
        $months = [
            '10' => '10 Months',
            '11' => '11 Months',
            '15' => '15 Months',
            '20' => '20 Months',
            '21' => '21 Months',
            '22' => '22/25 Months',
            '25' => '25 Months',
        ];
        return $months;
    }

    public function start_months(){
        $limit = 12;
        $months = range(1,$limit);
        return $months;
    }

    public function customer_add(){
        if(!$this->loggedin){
            return redirect()->to('/login');
        }
        $collection_by = $this->collectionby->groupBy('id')->get()->getResultArray();
        $groups = $this->groups->orderBy('group_name','asc')->get()->getResultArray();
        $months = $this->months();
        $start_months = $this->start_months();

        $data = [
            'collection_by' => $collection_by,
            'groups' => $groups,
            'months' => $months,
            'start_months' => $start_months
        ];
        // echo "<pre>";print_r($start_months);exit();
        return view('layout/header').view('admin/customer_add',$data).view('layout/footer');
    }

    public function customer_insert(){

        if(!$this->loggedin){
            return redirect()->to('/login');
        }
        
        $rules = [
            'group' => [
                'label' => 'Group',
                'rules' => 'required'
            ],
            'customer_name' => [
                'label' => 'Customer Name',
                'rules' => 'required'
            ],
            'date_of_join' => [
                'label' => 'Date of Join',
                'rules' => 'required'
            ],
            'due_amount' => [
                'label' => 'Due Amount',
                'rules' => 'required'
            ],
            'months' => [
                'label' => 'Months',
                'rules' => 'required'
            ],
            'starts_month' => [
                'label' => 'Starts Month',
                'rules' => 'required'
            ],
            'collection_by' => [
                'label' => 'Collection By',
                'rules' => 'required'
            ],
            'amount_needed_on' => [
                'label' => 'Amount Needed on',
                'rules' => 'required'
            ],
        ];

        $data = $this->request->getPost();

        if (!$this->validateData($data, $rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validation->getErrors()
            ]);
        }

        $validData = $this->validation->getValidated();
        $validData['forc'] = $data['forc'] ?? null;
        $validData['book_name'] = $data['book_name'] ?? null;
        $validData['address'] = $data['address'] ?? null;
        $validData['job_details'] = $data['job_details'] ?? null;
        $validData['contact_no'] = $data['contact_no'] ?? null;
        $validData['joined_date_guessed'] = $data['joined_date_guessed'] ?? null;
        $validData['approximate_date'] = (!empty($data['approximate_date']) && !empty($data['approximate_date']) == 'on')?1:0;
        // $validData['joined_date_guessed'] = $data['joined_date_guessed'] ?? null;

        $doj_array = explode("-",$validData['joined_date_guessed']);

        $customer = $this->customers->select('MAX(month_si) as max_si')->where('month(guessed_doj)',$doj_array[1])->where('year(guessed_doj)',$doj_array['2'])->get()->getRowArray();

        $customer_address = [
            'group_id_no_need' => $validData['group'],
            'forc' => $validData['forc'],
            'customer_name' => $validData['customer_name'],
            'address' => $validData['address'],
            'job_details' => $validData['job_details'],
            'contact_no' => $validData['contact_no'],
            'statu' => 1
        ];

        $customer_address_update = [
            'group_id_no_need' => $validData['group'],
            'forc' => $validData['forc'],
            'address' => $validData['address'],
            'job_details' => $validData['job_details'],
            'contact_no' => $validData['contact_no'],
            'statu' => 1
        ];

        $get_customer_address = $this->customers_address->where('customer_name',$validData['customer_name'])->where('forc',$validData['forc'])->get()->getRowArray();

        if(!empty($get_customer_address)){
            $address = $this->customers_address->update($validData['customer_name'],$customer_address_update);
        }else{
            $address = $this->customers_address->insert($customer_address);
        }

        if($address == 1){
            $address_id = $get_customer_address['address_id'];
        }else{
            $address_id = $address;
        }

        $customer_save = [
            'group_id' => $validData['group'],
            'group_name' => $this->groups->find($validData['group'])['group_name'],
            'month_si' => !empty($customer['max_si'])?$customer['max_si']:0,
            'starts_with_months' => $validData['starts_month'],
            'book_name' => $validData['book_name'],
            'care_of_name' => $validData['forc'],
            'is_group_address' => !empty($data['address'])?1:0,
            'address_id' => $address_id,
            'real_doj' => $validData['date_of_join'],
            'guessed_doj' => $validData['joined_date_guessed'],
            'aprox_doj' => $validData['approximate_date'],
            'due_amount' => $validData['due_amount'],
            'months' => $validData['months'],
            'collection_by' => $validData['collection_by'],
            'amount_need_on' => $validData['amount_needed_on'],
            'current_status' => 'running',
            'status' => 1, 
        ];

        $insert_customer = $this->customers->insert($customer_save);

        if ($insert_customer) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Customer added successfully'
            ]);
        }
    }

    public function group_details(){

        if(!$this->loggedin){
            return redirect()->to('/login');
        }

        $post = $this->request->getPost();
        $group_id = $post['group_id'];

        $get_group_details = $this->groups->find($group_id);

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $get_group_details
        ]);

    }

    public function customer_edit($id){
        if($this->loggedin){
            $customer_details = $this->customers->find($id);
            $customer_address = $this->customers_address->find($customer_details['address_id']);
            $amount_given_details = $this->amount_given_details->where(['customer_id' => $customer_details['customer_id']])->get()->getRowArray();
            $collection_by = $this->collectionby->groupBy('id')->get()->getResultArray();
            $group = $this->groups->orderBy('group_name','asc')->get()->getResultArray();
            $pending_details = $this->pending_details($id,$customer_details,$amount_given_details);
            $pending_details_lm = $this->pending_details_lm($id,$customer_details,$amount_given_details);
            $amount_entries = $this->amount_entries->where('amount_entries.customer_id',$customer_details['customer_id'])->get()->getResultArray();
            $months = $this->months();
            $start_months = $this->start_months();
            
            $data = [
                'customer_details' => $customer_details,
                'customer_address' => $customer_address,
                'amount_given_details' => $amount_given_details,
                'groups' => $group,
                'pending_details' => $pending_details,
                'pending_details_lm' => $pending_details_lm,
                'amount_entries' => $amount_entries,
                'collection_by' => $collection_by,
                'months' => $months,
                'start_months' => $start_months
            ];
            // echo "<pre>";print_r($data);exit();
            return view('layout/header').view('admin/customer_edit',$data).view('layout/footer');
        }else{
            return redirect()->to('/login');
        }
    }

    public function customer_update(){

        if(!$this->loggedin){
            return redirect()->to('/login');
        }
        
        $rules = [
            'group' => [
                'label' => 'Group',
                'rules' => 'required'
            ],
            'customer_name' => [
                'label' => 'Customer Name',
                'rules' => 'required'
            ],
            'date_of_join' => [
                'label' => 'Date of Join',
                'rules' => 'required'
            ],
            'due_amount' => [
                'label' => 'Due Amount',
                'rules' => 'required'
            ],
            'months' => [
                'label' => 'Months',
                'rules' => 'required'
            ],
            'starts_month' => [
                'label' => 'Starts Month',
                'rules' => 'required'
            ],
            'collection_by' => [
                'label' => 'Collection By',
                'rules' => 'required'
            ],
            'amount_needed_on' => [
                'label' => 'Amount Needed on',
                'rules' => 'required'
            ],
        ];

        $data = $this->request->getPost();

        if (!$this->validateData($data, $rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validation->getErrors()
            ]);
        }

        $validData = $this->validation->getValidated();
        $customer_id = $data['customer_id'];
        $validData['forc'] = $data['forc'] ?? null;
        $validData['book_name'] = $data['book_name'] ?? null;
        $validData['address'] = $data['address'] ?? null;
        $validData['job_details'] = $data['job_details'] ?? null;
        $validData['contact_no'] = $data['contact_no'] ?? null;
        $validData['joined_date_guessed'] = $data['joined_date_guessed'] ?? null;
        $validData['approximate_date'] = (!empty($data['approximate_date']) && !empty($data['approximate_date']) == 'on')?1:0;
        // $validData['joined_date_guessed'] = $data['joined_date_guessed'] ?? null;

        $doj_array = explode("-",$validData['joined_date_guessed']);

        $customer = $this->customers->select('MAX(month_si) as max_si')->where('month(guessed_doj)',$doj_array[1])->where('year(guessed_doj)',$doj_array['2'])->get()->getRowArray();

        $customer_address = [
            'group_id_no_need' => $validData['group'],
            'forc' => $validData['forc'],
            'customer_name' => $validData['customer_name'],
            'address' => $validData['address'],
            'job_details' => $validData['job_details'],
            'contact_no' => $validData['contact_no'],
            'statu' => 1
        ];

        $customer_address_update = [
            'group_id_no_need' => $validData['group'],
            'forc' => $validData['forc'],
            'address' => $validData['address'],
            'job_details' => $validData['job_details'],
            'contact_no' => $validData['contact_no'],
            'statu' => 1
        ];

        $get_customer_address = $this->customers_address->where('customer_name',$validData['customer_name'])->where('forc',$validData['forc'])->get()->getRowArray();

        if(!empty($get_customer_address)){
            $address = $this->customers_address->update($validData['customer_name'],$customer_address_update);
        }else{
            $address = $this->customers_address->insert($customer_address);
        }

        if($address == 1){
            $address_id = $get_customer_address['address_id'];
        }else{
            $address_id = $address;
        }

        $customer_update = [
            'group_id' => $validData['group'],
            'group_name' => $this->groups->find($validData['group'])['group_name'],
            'month_si' => !empty($customer['max_si'])?$customer['max_si']:0,
            'starts_with_months' => $validData['starts_month'],
            'book_name' => $validData['book_name'],
            'care_of_name' => $validData['forc'],
            'is_group_address' => !empty($data['address'])?1:0,
            'address_id' => $address_id,
            'real_doj' => $validData['date_of_join'],
            'guessed_doj' => $validData['joined_date_guessed'],
            'aprox_doj' => $validData['approximate_date'],
            'due_amount' => $validData['due_amount'],
            'months' => $validData['months'],
            'collection_by' => $validData['collection_by'],
            'amount_need_on' => $validData['amount_needed_on'],
            'current_status' => 'running',
            'status' => 1, 
        ];

        $insert_customer = $this->customers->update($customer_id,$customer_update);

        if ($insert_customer) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Customer Updated successfully'
            ]);
        }
    }

    public function groups_list(){
        if(!$this->loggedin){
            return redirect()->to('/login');
        }

        $groups = $this->groups->orderBy('group_name','ASC')->get()->getResultArray();
        $data = [
            'groups' => $groups
        ];

        return view('layout/header').view('admin/group_list',$data).view('layout/footer');
    }

    public function group_add(){
        if(!$this->loggedin){
            return redirect()->to('/login');
        }
        return view('layout/header').view('admin/group_add').view('layout/footer');
    }

    public function group_insert(){
        if(!$this->loggedin){
            return redirect()->to('/login');
        }

        $rules = [
            'group_name' => [
                'label' => "Group Name",
                'rules' => "required"
            ]
        ];

        $data = $this->request->getPost();

        if(!$this->validateData($data, $rules)){
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validation->getErrors()
            ]);
        }
        
        $validData = $this->validation->getValidated();
        $check_group = $this->groups->where('group_name',$validData['group_name'])->first();

        if($check_group){
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => ['Group Name Already exists']
            ]);
        }
        
        $validData['contact_no'] = $data['contact_no'] ?? null;
        $validData['address'] = $data['address'] ?? null;
        $validData['job_details'] = $data['job_details'] ?? null;

        $group_save = [
            'group_name' => $validData['group_name'],
            'address' => $validData['address'],
            'job_details' => $validData['job_details'],
            'contactno' => $validData['contact_no'],
            'status' => 1
        ];


        $insert_group = $this->groups->insert($group_save);

        if($insert_group){
            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Group Added successfully"
            ]);
        }else{
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => 'Group Not Saved'
            ]);
        }
    }
}