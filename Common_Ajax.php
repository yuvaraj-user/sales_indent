<?php 
include '../auto_load.php';
include 'Send_Mail.php';
/*---------------------------Sathish Maznet--------------------------------------*/
/*---------------------------Supply Type and Material--------------------------------------*/
$Action=@$_REQUEST['Action'];
function Get_Employee_Details($conn,$Emp_Id)
{
  $Employee_Name_Sql    = sqlsrv_query($conn,"SPANP_Get_Employee_Name @Emp_Id='".@$Emp_Id."'");  
  $Employee_Name_Dets   = sqlsrv_fetch_array($Employee_Name_Sql);
  $Employee_Name   = $Employee_Name_Dets['Employee_Name'];
  return $Employee_Name;
}

function Get_Mail_Recipient_Details_For_Plant($Plant){
	global $conn;
	$Plant=trim($Plant);
 	$Mail_Sql="EXEC Get_Plant_Mail_Details @Plant_Code='".$Plant."'";
	$Mail_Connection=sqlsrv_query($conn,$Mail_Sql);
	$Mail_Array=array();
	while($Mail_Details = sqlsrv_fetch_array($Mail_Connection,SQLSRV_FETCH_ASSOC))
	 {	
	 	$Mail_Array[]=@$Mail_Details['Email'];
	 }
	 return $Mail_Array;
}

function Get_STO_Mail_Recipient_Details($Emp_Id,$product_division,$CropId){
	global $conn;
  $Mail_Sql="STO_Indnet_Recipient_Details_WITH_Crop @Emp_Id='".$Emp_Id."',@ProductDivision='".@$product_division."',@CropId='".@$CropId."'";
$Mail_Connection=sqlsrv_query($conn,$Mail_Sql);
$Mail_Details=sqlsrv_fetch_array($Mail_Connection);
return array("Level_1"=>@$Mail_Details['Level_1'],"Level_2"=>@$Mail_Details['Level_2'],"Level_3"=>@$Mail_Details['Level_3'],"Level_4"=>@$Mail_Details['Level_4']);
}



function strToHex($string){
    $hex='';
    for ($i=0; $i < strlen($string); $i++){
        $hex .= dechex(ord($string[$i]));
    }
    return $hex;
}


function hexToStr($hex){
    $string='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
    return $string;
}


function user_role_levels($CropId,$Supply_Type_id,$requestor_id)
{
	global $conn;
	$sub_type = ($Supply_Type_id == 1) ? 'Direct_Supply' : 'C&F_Supply'; 
	$sql = "SELECT Recommender_Emp_Id as level_1,Recommender_Level_2_Emp_Id as level_2,Approver_Emp_Id as level_3
	from RASI_Role_Mapping_With_Crop WHERE Requester_Emp_Id = '".$requestor_id."' AND Crop = '".$CropId."' AND Sub_Type = '".$sub_type."'";
	$sql_process = sqlsrv_query($conn,$sql);
	$sql_res     = sqlsrv_fetch_array($sql_process,SQLSRV_FETCH_ASSOC);
	return $sql_res;
}

function sto_user_role_levels($CropId,$requestor_id)
{
	global $conn;
	$sql = "SELECT Recommender_Emp_Id as level_1,Recommender_Level_2_Emp_Id as level_2,Approver_Emp_Id as level_3
	from RASI_Role_Mapping_With_Crop_STO WHERE Requester_Emp_Id = '".$requestor_id."' AND Crop = '".$CropId."'";
	$sql_process = sqlsrv_query($conn,$sql);
	$sql_res     = sqlsrv_fetch_array($sql_process,SQLSRV_FETCH_ASSOC);
	return $sql_res;
}


function Post_SAP_Data($data,$url)
{
	$data="JSON=".$data;
	$ch = curl_init();
	$options = array(
	    CURLOPT_URL            => $url,
	    CURLOPT_RETURNTRANSFER => true,
	    CURLINFO_HEADER_OUT    => true,
	    CURLOPT_POST           => true,
	    CURLOPT_POSTFIELDS     => $data,
	    CURLOPT_FOLLOWLOCATION => true,
	    CURLOPT_ENCODING       => "",
	    CURLOPT_AUTOREFERER    => true,
	    CURLOPT_CONNECTTIMEOUT => 120,
	    CURLOPT_TIMEOUT        => 120,
	    CURLOPT_MAXREDIRS      => 10,
	   // CURLOPT_HTTPHEADER     => array('Content-Type: application/json','Content-Length: ' . strlen($data))
	);
	curl_setopt_array( $ch, $options );
	 $response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$res = curl_getinfo($ch);
	if ( $httpCode != 200 ){
	   // echo "Return code is {$httpCode} \n".curl_error($ch);
		return array('Status'=>0,'Message'=>curl_error($ch));
	} else {
	    //echo "<pre>".htmlspecialchars($response)."</pre>";
		return array('Status'=>1,'Message'=>$response);
	}
}

function Get_Mail_Recipient_Details($Emp_Id,$product_division,$Type,$CropId){
	global $conn;
 $Mail_Sql="Sales_Indnet_Recipient_Details_WITH_CROP @Emp_Id='".$Emp_Id."',@ProductDivision='".@$product_division."',@Type='".@$Type."',@CropId='".@$CropId."'";
 ///print_r($Mail_Sql);
$Mail_Connection=sqlsrv_query($conn,$Mail_Sql);
$Mail_Details=sqlsrv_fetch_array($Mail_Connection);

return array("Level_1"=>@$Mail_Details['Level_1'],"Level_2"=>@$Mail_Details['Level_2'],"Level_3"=>@$Mail_Details['Level_3'],"Level_4"=>@$Mail_Details['Level_4']);
}

if($Action =="Get_Plant_Details"){
	$Supply_Type=@$_POST['supply_type'];
	$Plant_Code=@$_POST['Plant_Code'];
	
	$Supply_Type=$Supply_Type=='1' ? 'Direct_Supply' : 'C_F_Supply';
$sql="EXEC [Sales_Indent_Plant_Details] @Supply_Type='$Supply_Type'";
  $sql_for_plant_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_plant_details);
  $option='<option value="">Select Plant</option>';
 while($result = sqlsrv_fetch_array($sql_for_plant_details,SQLSRV_FETCH_ASSOC))
 {	$selected="";
	 $selected=$Plant_Code==$result["PlantCode"] ? "Selected" : "";
 	$option.='<option value="'.$result["PlantCode"].'" '.$selected.'>'.$result["PlantName"].'</option>';
 }

 echo  json_encode(array('data' => $option));
}else if($Action =="Get_Quotation_Type_Details"){
$Supply_Type=@$_POST['supply_type'];
$Product_Division=@$_POST['Product_Division'];
$Supply_Type=$Supply_Type=='1' ? 'Direct supply' : 'C&F Supply';
$sql="EXEC Sales_Indent_Quotation_Details @Product_Division='".@$Product_Division."',@Supply_Type='".@$Supply_Type."'";
  $sql_for_Quotation_Type_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_Quotation_Type_details);
  $option='';
  $count=0;
 while($result = sqlsrv_fetch_array($sql_for_Quotation_Type_details,SQLSRV_FETCH_ASSOC))
 {	$selected="";
	$count++;
 	$option.='<option value="'.$result["Type"].'" '.$selected.'>'.$result["Descrpition"].'</option>';
 }
	$Result='';
	if($count >1)
	{
		$Result='<option value="">Select Quotation Type</option>';
		$Result.=$option;
	}else if($count ==1 )
	{
		$Result.=$option;
	}else 
	{
		$Result='<option value="">No Record Found </option>';
	}

 echo  json_encode(array('data' => $Result));
}else if($Action =="Get_Sales_Order_Type_Details"){
$Supply_Type=@$_POST['Supply_Type'];
$Product_Division=@$_POST['Product_Division'];
$Supply_Type=$Supply_Type=='1' ? 'Direct supply' : 'C&F Supply';
$sql="EXEC Sales_Indent_SaleOrder_Details @Product_Division='".@$Product_Division."',@Supply_Type='".@$Supply_Type."'";
  $sql_for_Quotation_Type_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_Quotation_Type_details);
  $option='';
  $count=0;
 while($result = sqlsrv_fetch_array($sql_for_Quotation_Type_details,SQLSRV_FETCH_ASSOC))
 {	$selected="";
	$count++;
 	$option.='<option value="'.$result["Type"].'" '.$selected.'>'.$result["Descrpition"].'</option>';
 }
	$Result='';
	if($count >1)
	{
		$Result='<option value="">Select Sales Order Type</option>';
		$Result.=$option;
	}else if($count ==1 )
	{
		$Result.=$option;
	}else 
	{
		$Result='<option value="">No Record Found </option>';
	}

 echo  json_encode(array('data' => $Result));
}else if($Action =="Get_STO_Plant_Details"){
	$plant_id=@$_POST['plant_id'];
	$product_division=@$_POST['product_division'];
	$Type=@$_POST['Type'];
	$Statement_Type=@$_POST['Statement_Type'];
	$Emp_Id=$_SESSION['EmpID'];
	$Region_Code=@$_POST['Region_Code'];
    $sql="EXEC STO_Plant_Details @Product_Division='".@$product_division."', @Type='".@$Type."', @Region_Code='".@$Region_Code."',@Statement_Type='".$Statement_Type."',@Emp_Id='".$Emp_Id."'";
  $sql_for_plant_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_plant_details);
  
  $option='';

  $count=0;
 while($result = sqlsrv_fetch_array($sql_for_plant_details,SQLSRV_FETCH_ASSOC))
 {	
 	$count++;
 	$selected="";
	 // $selected=$plant_id==$result["Plant_Code"] ? "Selected" : "";
 	$option.='<option value="'.$result["Plant_Code"].'" >'.$result["Plant_Name"].'</option>';
 }
$output="";
 if($count >1){
 	$output='<option value="" selected>Select Plant</option>';
 	$output.=$option;
 }else if($count == 1){
 	$output=$option;
 }else if($count == 0){
 	$output='<option value="">No Records Found</option>';
 }

 echo  json_encode(array('data' => $output,'sql'=>$sql));
}else if($Action =="Get_Storage_Location_Details"){
	$plant_id=@$_POST['plant_id'];
	$product_division=@$_POST['product_division'];
	$Type=@$_POST['Type'];
	$Statement_Type=@$_POST['Statement_Type'];
	$Emp_Id=$_SESSION['EmpID'];
	$Region_Code=@$_POST['Region_Code'];
	$Plant_Code=@$_POST['plant_id'];
    $sql="EXEC SalesIndent_Storage_Location_Details @Product_Division='".@$product_division."', @Type='".@$Type."', @Region_Code='".@$Region_Code."',@Statement_Type='".$Statement_Type."',@Emp_Id='".$Emp_Id."',@Plant_Code='".$Plant_Code."'";
  $sql_for_plant_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_plant_details);
  
  $option='';

  $count=0;
 while($result = sqlsrv_fetch_array($sql_for_plant_details,SQLSRV_FETCH_ASSOC))
 {	
 	$count++;
 	$selected="";
	 $selected=$plant_id==$result["Storage_Location"] ? "Selected" : "";
 	$option.='<option value="'.$result["Storage_Location"].'" '.$selected.'>'.$result["Storage_Location"].'-'.$result["Location_Desc"].'</option>';
 }
$output="";
 if($count >1){
 	$output='<option value="">Select Plant</option>';
 	$output.=$option;
 }else if($count == 1){
 	$output=$option;
 }else if($count == 0){
 	$output='<option value="">No Records Found</option>';
 }

 echo  json_encode(array('data' => $output,'sql'=>$sql));
}











else if($Action =="Get_Plant_Warhouse_Details"){
	$plant_id=@$_POST['plant_id'];
	$product_division=@$_POST['product_division'];
	$zone_id=@$_POST['zone_id'];
	$region_id=@$_POST['region_id'];
	
$sql="EXEC [spSel_CF_RegionWarehouse_Filter] @CropType='".@$product_division."',@RegionId='".@$region_id."',@Zone_ID='".@$zone_id."'";
  $sql_for_plant_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_plant_details);
  
  $option='';

  $count=0;
 while($result = sqlsrv_fetch_array($sql_for_plant_details,SQLSRV_FETCH_ASSOC))
 {	
 	$count++;
 	$selected="";
	 $selected=$plant_id==$result["PlantCode"] ? "Selected" : "";
 	$option.='<option value="'.$result["PlantCode"].'" '.$selected.'>'.$result["PlantName"].'</option>';
 }
