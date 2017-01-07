<?php
include ('../../fpdf16/fpdf.php');
//error_reporting(0);
$vid = $_GET['visit_id'];
$_SESSION['vid'] = $vid;
$personnel_id = $_SESSION['personnel_id'];
	$total="";$temp="";
	 $total1="";$temp1="";
$get = new invoice();
$rs = $get->get_user_details($personnel_id);
$num_rows = mysql_num_rows($rs);



if($num_rows > 0){
	$personnel_surname = mysql_result($rs, 0, "personnel_onames");
	$personnel_fname = mysql_result($rs, 0, "personnel_fname");
	$_SESSION['personnel_name'] = $personnel_surname." ".$personnel_fname;
}
	$get = new invoice();
	$rs = $get->get_patient2($_SESSION['vid']);
	$strath_no = mysql_result($rs, 0, "strath_no");
	$strath_type = mysql_result($rs, 0, "visit_type");
		$personnel_id = mysql_result($rs, 0,  	"personnel_id");
	$patient_number = mysql_result($rs, 0, "patient_number");
	$patient_insurance_id = mysql_result($rs, 0, "patient_insurance_id");
	


Class PDF extends FPDF{
	
	//page$$_SESSION[_SESSION[$_SERVER[ header
	function header(){
		
		
		$lineBreak = 20;
		//Colors of frames, background and Text
		$this->SetDrawColor(092, 123, 29);
		$this->SetFillColor(0, 232, 12);
		$this->SetTextColor(092, 123, 29);
		
		//thickness of frame (mm)
		//$this->SetLineWidth(1);
		//Logo
		$this->Image('../../images/strathmore.gif',10,8,45,15);
		//font
		$this->SetFont('Arial', 'B', 12);
		//title
		$this->Cell(0, 5, 'Strathmore University Medical Center', 0, 1, 'C');
		$this->Cell(0, 5, 'P.O. Box 59857 00200, Nairobi, Kenya', 0, 1, 'C');
		$this->Cell(0, 5, 'info@strathmore.edu', 0, 1, 'C');
		$this->Cell(0, 5, 'Madaraka Estate', 'B', 1, 'C');
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(0, 5, 'INVOICE', 'B', 1, 'C');
		//name
	$get = new invoice();
	$rs = $get->get_patient2($_SESSION['vid']);
	$strath_no = mysql_result($rs, 0, "strath_no");
	$strath_type = mysql_result($rs, 0, "visit_type_id");
		$personnel_idx = mysql_result($rs, 0,  	"personnel_id");
	$patient_number = mysql_result($rs, 0, "patient_number");
	$patient_insurance_id = mysql_result($rs, 0, "patient_insurance_id");
		$patient_insurance_number = mysql_result($rs, 0, "patient_insurance_number");
	$rsx = $get->get_user_details($personnel_idx);
$num_rowsx = mysql_num_rows($rsx);

if($num_rowsx > 0){
	$personnel_surname = mysql_result($rsx, 0, "personnel_onames");
	$personnel_fname = mysql_result($rsx, 0, "personnel_fname");
	$_SESSION['personnel_namex'] = $personnel_surname." ".$personnel_fname;
}
	$visit_date = $_SESSION['visit_date'];
	$name = "";
		$secondname = "";
		
	if($strath_type == 1){
							
		$get2 = new invoice();
		$rs2 = $get2->get_patient_2($strath_no);
		$rows = mysql_num_rows($rs2);//echo "rows = ".$rows; 
		
		$name = mysql_result($rs2, 0, "Other_names");
		$secondname = mysql_result($rs2, 0, "Surname");
		$patient_dob = mysql_result($rs2, 0, "DOB");
		$patient_sex = mysql_result($rs2, 0, "Gender");
	}
						
	else if($strath_type == 2){
		$connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("sumc", $connect)
                    or die("Could not select database".mysql_error()); 
		$sqlq = "select * from patients where strath_no='$strath_no' and dependant_id !=0";
	//echo PP.$sqlq;
		$rsq = mysql_query($sqlq)
        or die ("unable to Select ".mysql_error());
		$num_rowsp=mysql_num_rows($rsq);
		//echo $num_rowsp;
		
		//echo NUMP.$num_rowsp;
		if($num_rowsp>0){
	
			$get2 = new invoice();
		$rs2 = $get2->get_patient_4($strath_no);
		$rows = mysql_num_rows($rs2);//echo "rows = ".$rows;
			$name = mysql_result($rs2, 0, "names");
	//	$secondname = mysql_result($rs2, 0, "Surname");
		$patient_dob = mysql_result($rs2, 0, "DOB");
		$patient_sex = mysql_result($rs2, 0, "Gender");
	}
		else{
		$get2 = new invoice();
		$rs2 = $get2->get_patient_3($strath_no);
		$rows = mysql_num_rows($rs2);//echo "rows = ".$rows;
		//echo $rows;	
	
		$name = mysql_result($rs2, 0, "Other_names");
		$secondname = mysql_result($rs2, 0, "Surname");
		$patient_dob = mysql_result($rs2, 0, "DOB");
		$patient_sex = mysql_result($rs2, 0, "Gender");
		}
	}
	
	else{

		$name = mysql_result($rs, 0, "patient_othernames");
		$secondname = mysql_result($rs, 0, "patient_surname");
		$patient_dob = mysql_result($rs, 0, "patient_date_of_birth");
		$patient_sex = mysql_result($rs, 0, "gender_id");
		if($patient_sex == 1){
			$patient_sex = "Male";
		}
		else{
			$patient_sex = "Female";
		}
		
		$_SESSION['patient_sex'] == $patient_sex;
	}
	
		$name2 = $secondname." ".$name;
		$get_patient_invoice = new invoice;
		$get_invoice = $get_patient_invoice->get_patient($_SESSION['vid']);
		
		/*$patien_surname = mysql_result($get_invoice, 0, "patient_surname");
		$patient_middlename = mysql_result($get_invoice, 0, "patient_middlename");
		$patient_firstname = mysql_result($get_invoice, 0, "patient_firstname");
		$patient_number = mysql_result($get_invoice, 0, "patient_number");
		$personnel_surname = mysql_result($get_invoice, 0, "personnel_surname");
		$personnel_fname = mysql_result($get_invoice, 0, "personnel_fname");*/
		
		$doctor = $personnel_fname." ".$personnel_surname;
		
		$this->Ln(3);
		$this->Cell(100,5,'Patient Name:	'.$name2, 0, 0, 'L');
		$this->Cell(0,5,'Invoice Number:	INV/00'.$_SESSION['vid'], 0, 1, 'L');
		$this->Cell(100,5,'Patient Number:	'.$patient_number, 0, 0, 'L');
		$this->Cell(0,5,'Att. Doctor:	'.$_SESSION['personnel_namex'], 0, 1, 'L');
		
		//echo 'GULIANA'.$patient_insurance_id;
		
		if($patient_insurance_id!=0){
			
				$getf = new invoice();
$rsf = $getf->get_insurance_name($patient_insurance_id);
$num_rowsf = mysql_num_rows($rsf);
$insurance_name=mysql_result($rsf, 0, 'insurance_company_name');
		
		$this->Cell(100,5,'Insurance Name:'.$insurance_name, 'B', 0, 'L');
		$this->Cell(0,5,'Insurance Number:'.$patient_insurance_number , 'B', 1, 'L');	
		$this->Cell(0,5,'Invoice Date:	'.date("d-m-20y"), 'B', 1, 'L');
	}
	else{
			$this->Cell(0,5,'Invoice Date:	'.date("d-m-20y"), 'B', 1, 'L');
		
		}
	
	}
	
	//page footer
	function footer(){
		
		//position 1.5cm from Bottom
		$this->SetY(-15);
		//set Text color to gray
		$this->SetTextColor(128);
		//font
		$this->SetFont('Arial', 'I', 8);
		//page number
		$this->Cell(0,5,'Served By:	'.$_SESSION['personnel_name'], 0, 0, 'L');
		$this->Cell(0, 5, 'Page '.$this->PageNo().'/{nb}',0,1,"C");
		
		$this->Ln(-30);
$this->SetFont('Times', 'B', 13);
$this->Cell(0,10,"Patient Signature",'B',1,'L', $fill);
$this->SetFont('Times', '', 10);
$this->Cell(0,40,"",0,1,'L', $fill);
	}
}


