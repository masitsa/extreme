<?php 

		 //connect to database
        $connect = mysql_connect("192.168.170.16", "medical", "Med_centre890")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("hr", $connect)
                    or die("Could not select database".mysql_error());
		
			
			$sql1 = "select `employee`.`Employee_ID` AS `E_ID`, `employee`.`Employee_Code` AS `Employee_Code`,`employee`.`ID_No` AS `ID_No`,`employee`.`Title` AS `Title`,`employee`.`Surname` AS `Surname`,`employee`.`Other_Name` AS `Other_Name`,`employee`.`Gender` AS `Gender`,`employee`.`DOB` AS `DOB`,`employee`.`Nationality` AS `Nationality`,`employee`.`Marital_Status` AS `Marital_Status`,`dept`.`Dept` AS `Dept`,`emp_post`.`Post` AS `Post`,`contact`.`Tel_1` AS `Tel_1`,`contact`.`Address_2` AS `Address_2`,`contact`.`Postal_Code` AS `Postal_Code`,`contact`.`Email` AS `Email`,`contact`.`City` AS `City` from (((`employee` join `emp_post` on((`employee`.`Employee_ID` = `emp_post`.`Employee_ID`))) join `contact` on((`employee`.`Contact_ID` = `contact`.`Contact_ID`))) join `dept` on((`employee`.`Dept_ID` = `dept`.`Dept_ID`)))";
				echo $sql1.'<br />';
        $rs1 = mysql_query($sql1)
		
        or die ("unable to Select ".mysql_error());
		$row2 = mysql_num_rows($rs1);
		for($a=0; $a< $row2; $a++){
		    $E_ID=mysql_result($rs1, $a,'E_ID');
			$Employee_Code=mysql_result($rs1, $a,'Employee_Code');
			$ID_No=mysql_result($rs1, $a,'ID_No');
			$DOB=mysql_result($rs1, $a,'DOB');
			$Surname1=mysql_result($rs1, $a,'Surname');
			$Other_Name1=mysql_result($rs1, $a,'Other_Name');
			$Nationality=mysql_result($rs1, $a,'Nationality');
			$Marital_Status=mysql_result($rs1, $a,'Marital_Status');
			$Email=mysql_result($rs1, $a,'Email');
			$Gender=mysql_result($rs1, $a,'Gender');	$Title=mysql_result($rs1, $a,'Title');	$Tel_1=mysql_result($rs1, $a,'Tel_1');
			
			
		echo	$E_ID.'-->'.$Surname.'-->'.$Other_Name.'-->'.$E_ID.'-->'.$Employee_Code.'-->'.$DOB.'-->'.$Tel_1.'<br />';
		
		
		 //connect to database
        $connect = mysql_connect("localhost", "sumc_hms", "Oreo2014#")
                    or die("Unable to connect to MySQL".mysql_error());

        //selecting a database
        mysql_select_db("strathmore_population", $connect)
                    or die("Could not select database".mysql_error());
		
			$Surname=str_replace("'", "", "$Surname1");
			$Other_Name=str_replace("'", "", "$Other_Name1");
			$sql2 = "insert into staff (title,Surname,Other_names,DOB,contact,gender,Staff_Number,staff_system_id) values('$Title','$Surname','$Other_Name','$DOB','$Tel_1','$Gender','$Employee_Code','$E_ID')";
				echo $sql2.'<br />';
		     $rs2 = mysql_query($sql2)  or  die ("unable to Select ".mysql_error());
      
		
	}
?>