$output="";
 if($count >1){
 	$output='<option value="">Select Plant</option>';
 	$output.=$option;
 }else if($count == 1){
 	$output=$option;
 }else if($count == 0){
 	$output='<option value="">No Records Found</option>';
 }

 echo  json_encode(array('data' => $output,'sql'=>$sql));
}else if($Action =="Get_Customer_Details"){

	$product_division=@$_POST['product_division'];
	if($product_division=="ras"){
		$product_division="CT01";
	}else if($product_division=="fcm"){
		$product_division="FC01";
	} else if($product_division=="frg"){
		$product_division="FR01";
	}


	$zone_code=@$_POST['zone_code'];
	$region_code=@$_POST['region_code'];
	$territory_code=@$_POST['territory_code'];
	$CustomerCode=@$_POST['CustomerCode'];


	
	 $sql="EXEC Sales_Indent_Get_Customer_Details @Product_Division='".$product_division."',@Zone_Id='".$zone_code."',@Region_Id='".$region_code."',@Territory_Id='".$territory_code."'";
  $sql_for_customer_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_customer_details);
  $option='<option value="">Select Customer</option>';
 while($result = sqlsrv_fetch_array($sql_for_customer_details,SQLSRV_FETCH_ASSOC))
 {
	$selected=$CustomerCode==utf8_encode($result["SAPCode"]) ? "Selected" : "";
 	$option.='<option value="'.utf8_encode($result["SAPCode"]).'" '.$selected.' >'.utf8_encode($result["SAPCode"]).' - '.utf8_encode($result["CustomerName"]).'  - '.utf8_encode($result["District"]).' - '.utf8_encode($result["Post_Code"]).'</option>';
 }

 echo  json_encode(array('data' => $option,'sql'=>$sql));
}else if($Action =="Get_Material_Details"){
	$product_division=@$_POST['product_division'];
	if($product_division=="ras"){
		$product_division="CT01";
	}else if($product_division=="fcm"){
		$product_division="FC01";
	} else if($product_division=="frg"){
		$product_division="FR01";
	}

    $crop_code=@$_POST['crop_code'];
	$zone_code=@$_POST['zone_id'];
	$Material_Code=@$_POST['Material_Code'];
	$region_id = isset($_POST['region_id']) ? $_POST['region_id'] : '';
	
	$sql="EXEC Sales_Indent_Get_Material_Details @Crop_Code='".@$crop_code."',@Product_Division='".@$product_division."',@Zone_Id='".$zone_code."',@region_id='".$region_id."'";

  $sql_for_material_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_material_details);
  $option='<option value="">Select Material</option>';
 while($result = sqlsrv_fetch_array($sql_for_material_details,SQLSRV_FETCH_ASSOC))
 {
 	//echo "Material_Code==".$Material_Code."=ProductCode==".$result["ProductCode"];
	 $selected=@$Material_Code==trim($result["ProductCode"]) ? "Selected" : "";
 	$option.='<option value="'.trim($result["ProductCode"]).'" '.@$selected.'>'.$result["ProductCode"].'</option>';
 }

 echo  json_encode(array('data' => $option,'sql'  => $sql));
}else if($Action =="Get_Material_Details_Based_On_Customer"){
	$product_division=@$_POST['product_division'];
	if($product_division=="ras"){
		$product_division="CT01";
	}else if($product_division=="fcm"){
		$product_division="FC01";
	} else if($product_division=="frg"){
		$product_division="FR01";
	}

    $Customer_Id=@$_POST['Customer_Id'];
    $crop_code=@$_POST['crop_code'];
	$zone_code=@$_POST['zone_id'];
	$region_id=@$_POST['region_id'];
	$Material_Code=@$_POST['Material_Code'];
	if($product_division == "FC01") {
		$sql="EXEC Sales_Indent_Customer_Based_Material_Details @Crop_Code='".@$crop_code."',@Product_Division='".@$product_division."',@Zone_Id='".$zone_code."',@customer_Code='".@$Customer_Id."',@Region_Id='".$region_id."'";
	} else {
		$sql="EXEC Sales_Indent_Customer_Based_Material_Details @Crop_Code='".@$crop_code."',@Product_Division='".@$product_division."',@Zone_Id='".$zone_code."',@customer_Code='".@$Customer_Id."',@Region_Id=''";
	}

	//echo $sql;
	
  $sql_for_material_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_material_details);
  $option='<option value="">Select Material</option>';
 while($result = sqlsrv_fetch_array($sql_for_material_details,SQLSRV_FETCH_ASSOC))
 {
 	//echo "Material_Code==".$Material_Code."=ProductCode==".$result["ProductCode"];
	 $selected=@$Material_Code==$result["ProductCode"] ? "Selected" : "";
 	$option.='<option value="'.$result["ProductCode"].'" '.@$selected.'>'.$result["ProductCode"].'</option>';
 }

 echo  json_encode(array('data' => $option,'sql'  => $sql));
}else if($Action =="Get_Product_Based_Qty_Details")
{
$material_id=isset($_POST['material_id']) && !empty($_POST['material_id']) ? $_POST['material_id'] : 0;
$sql="EXEC Sales_Indent_Product_Based_Qty_Details @Material_Code='".@$material_id."'";
$sql_for_material_details=sqlsrv_prepare($conn,$sql);
sqlsrv_execute($sql_for_material_details);
$result = sqlsrv_fetch_array($sql_for_material_details,SQLSRV_FETCH_ASSOC);
echo json_encode(array('Sql'=>$sql,'QtyInPkt'=>@$result['QtyInPkt'] !='' ? @$result['QtyInPkt']: 0,'QtyInKg'=>@$result['QtyInKg'] !='' ? @$result['QtyInKg']: 0));
}else if($Action =="Delete_Material_Details")
{
$Id=isset($_POST['SalesIndentId']) && !empty($_POST['SalesIndentId']) ? $_POST['SalesIndentId'] : 0;
$sql="DELETE FROM Sales_Indent_Material_Details WHERE Id='$Id'";
$Delete_Sql=sqlsrv_query($conn,$sql);
$status=0;
	if($Delete_Sql)
	{
		$status=1;
	}
	echo json_encode(array('status'=>$status));exit;
}else if($Action =="Delete_STO_Material_Details")
{
$Id=isset($_POST['SalesIndentId']) && !empty($_POST['SalesIndentId']) ? $_POST['SalesIndentId'] : 0;
$sql="DELETE FROM Sales_Indent_STO_Material_Details WHERE Id='$Id'";
$Delete_Sql=sqlsrv_query($conn,$sql);
$status=0;
	if($Delete_Sql)
	{
		$status=1;
	}
	echo json_encode(array('status'=>$status));exit;
}else if($Action =="Validate_Material_Details")
{
	$User_Input=$_POST;


		//echo "<pre>";print_r($User_Input);
	 $Validate_At=date('Y-m-d H:i:s');
     $Validate_by=$_SESSION['EmpID'];
     $Rejected_At=date('Y-m-d H:i:s');
     $Rejected_by=$_SESSION['EmpID'];
     $Statement_Type=@$User_Input['Statement_Type'];
     // $SupplyType=@$User_Input['supply_type_id'];
	 // $Validate_At=date('Y-m-d H:i:s');
    // $Validate_by=$_SESSION['EmpID'];
	$count=0;
	$indent_id_array=array();
	$Employee_Based_Details=array();
	$update_count=0;


	$validate_arr['C&F']    = array();
	$validate_arr['direct'] = array(); 

	foreach(@$User_Input['validate'] as $key=>$value)
	{
		if($User_Input['INDENT_TYPES'][$key] == 'CS') {
			$validate_arr['C&F'][$value] = $value;
		} else {
			$validate_arr['direct'][$value] = $value;

		}
	}


	foreach ($validate_arr as $vk => $vvalue) {

		if(COUNT($validate_arr[$vk]) > 0) {
			$Employee_Based_Details=array();
			foreach(@$validate_arr[$vk] as $key=>$value)
			{
				$QtyInBag=@$User_Input['QtyInBag'][$key];
				$CropId=@$User_Input['CropId'][$key];
				$QtyInPkt=@$User_Input['QtyInPkt'][$key];
				$Sales_Indent_line_No=@$User_Input['Sales_Indent_line_No'][$key];
				$QtyInKg=@$User_Input['QtyInKg'][$key];
				$SalesIndentId=@$User_Input['SalesIndentId'][$key];
				$Product_Division=@$User_Input['SALES_ORG'][$key];
				$Employee_Based_Details[@$User_Input['EMPLOYEE_ID'][$key]]['SalesIndentId'][]=$Sales_Indent_line_No;
				$indent_id_array[]=$SalesIndentId;

		     $SupplyType=@$User_Input['supply_type_id'][$key];

				if($Statement_Type=="Approve")
				{
					$SQL="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Validate_by='".$Validate_by."',Validate_At='".$Validate_At."',CurrentStatus='4' WHERE Id='$Sales_Indent_line_No'";
				    $update=sqlsrv_query($conn,$SQL);
				    $update_count++;
				}else if($Statement_Type=="Reject")
				{
					$SQL="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Rejected_by='".$Rejected_by."',Rejected_At='".$Rejected_At."',RejectionStatus='2' WHERE Id='$Sales_Indent_line_No'";
				    $update=sqlsrv_query($conn,$SQL);

				}

			  
				if($update){
					
					$count++;
				}
				$sql="Sales_Indent_Material_status @SalesIndentId='$SalesIndentId',@Statement_Type='Validate'";
				$result=sqlsrv_query($conn,$sql);
				if(@$result['Count'] == '1'){
					$SQL="update Sales_Indent set CurrentStatus=2 where SalesIndentId='$SalesIndentId' ";
					$update=sqlsrv_query($conn,$SQL);
				}
			}

			if($count >0){
				if($update_count >0){

					foreach($Employee_Based_Details as $key=>$value){
						$Sales_Indent_Id=implode(',', @$value['SalesIndentId']);
						/* Send Mail For Indidual Indent No Start Here*/
						//"EXEC Sales_Indent_Details @Sales_Indent_Material_Id='".$Sales_Indent_Id."',@Status='3'";
						$details=sqlsrv_query($conn,"EXEC Sales_Indent_Report_Details_With_Crop @Sales_Indent_Material_Id='".$Sales_Indent_Id."',@Status='4'");
						$mail=new Send_Mail();
						$Employee_Name=Get_Employee_Details($conn,$_SESSION['EmpID']);

						$subject="Sales Indent Validation (Limit Exceed) By ".$Employee_Name." - Reg.";
						$Type="C&F_Supply";
						if($SupplyType==1){
							$Type="Direct_Supply";
						}else if($SupplyType==2){
							$Type="C&F_Supply";
						}
						$ProductDivision='fcm';
						if($Product_Division == "FC01")
						{
							$ProductDivision='fcm';
						}else if($Product_Division == "CT01"){
							$ProductDivision='ras';
						}	else if($Product_Division == "FR01"){
							$ProductDivision='frg';
						}		
						$Mail_Dets=Get_Mail_Recipient_Details(@$key,@$ProductDivision,@$Type,@$CropId);
						$To_Mail=@$Mail_Dets['Level_1'];
						$CC_Mail=@$Mail_Dets['Level_2'];
						$to=array();
						$cc=array();
						if(@$Mail_Dets['Level_1'] !=''){
							array_push($cc,@$Mail_Dets['Level_1']);
						}

						if(@$Mail_Dets['Level_2'] !=''){
							array_push($cc,@$Mail_Dets['Level_2']);
						}

						if(@$Mail_Dets['Level_3'] !=''){
							array_push($cc,@$Mail_Dets['Level_3']);
						}

						if(@$Mail_Dets['Level_4'] !=''){
							array_push($to,@$Mail_Dets['Level_4']);
						}
						//$bcc=array('');
							$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');

						// $to  = ['jr_developer4@mazenetsolution.com'];
						// $cc  = ['',''];
						// $bcc = ['',''];
						// $message=$mail->Generate_Mail_Tempalte($details);
						// $mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
						/* Send Mail For Indidual Indent No End here*/
					}

					/*$Sales_Indent_Id=implode(',', $indent_id_array);
					$details=sqlsrv_query($conn,"EXEC Sales_Indent_Details @Sales_Indent_Id='".$Sales_Indent_Id."',@Status='2'");
					$mail=new Send_Mail();
					$subject="Sales Indent Approve Request -reg.";
					$cc=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
					$to=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
					$bcc=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
					$message=$mail->Generate_Mail_Tempalte($details);
					$mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc); */
					}//exit();

					$status=1;
				}else{
				$status=0;
			}
		}
	}

echo json_encode(array('Status'=>$status));
}else if($Action =="Recommend_Material_Details")
{	
	$User_Input=$_POST;
	 $Validate_At=date('Y-m-d H:i:s');
     $Validate_by=$_SESSION['EmpID'];
     $Rejected_At=date('Y-m-d H:i:s');
     $Rejected_by=$_SESSION['EmpID'];
     $Statement_Type=@$User_Input['Statement_Type'];
     // $SupplyType=@$User_Input['Supply_Type'];
   // $Limit_Exceed=@$User_Input['Limit_Exceed'];
	 // $Validate_At=date('Y-m-d H:i:s');
    // $Validate_by=$_SESSION['EmpID'];
	$count=0;
	$indent_id_array=array();
	$update_count=0;


	$recommend_arr['C&F']    = array();
	$recommend_arr['direct'] = array(); 

	foreach(@$User_Input['validate'] as $key=>$value)
	{
		if($User_Input['INDENT_TYPES'][$key] == 'CS') {
			$recommend_arr['C&F'][$value] = $value;
		} else {
			$recommend_arr['direct'][$value] = $value;

		}
	}


	foreach ($recommend_arr as $rk => $rvalue) {

		if(COUNT($recommend_arr[$rk]) > 0) {
			$Employee_Based_Details=array();

			foreach(@$recommend_arr[$rk] as $key=>$value)
			{
				$QtyInBag=@$User_Input['QtyInBag'][$key];
				$CropId=@$User_Input['CropId'][$key];
				$QtyInPkt=@$User_Input['QtyInPkt'][$key];
				$Sales_Indent_line_No=@$User_Input['Sales_Indent_line_No'][$key];
				$QtyInKg=@$User_Input['QtyInKg'][$key];
				$SalesIndentId=@$User_Input['SalesIndentId'][$key];
				$Apdesign=@$User_Input['APDESIGN'][$key];
				$Product_Division=@$User_Input['SALES_ORG'][$key];
				$Employee_Based_Details[@$User_Input['EMPLOYEE_ID'][$key]]['SalesIndentId'][]=$Sales_Indent_line_No;

				$indent_id_array[]=$SalesIndentId;

		     $SupplyType=@$User_Input['supply_type_id'][$key];


				if($Statement_Type=="Approve")
				{
					$CurrentStatus='3';
					if(strtoupper(@$Apdesign) =="DBM")
					{
						$CurrentStatus='4';
					}elseif($Product_Division == "FR01"){
							$CurrentStatus='4';
					}


					// move appoval status upper level based on their roles
					if($CurrentStatus == 3) {
						$user_role_data = user_role_levels($CropId,$SupplyType,$User_Input['EMPLOYEE_ID'][$key]);
						if($user_role_data['level_2'] == $user_role_data['level_3']) {
							$CurrentStatus='4';
								$Employee_Based_Details[@$User_Input['EMPLOYEE_ID'][$key]]['same_level_approver'] = 1;
						}			
					}

					$SQL="UPDATE Sales_Indent_Material_Details SET Recommended_By='".$Validate_by."',Recommended_At='".$Validate_At."',CurrentStatus='".$CurrentStatus."' WHERE Id='$Sales_Indent_line_No'";
				    $update=sqlsrv_query($conn,$SQL);
				    $update_count++;
				}else if($Statement_Type=="Reject")
				{
					$SQL="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Rejected_by='".$Rejected_by."',Rejected_At='".$Rejected_At."',RejectionStatus='2' WHERE Id='$Sales_Indent_line_No'";
				    $update=sqlsrv_query($conn,$SQL);

				}

				if($update){
					
					$count++;
				}
				$sql="Sales_Indent_Material_status @SalesIndentId='$SalesIndentId',@Statement_Type='Validate'";
				$result=sqlsrv_query($conn,$sql);
				if(@$result['Count'] == '1'){
					$SQL="update Sales_Indent set CurrentStatus=2 where SalesIndentId='$SalesIndentId' ";
					$update=sqlsrv_query($conn,$SQL);
				}
			}
				// 		echo "<pre>";print_r($Employee_Based_Details);exit;
				// 	foreach($Employee_Based_Details as $key=>$value){
				// 		$Sales_Indent_Id=implode(',', @$value['SalesIndentId']);

				// }
			if($count >0){
				if($update_count >0){

					foreach($Employee_Based_Details as $key=>$value){
						$Sales_Indent_Id=implode(',', @$value['SalesIndentId']); 

						/* Send Mail For Indidual Indent No Start Here*/
						//"EXEC Sales_Indent_Details @Sales_Indent_Material_Id='".$Sales_Indent_Id."',@Status='3'";


						

						if($Product_Division == "FR01"){

							$details=sqlsrv_query($conn,"EXEC Sales_Indent_Report_Details_With_Crop @Sales_Indent_Material_Id='".$Sales_Indent_Id."',@Status='4'");

						}else{
							$Employee_Name=Get_Employee_Details($conn,$_SESSION['EmpID']);
							
							$sale_id = "'".implode("','",$value['SalesIndentId'])."'";

							$sql = "SELECT Id from Sales_Indent_Material_Details WHERE Id IN(".$sale_id.") AND CurrentStatus = 3";
							$sql_process = sqlsrv_query($conn,$sql);
							$level1 = array();
							while($sql_res     = sqlsrv_fetch_array($sql_process,SQLSRV_FETCH_ASSOC)) {
								$level1[] = $sql_res['Id'];
							}

							if(COUNT($level1) > 0) {
								$level1_indent = implode(',',$level1);
								$details=sqlsrv_query($conn,"EXEC Sales_Indent_Report_Details_With_Crop @Sales_Indent_Material_Id='".$level1_indent."',@Status='3'");
								$subject="Sales Indent  Recommended Limit Exceed) By ".$Employee_Name." - Reg.";

								$level2 = implode(',', array_diff($value['SalesIndentId'], $level1));
								if($level2 != '') {
									$details=sqlsrv_query($conn,"EXEC Sales_Indent_Report_Details_With_Crop @Sales_Indent_Material_Id='".$level2."',@Status='4'");
									$subject="Sales Indent Validation (Limit Exceed) By ".$Employee_Name." - Reg.";
								}

							} else {
								$subject="Sales Indent Validation (Limit Exceed) By ".$Employee_Name." - Reg.";
								$details=sqlsrv_query($conn,"EXEC Sales_Indent_Report_Details_With_Crop @Sales_Indent_Material_Id='".$Sales_Indent_Id."',@Status='4'");
							}


						}



						$mail=new Send_Mail();
						// $Employee_Name=Get_Employee_Details($conn,$_SESSION['EmpID']);

						// $subject="Sales Indent  Recommended Limit Exceed) By ".$Employee_Name." - Reg.";
						$Type="C&F_Supply";
						if($SupplyType==1){
							$Type="Direct_Supply";
						}else if($SupplyType==2){
							$Type="C&F_Supply";
						}
						$ProductDivision='fcm';
						if($Product_Division == "FC01")
						{
							$ProductDivision='fcm';
						}else if($Product_Division == "CT01"){
							$ProductDivision='ras';
						}	else if($Product_Division == "FR01"){
							$ProductDivision='frg';
						}		
						$Mail_Dets=Get_Mail_Recipient_Details(@$key,@$ProductDivision,@$Type,@$CropId);
						$To_Mail=@$Mail_Dets['Level_1'];
						$CC_Mail=@$Mail_Dets['Level_2'];

						$to=array();
						$cc=array();
					




						if($Product_Division == "FR01"){

							if(@$Mail_Dets['Level_1'] !=''){
											array_push($cc,@$Mail_Dets['Level_1']);
										}


										if(@$Mail_Dets['Level_2'] !=''){
											array_push($cc,@$Mail_Dets['Level_2']);
										}

									

										if(@$Mail_Dets['Level_4'] !=''){
											array_push($to,@$Mail_Dets['Level_4']);
										}

						}else{


							if(@$Mail_Dets['Level_1'] !=''){
											array_push($cc,@$Mail_Dets['Level_1']);
										}

										if(@$Mail_Dets['Level_2'] !=''){
											array_push($cc,@$Mail_Dets['Level_2']);
										}

										if(@$Mail_Dets['Level_3'] !=''){
											array_push($to,@$Mail_Dets['Level_3']);
										}

							
						}


						$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
						//$bcc=array('');

						// $to  = ['jr_developer4@mazenetsolution.com','sathish.r@rasiseeds.com'];
						// $cc  = ['',''];
						// $bcc = ['',''];
						// $message=$mail->Generate_Mail_Tempalte($details);
						// $mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
						/* Send Mail For Indidual Indent No End here*/
					}

					/*$Sales_Indent_Id=implode(',', $indent_id_array);
					$details=sqlsrv_query($conn,"EXEC Sales_Indent_Details @Sales_Indent_Id='".$Sales_Indent_Id."',@Status='2'");
					$mail=new Send_Mail();
					$subject="Sales Indent Approve Request -reg.";
					$cc=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
					$to=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
					$bcc=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
					$message=$mail->Generate_Mail_Tempalte($details);
					$mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc); */
				}
				$status=1;
			}else{
				$status=0;
			}			  

		}
	}
echo json_encode(array('Status'=>$status));
}else if($Action =="Approve_Material_Details")
{

  // echo "<pre>";print_r($_POST);exit;

	$User_Input=$_POST;


	//	echo "<pre>";print_r($User_Input);
		//exit();
	$Approved_At=date('Y-m-d H:i:s');
    $Approved_by=$_SESSION['EmpID'];
    $Rejected_At=date('Y-m-d H:i:s');
    $Rejected_by=$_SESSION['EmpID'];
    $Statement_Type=@$User_Input['Statement_Type'];
    // $SupplyType=@$User_Input['supply_type_id'];
    $Limit_Exceed=@$User_Input['Limit_Exceed'];
	$count=0;
	$Result_Array=array();
	$update_count=0;
	$Employee_Based_Details=array();

	$approve_arr['C&F'] = array();
	$approve_arr['direct'] = array(); 

	foreach(@$User_Input['validate'] as $key=>$value)
	{
		if($User_Input['INDENT_TYPES'][$key] == 'CS') {
				// array_push($approve_arr['C&F'],$cs);
			$approve_arr['C&F'][$value] = $value;
		} else {
				// array_push($approve_arr['direct'],$value);
			$approve_arr['direct'][$value] = $value;

		}
	}


	// echo "<pre>";print_r($approve_arr);exit;

	foreach ($approve_arr as $ak => $avalue) {

		if(COUNT($approve_arr[$ak]) > 0) {
			$Employee_Based_Details=array();

			foreach(@$approve_arr[$ak] as $key=>$value)
			{
				$Product_Division=@$User_Input['SALES_ORG'][$key];
				$CropId=@$User_Input['CropId'][$key];
				$array=[];
				$array['INDENT_NO']=@$User_Input['INDENT_NO'][$key];
				$array['EMPLOYEE_ID']=@$User_Input['EMPLOYEE_ID'][$key];
				$array['ACCOUNTNUM']=@$User_Input['ACCOUNTNUM'][$key];
				$array['SALES_ORG']=@$User_Input['SALES_ORG'][$key];
				$array['DIST_CHANNEL']=@$User_Input['DIST_CHANNEL'][$key];
				$array['DIVISION']=@$User_Input['DIVISION'][$key];
				$array['ZONE_ID']=@$User_Input['ZONE_ID'][$key];
				$array['REGION_ID']=@$User_Input['REGION_ID'][$key];
				$array['TM_ID']=@$User_Input['TM_ID'][$key];
				$array['QUOTATION_TYPE']=@$User_Input['QUOTATION_TYPE'][$key];
				$array['SALEORDER_TYPE']=@$User_Input['SALEORDER_TYPE'][$key];


				$array['MATERIAL_NO']=@$User_Input['MATERIAL_NO'][$key];

		    $SupplyType=@$User_Input['supply_type_id'][$key];

				//$MATERIAL_NO = strToHex(@$User_Input["MATERIAL_NO"][$key]);


				//$array['MATERIAL_NO']=$MATERIAL_NO;


				$array['QUANTITY']=@$User_Input['QtyInPkt'][$key];
				$array['PLANT']=@$User_Input['PLANT'][$key];
				$array['LGORT']=@$User_Input['LGORT'][$key];
				//$array['LGORT']=@$User_Input['LGORT'][$key];
		  	/*	if(@$User_Input['PLANT'][$key] =="B012"){
					$array['LGORT']='SE02';
				}*/
				
				$array['INDENT_TYPES']=@$User_Input['INDENT_TYPES'][$key];
				if(@$array['INDENT_TYPES'] == '')
				{
					$array['INDENT_TYPES']=$SupplyType == 1 ? "DS" : "CS";
				}
				
				$array['VALID_TO_DATE']=date('Ymd');
				$array['APPROVE_STATUS']=@$User_Input['APPROVE_STATUS'][$key];
				$array['RBM_EMP_VENDOR']=@$User_Input['RBM_EMP_VENDOR'][$key];
				$array['DBM_EMP_VENDOR']=@$User_Input['DBM_EMP_VENDOR'][$key];
				$array['RBM_MAIL_STATUS']=@$User_Input['RBM_MAIL_STATUS'][$key];
				$array['DBM_MAIL_STATUS']=@$User_Input['DBM_MAIL_STATUS'][$key];
				$array['Season_code']=@$User_Input['Season_code'][$key];
				$array['PLACE']=@$User_Input['PLACE'][$key];
				$array['PLACE']=str_replace("&", " ", @$User_Input['PLACE'][$key]);
				$array['MOBILE_NO']=@$User_Input['MOBILE_NO'][$key];
				$array['Expected_date']=@$User_Input['Expected_date'][$key];
				$plant=@$User_Input['PLANT'][$key];

				
				$QtyInBag=@$User_Input['QtyInBag'][$key];
				$CropId=@$User_Input['CropId'][$key];
				$QtyInPkt=@$User_Input['QtyInPkt'][$key];
				$Sales_Indent_line_No=@$User_Input['Sales_Indent_line_No'][$key];
				$QtyInKg=@$User_Input['QtyInKg'][$key];
				$SalesIndentId=@$User_Input['SalesIndentId'][$key];
				$Employee_Based_Details[@$User_Input['EMPLOYEE_ID'][$key]][$plant]['SalesIndentId'][]=$Sales_Indent_line_No;
				$indent_id_array[]=$SalesIndentId;

		    $Supply_type_arr[@$User_Input['EMPLOYEE_ID'][$key]][$plant][$User_Input['Sales_Indent_line_No'][$key]] = $SupplyType;

				if(trim($Statement_Type)=="Approved")
				{
			

						// $user_role_data = user_role_levels($CropId,$SupplyType,$User_Input['EMPLOYEE_ID'][$key]);

						if($SupplyType==2){
							 $SQL="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Approved_by='".$Approved_by."',Approved_At='".$Approved_At."',CurrentStatus='5' WHERE Id='$Sales_Indent_line_No'";

						}else if($SupplyType==1 && $Product_Division!='FC01' ){

								 $SQL="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Approved_by='".$Approved_by."',Approved_At='".$Approved_At."',CurrentStatus='5' WHERE Id='$Sales_Indent_line_No'";

						}


						if($SupplyType==1 && $Product_Division=='FC01'){
								 $CurrentStatus = 6;

								 if($Limit_Exceed == 1) {
								 		$CurrentStatus = 5;
								 }
								 // if($user_role_data['level_1'] == 'yes' && $user_role_data['level_2'] == 'yes') {
								 // 		$CurrentStatus = 5;
								 // }

								 $SQL="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Approved_by='".$Approved_by."',Approved_At='".$Approved_At."',CurrentStatus='".$CurrentStatus."' WHERE Id='$Sales_Indent_line_No'";

								}



						    $update=sqlsrv_query($conn,$SQL);
						       $update_count++;
				} else if($Statement_Type=="Reject") {
							 $SQL="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Rejected_by='".$Rejected_by."',Rejected_At='".$Rejected_At."',RejectionStatus='2' WHERE Id='$Sales_Indent_line_No'";
						    $update=sqlsrv_query($conn,$SQL);

				}

							/*	$SQL="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Approved_by='".$Approved_by."',Approved_At='".$Approved_At."',CurrentStatus='3' WHERE Id='$Sales_Indent_line_No'";
								$update=sqlsrv_query($conn,$SQL);*/
						
						if(@$update){
							$count++;
							$Result_Array[]=$array;
						}
						$sql="Sales_Indent_Material_status @SalesIndentId='$SalesIndentId',@Statement_Type='Validate'";
						$result=sqlsrv_query($conn,$sql);
						if(@$result['Count'] == '1'){
							$SQL="update Sales_Indent set CurrentStatus=2 where SalesIndentId='$SalesIndentId' ";
							$update=sqlsrv_query($conn,$SQL);
						}
			}

				// echo "<pre>";print_r($Employee_Based_Details);
			
			$Post_To_SAP_Dets="";
			$Sales_indnet_Line_No_String="";
			if($count >0){
				if($update_count >0){
					$url="http://192.168.162.213:8081/Sales_Indent/DEV/ZIN_RFC_ERAIN_UPDATE_QUOT_DAS.php";
					$SAP_Json_Data=json_encode($Result_Array);

					if($SupplyType ==2){
					// $Post_To_SAP_Dets=Post_SAP_Data($SAP_Json_Data,$url); 

					}else if($SupplyType ==1 && $Product_Division !='FC01'){
					// $Post_To_SAP_Dets=Post_SAP_Data($SAP_Json_Data,$url); 

					}else if($SupplyType ==1 && $Product_Division !='CT01'){
						if($Limit_Exceed == 1){
					// $Post_To_SAP_Dets=Post_SAP_Data($SAP_Json_Data,$url); 

						}

					}
					//print_r($Post_To_SAP_Dets);

					$credit_limit_excceed_cc=array();	
					foreach($Employee_Based_Details as $Employee_Key=>$Employee_Value)
					{
						foreach($Employee_Value as $Plant_Key=>$Plant_Value)
						{
							$Sales_Indent_Id=implode(',', @$Plant_Value['SalesIndentId']);

								$Sales_indnet_Line_No_String="";
								if($Limit_Exceed == 1){
									if($Sales_indnet_Line_No_String != ""){
										$Sales_indnet_Line_No_String.=",";
									}
									$Sales_indnet_Line_No_String.=$Sales_Indent_Id;
								}
								/* Send Mail For Indidual Indent No with Plant Start Here */
								$details=sqlsrv_query($conn,"EXEC Sales_Indent_Report_Details_With_Crop @Sales_Indent_Material_Id='".$Sales_Indent_Id."'");

								//echo "EXEC Sales_Indent_Report_Details_With_Crop @Sales_Indent_Material_Id='".$Sales_Indent_Id."'";

								$mail=new Send_Mail();
								$Employee_Name=Get_Employee_Details($conn,$_SESSION['EmpID']);


								//	$subject="Sales Indent Approved (With In Limit) By ".$Employee_Name." -reg.";

								//print_r($SupplyType);
								$Type="C&F_Supply";
								if($SupplyType ==1  && $Product_Division =='FC01'){
									$subject="Sales Indent Validate (With In Limit) By ".$Employee_Name." -reg.";
									$Type="Direct_Supply";
								}else if($SupplyType ==2){
									$subject="Sales Indent Approved (With In Limit) By ".$Employee_Name." -reg.";
									$Type="C&F_Supply";
								}if($SupplyType ==1  && $Product_Division !='FC01'){
									$subject="Sales Indent Approve (With In Limit) By ".$Employee_Name." -reg.";
									$Type="Direct_Supply";
								}
								$ProductDivision='fcm';
								if($Product_Division == "FC01")
								{
									$ProductDivision='fcm';
								}else if($Product_Division == "CT01"){
									$ProductDivision='ras';
								}	else if($Product_Division == "FR01"){
									$ProductDivision='frg';
								}		
								$Mail_Dets=Get_Mail_Recipient_Details(@$Employee_Key,@$ProductDivision,@$Type,@$CropId);
								$Plant_Mail_Dets=Get_Mail_Recipient_Details_For_Plant(@$Plant_Key);
								$To_Mail=@$Mail_Dets['Level_1'];
								$CC_Mail=@$Mail_Dets['Level_2'];
								$to=array();
								$cc=array();



									//print_r($Mail_Dets);
								//	print_r($Plant_Mail_Dets);
								if($Limit_Exceed == 0){
									//		$subject="Sales Indent Approved  (With In Limit) By ".$Employee_Name." -reg.";
									if($SupplyType ==2){
										if(@$Mail_Dets['Level_1'] !=''){
											array_push($to,@$Mail_Dets['Level_1']);
											foreach($Plant_Mail_Dets as $value){
												array_push($to,$value);
											}
										}


										if(@$Mail_Dets['Level_2'] !=''){
											array_push($cc,@$Mail_Dets['Level_2']);
										}




									} else if($SupplyType ==1  && $Product_Division[$indent_key] =='FC01'){

										if(@$Mail_Dets['Level_3'] !=''){
											array_push($to,@$Mail_Dets['Level_3']);
										}


										if(@$Mail_Dets['Level_2'] !=''){
											array_push($cc,@$Mail_Dets['Level_2']);
										}


										if(@$Mail_Dets['Level_1'] !=''){
											array_push($cc,@$Mail_Dets['Level_1']);
										}

									}

									if($SupplyType ==1  && $Product_Division[$indent_key] !='FC01'){

										if(@$Mail_Dets['Level_1'] !=''){
											array_push($to,@$Mail_Dets['Level_1']);
											foreach($Plant_Mail_Dets as $value){
												array_push($to,$value);
											}
										}

										if(@$Mail_Dets['Level_2'] !=''){
											array_push($cc,@$Mail_Dets['Level_2']);
										}

									}

								} else if($Limit_Exceed == 1){
									$credit_limit_excceed_cc=array();
									$subject="Sales Indent Approved (Limit Exceed) By ".$Employee_Name." -reg.";


									if($ProductDivision == 'frg'  && $SupplyType ==1 ){

			     					//$to=array('ts.logistics@rasiseeds.com',"fcdispatch@rasiseeds.com","gowrishankar.ch@rasiseeds.com","anandbabu.chidurala@rasiseeds.com","Shaikh.m@rasiseeds.com","prashanth.t@rasiseeds.com");

										foreach($Plant_Mail_Dets as $value)
										{
											array_push($to,$value);
											array_push($credit_limit_excceed_cc,@$value);
										}



									}else{
										foreach($Plant_Mail_Dets as $value)
										{
											array_push($to,$value);
											array_push($credit_limit_excceed_cc,@$value);
										}


									}


									if(@$Mail_Dets['Level_1'] !=''){
										array_push($to,@$Mail_Dets['Level_1']);
										array_push($credit_limit_excceed_cc,@$Mail_Dets['Level_1']);

									}

									if(@$Mail_Dets['Level_2'] !=''){
										array_push($cc,@$Mail_Dets['Level_2']);
										array_push($credit_limit_excceed_cc,@$Mail_Dets['Level_2']);
									}

									if(@$Mail_Dets['Level_3'] !=''){
										array_push($cc,@$Mail_Dets['Level_3']);
										array_push($credit_limit_excceed_cc,@$Mail_Dets['Level_3']);
									}

									if(@$Mail_Dets['Level_4'] !=''){
										array_push($cc,@$Mail_Dets['Level_4']);
									}
								}
								// $bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sureshbabu.k@rasiseeds.com',"sathish.r@rasiseeds.com",'saravanakumaran.n@rasiseeds.com');

								///	$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com','sureshbabu.k@rasiseeds.com');


								//$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
								///	$bcc=array('');


									$message=$mail->Generate_Mail_Tempalte($details,"Approve");

									//print_r("Hai");
									//exit();
									// $mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);

									// $to  = ['jr_developer4@mazenetsolution.com',''];
									// $cc  = ['',''];
									// $bcc = ['',''];

									// $mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
									/* Send Mail For Indidual Indent No with Plant End Here */
									//exit();

								/* Credit Limit Exceed Mail For Finance Team Start Here  */
								if($Limit_Exceed == 1 && $Sales_indnet_Line_No_String !="")
								{
									$Credit_Limit_Exceed_Mail=new Send_Mail();
									$Sql_Connection=sqlsrv_query($conn,"EXEC Sales_Indent_Details_For_Credit_Limit_Exceed_NEW_QUERY @SalesIndnetId='".$Sales_indnet_Line_No_String."'");

										// echo "EXEC Sales_Indent_Details_For_Credit_Limit_Exceed @SalesIndnetId='".$Sales_indnet_Line_No_String."'";

										//print_r($Sql_Connection);
										$Credit_Limit_Exceed_Mail_Template=$Credit_Limit_Exceed_Mail->Generate_Mail_Tempalte_For_Credit_Limit_Exceed_New_changed($Sql_Connection);

										//$to=array("vinothkumar.t@rasiseeds.com");
									// $to=array("harsha.daga@rasiseeds.com");
									// $cc=array();
									//	$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com","sathish.r@rasiseeds.com",'saravanakumaran.n@rasiseeds.com');
										//$bcc=array('sathish.r@rasiseeds.com');
									// $bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com","sathish.r@rasiseeds.com",'saravanakumaran.n@rasiseeds.com');
									// $to  = ['jr_developer4@mazenetsolution.com'];
									// $credit_limit_excceed_cc  = ['',''];
									// $bcc = ['',''];
									// if($Credit_Limit_Exceed_Mail_Template !=''){
								 	// $Credit_Limit_Exceed_Mail->Send_Mail_Details("Credit Limit Excceed - Reg",$Credit_Limit_Exceed_Mail_Template,$to,$credit_limit_excceed_cc,$bcc);
									// }

								}

								/* Credit Limit Exceed Mail For Finance Team End Here */
							

						}
					}
				}
				$status=1;
			} else{
				$status=0;
			}

		}

	}





	
	
echo json_encode(array('Status'=>$status,'data'=>json_encode($Result_Array),'SAP_Status'=>json_encode($Post_To_SAP_Dets)));
} else if($Action =="Status_Details")
{
	$User_Input=$_POST;
	$Status=$User_Input['status'] == 0 ? 1 : 0;
	$count=0;
	foreach ($User_Input['validate'] as $key => $value){
	$SQL= "UPDATE RASI_ZONETABLE SET CurrentStatus='".$Status."'  WHERE id='".$User_Input['id'][$key]."'";
		$update = sqlsrv_query($conn,$SQL);	
		$count++;	
	}
	

	if($count >0){
		$status=1;
	}else{
		$status=0;
	}
	
echo json_encode(array('Status'=>$status));
} else if($Action =="ProductStatus_Details")
{
	$User_Input=$_POST;
	$Status=$User_Input['status'] == 0 ? 1 : 0;
	$count=0;
	foreach ($User_Input['validate'] as $key => $value){
	$SQL= "UPDATE Master_Product_demo SET CurrentStatus='".$Status."'  WHERE id='".$User_Input['id'][$key]."'";
		$update = sqlsrv_query($conn,$SQL);	
		$count++;	
	}
	

	if($count >0){
		$status=1;
	}else{
		$status=0;
	}
	
echo json_encode(array('Status'=>$status));
}else if($Action =="Status_role")
{
	$User_Input=$_POST;
	$Status=$User_Input['status'] == 0 ? 1 : 0;
	$count=0;
	foreach ($User_Input['validate'] as $key => $value){
	$SQL= "UPDATE RASI_Role_Mapping SET CurrentStatus='".$Status."'  WHERE Id='".$User_Input['Id'][$key]."'";
		$update = sqlsrv_query($conn,$SQL);	
		$count++;	
	}
	

	if($count >0){
		$status=1;
	}else{
		$status=0;
	}
	
echo json_encode(array('Status'=>$status));
}
else if($Action =="Recommend_STO_Material_Details")
{
	$User_Input=$_POST;


	//echo "<pre>";print_r($User_Input);exit();
	$Approved_At=date('Y-m-d H:i:s');
    $Approved_by=$_SESSION['EmpID'];
    $Rejected_At=date('Y-m-d H:i:s');
    $Rejected_by=$_SESSION['EmpID'];
    $Statement_Type=@$User_Input['Statement_Type'];
    $Limit_Exceed=@$User_Input['Limit_Exceed'];
    $CropId=@$User_Input['CropId'];
    
	$count=0;
	$Result_Array=array();
	$update_count=0;
	$Plant_Based_Details=array();
	foreach(@$User_Input['validate'] as $key=>$value)
	{
	//	$CropId=@$User_Input['CropId'][$key];

		$CropId=@$User_Input['CropId'][$key];

		$QtyInBag=@$User_Input['QtyInBag'][$key];
		$QtyInPkt=@$User_Input['QtyInPkt'][$key];
		$Product_Division=@$User_Input['SALES_ORG'][$key];
		$Sales_Indent_line_No=@$User_Input['Sales_Indent_line_No'][$key];
		$Plant_Based_Details[@$User_Input['EMPLOYEE_ID'][$key]][@$User_Input['PLANT'][$key]]['SalesIndentId'][]=$Sales_Indent_line_No;
		$QtyInKg=@$User_Input['QtyInKg'][$key];
		$SalesIndentId=@$User_Input['SalesIndentId'][$key];
		$indent_id_array[]=$Sales_Indent_line_No;
		
		$CurrentStatus = 3;
		if($Statement_Type=="Approved")
		{

			// move appoval status upper level based on their roles
			if($CurrentStatus == 3) {
				$user_role_data = sto_user_role_levels($CropId,$User_Input['EMPLOYEE_ID'][$key]);
				if($user_role_data['level_2'] == $user_role_data['level_3']) {
					$CurrentStatus='7';
				}			
			}


		$SQL="UPDATE Sales_Indent_STO_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Approved_by='".$Approved_by."',Approved_At='".$Approved_At."',CurrentStatus='".$CurrentStatus."' WHERE Id='$Sales_Indent_line_No'";
		$update=sqlsrv_query($conn,$SQL);
		$update_count++;

	    }else if($Statement_Type=="Reject")
	    {
          $SQL="UPDATE Sales_Indent_STO_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Rejected_by='".$Rejected_by."',Rejected_At='".$Rejected_At."',RejectionStatus='2' WHERE Id='$Sales_Indent_line_No'";
		    $update=sqlsrv_query($conn,$SQL);

	    }
		
		if(@$update){
			$count++;
		//	$Result_Array[]=$array;
		}
		$Approve="Recommendation_Limit_Exceed";
		
		$sql="Sales_Indent_STO_Material_status @SalesIndentId='$SalesIndentId',@Statement_Type='".$Approve."'";
		$result=sqlsrv_query($conn,$sql);
		if(@$result['Count'] == '1'){
			$SQL="update Sales_Indent_STO set CurrentStatus='".$CurrentStatus."' where SalesIndentId='$SalesIndentId' ";
			$update=sqlsrv_query($conn,$SQL);
		}
	}

	if($count >0){
		if($update_count >0){
			foreach($Plant_Based_Details as $Employee_Key=>$Employee_Value)
	{
		foreach($Employee_Value as $Plant_Key=>$Plant_Value)
		{
			$Sales_Indent_Id=implode(',', @$Plant_Value['SalesIndentId']);
			/* Send Mail For Indidual Indent No with Plant Start Here */
			$details=sqlsrv_query($conn,"EXEC Sales_Indent_STO_Details_With_Limit @STO_Indent_Id='0',@Emp_Id =0,@Product_Division =0,@Dcode =0,@Plant_id =0,@Zone_id =0,@Region_Id =0,@Terrirory_Id =0,@QuotationType =0,@SaleOrderType =0,@From_Date =NULL,@To_Date =NULL,@Status =3,@STO_Indent_Material_Id ='".$Sales_Indent_Id."',@Length =10,@Offset =0");

			//$details=sqlsrv_query($conn,"EXEC Sales_Indent_STO_Details @STO_Indent_Id='".$Sales_Indent_Id."',@Status='2'");
			$mail=new Send_Mail();
			$Employee_Name=Get_Employee_Details($conn,$_SESSION['EmpID']);
			
			$ProductDivision='fcm';
			if($Product_Division == "FC01")
			{
				$ProductDivision='fcm';
			}else if($Product_Division == "CT01"){
				$ProductDivision='ras';
			}	else if($Product_Division == "FR01"){
					$ProductDivision='frg';
				}		
			$Mail_Dets=Get_STO_Mail_Recipient_Details(@$Employee_Key,@$ProductDivision,@$CropId);
			$Plant_Mail_Dets=Get_Mail_Recipient_Details_For_Plant(@$Plant_Key);

			//$To_Mail=@$Plant_Mail_Dets['Email'];
			//$To_Mail=array('sathish.r@rasiseeds.com');
			

			///print_r($Mail_Dets);
			$to=array();
			$cc=array();
			if(@$Mail_Dets['Level_1'] !=''){
				array_push($cc,@$Mail_Dets['Level_1']);
			}
			if(@$Mail_Dets['Level_2'] !=''){
				array_push($cc,@$Mail_Dets['Level_2']);
			}
			if(@$Mail_Dets['Level_4'] !=''){
				array_push($to,@$Mail_Dets['Level_4']);
			}
			$subject="STO Indent  Recommended (Limit Exceed) By ".$Employee_Name." -reg.";
			$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sureshbabu.k@rasiseeds.com','sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
			//$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
			// $message=$mail->Generate_Mail_Tempalte_STO($details);
			// $mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
			/* Send Mail For Indidual Indent No with Plant End Here */
		}
	}
}
		$status=1;
	}else{
		$status=0;
	}
	

echo json_encode(array('Status'=>$status,'data'=>json_encode($Result_Array)));
}else if($Action =="Approve_STO_Material_Details")
{


	$User_Input=$_POST;


	//echo "<pre>";print_r($User_Input);exit();

	$Approved_At=date('Y-m-d H:i:s');
    $Approved_by=$_SESSION['EmpID'];
    $Rejected_At=date('Y-m-d H:i:s');
    $Rejected_by=$_SESSION['EmpID'];
    $Statement_Type=@$User_Input['Statement_Type'];
    $Limit_Exceed=@$User_Input['Limit_Exceed'];
    //$CropId=@$User_Input['CropId'];
	$count=0;
	$Result_Array=array();
	$update_count=0;
	$Plant_Based_Details=array();
	foreach(@$User_Input['validate'] as $key=>$value)
	{
		$array=[];
		$Product_Division=@$User_Input['SALES_ORG'][$key];
		$array['INDENT_NO']=@$User_Input['INDENT_NO'][$key];
		$array['EMPLOYEE_ID']=@$User_Input['EMPLOYEE_ID'][$key];
		$array['ACCOUNTNUM']=@$User_Input['ACCOUNTNUM'][$key];
		$array['SALES_ORG']=@$User_Input['SALES_ORG'][$key];
		$array['DIST_CHANNEL']=@$User_Input['DIST_CHANNEL'][$key];
		$array['DIVISION']=@$User_Input['DIVISION'][$key];
		$array['ZONE_ID']=@$User_Input['ZONE_ID'][$key];
		$array['REGION_ID']=@$User_Input['REGION_ID'][$key];
		//$array['TM_ID']=@$User_Input['TM_ID'][$key];
		//$array['QUOTATION_TYPE']=@$User_Input['QUOTATION_TYPE'][$key];
		//$array['SALEORDER_TYPE']=@$User_Input['SALEORDER_TYPE'][$key];
		$array['MATERIAL_NO']=@$User_Input['MATERIAL_NO'][$key];

		//$MATERIAL_NO = strToHex(@$User_Input["MATERIAL_NO"][$key]);


		//$array['MATERIAL_NO']=$MATERIAL_NO;


		$array['QUANTITY']=@$User_Input['QUANTITY'][$key];
		$array['FROM_PLANT']=@$User_Input['PLANT'][$key];
		$array['TO_PLANT']=@$User_Input['Receiving_Plant'][$key];
		$array['TO_LGORT']=@$User_Input['LGORT'][$key];
		if(@$User_Input['Receiving_Plant'][$key] =="B012"){
			$array['TO_LGORT']="SE02";
		}
		
		$array['VALID_TO_DATE']=@$User_Input['VALID_TO_DATE'][$key] !='' ? date('Ymd',strtotime(@$User_Input['VALID_TO_DATE'][$key])) : date('Ymd');
		$array['APPROVE_STATUS']=@$User_Input['APPROVE_STATUS'][$key];
		$array['RBM_EMP_VENDOR']=@$User_Input['RBM_EMP_VENDOR'][$key];
		$array['DBM_EMP_VENDOR']=@$User_Input['DBM_EMP_VENDOR'][$key];
		$array['Season_code']=@$User_Input['Season_code'][$key];
		//$array['Expected_Date']=@$User_Input['Expected_Date'][$key];

		$array['Expected_date']=@$User_Input['Expected_date'][$key];

		//$array['RBM_MAIL_STATUS']=@$User_Input['RBM_MAIL_STATUS'][$key];
		//$array['DBM_MAIL_STATUS']=@$User_Input['DBM_MAIL_STATUS'][$key];
		$CropId=@$User_Input['CropId'][$key];
		
		$QtyInBag=@$User_Input['QtyInBag'][$key];
		$QtyInPkt=@$User_Input['QtyInPkt'][$key];
		$Sales_Indent_line_No=@$User_Input['Sales_Indent_line_No'][$key];
		$Plant_Based_Details[@$User_Input['EMPLOYEE_ID'][$key]][@$User_Input['PLANT'][$key]]['SalesIndentId'][]=$Sales_Indent_line_No;
		$QtyInKg=@$User_Input['QtyInKg'][$key];
		$SalesIndentId=@$User_Input['SalesIndentId'][$key];
		$indent_id_array[]=$Sales_Indent_line_No;

		if($Statement_Type=="Approved")
		{
		$SQL="UPDATE Sales_Indent_STO_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Approved_by='".$Approved_by."',Approved_At='".$Approved_At."',CurrentStatus='4' WHERE Id='$Sales_Indent_line_No'";
		$update=sqlsrv_query($conn,$SQL);
		$update_count++;

	    }else if($Statement_Type=="Reject")
	    {
          $SQL="UPDATE Sales_Indent_STO_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Rejected_by='".$Rejected_by."',Rejected_At='".$Rejected_At."',RejectionStatus='2' WHERE Id='$Sales_Indent_line_No'";
		    $update=sqlsrv_query($conn,$SQL);

	    }
		
		if(@$update){
			$count++;
			$Result_Array[]=$array;
		}
		$Approve="Approve";
		if($Limit_Exceed >0){
			$Approve="Approve_Limit_Exceed";
		}
		$sql="Sales_Indent_STO_Material_status @SalesIndentId='$SalesIndentId',@Statement_Type='".$Approve."'";
		$result=sqlsrv_query($conn,$sql);
		if(@$result['Count'] == '1'){
			$SQL="update Sales_Indent_STO set CurrentStatus=4 where SalesIndentId='$SalesIndentId' ";
			$update=sqlsrv_query($conn,$SQL);
		}
	}

	$Post_To_SAP_Dets="";

	if($count >0){
		if($update_count >0){
			$url="http://192.168.162.213:8081/Sales_Indent/DEV/ZIN_RFC_ERAIN_UPDATE_QUOT_STO_DAS.php";
		$SAP_Json_Data=json_encode($Result_Array);
			// $Post_To_SAP_Dets=Post_SAP_Data($SAP_Json_Data,$url);
			foreach($Plant_Based_Details as $Employee_Key=>$Employee_Value)
	{
		foreach($Employee_Value as $Plant_Key=>$Plant_Value)
		{
			$Sales_Indent_Id=implode(',', @$Plant_Value['SalesIndentId']);
			/* Send Mail For Indidual Indent No with Plant Start Here */
			$details=sqlsrv_query($conn,"EXEC Sales_Indent_STO_Details_With_Limit @STO_Indent_Id='0',@Emp_Id =0,@Product_Division =0,@Dcode =0,@Plant_id =0,@Zone_id =0,@Region_Id =0,@Terrirory_Id =0,@QuotationType =0,@SaleOrderType =0,@From_Date =NULL,@To_Date =NULL,@Status ='',@STO_Indent_Material_Id ='".$Sales_Indent_Id."',@Length =10,@Offset =0");

			//$details=sqlsrv_query($conn,"EXEC Sales_Indent_STO_Details @STO_Indent_Id='".$Sales_Indent_Id."',@Status='2'");
			$mail=new Send_Mail();
			$Employee_Name=Get_Employee_Details($conn,$_SESSION['EmpID']);
			
			$ProductDivision='fcm';
			if($Product_Division == "FC01")
			{
				$ProductDivision='fcm';
			}else if($Product_Division == "CT01"){
				$ProductDivision='ras';
			}	else if($Product_Division == "FR01"){
				$ProductDivision='frg';
			}		
			// $Mail_Dets=Get_STO_Mail_Recipient_Details(@$Employee_Key,@$ProductDivision,@$CropId);
			$Plant_Mail_Dets=Get_Mail_Recipient_Details_For_Plant(@$Plant_Key);

			// $To_Mail=@$Plant_Mail_Dets['Email'];
			$To_Mail=array('sathish.r@rasiseeds.com');
//print_r($Mail_Dets);
			
			$to=array();
			$cc=array();


			if($ProductDivision == 'frg'){

     $to=array('ts.logistics@rasiseeds.com',"fcdispatch@rasiseeds.com","gowrishankar.ch@rasiseeds.com","anandbabu.chidurala@rasiseeds.com","Shaikh.m@rasiseeds.com","prashanth.t@rasiseeds.com");


			if(@$Mail_Dets['Level_1'] !=''){
				array_push($cc,@$Mail_Dets['Level_1']);
			}

			if(@$Mail_Dets['Level_2'] !=''){
				array_push($cc,@$Mail_Dets['Level_2']);
			}
			if(@$Mail_Dets['Level_4'] !=''){
				array_push($cc,@$Mail_Dets['Level_4']);
			}
			




			}else{



				foreach($Plant_Mail_Dets as $value)
			{
					array_push($to,$value);
			}
			if(@$Mail_Dets['Level_1'] !=''){
				array_push($cc,@$Mail_Dets['Level_1']);
			}

			if(@$Mail_Dets['Level_2'] !=''){
				array_push($cc,@$Mail_Dets['Level_2']);
			}
			if(@$Mail_Dets['Level_4'] !=''){
				array_push($cc,@$Mail_Dets['Level_4']);
			}




			}
	
			
			$subject="STO Indent Approved (With In Limit) By ".$Employee_Name." -reg.";

			if($Limit_Exceed >0){
				if(@$Mail_Dets['Level_3'] !=''){
				array_push($cc,@$Mail_Dets['Level_3']);
			}
			$subject="STO Indent  Approved (Limit Exceed) By ".$Employee_Name." -reg.";
			}

			
			$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sureshbabu.k@rasiseeds.com','sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
///$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');

			// $message=$mail->Generate_Mail_Tempalte_STO($details,"Approve");
			// $mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
			/* Send Mail For Indidual Indent No with Plant End Here */
		}
	}
/*$Sales_Indent_Id=implode(',', $indent_id_array);
$details=sqlsrv_query($conn,"EXEC Sales_Indent_STO_Details @STO_Indent_Id='".$Sales_Indent_Id."',@Status='2'");
$mail=new Send_Mail();
$subject="STO Indent Approved  -reg.";
$cc=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
$to=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
$bcc=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
$message=$mail->Generate_Mail_Tempalte_STO($details);
$mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc); */
}
		$status=1;
	}else{
		$status=0;
	}
	
	//print_r($Result_Array);exit;
echo json_encode(array('Status'=>$status,'data'=>json_encode($Result_Array),'SAP_Status'=>json_encode($Post_To_SAP_Dets)));
}else if($Action =="Get_Season_Code_Details"){
	$region_id=@$_POST['region_id'];
	$material_id=@$_POST['material_id'];
	$Season_Code=@$_POST['Season'];
	$sql="EXEC Sales_Indent_Season_Code_details @Material_Code='".@$material_id."',@Region_id='".@$region_id."'";

  $sql_for_Season_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_Season_details);
  $option="";
  $count=0;
 while($result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC))
 {
 	$count++;
	 $selected=@$Season_Code==$result["SEASONCODE"] ? "Selected" : "";
 	$option.='<option value="'.$result["SEASONCODE"].'" '.@$selected.'>'.$result["SHORTDESC"].'</option>';
 }