$v_type = new invoice;
$v_type_rs =$v_type->get_visit_type($vid);
$num_type= mysql_num_rows($v_type_rs);
if($num_type >0){
	$visit_t = mysql_result($v_type_rs, 0 ,"visit_type");
}
$get_charge = new invoice();
$rs = $get_charge->get_invoice($vid,$visit_t);
$num_rows = mysql_num_rows($rs);

$sqlf= "SELECT * FROM visit_type WHERE  visit_type_id= $visit_t"; //echo $sqlf;
    $rs21f = mysql_query($sqlf)
        or die ("unable to Select ".mysql_error());
$num_type0= mysql_num_rows($rs21f);
$visit_type_name = mysql_result($rs21f, 0 ,"visit_type_name");
////echo VT.$visit_type_name;


$sqlfa= "SELECT * FROM visit WHERE  visit_id= $vid"; //echo $sqlf;
    $rs21fa = mysql_query($sqlfa)
        or die ("unable to Select ".mysql_error());
$num_type0a= mysql_num_rows($rs21fa);
$patient_ida= mysql_result($rs21fa, 0 ,"patient_id");
////echo VT.$visit_type_name;


$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setFont('Times', '', 12);
$pdf->SetFillColor(174, 255, 187); //226, 225, 225

//HEADER
$billTotal = 0;
$linespacing = 2;
$majorSpacing = 7;
$pageH = 4;
$width = 25;
$units23 = 15;
$pdf->SetFont('Times','B',11);

