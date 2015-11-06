<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// require_once "./application/modules/auth/controllers/auth.php";
class Cloud  extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('cloud_model');
	}
	
	public function save_cloud_data()
	{
		$json = file_get_contents('php://input');
		//$json ='{"branch_code":"OSH","patients":[{"patient_id":"3224","patient_number":"OSH-000014","patient_date":"2015-09-22 23:54:28","title_id":"0","patient_surname":"Penina","patient_othernames":"Moraa","patient_date_of_birth":"0000-00-00","patient_picture":"","patient_bloodgroup":"","civil_status_id":"0","patient_address":"","patient_postalcode":"","patient_town":"","patient_phone1":"0702-672053","patient_phone2":"","patient_email":"","patient_national_id":"","religion_id":"0","gender_id":"2","patient_kin_othernames":"","patient_kin_sname":"","patient_kin_phonenumber1":"","patient_kin_phonenumber2":"","relationship_id":"0","strath_no":"","visit_type_id":"0","patient_status":"0","nurse_notes":"","dependant_id":"","strath_type_id":"0","created_by":"0","modified_by":"0","last_visit":"2015-11-04 00:00:00","last_modified":"2015-11-04 14:46:38","patient_delete":"0","deleted_by":null,"date_deleted":null,"department":null,"faculty":null,"insurance_company_id":null,"branch_code":"OSH","current_patient_number":"Osh014\/201","inpatient":"0","ward_id":null}],"visit":[{"visit_id":"485","visit_date":"2015-11-04","nurse_visit":"0","doc_visit":"0","dental_visit":"0","lab_visit":"0","pharmarcy":"0","schedule_id":"0","color_code_id":"0","visit_symptoms":null,"visit_objective_findings":null,"visit_assessment":null,"visit_plan":"","visit_time":"2015-11-04 14:46:37","visit_time_out":"0000-00-00 00:00:00","appointment_id":"0","time_start":"3:00 PM","time_end":"3:00 PM","consultation_type_id":null,"visit_type":"1","patient_id":"3224","close_card":"0","vitals_alert":null,"payment_insurance":"","payment_cash":"","payment_mobilemoney":"","payment_cheque":"","strath_no":"","lab_visit_comment":"","personnel_id":"46","patient_insurance_id":"0","patient_insurance_number":"","visit_delete":"0","date_deleted":null,"deleted_by":null,"bill_to_id":null,"total_payments":"0","total_debit_notes":"0","total_credit_notes":"0","consultation":"0","counseling":"0","dental":"0","ecg":"0","laboratory":"0","nursing_fee":"0","paediatrics":"0","pharmacy":"0","physician":"0","physiotherapy":"0","procedures":"0","radiology":"0","ultrasound":"0","cash":null,"cheque":null,"mpesa":null,"clinic_meds":null,"branch_code":"OSH","insurance_limit":"","visit_number":null,"inpatient":"0","ward_id":null,"held_by":null}],"visit_charge":[{"visit_charge_id":"1276","visit_id":"485","service_charge_id":"15314","visit_charge_timestamp":"2015-11-04 14:46:38","visit_charge_amount":"500","visit_charge_qty":"0","visit_charge_units":"1","visit_charge_results":"","visit_charge_comment":"","date":"2015-11-04","time":"00:00:00","visit_charge_delete":"0","deleted_by":null,"deleted_on":null,"created_by":"0","modified_by":null,"date_modified":null,"personnel_id":null}],"payments":[{"payment_id":"1","visit_id":"485","personnel_id":"0","amount_paid":"500","time":"2015-11-04 15:33:20","payment_method_id":"2","confirm_number":"","payment_type":"1","payment_created":"2015-11-04","payment_created_by":"0","payment_service_id":"8","transaction_code":"","payment_status":"1","modified_by":null,"modified_on":null,"approved_by":null,"date_approved":"2015-11-04","cancel":"0","cancel_action_id":null,"cancel_description":null,"cancelled_by":null,"cancelled_date":null},{"payment_id":"2","visit_id":"485","personnel_id":"0","amount_paid":"500","time":"2015-11-04 15:33:35","payment_method_id":"2","confirm_number":"","payment_type":"1","payment_created":"2015-11-04","payment_created_by":"0","payment_service_id":"8","transaction_code":"","payment_status":"1","modified_by":null,"modified_on":null,"approved_by":null,"date_approved":"2015-11-04","cancel":"0","cancel_action_id":null,"cancel_description":null,"cancelled_by":null,"cancelled_date":null},{"payment_id":"3","visit_id":"485","personnel_id":"0","amount_paid":"500","time":"2015-11-04 15:51:35","payment_method_id":"2","confirm_number":"","payment_type":"1","payment_created":"2015-11-04","payment_created_by":"0","payment_service_id":"8","transaction_code":"","payment_status":"1","modified_by":null,"modified_on":null,"approved_by":null,"date_approved":"2015-11-04","cancel":"0","cancel_action_id":null,"cancel_description":null,"cancelled_by":null,"cancelled_date":null},{"payment_id":"4","visit_id":"485","personnel_id":"0","amount_paid":"500","time":"2015-11-04 15:53:00","payment_method_id":"2","confirm_number":"","payment_type":"1","payment_created":"2015-11-04","payment_created_by":"0","payment_service_id":"8","transaction_code":"","payment_status":"1","modified_by":null,"modified_on":null,"approved_by":null,"date_approved":"2015-11-04","cancel":"0","cancel_action_id":null,"cancel_description":null,"cancelled_by":null,"cancelled_date":null},{"payment_id":"5","visit_id":"485","personnel_id":"0","amount_paid":"500","time":"2015-11-04 15:54:58","payment_method_id":"2","confirm_number":"","payment_type":"1","payment_created":"2015-11-04","payment_created_by":"0","payment_service_id":"8","transaction_code":"","payment_status":"1","modified_by":null,"modified_on":null,"approved_by":null,"date_approved":"2015-11-04","cancel":"0","cancel_action_id":null,"cancel_description":null,"cancelled_by":null,"cancelled_date":null}]}';
		
	   	$response = $this->cloud_model->save_visit_data($json);

	    /*$decoded = json_decode($json);
	    $patients = $decoded->patients;
	    $member = $patients[0];
		var_dump($member->patient_id);*/

		echo json_encode($response);
	}
	public function sync_up_petty_cash()
	{
		$json = file_get_contents('php://input');

	   	$response = $this->cloud_model->save_petty_cash_data($json);

		echo json_encode($response);

	}
	
	public function cron_sync_up()
	{
		//get all unsynced visits
		$unsynced_visits = $this->cloud_model->get_unsynced_visits();
		
		$patients = array();
		
		if($unsynced_visits->num_rows() > 0)
		{
			//delete all unsynced visits
			$this->db->where('sync_status', 0);
			if($this->db->delete('sync'))
			{
				foreach($unsynced_visits->result() as $res)
				{
					$sync_data = $res->sync_data;
					$sync_table_name = $res->sync_table_name;
					$branch_code = $res->branch_code;
					array_push($patients[$sync_table_name], $value);
					
					/*$response = $this->sync_model->syn_up_on_closing_visit($visit_id);
		
					if($response)
					{
					}*/
				}
				$patients['branch_code'] = $branch_code;
			}
		}
		
		//sync data
		$response = $this->cloud_model->send_unsynced_visits($patients);
		var_dump($response);
		if($response)
		{
		}
		
		else
		{
		}
	}
}
?>