$result="";
 if($count == 1){
$result=$option;
 }else if($count == 0){
 	$result='<option value="">Select Season</option>';
 }else{
 	$result='<option value="">Select Season</option>';
 	$result.=$option;

 }

 echo  json_encode(array('data' => $result,'sql'  => $sql));
}else if($Action =="Get_Season_Code_Details_multiple"){
	$region_id=@$_POST['region_id'];
	$material_id=@$_POST['material_id'];
	$sql="EXEC Sales_Indent_Season_Code_details_Multiple @Material_Code='".@$material_id."',@Region_id='".@$region_id."'";

  $sql_for_Season_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_Season_details);
  $option="";
  $count=0;
 while($result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC))
 {
 	$count++;
	 $selected=@$Season_Code==$result["SEASONCODE"] ? "Selected" : "";
 	$option.='<option value="'.$result["SEASONCODE"].'" '.@$selected.'>'.$result["SHORTDESC"].'</option>';
 }

$result="";
 if($count == 1){
$result=$option;
 }else if($count == 0){
 	$result='<option value="">Select Season</option>';
 }else{
 	$result='<option value="">Select Season</option>';
 	$result.=$option;

 }

 echo  json_encode(array('data' => $result,'sql'  => $sql));
}else if($Action =="Sto_Validate_Material_Details")
{
	$User_Input=$_POST;
	 $Validate_At=date('Y-m-d H:i:s');
     $Validate_by=$_SESSION['EmpID']; 
     $Rejected_At=date('Y-m-d H:i:s');
     $Rejected_by=$_SESSION['EmpID'];
     $Statement_Type=@$User_Input['Statement_Type'];
     $count=0;
	 $indent_id_array=array();
	 $update_count=0;
	 // $Validate_At=date('Y-m-d H:i:s');
    // $Validate_by=$_SESSION['EmpID'];
	$count=0;
	foreach(@$User_Input['validate'] as $key=>$value)
	{
		$QtyInBag=@$User_Input['QtyInBag'][$key];
		$Product_Division=@$User_Input['SALES_ORG'][$key];
		$QtyInPkt=@$User_Input['QtyInPkt'][$key];
		$Sales_Indent_line_No=@$User_Input['Sales_Indent_line_No'][$key];
		$QtyInKg=@$User_Input['QtyInKg'][$key];
		$SalesIndentId=@$User_Input['SalesIndentId'][$key];
		$indent_id_array[]=$SalesIndentId;

		if($Statement_Type=="Approve")
		{
		
	   $SQL="UPDATE Sales_Indent_STO_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Validate_by='".$Validate_by."',Validate_At='".$Validate_At."',CurrentStatus='2' WHERE Id='$Sales_Indent_line_No'";
		$update=sqlsrv_query($conn,$SQL);
		$update_count++;
	    }else if($Statement_Type=="Reject")
		{
			 $SQL="UPDATE Sales_Indent_STO_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Rejected_by='".$Rejected_by."',Rejected_At='".$Rejected_At."',RejectionStatus='2' WHERE Id='$Sales_Indent_line_No'";
		    $update=sqlsrv_query($conn,$SQL);

		}
		if($update){
			
			$count++;
		}
		$sql="Sales_Indent_STO_Material_status @SalesIndentId='$SalesIndentId',@Statement_Type='Validate'";
		$result=sqlsrv_query($conn,$sql);
		if(@$result['Count'] == '1'){
			$SQL="update Sales_Indent_STO set CurrentStatus=2 where SalesIndentId='$SalesIndentId' ";
			$update=sqlsrv_query($conn,$SQL);
		}
	}

	if($count >0){
		if($update_count >0){
$Sales_Indent_Id=implode(',', $indent_id_array);
$details=sqlsrv_query($conn,"EXEC Sales_Indent_STO_Details @STO_Indent_Id='".$Sales_Indent_Id."',@Status='2'");
$mail=new Send_Mail();
$subject="STO Indent Approve Request -reg.";
$cc=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
$to=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
$bcc=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
$message=$mail->Generate_Mail_Tempalte_STO($details);
//$mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
}

		$status=1;
	}else{
		$status=0;
	}
echo json_encode(array('Status'=>$status));
}else if($Action=="Get_Sales_Plan_Details"){
	$zone_id=@$_POST['zone_id'];
	$region_id=@$_POST['region_id'];
	$material_id=@$_POST['material_id'];
	$season=@$_POST['season'];
	$product_division=@$_POST['Product_Division'];
	$sql="EXEC Sales_Indent_Get_Sales_Plan_Details_With_Season @Product_Code='".@$material_id."',@Region_id='".@$region_id."',@Zone_Id='".@$zone_id."',@Product_Division='".@$product_division."',@Season='".@$season."'";

  $sql_for_Season_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_Season_details);
  $option="";
  $count=0;
  $result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
  $Sales_Plan=@$result['Plan_Qty'];
  echo json_encode(array('qty'=>$Sales_Plan,"sql"=>$sql));exit;
	
}else if($Action=="Get_Indent_Qty"){
	$zone_id=@$_POST['zone_id'];
	$region_id=@$_POST['region_id'];
	$material_id=@$_POST['material_id'];
	$product_division=@$_POST['Product_Division'];
	$CropId=@$_POST['CropId'];
	$PlantId=@$_POST['PlantId'];
	$season=@$_POST['season'];
	//exec Sales_Indent_STO_Indent_Qty_Details @ProductDivision='ras',@ZoneId='S100',@RegionId='AP01',@PlantId='B019',@CropId='014',@MaterialCode='RASIMAGIC386TL475dG'
	$sql="EXEC Sales_Indent_STO_Indent_Qty_Details_WITH_SEASON @MaterialCode='".@$material_id."',@RegionId='".@$region_id."',@ZoneId='".@$zone_id."',@ProductDivision='".@$product_division."',@PlantId='".@$PlantId."',@CropId='".@$CropId."',@Season='".@$season."'";

  $sql_for_Season_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_Season_details);
  $option="";
  $count=0;
  $result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
  $Sales_Plan=@$result['Indent_Qty'];
  echo json_encode(array('qty'=>$Sales_Plan,"sql"=>$sql));exit;
	
}else if($Action=="Get_Customer_Balance_Details"){

	$customer_id=@$_POST['customer_id'];
	$sql="EXEC Get_Customer_Balance_Details @Customer_Code='".@$customer_id."'";
  $sql_for_Season_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_Season_details);
  $option="";
  $count=0;
  $result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
  $BALANCE=@$result['BALANCE'] !='' ? @$result['BALANCE'] : 0 ;
  $CREDIT_LIMIT=@$result['CREDIT_LIMIT'] !='' ? @$result['CREDIT_LIMIT'] : 0 ;
  echo json_encode(array('BALANCE'=>$BALANCE,'CREDIT_LIMIT'=>$CREDIT_LIMIT,"sql"=>$sql));exit;

}else if($Action=="Get_Material_Price_Details"){

	$Date=date('Y-m-d');
	$Distribution_Channel='TR';
	$Product_Division=@$_POST['Product_Division'];
	$Material=@$_POST['Material'];
	$Customer=@$_POST['Customer'];

	
	$sql="EXEC Sales_Indent_Get_Material_Sales_Price @Date='".$Date."',@Distribution_Channel='".$Distribution_Channel."',@Product_Division='".$Product_Division."',@Material='".$Material."',@Customer='".$Customer."'";
  $sql_for_Season_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_Season_details);
  $option="";
  $count=0;
  $result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
  $Price=@$result['Price'] !='' ? @$result['Price'] : 0 ;
  echo json_encode(array('Price'=>$Price,"sql"=>$sql));exit;

}else if($Action=="Get_Material_Price_Details_multiple"){

	$Date=date('Y-m-d');
	$Distribution_Channel='TR';
	$Product_Division=@$_POST['Product_Division'];
	$Material=@$_POST['Material'];
	$Customer=@$_POST['Customer'];
	$region_id=$_POST['region_id'];


	$sql="EXEC Sales_Indent_Get_Material_Sales_Price_multiple @Date='".$Date."',@Distribution_Channel='".$Distribution_Channel."',@Product_Division='".$Product_Division."',@Material='".$Material."',@Customer='".$Customer."',@Region_id='".$region_id."'";
  $sql_for_Season_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_Season_details);
  $option="";
  $count=0;
  $result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
  $Price=@$result['Price'] !='' ? @$result['Price'] : 0 ;
  echo json_encode(array('Price'=>$Price,"sql"=>$sql));exit;

}else if($Action=="Get_Prodcut_and_Zone_Based_Discount"){

	$Date=date('Y-m-d');
	$Distribution_Channel='TR';
	$Product_Division=@$_POST['Product_Division'];
	$Material=@$_POST['Material'];
	$Customer=@$_POST['Customer'];

	
	$sql="EXEC Sales_Indent_Get_Material_Discount_Price_FCM @Date='".$Date."',@Distribution_Channel='".$Distribution_Channel."',@Product_Division='".$Product_Division."',@Material='".$Material."',@Customer='".$Customer."'";
  $sql_for_Season_details=sqlsrv_prepare($conn,$sql);
  sqlsrv_execute($sql_for_Season_details);
  $option="";
  $count=0;
  $result = sqlsrv_fetch_array($sql_for_Season_details,SQLSRV_FETCH_ASSOC);
  $discount=@$result['KBETR'] !='' ? @$result['KBETR'] : 0 ;
  echo json_encode(array('discount'=>$discount,"sql"=>$sql));exit;

}else if($Action =="Approve_Customer_Collection_Details")
{
	$User_Input=$_POST;
	 $Validate_At=date('Y-m-d H:i:s');
     $Validate_by=$_SESSION['EmpID'];
     $Rejected_At=date('Y-m-d H:i:s');
     $Rejected_by=$_SESSION['EmpID'];
     $Statement_Type=@$User_Input['Statement_Type'];
	 // $Validate_At=date('Y-m-d H:i:s');
    // $Validate_by=$_SESSION['EmpID'];
	$count=0;
	$indent_id_array=array();
	$update_count=0;
	foreach(@$User_Input['validate'] as $key=>$value)
	{
		
		 $id=@$User_Input['Id'][$key];
		if($Statement_Type=="Approve")
		{
			$SQL="update Sales_Indent_Customer_Collection set Status='2',Approved_By='".$Rejected_by."',Approved_At='".$Rejected_At."' where id=$id";
		    $update=sqlsrv_query($conn,$SQL);
		    $update_count++;
		}else if($Statement_Type=="Reject")
		{
			$SQL="update Sales_Indent_Customer_Collection set RejectionStatus='2',Rejected_By='".$Rejected_by."',Rejected_At='".$Rejected_At."' where id=$id";

		    $update=sqlsrv_query($conn,$SQL);

		}

	  
		if($update){
			
			$count++;
		}
		
	}
	if($count >0){
	

		$status=1;
	}else{
		$status=0;
	}
echo json_encode(array('Status'=>$status));
}