$pdf->Cell(20,$pageH,'Service','B');
$pdf->Cell(40,$pageH,'Date/Time','B');
$pdf->Cell(60,$pageH,'Service Item','B');
$pdf->Cell($units23,$pageH,'Units','B');

$pdf->Cell($width,$pageH,'@','B',0,'C');
$pdf->Cell($width,$pageH,'Amount','B',1);
$width = 15;
//$pdf->Cell($width,$pageH,'Total','B',1);
$pdf->SetFont('Times','',10);
$pdf->Ln(2);
$pageH = 5;
$total = 0;
$width = 25;
$pdf->SetFillColor(174, 255, 187); //226, 225, 225

if($num_rows > 0){
	$a=0;
	for($r = 0; $r < $num_rows; $r++){
		
		$width = 25;
		$units23 = 15;
		$service = mysql_result($rs, $r, "service_name");
		$service_id1 = mysql_result($rs, $r, "service_id");
		$service_charge_name = mysql_result($rs, $r, "service_charge_name");
		$visit_charge_amount = mysql_result($rs, $r, "visit_charge_amount");
		$visit_charge_units = mysql_result($rs, $r, "visit_charge_units");
		$visit_charge_timestamp = mysql_result($rs, $r, "visit_charge_timestamp");
	//	$total = $total + ($visit_charge_amount * $visit_charge_units);
		
		$pdf->Cell(20,$pageH,$service,'B');
		$pdf->Cell(40,$pageH,$visit_charge_timestamp,'B');
		$pdf->Cell(60,$pageH,$service_charge_name,'B');
		$pdf->Cell($units23,$pageH,$visit_charge_units,'B');
		
	
if ($patient_insurance_id!=0)
{

$discounted_value="";
			
$sql1= "SELECT * FROM insurance_discounts WHERE insurance_id = (SELECT company_insurance_id FROM `patient_insurance` where patient_insurance_id  =$patient_insurance_id ) and service_id=$service_id1";
 //echo $sql1;
    $rs1 = mysql_query($sql1)
        or die ("unable to Select ".mysql_error());
$num_type1= mysql_num_rows($rs1);

$percentage = mysql_result($rs1,0, "percentage");
$amounts = mysql_result($rs1, 0, "amount");
$discounted_value="";	
		if($percentage==0){
			$discounted_value=$amounts;	
		$amount = $visit_charge_amount -$discounted_value;
$amoun_money=($amount * $visit_charge_units);
	$pdf->Cell($width,$pageH,$amount,'B',0,'C');
	$pdf->Cell($width,$pageH,$amoun_money,'B',1);	
	$width = 15;
		//$pdf->Cell($width,$pageH,'','B',1);
$total = $total + $amoun_money;
		}
		elseif($amounts==0){
			$discounted_value=$percentage;
			$amount = $visit_charge_amount *((100-$discounted_value)/100);
			$amoun_money=($amount * $visit_charge_units);
			$pdf->Cell($width,$pageH,$amount,'B',0,'C');
			$pdf->Cell($width,$pageH,$amoun_money,'B',1);
		$width = 15;
		//$pdf->Cell($width,$pageH,'','B',1);	
		$total = $total + $amoun_money;
}}

else{
	
		$amount=($visit_charge_amount * $visit_charge_units);
		//$amoun_money=($amount * $visit_charge_units);
		$pdf->Cell($width,$pageH,$visit_charge_amount,'B',0,'C');
		$pdf->Cell($width,$pageH,$amount,'B',1);
		$width = 15;
		//$pdf->Cell($width,$pageH,'','B',1);
		$total = $total + $amount;
}

}
}
$width = 45;
$units23 = 15;
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50,$pageH,"",'B');
//$pdf->Cell(45,$pageH,"",'B');
$pdf->Cell($units23,$pageH,"",'B');
$pdf->Cell($width,$pageH,"",'B',0,'C');

//$pdf->Cell($width,$pageH,"Total",'B');
$pdf->Cell($width,$pageH,"Total",'B');
$pdf->Cell($width,$pageH,'KSH.  '.$total,'B');

$_SESSION['vid'] = NULL;
$_SESSION['personnel_name'] = NULL;
$pdf->Output();
?>