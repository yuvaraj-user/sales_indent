<?php
/**
 * 
 */
Class Sales_Indent_Details 
{
	public $conn;
	function __construct($conn) {
    	$this->conn = $conn;
  	}

	private function Get_Sql_Data($sql)
	{
		$result_array = array();
		$Sql_Details  = sqlsrv_prepare($this->conn, $sql);
		sqlsrv_execute($Sql_Details);
		while($sql_result = sqlsrv_fetch_array($Sql_Details,SQLSRV_FETCH_ASSOC))
		{
			$result_array[] = $sql_result;
		}
		return $result_array;
	}

	public function View_Cutsomer_Claim_Details($data)
	{
		$Filter_Data=@$data['Data'];
		parse_str($Filter_Data,$Filter_Data);
		$length=@$data['length'];
		$Offset=@$data['start'];
		$Zone_id=@$data['Zone_id'];
		$Region_Id=@$data['Region_Id'];
		$Terrirory_Id=@$data['Terrirory_Id'];
		$Status=@$data['Status'];
		$Customer=@$data['Customer'];
		$length=$length == '-1' ? "All" : $length;
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$sql="EXEC Sales_Indent_Customer_Collection_Details @Length='$length',@Offset='$Offset', @Emp_Id='".$Emp_Id."',@Date='".date('Y-m-d')."',@Dcode='".$Dcode."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Territory_Id='".$Terrirory_Id."',@Status='".$Status."',@Customer='".$Customer."'";
		$Result=$this->Get_Sql_Data($sql);
		$sno=$Offset+1;
		$recordsTotal=0;
		$resultarr=array();

		
		foreach($Result as $key=>$value)
		{
			$res['recordsTotal'] = @$value['TOTALROW'];
		 	$resarr 	= array();
		 	$resarr[]	= $sno++;
			$resarr[] 	= @$value['CUSTOMER'];
			$resarr[] 	= @$value['Date']->format('d-m-Y');	
				
			$resarr[] 	= @$value['UTR_NO'];
			$resarr[] 	= @$value['Amount'];		
			$resarr[] 	= '<div class="form-check row"> 
						<label class="form-check-label" for="">
						
					   </label>
					   <input class="form-check-input validate All_Validate" type="checkbox" name="validate['.@$value['Id'].']" value="'.@$value['Id'].'">
					    <input class="form-control" type="hidden" name="Id['.@$value['Id'].']" value="'.@$value['Id'].'">';


			
			$resultarr[] = $resarr;
		}
		$res=array();
		if(isset($data['draw']))
		{
			$res['draw'] = @$data['draw'];	
		}else
		{
			$res['draw'] = 1;	
		}
		$res['recordsFiltered'] = @$recordsTotal;
		$res['recordsTotal'] = @$recordsTotal;
	    $res['data'] = @$resultarr;
	    $res['sql'] = @$resultarr;
	    return $result = $res;
	    return json_encode($result);

	}


	public function Get_Customer_Collection_Details($data)
	{
		$Filter_Data=@$data['Data'];
		parse_str($Filter_Data,$Filter_Data);
		$length=@$data['length'];
		$Offset=@$data['start'];
		$Zone_id=@$data['Zone_id'];
		$Region_Id=@$data['Region_Id'];
		$Terrirory_Id=@$data['Terrirory_Id'];
		$Status=@$data['Status'];
		$Customer=@$data['Customer'];
		$length=$length == '-1' ? "All" : $length;
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$sql="EXEC Sales_Indent_Customer_Collection_Details @Length='$length',@Offset='$Offset', @Emp_Id='".$Emp_Id."',@Date='".date('Y-m-d')."',@Dcode='".$Dcode."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Territory_Id='".$Terrirory_Id."',@Status='1',@Customer='".$Customer."'";
		$Result=$this->Get_Sql_Data($sql);
		$sno=$Offset+1;
		$recordsTotal=0;
		$resultarr=array();

		
		foreach($Result as $key=>$value)
		{
			$res['recordsTotal'] = @$value['TOTALROW'];
		 	$resarr 	= array();
		 	$resarr[]	= $sno++;
			$resarr[] 	= @$value['CUSTOMER'];
			$resarr[] 	= @$value['Date']->format('d-m-Y');	
				
			$resarr[] 	= @$value['UTR_NO'];
			$resarr[] 	= @$value['Amount'];		
			$resarr[] 	= '<div class="form-check row"> 
						<label class="form-check-label" for="">
						
					   </label>
					   <input class="form-check-input validate All_Validate" type="checkbox" name="validate['.@$value['Id'].']" value="'.@$value['Id'].'">
					    <input class="form-control" type="hidden" name="Id['.@$value['Id'].']" value="'.@$value['Id'].'">';


			
			$resultarr[] = $resarr;
		}
		$res=array();
		if(isset($data['draw']))
		{
			$res['draw'] = @$data['draw'];	
		}else
		{
			$res['draw'] = 1;	
		}
		$res['recordsFiltered'] = @$recordsTotal;
		$res['recordsTotal'] = @$recordsTotal;
	    $res['data'] = @$resultarr;
	    $res['sql'] = @$resultarr;
	    return $result = $res;
	    return json_encode($result);

	}

	

	public function View_Sales_Indent_Details($data)
	{
		$Filter_Data=@$data['Data'];
		parse_str($Filter_Data,$Filter_Data);
		$length=@$data['length'];
		$Offset=@$data['start'];
		$ProductDivision=@$data['ProductDivision'];
		$Supply_Type=@$data['Supply_Type'];
		$Zone_id=@$data['Zone_id'];
		$Region_Id=@$data['Region_Id'];
		$Terrirory_Id=@$data['Terrirory_Id'];
		$Status=@$data['Status'];
		$Customer=@$data['Customer'];
		$QuotationType=@$data['QuotationType'];
		$SaleOrderType=@$data['SaleOrderType'];
		$length=$length == '-1' ? "All" : $length;
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$sql="EXEC Sales_Indent_Report_Details @Length='$length',@Offset='$Offset', @Emp_Id='".$Emp_Id."',@Product_Division='".$ProductDivision."',@Dcode='".$Dcode."',@Supply_Type='".$Supply_Type."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Terrirory_Id='".$Terrirory_Id."',@Status='".$Status."',@QuotationType='".$QuotationType."',@Customer='".$Customer."',@SaleOrderType='".$SaleOrderType."'";
		$Result=$this->Get_Sql_Data($sql);
		$sno=$Offset+1;
		$recordsTotal=0;
		$resultarr=array();
		foreach($Result as $key=>$value)
		{
			$recordsTotal = @$value['TOTALROW'];
		 	$resarr 	= array();
		 	$resarr[]	= $sno++;
			$resarr[] 	= @$value['ReqId'];
			$resarr[] 	= @$value['ReqDate'];	
			$resarr[] 	= @$value['RequestBy'];		
			$resarr[] 	= @$value['EMPLNAME'];		
			$resarr[] 	= @$value['ProductDivision'];
			$resarr[] 	= @$value['CropName'];
			$resarr[] 	= @$value['ZONENAME'];
			$resarr[] 	= @$value['REGIONNAME'];
			$resarr[] 	= @$value['TMNAME'];
			$resarr[] 	= @$value['CustomerCode'];
			$resarr[] 	= @$value['CustomerName'];
			$resarr[] 	= @$value['QuotationType'];
			$resarr[] 	= @$value['SaleOrderType'];
			$resarr[] 	= @$value['MaterialCode'];
			$resarr[] 	= @$value['QtyInBag'];
			$resarr[] 	= @$value['QtyInPkt'];
			$resarr[] 	= @$value['QtyInKg'];
			$resarr[] 	= @$value['Status_Text'];
			if(@$value['CurrentStatus'] == 1 &&  @$value['RejectionStatus'] == 1 && $length !='All'){
				$resarr[] 	= "<a href='edit_sales_indent_details?Id=".safe_encode(@$value['SalesIndentId'])."'><button type='button' class='btn btn-success'>Edit</i></button></a>";
			}else{
				$resarr[] 	= "";
			}
			$resultarr[] = $resarr;
		}
		$res=array();
		if(isset($data['draw']))
		{
			$res['draw'] = @$data['draw'];	
		}else
		{
			$res['draw'] = 1;	
		}
		$res['recordsFiltered'] = @$recordsTotal;
		$res['recordsTotal'] = @$recordsTotal;
	    $res['data'] = @$resultarr;
	    $res['sql'] = @$sql;
	    return $result = $res;
	    return json_encode($result);
	}

	public function Approve_Details_For_With_In_Limit($data)
	{
		$Filter_Data=@$data['Data'];
		parse_str($Filter_Data,$Filter_Data);
		$length=@$data['length'];
		$Offset=@$data['start'];
		$ProductDivision=@$data['ProductDivision'];
		$Supply_Type=@$data['Supply_Type'];
		$Zone_id=@$data['Zone_id'];
		$Region_Id=@$data['Region_Id'];
		$Terrirory_Id=@$data['Terrirory_Id'];
		$Status=1;
		$Customer=@$data['Customer'];
		$QuotationType=@$data['QuotationType'];
		$SaleOrderType=@$data['SaleOrderType'];
		$length=$length == '-1' ? "All" : $length;
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$sql="EXEC Sales_Indent_Report_Details_WITh_CROP_Limit @Length='$length',@Offset='$Offset', @Emp_Id='".$Emp_Id."',@Product_Division='".$ProductDivision."',@Dcode='".$Dcode."',@Supply_Type='".$Supply_Type."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Terrirory_Id='".$Terrirory_Id."',@Status='".$Status."',@QuotationType='".$QuotationType."',@Customer='".$Customer."',@SaleOrderType='".$SaleOrderType."'";
		$Result=$this->Get_Sql_Data($sql);
		$sno=$Offset+1;
		$recordsTotal=0;
		$resultarr=array();
		foreach($Result as $key=>$value)
		{
			if($value['Recommender_Emp_Id'] == '1' && $value['CurrentStatus'] == '1') 
			{
				$recordsTotal = @$value['TOTALROW'];
			 	$resarr 	= array();
			 	// $resarr[]	= $sno++;
				$resarr[] 	= @$value['ReqId'];
			
				$resarr[] 	= @$value['RequestBy']." / ". @$value['EMPLNAME'];
				$resarr[] 	= @$value['ReqDate'];
				$resarr[] 	= @$value['REGIONNAME'];			
				$resarr[] 	= @$value['CustomerCode'];
				$resarr[] 	= @$value['CustomerName'];
				$resarr[] 	= @$value['Customer_Balance'];
				$resarr[] 	= @$value['Customer_CREDIT_LIMIT'];
				$resarr[] 	= @$value['MaterialCode'];

				$supply_type_id = ($value['SupplyType'] == 'Direct Supply') ? 1 : 2;

				if(@$length !='All')
				{
					$resarr[] 	= '<input type="text" class="form-control QtyInBag" name="QtyInBag['.@$value['Id'].']" value="'.round(@$value['QtyInBag'],2).'" >
				<input type="hidden" class="form-control APDESIGN" name="APDESIGN['.@$value['Id'].']" value="'.@$value['APDESIGN'].'" >';
				$resarr[] 	= '<input type="text" class="form-control QtyInPkt" name="QtyInPkt['.@$value['Id'].']" value="'.round(@$value['QtyInPkt'],2).'"readonly><input type="hidden" class="MaterialQtyInPkt" value="'.round(@$value['MaterialQtyInPkt'],2).'"readonly>';
				$resarr[] 	= '<input type="text" class="form-control QtyInKg" name="QtyInKg['.@$value['Id'].']" value="'.round(@$value['QtyInKg'],2).'"readonly><input type="hidden" class="MaterialQtyInKg" value="'.round(@$value['MaterialQtyInKg'],2).'"readonly>
				<input type="hidden" class="form-control Customer_Credit_Limit" name="Customer_Credit_Limit['.@$value['Id'].']" value="'.@$value['Customer_CREDIT_LIMIT'].'">
				<input type="hidden" class="form-control" name="supply_type_id['.@$value['Id'].']" value="'.$supply_type_id.'">

				<input type="hidden" class="form-control CropId" name="CropId['.@$value['Id'].']" value="'.@$value['CropId'].'">';
				$resarr[] 	= '<input type="text" class="form-control Price" name="Price['.@$value['Id'].']" readonly value="'.round(@$value['Price'],2).'">';
				$resarr[] 	= '<input type="text" class="form-control Total_Price" readonly name="Total_Price['.@$value['Id'].']" value="'.round(@$value['Total_Price'],2).'">';

				}else{
					$resarr[] 	= round(@$value['QtyInBag'],2);
					$resarr[] 	= round(@$value['QtyInPkt'],2);
					$resarr[] 	= round(@$value['QtyInKg'],2);
					$resarr[] 	= round(@$value['Price'],2);
					$resarr[] 	= round(@$value['Total_Price'],2);
				}
				

				//discount get from the SAP TABLE  
				$sql="EXEC Sales_Indent_Get_Material_Discount_Price_FCM @Date='".$value['ReqDate']."',@Distribution_Channel='TR',@Product_Division='".$ProductDivision."',@Material='".$value['MaterialCode']."',@Customer='".$value['CustomerCode']."'";
				$sql_for_Season_details=sqlsrv_prepare($this->conn,$sql);
				sqlsrv_execute($sql_for_Season_details);
				$option="";
				$count=0;
				$result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
				$Discount=@$result['KBETR'] !='' ? @$result['KBETR'] : 0 ;
				
				// $Discount=@$value['Sales_Org']=="FC01" ? 5 : 0;

				if(@$value['Recommender_Emp_Id'] == 1 && @$length !='All'){
				$resarr[] 	= '<div class="form-check"> 
								<label class="form-check-label" for="">
								
							   </label>
							   <input class="form-check-input validate All_Validate m-auto" type="checkbox" name="validate['.@$value['Id'].']" value="'.@$value['Id'].'">
							    <input class="form-control" type="hidden" name="SalesIndentId['.@$value['Id'].']" value="'.@$value['SalesIndentId'].'">
							   <input class="form-control" type="hidden" name="Sales_Indent_line_No['.@$value['Id'].']" value="'.@$value['Id'].'">
							     <input class="form-control" type="hidden" name="EMPLOYEE_ID['.@$value['Id'].']" value="'.@$value['RequestBy'].'">
							     <input class="form-control" type="hidden" name="INDENT_NO['.@$value['Id'].']" value="'.@$value['ReqId'].'">
							   <input class="form-control" type="hidden" name="ACCOUNTNUM['.@$value['Id'].']" value="'.@$value['CustomerCode'].'">
							   <input class="form-control SALES_ORG" type="hidden" name="SALES_ORG['.@$value['Id'].']" value="'.@$value['Sales_Org'].'">
							   <input class="form-control Discount" type="hidden" name="Discount['.@$value['Id'].']" value="'.@$Discount.'">
							   <input class="form-control" type="hidden" name="DIST_CHANNEL['.@$value['Id'].']" value="'.@$value['Distribution_Channel'].'">
							   <input class="form-control" type="hidden" name="DIVISION['.@$value['Id'].']" value="'.@$value['Division'].'">
							   <input class="form-control" type="hidden" name="ZONE_ID['.@$value['Id'].']" value="'.@$value['ZoneId'].'">
							   <input class="form-control" type="hidden" name="REGION_ID['.@$value['Id'].']" value="'.@$value['RegionId'].'">
							   <input class="form-control" type="hidden" name="TM_ID['.@$value['Id'].']" value="'.@$value['TerritoryId'].'">
							   <input class="form-control" type="hidden" name="QUOTATION_TYPE['.@$value['Id'].']" value="'.@$value['QuotationType'].'">
							   <input class="form-control" type="hidden" name="SALEORDER_TYPE['.@$value['Id'].']" value="'.@$value['SaleOrderType'].'">
							   <input class="form-control" type="hidden" name="MATERIAL_NO['.@$value['Id'].']" value="'.@$value['MaterialCode'].'">
							   <input class="form-control" type="hidden" name="QUANTITY['.@$value['Id'].']" value="'.round(@$value['QtyInPkt'],2).'">
							   <input class="form-control" type="hidden" name="PLANT['.@$value['Id'].']" value="'.@$value['PlantId'].'">
							   <input class="form-control" type="hidden" name="INDENT_TYPES['.@$value['Id'].']" value="'.@$value['Indnet_Type'].'">
							   <input class="form-control" type="hidden" name="LGORT['.@$value['Id'].']" value="'.@$value['StorageLocation'].'">
							   <input class="form-control" type="hidden" name="VALID_TO_DATE['.@$value['Id'].']" value="'.date("Y-m-d",strtotime(@$value['ReqDate'])).'">
							   <input class="form-control" type="hidden" name="APPROVE_STATUS['.@$value['Id'].']" value="Approved">
							   <input class="form-control" type="hidden" name="RBM_EMP_VENDOR['.@$value['Id'].']" value="'.@$value['Validate_by'].'">
							   <input class="form-control" type="hidden" name="DBM_EMP_VENDOR['.@$value['Id'].']" value="'.$_SESSION['EmpID'].'">
							   <input class="form-control" type="hidden" name="RBM_MAIL_STATUS['.@$value['Id'].']" value="No">
							   <input class="form-control" type="hidden" name="DBM_MAIL_STATUS['.@$value['Id'].']" value="No">
							    <input class="form-control" type="hidden" name="Season_code['.@$value['Id'].']" value="'.@$value['Season_Code'].'">
							    <input class="form-control" type="hidden" name="PLACE['.@$value['Id'].']" value="'.@$value['Customer_Address'].'">
							    <input class="form-control" type="hidden" name="MOBILE_NO['.@$value['Id'].']" value="'.@$value['Customer_Tel_Number'].'">

							     <input class="form-control" type="hidden" name="Expected_date['.@$value['Id'].']" value="'.date("Ymd",strtotime(@$value['Expected_date'])).'">



							   </div>';
							}else{
								$resarr[] 	="";
							}
							
				
				$resultarr[] = $resarr;
				}
			}
		$res=array();
		if(isset($data['draw']))
		{
			$res['draw'] = @$data['draw'];	
		}else
		{
			$res['draw'] = 1;	
		}
		$res['recordsFiltered'] = @$recordsTotal;
		$res['recordsTotal'] = @$recordsTotal;
	    $res['data'] = @$resultarr;
	    $res['sql'] = @$sql;
	    return $result = $res;
	    return json_encode($result);
	}




	public function Approve_Details_For_With_In_Limit_Direct($data)
	{
		$Filter_Data=@$data['Data'];
		parse_str($Filter_Data,$Filter_Data);
		$length=@$data['length'];
		$Offset=@$data['start'];
		$ProductDivision=@$data['ProductDivision'];
		$Supply_Type=@$data['Supply_Type'];
		$Zone_id=@$data['Zone_id'];
		$Region_Id=@$data['Region_Id'];
		$Terrirory_Id=@$data['Terrirory_Id'];
		$Status=6;
		$Customer=@$data['Customer'];
		$QuotationType=@$data['QuotationType'];
		$SaleOrderType=@$data['SaleOrderType'];
		$length=$length == '-1' ? "All" : $length;
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$sql="EXEC Sales_Indent_Report_Details_WITh_CROP_Limit @Length='$length',@Offset='$Offset', @Emp_Id='".$Emp_Id."',@Product_Division='".$ProductDivision."',@Dcode='".$Dcode."',@Supply_Type='".$Supply_Type."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Terrirory_Id='".$Terrirory_Id."',@Status='".$Status."',@QuotationType='".$QuotationType."',@Customer='".$Customer."',@SaleOrderType='".$SaleOrderType."'";
		// echo $sql;exit;
		$Result=$this->Get_Sql_Data($sql);
		$sno=$Offset+1;
		$recordsTotal=0;
		$resultarr=array();
		foreach($Result as $key=>$value)
		{	
			if($value['Recommender_Level_2_Emp_Id'] == '1' && $value['CurrentStatus'] == '6') {
				$recordsTotal = @$value['TOTALROW'];
			 	$resarr 	= array();
			 	// $resarr[]	= $sno++;
				$resarr[] 	= @$value['ReqId'];
			
				$resarr[] 	= @$value['RequestBy']." / ". @$value['EMPLNAME'];
				$resarr[] 	= @$value['ReqDate'];
				$resarr[] 	= @$value['REGIONNAME'];			
				$resarr[] 	= @$value['CustomerCode'];
				$resarr[] 	= @$value['CustomerName'];
				$resarr[] 	= @$value['Customer_Balance'];
				$resarr[] 	= @$value['Customer_CREDIT_LIMIT'];
				$resarr[] 	= @$value['MaterialCode'];

				if(@$length !='All')
				{
					$resarr[] 	= '<input type="text" class="form-control QtyInBag" name="QtyInBag['.@$value['Id'].']" value="'.round(@$value['QtyInBag'],2).'" >
				<input type="hidden" class="form-control APDESIGN" name="APDESIGN['.@$value['Id'].']" value="'.@$value['APDESIGN'].'" >';
				$resarr[] 	= '<input type="text" class="form-control QtyInPkt" name="QtyInPkt['.@$value['Id'].']" value="'.round(@$value['QtyInPkt'],2).'"readonly><input type="hidden" class="MaterialQtyInPkt" value="'.round(@$value['MaterialQtyInPkt'],2).'"readonly>';
				$resarr[] 	= '<input type="text" class="form-control QtyInKg" name="QtyInKg['.@$value['Id'].']" value="'.round(@$value['QtyInKg'],2).'"readonly><input type="hidden" class="MaterialQtyInKg" value="'.round(@$value['MaterialQtyInKg'],2).'"readonly>
				<input type="hidden" class="form-control Customer_Credit_Limit" name="Customer_Credit_Limit['.@$value['Id'].']" value="'.@$value['Customer_CREDIT_LIMIT'].'">

				<input type="hidden" class="form-control CropId" name="CropId['.@$value['Id'].']" value="'.@$value['CropId'].'">';
				$resarr[] 	= '<input type="text" class="form-control Price" name="Price['.@$value['Id'].']" readonly value="'.round(@$value['Price'],2).'">';
				$resarr[] 	= '<input type="text" class="form-control Total_Price" readonly name="Total_Price['.@$value['Id'].']" value="'.round(@$value['Total_Price'],2).'">';

				}else{
					$resarr[] 	= round(@$value['QtyInBag'],2);
					$resarr[] 	= round(@$value['QtyInPkt'],2);
					$resarr[] 	= round(@$value['QtyInKg'],2);
					$resarr[] 	= round(@$value['Price'],2);
					$resarr[] 	= round(@$value['Total_Price'],2);
				}
				
				
				//discount get from the SAP TABLE  
				$sql="EXEC Sales_Indent_Get_Material_Discount_Price_FCM @Date='".$value['ReqDate']."',@Distribution_Channel='TR',@Product_Division='".$ProductDivision."',@Material='".$value['MaterialCode']."',@Customer='".$value['CustomerCode']."'";
				$sql_for_Season_details=sqlsrv_prepare($this->conn,$sql);
				sqlsrv_execute($sql_for_Season_details);
				$option="";
				$count=0;
				$result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
				$Discount=@$result['KBETR'] !='' ? @$result['KBETR'] : 0 ;
				// $Discount=@$value['Sales_Org']=="FC01" ? 5 : 0;

				if(@$value['Recommender_Level_2_Emp_Id'] == 1 && @$length !='All'){
				$resarr[] 	= '<div class="form-check"> 
								<label class="form-check-label" for="">
								
							   </label>
							   <input class="form-check-input validate All_Validate m-auto" type="checkbox" name="validate['.@$value['Id'].']" value="'.@$value['Id'].'">
							    <input class="form-control" type="hidden" name="SalesIndentId['.@$value['Id'].']" value="'.@$value['SalesIndentId'].'">
							   <input class="form-control" type="hidden" name="Sales_Indent_line_No['.@$value['Id'].']" value="'.@$value['Id'].'">
							     <input class="form-control" type="hidden" name="EMPLOYEE_ID['.@$value['Id'].']" value="'.@$value['RequestBy'].'">
							     <input class="form-control" type="hidden" name="INDENT_NO['.@$value['Id'].']" value="'.@$value['ReqId'].'">
							   <input class="form-control" type="hidden" name="ACCOUNTNUM['.@$value['Id'].']" value="'.@$value['CustomerCode'].'">
							   <input class="form-control SALES_ORG" type="hidden" name="SALES_ORG['.@$value['Id'].']" value="'.@$value['Sales_Org'].'">
							   <input class="form-control Discount" type="hidden" name="Discount['.@$value['Id'].']" value="'.@$Discount.'">
							   <input class="form-control" type="hidden" name="DIST_CHANNEL['.@$value['Id'].']" value="'.@$value['Distribution_Channel'].'">
							   <input class="form-control" type="hidden" name="DIVISION['.@$value['Id'].']" value="'.@$value['Division'].'">
							   <input class="form-control" type="hidden" name="ZONE_ID['.@$value['Id'].']" value="'.@$value['ZoneId'].'">
							   <input class="form-control" type="hidden" name="REGION_ID['.@$value['Id'].']" value="'.@$value['RegionId'].'">
							   <input class="form-control" type="hidden" name="TM_ID['.@$value['Id'].']" value="'.@$value['TerritoryId'].'">
							   <input class="form-control" type="hidden" name="QUOTATION_TYPE['.@$value['Id'].']" value="'.@$value['QuotationType'].'">
							   <input class="form-control" type="hidden" name="SALEORDER_TYPE['.@$value['Id'].']" value="'.@$value['SaleOrderType'].'">
							   <input class="form-control" type="hidden" name="MATERIAL_NO['.@$value['Id'].']" value="'.@$value['MaterialCode'].'">
							   <input class="form-control" type="hidden" name="QUANTITY['.@$value['Id'].']" value="'.round(@$value['QtyInPkt'],2).'">
							   <input class="form-control" type="hidden" name="PLANT['.@$value['Id'].']" value="'.@$value['PlantId'].'">
							   <input class="form-control" type="hidden" name="INDENT_TYPES['.@$value['Id'].']" value="'.@$value['Indnet_Type'].'">
							   <input class="form-control" type="hidden" name="LGORT['.@$value['Id'].']" value="'.@$value['StorageLocation'].'">
							   <input class="form-control" type="hidden" name="VALID_TO_DATE['.@$value['Id'].']" value="'.date("Y-m-d",strtotime(@$value['ReqDate'])).'">
							   <input class="form-control" type="hidden" name="APPROVE_STATUS['.@$value['Id'].']" value="Approved">
							   <input class="form-control" type="hidden" name="RBM_EMP_VENDOR['.@$value['Id'].']" value="'.@$value['Validate_by'].'">
							   <input class="form-control" type="hidden" name="DBM_EMP_VENDOR['.@$value['Id'].']" value="'.$_SESSION['EmpID'].'">
							   <input class="form-control" type="hidden" name="RBM_MAIL_STATUS['.@$value['Id'].']" value="No">
							   <input class="form-control" type="hidden" name="DBM_MAIL_STATUS['.@$value['Id'].']" value="No">
							    <input class="form-control" type="hidden" name="Season_code['.@$value['Id'].']" value="'.@$value['Season_Code'].'">
							    <input class="form-control" type="hidden" name="PLACE['.@$value['Id'].']" value="'.@$value['Customer_Address'].'">
							    <input class="form-control" type="hidden" name="MOBILE_NO['.@$value['Id'].']" value="'.@$value['Customer_Tel_Number'].'">

							       <input class="form-control" type="hidden" name="Expected_date['.@$value['Id'].']" value="'.date("Ymd",strtotime(@$value['Expected_date'])).'">

							   </div>';
							}else{
								$resarr[] 	="";
							}
							
				
				$resultarr[] = $resarr;
				}
			}

		$res=array();
		if(isset($data['draw']))
		{
			$res['draw'] = @$data['draw'];	
		}else
		{
			$res['draw'] = 1;	
		}
		$res['recordsFiltered'] = @$recordsTotal;
		$res['recordsTotal'] = @$recordsTotal;
	    $res['data'] = @$resultarr;
	    $res['sql'] = @$sql;
	    return $result = $res;
	    return json_encode($result);
	}




	public function Recommedation_Details_For_Limit_Exceed($data)
	{
		$Filter_Data=@$data['Data'];
		parse_str($Filter_Data,$Filter_Data);
		$length=@$data['length'];
		$Offset=@$data['start'];
		$ProductDivision=@$data['ProductDivision'];
		$Supply_Type=@$data['Supply_Type'];
		$Zone_id=@$data['Zone_id'];
		$Region_Id=@$data['Region_Id'];
		$Terrirory_Id=@$data['Terrirory_Id'];
		$Status=2;
		$Customer=@$data['Customer'];
		$QuotationType=@$data['QuotationType'];
		$SaleOrderType=@$data['SaleOrderType'];
		$length=$length == '-1' ? "All" : $length;
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$sql="EXEC Sales_Indent_Report_Details_With_Crop @Length='$length',@Offset='$Offset', @Emp_Id='".$Emp_Id."',@Product_Division='".$ProductDivision."',@Dcode='".$Dcode."',@Supply_Type='".$Supply_Type."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Terrirory_Id='".$Terrirory_Id."',@Status='".$Status."',@QuotationType='".$QuotationType."',@Customer='".$Customer."',@SaleOrderType='".$SaleOrderType."'";
		$Result=$this->Get_Sql_Data($sql);
		// echo "<pre>";print_r($Result);exit;
		$sno=$Offset+1;
		$recordsTotal=0;
		$resultarr=array();
		foreach($Result as $key=>$value)
		{
			if($value['Recommender_Emp_Id'] == 1 && $value['CurrentStatus'] == '2') {
				$recordsTotal = @$value['TOTALROW'];
			 	$resarr 	= array();
			 	// $resarr[]	= $sno++;
				$resarr[] 	= @$value['ReqId'];
			
				$resarr[] 	= @$value['RequestBy']." / ". @$value['EMPLNAME'];
				$resarr[] 	= @$value['ReqDate'];
				$resarr[] 	= @$value['REGIONNAME'];			
				$resarr[] 	= @$value['CustomerCode'];
				$resarr[] 	= @$value['CustomerName'];
				$resarr[] 	= @$value['Customer_Balance'];
				$resarr[] 	= @$value['Customer_CREDIT_LIMIT'];
				$resarr[] 	= @$value['MaterialCode'];

				$supply_type_id = ($value['SupplyType'] == 'Direct Supply') ? 1 : 2;


				if(@$length !='All')
				{
					$resarr[] 	= '<input type="text" class="form-control QtyInBag" name="QtyInBag['.@$value['Id'].']" value="'.round(@$value['QtyInBag'],2).'" readonly >
				<input type="hidden" class="form-control APDESIGN" name="APDESIGN['.@$value['Id'].']" value="'.@$value['APDESIGN'].'" >';
				$resarr[] 	= '<input type="text" class="form-control QtyInPkt" name="QtyInPkt['.@$value['Id'].']" value="'.round(@$value['QtyInPkt'],2).'" readonly><input type="hidden" class="MaterialQtyInPkt" value="'.round(@$value['MaterialQtyInPkt'],2).'"readonly>';
				$resarr[] 	= '<input type="text" class="form-control QtyInKg" name="QtyInKg['.@$value['Id'].']" value="'.round(@$value['QtyInKg'],2).'"readonly><input type="hidden" class="MaterialQtyInKg" value="'.round(@$value['MaterialQtyInKg'],2).'"readonly>
				<input type="hidden" class="form-control Customer_Credit_Limit" name="Customer_Credit_Limit['.@$value['Id'].']" value="'.@$value['Customer_CREDIT_LIMIT'].'">';
				$resarr[] 	= '<input type="text" class="form-control Price" name="Price['.@$value['Id'].']" readonly value="'.round(@$value['Price'],2).'">';
				$resarr[] 	= '<input type="text" class="form-control Total_Price" readonly name="Total_Price['.@$value['Id'].']" value="'.round(@$value['Total_Price'],2).'">
				<input type="hidden" class="form-control CropId" name="supply_type_id['.@$value['Id'].']" value="'.$supply_type_id.'">

				<input type="hidden" class="form-control CropId" name="CropId['.@$value['Id'].']" value="'.@$value['CropId'].'">';
				}else{
					$resarr[] 	= round(@$value['QtyInBag'],2);
					$resarr[] 	= round(@$value['QtyInPkt'],2);
					$resarr[] 	= round(@$value['QtyInKg'],2);
					$resarr[] 	= round(@$value['Price'],2);
					$resarr[] 	= round(@$value['Total_Price'],2);
				}

				//discount get from the SAP TABLE  
				$sql="EXEC Sales_Indent_Get_Material_Discount_Price_FCM @Date='".$value['ReqDate']."',@Distribution_Channel='TR',@Product_Division='".$ProductDivision."',@Material='".$value['MaterialCode']."',@Customer='".$value['CustomerCode']."'";
				$sql_for_Season_details=sqlsrv_prepare($this->conn,$sql);
				sqlsrv_execute($sql_for_Season_details);
				$option="";
				$count=0;
				$result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
				$Discount=@$result['KBETR'] !='' ? @$result['KBETR'] : 0 ;
				
				// $Discount=@$value['Sales_Org']=="FC01" ? 5 : 0;

				if(@$value['Recommender_Emp_Id'] == 1 && @$length !='All'){
				$resarr[] 	= '<div class="form-check"> 
								<label class="form-check-label" for="">
								
							   </label>
							   <input class="form-check-input validate All_Validate m-auto" type="checkbox" name="validate['.@$value['Id'].']" value="'.@$value['Id'].'">
							    <input class="form-control" type="hidden" name="SalesIndentId['.@$value['Id'].']" value="'.@$value['SalesIndentId'].'">
							   <input class="form-control" type="hidden" name="Sales_Indent_line_No['.@$value['Id'].']" value="'.@$value['Id'].'">
							    <input class="form-control SALES_ORG" type="hidden" name="SALES_ORG['.@$value['Id'].']" value="'.@$value['Sales_Org'].'">
							   <input class="form-control Discount" type="hidden" name="Discount['.@$value['Id'].']" value="'.@$Discount.'">
							   <input class="form-control" type="hidden" name="INDENT_TYPES['.@$value['Id'].']" value="'.@$value['Indnet_Type'].'">

							   <input class="form-control" type="hidden" name="EMPLOYEE_ID['.@$value['Id'].']" value="'.@$value['RequestBy'].'">


							     ';
							 }else{
							 	$resarr[] 	= '';
							 }
							
				
				$resultarr[] = $resarr;
				}

			}
		$res=array();
		if(isset($data['draw']))
		{
			$res['draw'] = @$data['draw'];	
		}else
		{
			$res['draw'] = 1;	
		}
		$res['recordsFiltered'] = @$recordsTotal;
		$res['recordsTotal'] = @$recordsTotal;
	    $res['data'] = @$resultarr;
	    return $result = $res;
	    return json_encode($result);
	}

	public function Sales_Indent_Validation_With_In_Limit($data)
	{
		$Filter_Data=@$data['Data'];
		parse_str($Filter_Data,$Filter_Data);
		$length=@$data['length'];
		$Offset=@$data['start'];
		$ProductDivision=@$data['ProductDivision'];
		$Supply_Type=@$data['Supply_Type'];
		$Zone_id=@$data['Zone_id'];
		$Region_Id=@$data['Region_Id'];
		$Terrirory_Id=@$data['Terrirory_Id'];
		$Status=6;
		$Customer=@$data['Customer'];
		$QuotationType=@$data['QuotationType'];
		$SaleOrderType=@$data['SaleOrderType'];
		$length=$length == '-1' ? "All" : $length;
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$sql="EXEC Sales_Indent_Report_Details @Length='$length',@Offset='$Offset', @Emp_Id='".$Emp_Id."',@Product_Division='".$ProductDivision."',@Dcode='".$Dcode."',@Supply_Type='".$Supply_Type."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Terrirory_Id='".$Terrirory_Id."',@Status='".$Status."',@QuotationType='".$QuotationType."',@Customer='".$Customer."',@SaleOrderType='".$SaleOrderType."'";
		$Result=$this->Get_Sql_Data($sql);
		$sno=$Offset+1;
		$recordsTotal=0;
		$resultarr=array();
		foreach($Result as $key=>$value)
		{
			$recordsTotal = @$value['TOTALROW'];
	 	$resarr 	= array();
	 	$resarr[]	= $sno++;
		$resarr[] 	= @$value['ReqId'];
	
		$resarr[] 	= @$value['RequestBy']." / ". @$value['EMPLNAME'];
		$resarr[] 	= @$value['ReqDate'];
		$resarr[] 	= @$value['REGIONNAME'];			
		$resarr[] 	= @$value['CustomerCode'];
		$resarr[] 	= @$value['CustomerName'];
		$resarr[] 	= @$value['Customer_Balance'];
		$resarr[] 	= @$value['Customer_CREDIT_LIMIT'];
		$resarr[] 	= @$value['MaterialCode'];
		$resarr[] 	= '<input type="text" class="form-control QtyInBag" name="QtyInBag['.@$value['Id'].']" value="'.round(@$value['QtyInBag'],2).'" ><input type="hidden" class="form-control APDESIGN" name="APDESIGN['.@$value['Id'].']" value="'.@$value['APDESIGN'].'" >';
		$resarr[] 	= '<input type="text" class="form-control QtyInPkt" name="QtyInPkt['.@$value['Id'].']" value="'.round(@$value['QtyInPkt'],2).'"readonly><input type="hidden" class="MaterialQtyInPkt" value="'.round(@$value['MaterialQtyInPkt'],2).'"readonly>';
		$resarr[] 	= '<input type="text" class="form-control QtyInKg" name="QtyInKg['.@$value['Id'].']" value="'.round(@$value['QtyInKg'],2).'"readonly><input type="hidden" class="MaterialQtyInKg" value="'.round(@$value['MaterialQtyInKg'],2).'"readonly>
		<input type="hidden" class="form-control Customer_Credit_Limit" name="Customer_Credit_Limit['.@$value['Id'].']" value="'.@$value['Customer_CREDIT_LIMIT'].'">';
		$resarr[] 	= '<input type="text" class="form-control Price" name="Price['.@$value['Id'].']" readonly value="'.round(@$value['Price'],2).'">';
		$resarr[] 	= '<input type="text" class="form-control Total_Price" readonly name="Total_Price['.@$value['Id'].']" value="'.round(@$value['Total_Price'],2).'">';
		
				//discount get from the SAP TABLE  
		$sql="EXEC Sales_Indent_Get_Material_Discount_Price_FCM @Date='".$value['ReqDate']."',@Distribution_Channel='TR',@Product_Division='".$ProductDivision."',@Material='".$value['MaterialCode']."',@Customer='".$value['CustomerCode']."'";
		$sql_for_Season_details=sqlsrv_prepare($this->conn,$sql);
		sqlsrv_execute($sql_for_Season_details);
		$option="";
		$count=0;
		$result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
		$Discount=@$result['KBETR'] !='' ? @$result['KBETR'] : 0 ;

		// $Discount=@$value['Sales_Org']=="FC01" ? 5 : 0;
		if(@$value['Recommender_Emp_Id'] == 0){
		$resarr[] 	= '<div class="form-check"> 
						<label class="form-check-label" for="">
						
					   </label>
					   <input class="form-check-input validate All_Validate m-auto" type="checkbox" name="validate['.@$value['Id'].']" value="'.@$value['Id'].'">
					    <input class="form-control" type="hidden" name="SalesIndentId['.@$value['Id'].']" value="'.@$value['SalesIndentId'].'">
					   <input class="form-control" type="hidden" name="Sales_Indent_line_No['.@$value['Id'].']" value="'.@$value['Id'].'">
					    <input class="form-control SALES_ORG" type="hidden" name="SALES_ORG['.@$value['Id'].']" value="'.@$value['Sales_Org'].'">
					   <input class="form-control Discount" type="hidden" name="Discount['.@$value['Id'].']" value="'.@$Discount.'">
					     ';

					 }else{
					 	$resarr[]="";
					 }
					
		
		$resultarr[] = $resarr;
		}
		$res=array();
		if(isset($data['draw']))
		{
			$res['draw'] = @$data['draw'];	
		}else
		{
			$res['draw'] = 1;	
		}
		$res['recordsFiltered'] = @$recordsTotal;
		$res['recordsTotal'] = @$recordsTotal;
	    $res['data'] = @$resultarr;
	    $res['sql'] = @$sql;
	    return $result = $res;
	    return json_encode($result);
	}

	public function Validation_Details_For_Limit_Exceed($data)
	{
		$Filter_Data=@$data['Data'];
		parse_str($Filter_Data,$Filter_Data);
		$length=@$data['length'];
		$Offset=@$data['start'];
		$ProductDivision=@$data['ProductDivision'];
		$Supply_Type=@$data['Supply_Type'];
		$Zone_id=@$data['Zone_id'];
		$Region_Id=@$data['Region_Id'];
		$Terrirory_Id=@$data['Terrirory_Id'];
		$Status=3;
		$Customer=@$data['Customer'];
		$QuotationType=@$data['QuotationType'];
		$SaleOrderType=@$data['SaleOrderType'];
		$length=$length == '-1' ? "All" : $length;
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$sql="EXEC Sales_Indent_Report_Details_With_Crop @Length='$length',@Offset='$Offset', @Emp_Id='".$Emp_Id."',@Product_Division='".$ProductDivision."',@Dcode='".$Dcode."',@Supply_Type='".$Supply_Type."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Terrirory_Id='".$Terrirory_Id."',@Status='".$Status."',@QuotationType='".$QuotationType."',@Customer='".$Customer."',@SaleOrderType='".$SaleOrderType."'";
		$Result=$this->Get_Sql_Data($sql);
		$sno=$Offset+1;
		$recordsTotal=0;
		$resultarr=array();
		foreach($Result as $key=>$value)
		{
			if($value['Recommender_Level_2_Emp_Id'] == 1 && $value['CurrentStatus'] == '3') {
				$recordsTotal = @$value['TOTALROW'];
			 	$resarr 	= array();
			 	// $resarr[]	= $sno++;
				$resarr[] 	= @$value['ReqId'];
			
				$resarr[] 	= @$value['RequestBy']." / ". @$value['EMPLNAME'];
				$resarr[] 	= @$value['ReqDate'];
				$resarr[] 	= @$value['REGIONNAME'];			
				$resarr[] 	= @$value['CustomerCode'];
				$resarr[] 	= @$value['CustomerName'];
				$resarr[] 	= @$value['Customer_Balance'];
				$resarr[] 	= @$value['Customer_CREDIT_LIMIT'];
				$resarr[] 	= @$value['MaterialCode'];
				

				if(@$length !='All')
				{
					$resarr[] 	= '<input type="text" class="form-control QtyInBag" name="QtyInBag['.@$value['Id'].']" value="'.round(@$value['QtyInBag'],2).'" ><input type="hidden" class="form-control APDESIGN" name="APDESIGN['.@$value['Id'].']" value="'.@$value['APDESIGN'].'" >';
				$resarr[] 	= '<input type="text" class="form-control QtyInPkt" name="QtyInPkt['.@$value['Id'].']" value="'.round(@$value['QtyInPkt'],2).'"readonly><input type="hidden" class="MaterialQtyInPkt" value="'.round(@$value['MaterialQtyInPkt'],2).'"readonly>';
				$resarr[] 	= '<input type="text" class="form-control QtyInKg" name="QtyInKg['.@$value['Id'].']" value="'.round(@$value['QtyInKg'],2).'"readonly><input type="hidden" class="MaterialQtyInKg" value="'.round(@$value['MaterialQtyInKg'],2).'"readonly>
				<input type="hidden" class="form-control Customer_Credit_Limit" name="Customer_Credit_Limit['.@$value['Id'].']" value="'.@$value['Customer_CREDIT_LIMIT'].'">';
				$resarr[] 	= '<input type="text" class="form-control Price" name="Price['.@$value['Id'].']" readonly value="'.round(@$value['Price'],2).'">';
				$resarr[] 	= '<input type="text" class="form-control Total_Price" readonly name="Total_Price['.@$value['Id'].']" value="'.round(@$value['Total_Price'],2).'">
				<input type="hidden" class="form-control CropId" name="CropId['.@$value['Id'].']" value="'.@$value['CropId'].'">
				<input type="hidden" class="form-control SupplyType_id" name="SupplyType['.@$value['Id'].']" value="'.@$value['SupplyType_id'].'">';
				}else{
					$resarr[] 	= round(@$value['QtyInBag'],2);
					$resarr[] 	= round(@$value['QtyInPkt'],2);
					$resarr[] 	= round(@$value['QtyInKg'],2);
					$resarr[] 	= round(@$value['Price'],2);
					$resarr[] 	= round(@$value['Total_Price'],2);
				}


				//discount get from the SAP TABLE  
				$sql="EXEC Sales_Indent_Get_Material_Discount_Price_FCM @Date='".$value['ReqDate']."',@Distribution_Channel='TR',@Product_Division='".$ProductDivision."',@Material='".$value['MaterialCode']."',@Customer='".$value['CustomerCode']."'";
				$sql_for_Season_details=sqlsrv_prepare($this->conn,$sql);
				sqlsrv_execute($sql_for_Season_details);
				$option="";
				$count=0;
				$result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
				$Discount=@$result['KBETR'] !='' ? @$result['KBETR'] : 0 ;

				// $Discount=@$value['Sales_Org']=="FC01" ? 5 : 0;

				if(@$value['Recommender_Level_2_Emp_Id'] == 1 && @$length !='All'){
				$resarr[] 	= '<div class="form-check"> 
								<label class="form-check-label" for="">
								
							   </label>
							   <input class="form-check-input validate All_Validate m-auto" type="checkbox" name="validate['.@$value['Id'].']" value="'.@$value['Id'].'">
							    <input class="form-control" type="hidden" name="SalesIndentId['.@$value['Id'].']" value="'.@$value['SalesIndentId'].'">
							   <input class="form-control" type="hidden" name="Sales_Indent_line_No['.@$value['Id'].']" value="'.@$value['Id'].'">
							    <input class="form-control SALES_ORG" type="hidden" name="SALES_ORG['.@$value['Id'].']" value="'.@$value['Sales_Org'].'">
							   <input class="form-control Discount" type="hidden" name="Discount['.@$value['Id'].']" value="'.@$Discount.'">
							   <input class="form-control" type="hidden" name="EMPLOYEE_ID['.@$value['Id'].']" value="'.@$value['RequestBy'].'">
							   <input class="form-control" type="hidden" name="INDENT_TYPES['.@$value['Id'].']" value="'.@$value['Indnet_Type'].'">

							     ';
							 }else{
							 	$resarr[]="";
							 }
							
				
				$resultarr[] = $resarr;
				}

			}
		$res=array();
		if(isset($data['draw']))
		{
			$res['draw'] = @$data['draw'];	
		}else
		{
			$res['draw'] = 1;	
		}
		$res['recordsFiltered'] = @$recordsTotal;
		$res['recordsTotal'] = @$recordsTotal;
	    $res['data'] = @$resultarr;
	    $res['Sql'] = @$sql;
	    return $result = $res;
	    return json_encode($result);
	}

	public function Approve_Details_For_With_Limit_Exceed($data)
	{
		$Filter_Data=@$data['Data'];
		parse_str($Filter_Data,$Filter_Data);
		$length=@$data['length'];
		$Offset=@$data['start'];
		$ProductDivision=@$data['ProductDivision'];
		$Supply_Type=@$data['Supply_Type'];
		$Zone_id=@$data['Zone_id'];
		$Region_Id=@$data['Region_Id'];
		$Terrirory_Id=@$data['Terrirory_Id'];
		$Status=4;
		$Customer=@$data['Customer'];
		$QuotationType=@$data['QuotationType'];
		$SaleOrderType=@$data['SaleOrderType'];
		$length=$length == '-1' ? "All" : $length;
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$sql="EXEC Sales_Indent_Report_Details_With_Crop @Length='$length',@Offset='$Offset', @Emp_Id='".$Emp_Id."',@Product_Division='".$ProductDivision."',@Dcode='".$Dcode."',@Supply_Type='".$Supply_Type."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Terrirory_Id='".$Terrirory_Id."',@Status='".$Status."',@QuotationType='".$QuotationType."',@Customer='".$Customer."',@SaleOrderType='".$SaleOrderType."'";
		$Result=$this->Get_Sql_Data($sql);
		$sno=$Offset+1;
		$recordsTotal=0;
		$resultarr=array();

		foreach($Result as $key=>$value)
		{
			if($value['Approver_Emp_Id']=='1' && $value['CurrentStatus']=='4') {
				$recordsTotal = @$value['TOTALROW'];
			 	$resarr 	= array();
			 	// $resarr[]	= $sno++;
				$resarr[] 	= @$value['ReqId'];
			
				$resarr[] 	= @$value['RequestBy']." / ". @$value['EMPLNAME'];
				$resarr[] 	= @$value['ReqDate'];
				$resarr[] 	= @$value['REGIONNAME'];			
				$resarr[] 	= @$value['CustomerCode'];
				$resarr[] 	= @$value['CustomerName'];
				$resarr[] 	= @$value['Customer_Balance'];
				$resarr[] 	= @$value['Customer_CREDIT_LIMIT'];
				$resarr[] 	= @$value['MaterialCode'];
				
				$supply_type_id = ($value['SupplyType'] == 'Direct Supply') ? 1 : 2;

				if(@$length !='All')
				{
					$resarr[] 	= '<input type="text" class="form-control QtyInBag" name="QtyInBag['.@$value['Id'].']" value="'.round(@$value['QtyInBag'],2).'" ><input type="hidden" class="form-control APDESIGN" name="APDESIGN['.@$value['Id'].']" value="'.@$value['APDESIGN'].'" >';
				$resarr[] 	= '<input type="text" class="form-control QtyInPkt" name="QtyInPkt['.@$value['Id'].']" value="'.round(@$value['QtyInPkt'],2).'"readonly><input type="hidden" class="MaterialQtyInPkt" value="'.round(@$value['MaterialQtyInPkt'],2).'"readonly>';
				$resarr[] 	= '<input type="text" class="form-control QtyInKg" name="QtyInKg['.@$value['Id'].']" value="'.round(@$value['QtyInKg'],2).'"readonly><input type="hidden" class="MaterialQtyInKg" value="'.round(@$value['MaterialQtyInKg'],2).'"readonly>
				<input type="hidden" class="form-control Customer_Credit_Limit" name="Customer_Credit_Limit['.@$value['Id'].']" value="'.@$value['Customer_CREDIT_LIMIT'].'">';
				$resarr[] 	= '<input type="text" class="form-control Price" name="Price['.@$value['Id'].']" readonly value="'.round(@$value['Price'],2).'">';
				$resarr[] 	= '<input type="text" class="form-control Total_Price" readonly name="Total_Price['.@$value['Id'].']" value="'.round(@$value['Total_Price'],2).'">
				<input type="hidden" class="form-control CropId" name="CropId['.@$value['Id'].']" value="'.@$value['CropId'].'">
				<input type="hidden" class="form-control SupplyType_id" name="supply_type_id['.@$value['Id'].']" value="'.$supply_type_id.'">';
				}else{
					$resarr[] 	= round(@$value['QtyInBag'],2);
					$resarr[] 	= round(@$value['QtyInPkt'],2);
					$resarr[] 	= round(@$value['QtyInKg'],2);
					$resarr[] 	= round(@$value['Price'],2);
					$resarr[] 	= round(@$value['Total_Price'],2);
				}


						//discount get from the SAP TABLE  
				$sql="EXEC Sales_Indent_Get_Material_Discount_Price_FCM @Date='".$value['ReqDate']."',@Distribution_Channel='TR',@Product_Division='".$ProductDivision."',@Material='".$value['MaterialCode']."',@Customer='".$value['CustomerCode']."'";
				$sql_for_Season_details=sqlsrv_prepare($this->conn,$sql);
				sqlsrv_execute($sql_for_Season_details);
				$option="";
				$count=0;
				$result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
				$Discount=@$result['KBETR'] !='' ? @$result['KBETR'] : 0 ;

				// $Discount=@$value['Sales_Org']=="FC01" ? 5 : 0;

				if(@$value['Approver_Emp_Id']=='1' && @$length !='All'){
				$resarr[] 	= '<div class="form-check"> 
								<label class="form-check-label" for="">
								
							   </label>
							   <input class="form-check-input validate All_Validate m-auto" type="checkbox" name="validate['.@$value['Id'].']" value="'.@$value['Id'].'">
							    <input class="form-control" type="hidden" name="SalesIndentId['.@$value['Id'].']" value="'.@$value['SalesIndentId'].'">
							   <input class="form-control" type="hidden" name="Sales_Indent_line_No['.@$value['Id'].']" value="'.@$value['Id'].'">
							     <input class="form-control" type="hidden" name="EMPLOYEE_ID['.@$value['Id'].']" value="'.@$value['RequestBy'].'">
							     <input class="form-control" type="hidden" name="INDENT_NO['.@$value['Id'].']" value="'.@$value['ReqId'].'">
							   <input class="form-control" type="hidden" name="ACCOUNTNUM['.@$value['Id'].']" value="'.@$value['CustomerCode'].'">
							   <input class="form-control SALES_ORG" type="hidden" name="SALES_ORG['.@$value['Id'].']" value="'.@$value['Sales_Org'].'">
							   <input class="form-control Discount" type="hidden" name="Discount['.@$value['Id'].']" value="'.@$Discount.'">
							   <input class="form-control" type="hidden" name="DIST_CHANNEL['.@$value['Id'].']" value="'.@$value['Distribution_Channel'].'">
							   <input class="form-control" type="hidden" name="DIVISION['.@$value['Id'].']" value="'.@$value['Division'].'">
							   <input class="form-control" type="hidden" name="ZONE_ID['.@$value['Id'].']" value="'.@$value['ZoneId'].'">
							   <input class="form-control" type="hidden" name="REGION_ID['.@$value['Id'].']" value="'.@$value['RegionId'].'">
							   <input class="form-control" type="hidden" name="TM_ID['.@$value['Id'].']" value="'.@$value['TerritoryId'].'">
							   <input class="form-control" type="hidden" name="QUOTATION_TYPE['.@$value['Id'].']" value="'.@$value['QuotationType'].'">
							   <input class="form-control" type="hidden" name="SALEORDER_TYPE['.@$value['Id'].']" value="'.@$value['SaleOrderType'].'">
							   <input class="form-control" type="hidden" name="MATERIAL_NO['.@$value['Id'].']" value="'.@$value['MaterialCode'].'">
							   <input class="form-control" type="hidden" name="QUANTITY['.@$value['Id'].']" value="'.round(@$value['QtyInPkt'],2).'">
							   <input class="form-control" type="hidden" name="PLANT['.@$value['Id'].']" value="'.@$value['PlantId'].'">
							   <input class="form-control" type="hidden" name="LGORT['.@$value['Id'].']" value="'.@$value['StorageLocation'].'">
							   <input class="form-control" type="hidden" name="VALID_TO_DATE['.@$value['Id'].']" value="'.date("Y-m-d",strtotime(@$value['ReqDate'])).'">
							   <input class="form-control" type="hidden" name="APPROVE_STATUS['.@$value['Id'].']" value="Approved">
							   <input class="form-control" type="hidden" name="RBM_EMP_VENDOR['.@$value['Id'].']" value="'.@$value['Validate_by'].'">
							   <input class="form-control" type="hidden" name="INDENT_TYPES['.@$value['Id'].']" value="'.@$value['Indnet_Type'].'">
							   <input class="form-control" type="hidden" name="DBM_EMP_VENDOR['.@$value['Id'].']" value="'.$_SESSION['EmpID'].'">
							   <input class="form-control" type="hidden" name="RBM_MAIL_STATUS['.@$value['Id'].']" value="No">
							   <input class="form-control" type="hidden" name="DBM_MAIL_STATUS['.@$value['Id'].']" value="No">
							    <input class="form-control" type="hidden" name="Season_code['.@$value['Id'].']" value="'.@$value['Season_Code'].'">
							    <input class="form-control" type="hidden" name="PLACE['.@$value['Id'].']" value="'.@$value['Customer_Address'].'">
							    <input class="form-control" type="hidden" name="MOBILE_NO['.@$value['Id'].']" value="'.@$value['Customer_Tel_Number'].'">

							     <input class="form-control" type="hidden" name="Expected_date['.@$value['Id'].']" value="'.date("Ymd",strtotime(@$value['Expected_date'])).'">

							     
							   </div>';

							}else{
								$resarr[]="";
							}

							
				
				$resultarr[] = $resarr;
				}

			}
		$res=array();
		if(isset($data['draw']))
		{
			$res['draw'] = @$data['draw'];	
		}else
		{
			$res['draw'] = 1;	
		}
		$res['recordsFiltered'] = @$recordsTotal;
		$res['recordsTotal'] = @$recordsTotal;
	    $res['data'] = @$resultarr;
	    $res['sql'] = @$sql;
	    return $result = $res;
	    return json_encode($result);
	}






	public function Limit_Exceed_Limit_Checking($data)
	{
		$Filter_Data=@$data['Data'];
		parse_str($Filter_Data,$Filter_Data);
		$length=@$data['length'];
		$Offset=@$data['start'];
		$ProductDivision=@$data['ProductDivision'];
		$Supply_Type=@$data['Supply_Type'];
		$Zone_id=@$data['Zone_id'];
		$Region_Id=@$data['Region_Id'];
		$Terrirory_Id=@$data['Terrirory_Id'];
		$Indent_num=@$data['Indent_num'];
		$status=@$data['status'];
		$Customer=@$data['Customer'];
		$QuotationType=@$data['QuotationType'];
		$SaleOrderType=@$data['SaleOrderType'];
		$length=$length == '-1' ? "All" : $length;
		$Emp_Id=isset($_SESSION['EmpID']) && ($_SESSION['EmpID'] !='Admin' || $_SESSION['EmpID'] !='SuperAdmin') ? $_SESSION['EmpID'] : '';
		$Dcode=$_SESSION['Dcode'];
		$sql="EXEC Sales_Indent_Report_Details_Limiit_Exceed_For_Finance_Check @Length='$length',@Offset='$Offset', @Emp_Id='".$Emp_Id."',@Product_Division='".$ProductDivision."',@Dcode='".$Dcode."',@Supply_Type='".$Supply_Type."',@Zone_id='".$Zone_id."',@Region_Id='".$Region_Id."',@Terrirory_Id='".$Terrirory_Id."',@Indent_num='".$Indent_num."',@QuotationType='".$QuotationType."',@Customer='".$Customer."',@SaleOrderType='".$SaleOrderType."',@status='".$status."'";
		$Result=$this->Get_Sql_Data($sql);
		$sno=$Offset+1;
		$recordsTotal=0;
		$resultarr=array();
		foreach($Result as $key=>$value)
		{
			$recordsTotal = @$value['TOTALROW'];
		 	$resarr 	= array();
		 	$resarr[]	= $sno++;
			$resarr[] 	= @$value['ReqId'];
			$resarr[] 	= @$value['CustomerCode'];	
			$resarr[] 	= @$value['Customer_Name'];		
			$resarr[] 	= @$value['District'];		
			$resarr[] 	= @$value['Post_Code'];
			$resarr[] 	= @$value['CREDIT_LIMIT_EXISTING'];
			$resarr[] 	= @$value['CREDIT_LIMIT_UTILIZE'];
			$resarr[] 	= @$value['CREDIT_LIMIT_AVAILABLE'];
			$resarr[] 	= @$value['CREDIT_LIMIT_NEW'];
			$resarr[] 	= @$value['APPROVED_VALUE'];
			$resarr[] 	= @$value['FINAL'];

			
			
			$resultarr[] = $resarr;
		}
		$res=array();
		if(isset($data['draw']))
		{
			$res['draw'] = @$data['draw'];	
		}else
		{
			$res['draw'] = 1;	
		}
		$res['recordsFiltered'] = @$recordsTotal;
		$res['recordsTotal'] = @$recordsTotal;
	    $res['data'] = @$resultarr;
	    $res['sql'] = @$sql;
	    return $result = $res;
	    return json_encode($result);
	}





	
}
 ?>