else if($Action =="Validate_STO_Material_Details_With_Limit")
{
	$User_Input=$_POST;


	//echo "<pre>";print_r($User_Input);exit();
	$Validate_At=date('Y-m-d H:i:s');
    $Validate_by=$_SESSION['EmpID'];
    $Rejected_At=date('Y-m-d H:i:s');
    $Rejected_by=$_SESSION['EmpID'];
    $Statement_Type=@$User_Input['Statement_Type'];
    $Limit_Exceed=@$User_Input['Limit_Exceed'];
    $CropId=@$User_Input['CropId'];
    
	$count=0;
	$Result_Array=array();
	$update_count=0;
	$Plant_Based_Details=array();
	foreach(@$User_Input['validate'] as $key=>$value)
	{
	//	$CropId=@$User_Input['CropId'][$key];

		$CropId=@$User_Input['CropId'][$key];

		$QtyInBag=@$User_Input['QtyInBag'][$key];
		$QtyInPkt=@$User_Input['QtyInPkt'][$key];
		$Product_Division=@$User_Input['SALES_ORG'][$key];
		$Sales_Indent_line_No=@$User_Input['Sales_Indent_line_No'][$key];
		$Plant_Based_Details[@$User_Input['EMPLOYEE_ID'][$key]][@$User_Input['PLANT'][$key]]['SalesIndentId'][]=$Sales_Indent_line_No;
		$QtyInKg=@$User_Input['QtyInKg'][$key];
		$SalesIndentId=@$User_Input['SalesIndentId'][$key];
		$indent_id_array[]=$Sales_Indent_line_No;
		if($Statement_Type=="Approved")
		{
		$SQL="UPDATE Sales_Indent_STO_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Validate_by='".$Validate_by."',Validate_At='".$Validate_At."',CurrentStatus='5' WHERE Id='$Sales_Indent_line_No'";
		$update=sqlsrv_query($conn,$SQL);
		$update_count++;

	    }else if($Statement_Type=="Reject")
	    {
          $SQL="UPDATE Sales_Indent_STO_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Rejected_by='".$Rejected_by."',Rejected_At='".$Rejected_At."',RejectionStatus='2' WHERE Id='$Sales_Indent_line_No'";
		    $update=sqlsrv_query($conn,$SQL);

	    }
		
		if(@$update){
			$count++;
			//$Result_Array[]=$array;
		}
		$Approve="Recommendation_Limit_Exceed";
		
		$sql="Sales_Indent_STO_Material_status @SalesIndentId='$SalesIndentId',@Statement_Type='".$Approve."'";
		$result=sqlsrv_query($conn,$sql);
		if(@$result['Count'] == '1'){
			$SQL="update Sales_Indent_STO set CurrentStatus=3 where SalesIndentId='$SalesIndentId' ";
			$update=sqlsrv_query($conn,$SQL);
		}
	}

	if($count >0){
		if($update_count >0){
			foreach($Plant_Based_Details as $Employee_Key=>$Employee_Value)
	{
		foreach($Employee_Value as $Plant_Key=>$Plant_Value)
		{
			$Sales_Indent_Id=implode(',', @$Plant_Value['SalesIndentId']);
			/* Send Mail For Indidual Indent No with Plant Start Here */
			$details=sqlsrv_query($conn,"EXEC Sales_Indent_STO_Details_With_Limit @STO_Indent_Id='0',@Emp_Id =0,@Product_Division =0,@Dcode =0,@Plant_id =0,@Zone_id =0,@Region_Id =0,@Terrirory_Id =0,@QuotationType =0,@SaleOrderType =0,@From_Date =NULL,@To_Date =NULL,@Status ='',@STO_Indent_Material_Id ='".$Sales_Indent_Id."',@Length =10,@Offset =0");


		//	echo "EXEC Sales_Indent_STO_Details_With_Limit @STO_Indent_Id='0',@Emp_Id =0,@Product_Division =0,@Dcode =0,@Plant_id =0,@Zone_id =0,@Region_Id =0,@Terrirory_Id =0,@QuotationType =0,@SaleOrderType =0,@From_Date =NULL,@To_Date =NULL,@Status ='',@STO_Indent_Material_Id ='".$Sales_Indent_Id."',@Length =10,@Offset =0";

			//$details=sqlsrv_query($conn,"EXEC Sales_Indent_STO_Details @STO_Indent_Id='".$Sales_Indent_Id."',@Status='2'");
			$mail=new Send_Mail();
			$Employee_Name=Get_Employee_Details($conn,$_SESSION['EmpID']);
			
			$ProductDivision='fcm';
			if($Product_Division == "FC01")
			{
				$ProductDivision='fcm';
			}else if($Product_Division == "CT01"){
				$ProductDivision='ras';
			}		else if($Product_Division == "FR01"){
				$ProductDivision='frg';

			}
			// $Mail_Dets=Get_STO_Mail_Recipient_Details(@$Employee_Key,@$ProductDivision,@$CropId);
			// $Plant_Mail_Dets=Get_Mail_Recipient_Details_For_Plant(@$Plant_Key);

			//$To_Mail=@$Plant_Mail_Dets['Email'];
			//$To_Mail=array('sathish.r@rasiseeds.com');
			

		//print_r($Mail_Dets);
			$to=array();
			$cc=array();
			if(@$Mail_Dets['Level_1'] !=''){
				array_push($cc,@$Mail_Dets['Level_1']);
			}
			if(@$Mail_Dets['Level_2'] !=''){
				array_push($cc,@$Mail_Dets['Level_2']);
			}
			if(@$Mail_Dets['Level_4'] !=''){
				array_push($to,@$Mail_Dets['Level_4']);
			}
			$subject="STO Indent  Recommended (With Limit) By ".$Employee_Name." -reg.";
			// $bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sureshbabu.k@rasiseeds.com','sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
		///	$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
			// $message=$mail->Generate_Mail_Tempalte_STO($details);
			// $mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
			/* Send Mail For Indidual Indent No with Plant End Here */
		}
	}
}
		$status=1;
	}else{
		$status=0;
	}
	

echo json_encode(array('Status'=>$status,'data'=>json_encode($Result_Array)));
}
else if($Action =="Validate_STO_Material_Details_Limit_Exceed")
{
	$User_Input=$_POST;


	//echo "<pre>";print_r($User_Input);exit();
	$Approved_At=date('Y-m-d H:i:s');
    $Approved_by=$_SESSION['EmpID'];
    $Rejected_At=date('Y-m-d H:i:s');
    $Rejected_by=$_SESSION['EmpID'];
    $Statement_Type=@$User_Input['Statement_Type'];
    $Limit_Exceed=@$User_Input['Limit_Exceed'];
    $CropId=@$User_Input['CropId'];
    
	$count=0;
	$Result_Array=array();
	$update_count=0;
	$Plant_Based_Details=array();
	foreach(@$User_Input['validate'] as $key=>$value)
	{
	//	$CropId=@$User_Input['CropId'][$key];

		$CropId=@$User_Input['CropId'][$key];

		$QtyInBag=@$User_Input['QtyInBag'][$key];
		$QtyInPkt=@$User_Input['QtyInPkt'][$key];
		$Product_Division=@$User_Input['SALES_ORG'][$key];
		$Sales_Indent_line_No=@$User_Input['Sales_Indent_line_No'][$key];
		$Plant_Based_Details[@$User_Input['EMPLOYEE_ID'][$key]][@$User_Input['PLANT'][$key]]['SalesIndentId'][]=$Sales_Indent_line_No;
		$QtyInKg=@$User_Input['QtyInKg'][$key];
		$SalesIndentId=@$User_Input['SalesIndentId'][$key];
		$indent_id_array[]=$Sales_Indent_line_No;
		if($Statement_Type=="Approved")
		{
		$SQL="UPDATE Sales_Indent_STO_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Approved_by='".$Approved_by."',Approved_At='".$Approved_At."',CurrentStatus='7' WHERE Id='$Sales_Indent_line_No'";
		$update=sqlsrv_query($conn,$SQL);
		$update_count++;

	    }else if($Statement_Type=="Reject")
	    {
          $SQL="UPDATE Sales_Indent_STO_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Rejected_by='".$Rejected_by."',Rejected_At='".$Rejected_At."',RejectionStatus='2' WHERE Id='$Sales_Indent_line_No'";
		    $update=sqlsrv_query($conn,$SQL);

	    }
		
		if(@$update){
			$count++;
		//	$Result_Array[]=$array;
		}
		$Approve="Recommendation_Limit_Exceed";
		
		$sql="Sales_Indent_STO_Material_status @SalesIndentId='$SalesIndentId',@Statement_Type='".$Approve."'";
		$result=sqlsrv_query($conn,$sql);
		if(@$result['Count'] == '1'){
			$SQL="update Sales_Indent_STO set CurrentStatus=3 where SalesIndentId='$SalesIndentId' ";
			$update=sqlsrv_query($conn,$SQL);
		}
	}

	if($count >0){
		if($update_count >0){
			foreach($Plant_Based_Details as $Employee_Key=>$Employee_Value)
	{
		foreach($Employee_Value as $Plant_Key=>$Plant_Value)
		{
			$Sales_Indent_Id=implode(',', @$Plant_Value['SalesIndentId']);
			/* Send Mail For Indidual Indent No with Plant Start Here */
			$details=sqlsrv_query($conn,"EXEC Sales_Indent_STO_Details_With_Limit @STO_Indent_Id='0',@Emp_Id =0,@Product_Division =0,@Dcode =0,@Plant_id =0,@Zone_id =0,@Region_Id =0,@Terrirory_Id =0,@QuotationType =0,@SaleOrderType =0,@From_Date =NULL,@To_Date =NULL,@Status ='',@STO_Indent_Material_Id ='".$Sales_Indent_Id."',@Length =10,@Offset =0");

			//$details=sqlsrv_query($conn,"EXEC Sales_Indent_STO_Details @STO_Indent_Id='".$Sales_Indent_Id."',@Status='2'");
			$mail=new Send_Mail();
			$Employee_Name=Get_Employee_Details($conn,$_SESSION['EmpID']);
			
			$ProductDivision='fcm';
			if($Product_Division == "FC01")
			{
				$ProductDivision='fcm';
			}else if($Product_Division == "CT01"){
				$ProductDivision='ras';
			}		
			$Mail_Dets=Get_STO_Mail_Recipient_Details(@$Employee_Key,@$ProductDivision,@$CropId);
			$Plant_Mail_Dets=Get_Mail_Recipient_Details_For_Plant(@$Plant_Key);

			//$To_Mail=@$Plant_Mail_Dets['Email'];
			//$To_Mail=array('sathish.r@rasiseeds.com');
			

		///	print_r($Mail_Dets);
			$to=array();
			$cc=array();
			if(@$Mail_Dets['Level_1'] !=''){
				array_push($cc,@$Mail_Dets['Level_1']);
			}
			if(@$Mail_Dets['Level_2'] !=''){
				array_push($cc,@$Mail_Dets['Level_2']);
			}
			if(@$Mail_Dets['Level_3'] !=''){
				array_push($to,@$Mail_Dets['Level_3']);
			}

			if(@$Mail_Dets['Level_4'] !=''){
				array_push($cc,@$Mail_Dets['Level_4']);
			}
			$subject="STO Indent  Validate (Limit Exceed) By ".$Employee_Name." -reg.";
			$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sureshbabu.k@rasiseeds.com','sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
		//	$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
			// $message=$mail->Generate_Mail_Tempalte_STO($details);
			// $mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
			/* Send Mail For Indidual Indent No with Plant End Here */
		}
	}
}
		$status=1;
	}else{
		$status=0;
	}
	

echo json_encode(array('Status'=>$status,'data'=>json_encode($Result_Array)));
}

