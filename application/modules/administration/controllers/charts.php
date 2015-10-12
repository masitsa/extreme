<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/auth/controllers/auth.php";

class Charts extends auth
{
	var $days;

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('charts_model');
		$this->load->model('dates_model');
		$this->load->model('reports_model');
		$this->days = 7;
	}
	
	function latest_patient_totals()
	{
		$period = $this->dates_model->last_seven_days();
		//var_dump($period);
		$patient_totals = '';
		$r = 0;
		$highest_bar = 0;
		
		foreach( $period as $day) 
		{
			$date = $day->format( 'Y-m-d');
			
			$total = $this->reports_model->get_queue_total($date);
			
			if($total > $highest_bar)
			{
				$highest_bar = $total;
			}
			
			if($r == 7)
			{
				$patient_totals .= $total;
			}
			
			else
			{
				$patient_totals .= $total.',';
			}
			
			$r++;
		}
		
		$result['highest_bar'] = $highest_bar;
		$result['bars'] = $patient_totals;
		
		echo json_encode($result);
	}
	
	function queue_total()
	{
		$highest_bar = 0;
		//nurse total
		$nurse_total = $this->reports_model->get_queue_total(NULL, 'visit_department.department_id = 7');
		$result['bars'] = $nurse_total;
		
		if($nurse_total > $highest_bar)
		{
			$highest_bar = $nurse_total;
		}
		
		//doctor total
		$doctor_total = $this->reports_model->get_queue_total(NULL, 'visit_department.department_id = 2');
		$result['bars'] .= $doctor_total.',';
		
		if($doctor_total > $highest_bar)
		{
			$highest_bar = $doctor_total;
		}
		
		//dental total
		$dental_total = $this->reports_model->get_queue_total(NULL, 'visit_department.department_id = 10');
		$result['bars'] .= $dental_total.',';
		
		if($dental_total > $highest_bar)
		{
			$highest_bar = $dental_total;
		}
		
		//lab total
		$lab_total = $this->reports_model->get_queue_total(NULL, 'visit_department.department_id = 4');
		$result['bars'] .= $lab_total.',';
		
		if($lab_total > $highest_bar)
		{
			$highest_bar = $lab_total;
		}
		
		//pharmacy total
		$pharmacy_total = $this->reports_model->get_queue_total(NULL, 'visit_department.department_id = 5');
		$result['bars'] .= $pharmacy_total;
		
		if($pharmacy_total > $highest_bar)
		{
			$highest_bar = $pharmacy_total;
		}
		
		$result['highest_bar'] = $highest_bar;
		
		echo json_encode($result);
	}
	
	function payment_methods()
	{
		//get all payment methods
		$methods_result = $this->reports_model->get_all_payment_methods();
		
		$totals = '';
		$highest_bar = 0;
		$r = 0;
		
		if($methods_result->num_rows() > 0)
		{
			$result = $methods_result->result();
			
			foreach($result as $res)
			{
				$payment_method_id = $res->payment_method_id;
				
				//get method total
				$total = $this->reports_model->get_payment_method_total($payment_method_id);
				
				if($total > $highest_bar)
				{
					$highest_bar = $total;
				}
				
				if($r == $methods_result->num_rows())
				{
					$totals .= $total;
				}
				
				else
				{
					$totals .= $total.',';
				}
				$r++;
			}
		}
		
		$result['highest_bar'] = $highest_bar;
		$result['bars'] = $totals;
		
		echo json_encode($result);
	}
	
	function patient_type_totals($timestamp)
	{
		$date = gmdate("Y-m-d", ($timestamp/1000));
		
		//initialize required variables
		$highest_bar = 0;
		
		//get outpatient total
		$total_outpatients = $this->reports_model->get_patient_type_total(0, $date);
		$total_inpatients = $this->reports_model->get_patient_type_total(1, $date);
		//mark the highest bar
		if($total_outpatients > $highest_bar)
		{
			$highest_bar = $total_outpatients;
		}
		
		//prep data for the particular visit type
		$result[strtolower('outpatients')] = $total_outpatients;
		$result[strtolower('inpatients')] = $total_inpatients;
		
		$result['highest_bar'] = $highest_bar;//var_dump($result['bars']);
		echo json_encode($result);
	}
	
	function patient_type_totals3($timestamp)
	{
		$date = gmdate("Y-m-d", ($timestamp/1000));
		
		//get all patient types
		$visits_result = $this->reports_model->get_all_visit_types();
		
		//initialize required variables
		$highest_bar = 0;
		
		if($visits_result->num_rows() > 0)
		{
			$result = $visits_result->result();
			
			foreach($result as $res)
			{				
				$visit_type_id = $res->visit_type_id;
				$visit_type_name = $res->visit_type_name;
				
				//get method total
				$total = $this->reports_model->get_visit_type_total($visit_type_id, $date);
				//mark the highest bar
				if($total > $highest_bar)
				{
					$highest_bar = $total;
				}
				
				//prep data for the particular visit type
				$result[strtolower($visit_type_name)] = $total;
			}
		}
		$result['highest_bar'] = $highest_bar;//var_dump($result['bars']);
		echo json_encode($result);
	}
	
	function patient_type_totals2()
	{
		//get last 7 days
		$period = $this->dates_model->last_seven_days();
		
		//get all patient types
		$visits_result = $this->reports_model->get_all_visit_types();
		
		//initialize required variables
		$highest_bar = 0;
		
		if($visits_result->num_rows() > 0)
		{
			$result = $visits_result->result();
			
			foreach($result as $res)
			{
				$totals = '';
				$r = 0;
				
				$visit_type_id = $res->visit_type_id;
				$visit_type_name = $res->visit_type_name;
				
				//fetch visit for each day for the last 6 days
				foreach( $period as $day) 
				{
					$date = $day->format( 'Y-m-d');
				
					//get method total
					$total = $this->reports_model->get_visit_type_total($visit_type_id, $date);
					
					//mark the highest bar
					if($total > $highest_bar)
					{
						$highest_bar = $total;
					}
					//echo $date." :: ".$r."<br/>";
					if($r == $this->days)
					{
						$totals .= $total;
					}
					
					else
					{
						$totals .= $total.',';
					}
					$r++;
				}
				
				//prep data for the particular visit type
				$result['bars'][strtolower($visit_type_name)] = $totals;
			}
		}
		$result['highest_bar'] = $highest_bar;//var_dump($result['bars']);
		echo json_encode($result);
	}
	
	function service_type_totals()
	{	
		//get all service types
		$services_result = $this->reports_model->get_all_service_types();
		
		//initialize required variables
		$totals = '';
		$names = '';
		$highest_bar = 0;
		$r = 1;
		
		if($services_result->num_rows() > 0)
		{
			$result = $services_result->result();
			
			foreach($result as $res)
			{
				$service_id = $res->service_id;
				$service_name = $res->service_name;
				
				//get service total
				$total = $this->reports_model->get_service_total($service_id);
				
				//mark the highest bar
				if($total > $highest_bar)
				{
					$highest_bar = $total;
				}
				
				if($r == $services_result->num_rows())
				{
					$totals .= $total;
					$names .= $service_name;
				}
				
				else
				{
					$totals .= $total.',';
					$names .= $service_name.',';
				}
				$r++;
			}
		}
		
		$result['total_services'] = $services_result->num_rows();
		$result['names'] = $names;
		$result['bars'] = $totals;
		$result['highest_bar'] = $highest_bar;
		echo json_encode($result);
	}
}