else if($Action =="Approve_Material_Details_Direct")
{

	$User_Input=$_POST;


	//	echo "<pre>";print_r($User_Input);exit();
	$Approved_At=date('Y-m-d H:i:s');
    $Approved_by=$_SESSION['EmpID'];
    $Rejected_At=date('Y-m-d H:i:s');
    $Rejected_by=$_SESSION['EmpID'];
    $Statement_Type=@$User_Input['Statement_Type'];
    $SupplyType=@$User_Input['supply_type_id'];
    $Limit_Exceed=@$User_Input['Limit_Exceed'];
	$count=0;
	$Result_Array=array();
	$update_count=0;
	$Employee_Based_Details=array();
	foreach(@$User_Input['validate'] as $key=>$value)
	{
		$Product_Division=@$User_Input['SALES_ORG'][$key];
		$CropId=@$User_Input['CropId'][$key];
		$array=[];
		$array['INDENT_NO']=@$User_Input['INDENT_NO'][$key];
		$array['EMPLOYEE_ID']=@$User_Input['EMPLOYEE_ID'][$key];
		$array['ACCOUNTNUM']=@$User_Input['ACCOUNTNUM'][$key];
		$array['SALES_ORG']=@$User_Input['SALES_ORG'][$key];
		$array['DIST_CHANNEL']=@$User_Input['DIST_CHANNEL'][$key];
		$array['DIVISION']=@$User_Input['DIVISION'][$key];
		$array['ZONE_ID']=@$User_Input['ZONE_ID'][$key];
		$array['REGION_ID']=@$User_Input['REGION_ID'][$key];
		$array['TM_ID']=@$User_Input['TM_ID'][$key];
		$array['QUOTATION_TYPE']=@$User_Input['QUOTATION_TYPE'][$key];
		$array['SALEORDER_TYPE']=@$User_Input['SALEORDER_TYPE'][$key];


		$array['MATERIAL_NO']=@$User_Input['MATERIAL_NO'][$key];

		//$MATERIAL_NO = strToHex(@$User_Input["MATERIAL_NO"][$key]);


		//$array['MATERIAL_NO']=$MATERIAL_NO;


		$array['QUANTITY']=@$User_Input['QtyInPkt'][$key];
		$array['PLANT']=@$User_Input['PLANT'][$key];
		$array['LGORT']=@$User_Input['LGORT'][$key];
		//$array['LGORT']=@$User_Input['LGORT'][$key];
	/*	if(@$User_Input['PLANT'][$key] =="B012"){
			$array['LGORT']='SE02';
		}*/
		
		$array['INDENT_TYPES']=@$User_Input['INDENT_TYPES'][$key];
		if(@$array['INDENT_TYPES'] == '')
		{
			$array['INDENT_TYPES']=$SupplyType == 1 ? "DS" : "CS";
		}
		
		$array['VALID_TO_DATE']=date('Ymd');
		$array['APPROVE_STATUS']=@$User_Input['APPROVE_STATUS'][$key];
		$array['RBM_EMP_VENDOR']=@$User_Input['RBM_EMP_VENDOR'][$key];
		$array['DBM_EMP_VENDOR']=@$User_Input['DBM_EMP_VENDOR'][$key];
		$array['RBM_MAIL_STATUS']=@$User_Input['RBM_MAIL_STATUS'][$key];
		$array['DBM_MAIL_STATUS']=@$User_Input['DBM_MAIL_STATUS'][$key];
		$array['Season_code']=@$User_Input['Season_code'][$key];
		$array['PLACE']=@$User_Input['PLACE'][$key];
		$array['PLACE']=str_replace("&", " ", @$User_Input['PLACE'][$key]);
		$array['MOBILE_NO']=@$User_Input['MOBILE_NO'][$key];
		$array['Expected_date']=@$User_Input['Expected_date'][$key];
		$plant=@$User_Input['PLANT'][$key];

		
		$QtyInBag=@$User_Input['QtyInBag'][$key];
		$CropId=@$User_Input['CropId'][$key];
		$QtyInPkt=@$User_Input['QtyInPkt'][$key];
		$Sales_Indent_line_No=@$User_Input['Sales_Indent_line_No'][$key];
		$QtyInKg=@$User_Input['QtyInKg'][$key];
		$SalesIndentId=@$User_Input['SalesIndentId'][$key];
		$Employee_Based_Details[@$User_Input['EMPLOYEE_ID'][$key]][$plant]['SalesIndentId'][]=$Sales_Indent_line_No;
		$indent_id_array[]=$SalesIndentId;
		if(trim($Statement_Type)=="Approved")
		{
	
			 $SQL="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Approved_by='".$Approved_by."',Approved_At='".$Approved_At."',CurrentStatus='5' WHERE Id='$Sales_Indent_line_No'";
		    $update=sqlsrv_query($conn,$SQL);
		       $update_count++;
		}else if($Statement_Type=="Reject")
		{
			 $SQL="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Rejected_by='".$Rejected_by."',Rejected_At='".$Rejected_At."',RejectionStatus='2' WHERE Id='$Sales_Indent_line_No'";
		    $update=sqlsrv_query($conn,$SQL);

		}

	/*	$SQL="UPDATE Sales_Indent_Material_Details SET QtyInBag='$QtyInBag' ,QtyInKg='$QtyInKg',QtyInPkt='$QtyInPkt',Approved_by='".$Approved_by."',Approved_At='".$Approved_At."',CurrentStatus='3' WHERE Id='$Sales_Indent_line_No'";
		$update=sqlsrv_query($conn,$SQL);*/
		
		if(@$update){
			$count++;
			$Result_Array[]=$array;
		}
		$sql="Sales_Indent_Material_status @SalesIndentId='$SalesIndentId',@Statement_Type='Validate'";
		$result=sqlsrv_query($conn,$sql);
		if(@$result['Count'] == '1'){
			$SQL="update Sales_Indent set CurrentStatus=2 where SalesIndentId='$SalesIndentId' ";
			$update=sqlsrv_query($conn,$SQL);
		}
	}
	//echo "<pre>";print_r($Employee_Based_Details);

	
	$Post_To_SAP_Dets="";
	$Sales_indnet_Line_No_String="";
	if($count >0){
		if($update_count >0){
	$url="http://192.168.162.213:8081/Sales_Indent/DEV/ZIN_RFC_ERAIN_UPDATE_QUOT_DAS.php";
			$SAP_Json_Data=json_encode($Result_Array);
			// $Post_To_SAP_Dets=Post_SAP_Data($SAP_Json_Data,$url); 
			//print_r($Post_To_SAP_Dets);

		$credit_limit_excceed_cc=array();	
	foreach($Employee_Based_Details as $Employee_Key=>$Employee_Value)
	{
		foreach($Employee_Value as $Plant_Key=>$Plant_Value)
		{
			$Sales_indnet_Line_No_String="";
			$Sales_Indent_Id=implode(',', @$Plant_Value['SalesIndentId']);
			if($Limit_Exceed == 1){
				if($Sales_indnet_Line_No_String != ""){
					$Sales_indnet_Line_No_String.=",";
				}
				$Sales_indnet_Line_No_String.=$Sales_Indent_Id;
			}
			/* Send Mail For Indidual Indent No with Plant Start Here */
			$details=sqlsrv_query($conn,"EXEC Sales_Indent_Report_Details_With_Crop @Sales_Indent_Material_Id='".$Sales_Indent_Id."',@Status='5'");
			$mail=new Send_Mail();
			$Employee_Name=Get_Employee_Details($conn,$_SESSION['EmpID']);
			$subject="Sales Indent Approved (With In Limit) By ".$Employee_Name." -reg.";
			$Type="C&F_Supply";
			if($SupplyType==1){
				$Type="Direct_Supply";
			}else if($SupplyType==2){
				$Type="C&F_Supply";
			}
			$ProductDivision='fcm';
			if($Product_Division == "FC01")
			{
				$ProductDivision='fcm';
			}else if($Product_Division == "CT01"){
				$ProductDivision='ras';
			}	else if($Product_Division == "FR01"){
					$ProductDivision='frg';
				}		
			$Mail_Dets=Get_Mail_Recipient_Details(@$Employee_Key,@$ProductDivision,@$Type,@$CropId);
			$Plant_Mail_Dets=Get_Mail_Recipient_Details_For_Plant(@$Plant_Key);
			$To_Mail=@$Mail_Dets['Level_1'];
			$CC_Mail=@$Mail_Dets['Level_2'];
			$to=array();
			$cc=array();



		//	print_r($Mail_Dets);
		//	print_r($Plant_Mail_Dets);
			if($Limit_Exceed == 0){
				$subject="Sales Indent Approved  (With In Limit) By ".$Employee_Name." -reg.";
				if(@$Mail_Dets['Level_1'] !=''){
				array_push($to,@$Mail_Dets['Level_1']);
				foreach($Plant_Mail_Dets as $value){
					array_push($to,$value);
				}
			}

			if(@$Mail_Dets['Level_2'] !=''){
				array_push($cc,@$Mail_Dets['Level_2']);
			}

			if($Product_Division == "FC01"){


			if(@$Mail_Dets['Level_3'] !=''){
				array_push($cc,@$Mail_Dets['Level_3']);
			}

		}

		if($SupplyType==1 ){


			if($Approved_by=='RS6521'){


			array_push($cc,'prasanth.bashetti@rasiseeds.com');

				


			}

		}



			}else if($Limit_Exceed == 1){
				$credit_limit_excceed_cc=array();
				$subject="Sales Indent Approved (Limit Exceed) By ".$Employee_Name." -reg.";
			foreach($Plant_Mail_Dets as $value)
			{
					array_push($to,$value);
					array_push($credit_limit_excceed_cc,@$value);
			}
			if(@$Mail_Dets['Level_1'] !=''){
				array_push($to,@$Mail_Dets['Level_1']);
				array_push($credit_limit_excceed_cc,@$Mail_Dets['Level_1']);

			}

			if(@$Mail_Dets['Level_2'] !=''){
				array_push($cc,@$Mail_Dets['Level_2']);
				array_push($credit_limit_excceed_cc,@$Mail_Dets['Level_2']);
			}

			if(@$Mail_Dets['Level_3'] !=''){
				array_push($cc,@$Mail_Dets['Level_3']);
				array_push($credit_limit_excceed_cc,@$Mail_Dets['Level_3']);
			}

			if(@$Mail_Dets['Level_4'] !=''){
				array_push($cc,@$Mail_Dets['Level_4']);
			}
			}
			$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sureshbabu.k@rasiseeds.com',"sathish.r@rasiseeds.com",'saravanakumaran.n@rasiseeds.com');

			//$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com','sureshbabu.k@rasiseeds.com');


///$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
			///$bcc=array('');


						// $to  = ['jr_developer4@mazenetsolution.com'];
						// $cc  = ['',''];
						// $bcc = ['',''];
			// $message=$mail->Generate_Mail_Tempalte($details,"Approve");
			// $mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
			/* Send Mail For Indidual Indent No with Plant End Here */

			/* Credit Limit Exceed Mail For Finance Team Start Here */
if($Limit_Exceed == 1 && $Sales_indnet_Line_No_String !="")
{
	$Credit_Limit_Exceed_Mail=new Send_Mail();
	$Sql_Connection=sqlsrv_query($conn,"EXEC Sales_Indent_Details_For_Credit_Limit_Exceed_NEW_QUERY @SalesIndnetId='".$Sales_indnet_Line_No_String."'");

	///echo "EXEC Sales_Indent_Details_For_Credit_Limit_Exceed @SalesIndnetId='".$Sales_indnet_Line_No_String."'";

	//print_r($Sql_Connection);
	// $Credit_Limit_Exceed_Mail_Template=$Credit_Limit_Exceed_Mail->Generate_Mail_Tempalte_For_Credit_Limit_Exceed_New_changed($Sql_Connection);
	//$to=array("vinothkumar.t@rasiseeds.com");
	$to=array("harsha.daga@rasiseeds.com");
	$cc=array();
//	$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com","sathish.r@rasiseeds.com",'saravanakumaran.n@rasiseeds.com');
	//$bcc=array('sathish.r@rasiseeds.com');
		$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com","sathish.r@rasiseeds.com",'saravanakumaran.n@rasiseeds.com');
	if($Credit_Limit_Exceed_Mail_Template !=''){
 	// $Credit_Limit_Exceed_Mail->Send_Mail_Details("Credit Limit Excceed - Reg",$Credit_Limit_Exceed_Mail_Template,$to,$credit_limit_excceed_cc,$bcc);
	}
   
}
/* Credit Limit Exceed Mail For Finance Team End Here */
		}
	}

	


			/*foreach($Employee_Based_Details as $key=>$value){

$Sales_Indent_Id=implode(',', @$value['SalesIndentId']);
				$details=sqlsrv_query($conn,"EXEC Sales_Indent_Report_Details @Sales_Indent_Material_Id='".$Sales_Indent_Id."',@Status='5'");
				$mail=new Send_Mail();
				$Employee_Name=Get_Employee_Details($conn,$_SESSION['EmpID']);

				$subject="Sales Indent Approved (With In Limit) By ".$Employee_Name." -reg.";
				$Type="C&F_Supply";
				if($SupplyType==1){
					$Type="Direct_Supply";
				}else if($SupplyType==2){
					$Type="C&F_Supply";
				}
				$ProductDivision='fcm';
				if($Product_Division == "FC01")
				{
					$ProductDivision='fcm';
				}else if($Product_Division == "CT01"){
					$ProductDivision='ras';
				}		
				$Mail_Dets=Get_Mail_Recipient_Details(@$key,@$ProductDivision,@$Type);
				$To_Mail=@$Mail_Dets['Level_1'];
				$CC_Mail=@$Mail_Dets['Level_2'];
				$to=array();
				$cc=array();
				
				if($Limit_Exceed == 0){
					$subject="Sales Indent Approved (With In Limit) By ".$Employee_Name." -reg.";
					if(@$Mail_Dets['Level_1'] !=''){
					array_push($to,@$Mail_Dets['Level_1']);
				}

				if(@$Mail_Dets['Level_2'] !=''){
					array_push($cc,@$Mail_Dets['Level_2']);
				}
				}else if($Limit_Exceed == 1){
					$subject="Sales Indent Approved (Limit Exceed) By ".$Employee_Name." -reg.";

					if(@$Mail_Dets['Level_1'] !=''){
					array_push($to,@$Mail_Dets['Level_1']);
				}

				if(@$Mail_Dets['Level_2'] !=''){
					array_push($cc,@$Mail_Dets['Level_2']);
				}

				if(@$Mail_Dets['Level_3'] !=''){
					array_push($cc,@$Mail_Dets['Level_3']);
				}

				if(@$Mail_Dets['Level_4'] !=''){
					array_push($cc,@$Mail_Dets['Level_4']);
				}


				}
				$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com');
				$message=$mail->Generate_Mail_Tempalte($details);
				$mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
	} */

	//exit();
}
		$status=1;
	}else{
		$status=0;
	}
	
echo json_encode(array('Status'=>$status,'data'=>json_encode($Result_Array),'SAP_Status'=>json_encode($Post_To_SAP_Dets)));
}




/*-----------------------------Quotation And Sales Order Type ------------------------*/


?>