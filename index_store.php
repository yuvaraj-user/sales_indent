<<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- sweeetalert css -->
	<link href="assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
	<script src="assets/libs/sweetalert2/sweetalert2.min.js"></script>
</head>
<body>

</body>
</html>
<?php
include '../auto_load.php';
include 'Send_Mail.php';


 function Get_Employee_Details($conn,$Emp_Id)
{
  $Employee_Name_Sql    = sqlsrv_query($conn,"SPANP_Get_Employee_Name @Emp_Id='".@$Emp_Id."'");  
  $Employee_Name_Dets   = sqlsrv_fetch_array($Employee_Name_Sql);
  $Employee_Name   = $Employee_Name_Dets['Employee_Name'];
  return $Employee_Name;
}

function Check_Bypass_Role_Mapping_Dets($product_division,$Zone_Id)
{
	global $conn;
	$Sql="Sales_Indent_Bypass_Role_Mapping_Details @Product_Division='".@$product_division."',@Zone_Id='".@$Zone_Id."'";
	$Sql_Connection=sqlsrv_query($conn,$Sql);
	$Sql_Result   = sqlsrv_fetch_array($Sql_Connection);
  	$Count   = $Sql_Result['Count'];
    return $Count;
}

function Get_Mail_Recipient_Details_For_Plant($Plant){
	global $conn;
 	$Mail_Sql="EXEC Get_Plant_Mail_Details @Plant_Code='".$Plant."'";
	$Mail_Connection=sqlsrv_query($conn,$Mail_Sql);
	$Mail_Array=array();
	while($Mail_Details = sqlsrv_fetch_array($Mail_Connection,SQLSRV_FETCH_ASSOC))
	 {	
	 	$Mail_Array[]=@$Mail_Details['Email'];
	 }
	 return $Mail_Array;
}

function Get_Mail_Recipient_Details($Emp_Id,$product_division,$Type){
	global $conn;
 $Mail_Sql="Sales_Indnet_Recipient_Details @Emp_Id='".$Emp_Id."',@ProductDivision='".@$product_division."',@Type='".@$Type."'";
$Mail_Connection=sqlsrv_query($conn,$Mail_Sql);
$Mail_Details=sqlsrv_fetch_array($Mail_Connection);
return array("Level_1"=>@$Mail_Details['Level_1'],"Level_2"=>@$Mail_Details['Level_2'],"Level_3"=>@$Mail_Details['Level_3'],"Level_4"=>@$Mail_Details['Level_4'],'With_In_Limit_Approve_Status'=>@$Mail_Details['With_In_Limit_Approve_Status']);
}


function Get_Mail_Recipient_Details_New($Emp_Id,$product_division,$Type,$CropId){
	global $conn;
  $Mail_Sql="Sales_Indnet_Recipient_Details_WITH_CROP @Emp_Id='".$Emp_Id."',@ProductDivision='".@$product_division."',@Type='".@$Type."',@CropId='".@$CropId."'";
 //print_r($Mail_Sql);
$Mail_Connection=sqlsrv_query($conn,$Mail_Sql);
$Mail_Details=sqlsrv_fetch_array($Mail_Connection);
return array("Level_1"=>@$Mail_Details['Level_1'],"Level_2"=>@$Mail_Details['Level_2'],"Level_3"=>@$Mail_Details['Level_3'],"Level_4"=>@$Mail_Details['Level_4'],'With_In_Limit_Approve_Status'=>@$Mail_Details['With_In_Limit_Approve_Status']);
}



$send_mail=new Send_Mail();
		/*Insert Header Details Start Here */
function Generate_DocumentNo($Emp_Id,$id='0'){
  global $conn;
  $Emp_Id=$Emp_Id !=''? strtoupper($Emp_Id) : "";
  $Doc_No_Auto_Generation_Sql="Sales_Indent_Generate_Document_No @Emp_Id='".$Emp_Id."',@Id=".$id."";
  $Doc_No_Auto_Generation_Dets=sqlsrv_query($conn,$Doc_No_Auto_Generation_Sql);
  $Doc_No_Auto_Generation_Result = sqlsrv_fetch_array($Doc_No_Auto_Generation_Dets);
  return $Anp_Doc_No_Generation_Id=$Doc_No_Auto_Generation_Result['PrimaryId'];
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



		$Data=$_POST;
		$input_array=[];
		/* Generate Crop based Geoup Of Input Start Here */
		foreach($Data['CropId'] as $key=>$value)
		{
			$array=[];
			$array['CropId']=@$Data['CropId'][$key];
			$array['season']=@$Data['season'][$key];
			$array['StorageLocation']=@$Data['StorageLocation'][$key];
			$array['MaterialCode']=@$Data['MaterialCode'][$key];
			$array['QtyInPkt']=@$Data['QtyInPkt'][$key];
			$array['QtyInKg']=@$Data['QtyInKg'][$key];
			$array['QtyInBag']=@$Data['QtyInBag'][$key];
			$array['MaterialQtyInPkt']=@$Data['MaterialQtyInPkt'][$key];
			$array['MaterialQtyInKg']=@$Data['MaterialQtyInKg'][$key];
			$array['Price']=@$Data['Price'][$key];
			$array['Total_Price']=@$Data['Grand_Total_Price'][$key];
			$input_array[$value][]=$array;
		}
//print_r($array);
	// echo "<pre>";print_r($Data);exit;

		/* Generate Crop based Geoup Of Input End Here */
		$Document_No=@$Data['Document_No'];
		$SaleOrderType=@$Data['SaleOrderType'];
		$QuotationType=@$Data['QuotationType'];
		$ReqDate=@$Data['ReqDate'];
		$ReqDate=!empty($ReqDate) ? date('Y-m-d',strtotime(@$ReqDate)) :date('Y-m-d');
		$RequestBy=@$_SESSION['EmpID'];
		$ProductDivision=@$Data['ProductDivision'];
		$ZoneId=@$Data['ZoneId'];
		$RegionId=@$Data['RegionId'];
		$TerritoryId=@$Data['TerritoryId'];
		$CustomerCode=@$Data['CustomerCode'];
		$SupplyType=@$Data['SupplyType'];
		$PlantId=@$Data['PlantId'];
		$Address=@$Data['Address'];
		$Limit_Exceed=@$Data['Limit_Exceed'];
		$CreatedBy=@$_SESSION['EmpID'];
		$Dcode=@$_SESSION['Dcode'];
		$CreatedAt=date('Y-m-d H:i:s');
		$CurrentStatus="1";
		$RejectionStatus="1";
		$Remark=@$Data['Remark'];
		$Customer_Credit_Limit=@$Data['Customer_Credit_Limit'];
		$Expected_Date=@$Data['Expected_Date'];
		$Storage_Location=@$Data['Storage_Location'];
		$Bypass_Approve_Mechnaism=Check_Bypass_Role_Mapping_Dets($ProductDivision,$ZoneId);          
		$CurrentStatus=1;
		if($Limit_Exceed == 0)
		{
			if($Bypass_Approve_Mechnaism== 1 && $SupplyType=='2'){
				$CurrentStatus=5;
			}/*else if((strtoupper($_SESSION['Dcode']) =='DBM' || strtoupper($_SESSION['Dcode']) =='ZM') && $SupplyType=='1')
			{
				$CurrentStatus=6;
			} */else
			{
				$CurrentStatus=1; /* If With In Limit*/
			}
		}else if($Limit_Exceed == 1)
		{
			$CurrentStatus=2; /* If Limit Exceeds*/
		}



		

			$QtyInBag=@$Data['QtyInBag'];


		//print_r($QtyInBag);

		if($QtyInBag !=''){



	

	

		foreach($input_array as $key=>$sub_array)
		{
			$CropId=@$input_array[$key][0]['CropId'];
			/*Generate Crop Based Indent Document no*/
			$Doc_No=Generate_DocumentNo($_SESSION['EmpID'],0);
			$ReqId=@$Doc_No;
			  $SQL="EXEC Sales_Indent_Insert_Details_NEW @Document_No='".$Document_No."',@ReqId='".$ReqId."',@ReqDate='".$ReqDate."',@RequestBy='".$RequestBy."',@ProductDivision='".$ProductDivision."',@CropId='".@$CropId."',@ZoneId='".$ZoneId."',@RegionId='".$RegionId."',@TerritoryId='".$TerritoryId."',@CustomerCode='".$CustomerCode."',@SupplyType='".$SupplyType."',@PlantId='".$PlantId."',@CreatedBy='".$CreatedBy."',@CreatedAt='".$CreatedAt."',@CurrentStatus='".$CurrentStatus."',@RejectionStatus='".$RejectionStatus."',@Remark='".$Remark."',@SaleOrderType='".$SaleOrderType."',@QuotationType='".$QuotationType."',@Last_Insert_Id='',@Address='".$Address."',@Customer_Credit_Limit='".$Customer_Credit_Limit."'";
	       $Result=sqlsrv_query($conn,$SQL);
			$Last_Insert_id_details=sqlsrv_fetch_array($Result);
			$Last_Insert_id=@$Last_Insert_id_details['Last_Insert_Id'];



			 $SQL="Update Sales_Indent SET Expected_Date='".$Expected_Date."' where SalesIndentId='".@$Last_Insert_id."'";
    $Result=sqlsrv_query($conn,$SQL);






			$count=0;



if($SupplyType != '1')
		{


			foreach($sub_array as $subkey=>$value)
			{
				//$PlantId=@$value['PlantName'];
				$season=@$value['season'];
				$StorageLocation=@$value['StorageLocation'];
				if($PlantId == 'B012'){
					$StorageLocation='SE02';
				}
				
				$MaterialCode=@$value['MaterialCode'];
				$QtyInPkt=@$value['QtyInPkt'];
				$QtyInBag=@$value['QtyInBag'];
				$QtyInKg=@$value['QtyInKg'];
				$MaterialQtyInPkt=@$value['MaterialQtyInPkt'];
				$MaterialQtyInKg=@$value['MaterialQtyInKg'];
				$Price=@$value['Price'];
				$Total_Price=@$value['Total_Price'];
				 $SQL="EXEC Sales_Indent_Insert_Material_Details @SalesIndentId=".@$Last_Insert_id.",@PlantId='".$PlantId."',@season='".$season."',@StorageLocation='".$StorageLocation."',@MaterialCode='".$MaterialCode."',@QtyInPkt='".$QtyInPkt."',@QtyInBag='".$QtyInBag."',@QtyInKg='".$QtyInKg."',@QtyInKgs='".$QtyInPkt."',@Price='".$Price."',@Total_Price='".$Total_Price."',@CreatedBy='".$CreatedBy."',@CreatedAt='".$CreatedAt."',@CurrentStatus='".@$CurrentStatus."',@RejectionStatus='1',@Remark='',@MaterialQtyInPkt='".$MaterialQtyInPkt."',@MaterialQtyInKg='".$MaterialQtyInKg."',@Limit_Exceed='".$Limit_Exceed."'";

				


				 /* Post SAP Data End Here */


				if($Sql_Connection=sqlsrv_query($conn,$SQL))
				{
					$Sql_Result   = sqlsrv_fetch_array($Sql_Connection);
  					$Id   = $Sql_Result['Id'];
  					if($CurrentStatus == 5){
					  $sql="update Sales_Indent_Material_Details set Approve_Remarks='Direct Approval From The Requester',Approved_At='".$CreatedAt."',Approved_by='".$CreatedBy."' where Id='".@$Id."'";
					 sqlsrv_query($conn,$sql);
  					}

					$count++;
				}
			}


		}else if($SupplyType != '2')
		{

//print_r($SupplyType);
			foreach($Data['MaterialCode'] as $key=>$value)
			{
				//$PlantId=@$value['PlantName'];
				$season=@$Data['season'][$key];
				$StorageLocation=@$Data['StorageLocation'][$key];
				if($PlantId == 'B012'){
					$StorageLocation='SE02';
				}
				
				$MaterialCode=@$Data['MaterialCode'][$key];
				$QtyInPkt=@$Data['QtyInPkt'][$key];
				$QtyInBag=@$Data['QtyInBag'][$key];
				$QtyInKg=@$Data['QtyInKg'][$key];
				$MaterialQtyInPkt=@$Data['MaterialQtyInPkt'][$key];
				$MaterialQtyInKg=@$Data['MaterialQtyInKg'][$key];
				$Price=@@$Data['Price'][$key];
				$Total_Price=@$Data['Total_Price'][$key];
				 $SQL="EXEC Sales_Indent_Insert_Material_Details @SalesIndentId=".@$Last_Insert_id.",@PlantId='".$PlantId."',@season='".$season."',@StorageLocation='".$StorageLocation."',@MaterialCode='".$MaterialCode."',@QtyInPkt='".$QtyInPkt."',@QtyInBag='".$QtyInBag."',@QtyInKg='".$QtyInKg."',@QtyInKgs='".$QtyInPkt."',@Price='".$Price."',@Total_Price='".$Total_Price."',@CreatedBy='".$CreatedBy."',@CreatedAt='".$CreatedAt."',@CurrentStatus='".@$CurrentStatus."',@RejectionStatus='1',@Remark='',@MaterialQtyInPkt='".$MaterialQtyInPkt."',@MaterialQtyInKg='".$MaterialQtyInKg."',@Limit_Exceed='".$Limit_Exceed."'";

				


				 /* Post SAP Data End Here */


				if($Sql_Connection=sqlsrv_query($conn,$SQL))
				{
					$Sql_Result   = sqlsrv_fetch_array($Sql_Connection);
  					$Id   = $Sql_Result['Id'];
  					if($CurrentStatus == 5){
					  $sql="update Sales_Indent_Material_Details set Approve_Remarks='Direct Approval From The Requester',Approved_At='".$CreatedAt."',Approved_by='".$CreatedBy."' where Id='".@$Id."'";
					 sqlsrv_query($conn,$sql);
  					}

					$count++;
				}
			}


		}




  $SQL="Update Sales_Indent_Material_Details SET StorageLocation='".$Storage_Location."' where SalesIndentId='".@$Last_Insert_id."'";
    $Result=sqlsrv_query($conn,$SQL);




	}
	





			if($count >0)
			{
				//echo "EXEC Sales_Indent_Report_Details @Sales_Indent_Id='".$Last_Insert_id."'";exit;
				$Mail_Tem=sqlsrv_query($conn,"EXEC Sales_Indent_Report_Details @Sales_Indent_Id='".$Last_Insert_id."'");
				$details=sqlsrv_query($conn,"EXEC Sales_Indent_Report_Details @Sales_Indent_Id='".$Last_Insert_id."'");
				$Sap_Data_Array=array();
				if($CurrentStatus == 5){
				while($Sql_Result   = sqlsrv_fetch_array($details)){
					$array=[];
		$array['INDENT_NO']=@$Sql_Result['ReqId'];
		$array['EMPLOYEE_ID']=@$Sql_Result['RequestBy'];
		$array['ACCOUNTNUM']=@$Sql_Result['CustomerCode'];
		$array['SALES_ORG']=@$Sql_Result['Sales_Org'];
		$array['DIST_CHANNEL']=@$Sql_Result['Distribution_Channel'];
		$array['DIVISION']=@$Sql_Result['Division'];
		$array['ZONE_ID']=@$Sql_Result['ZoneId'];
		$array['REGION_ID']=@$Sql_Result['RegionId'];
		$array['TM_ID']=@$Sql_Result['TerritoryId'];
		$array['QUOTATION_TYPE']=@$Sql_Result['QuotationType'];
		$array['SALEORDER_TYPE']=@$Sql_Result['SaleOrderType'];

		//	$MATERIAL_NO = strToHex(@$Sql_Result["MaterialCode"][$key]);


		//$array['MATERIAL_NO']=$MATERIAL_NO;


		$array['MATERIAL_NO']=@$Sql_Result['MaterialCode'];
		$array['QUANTITY']=@$Sql_Result['QtyInPkt'];
		$array['PLANT']=@$Sql_Result['PlantId'];
		
		if(@$Sql_Result['PlantId'] == "B012"){
			$array['LGORT']='SE02';
		}else{
			$array['LGORT']='SE01';
		}
		
		$array['INDENT_TYPES']=@$Sql_Result['Indnet_Type'];
		$SupplyType=@$Sql_Result['Indnet_Type'];
		if($SupplyType != '')
		{
			$array['INDENT_TYPES']=$SupplyType == 1 ? "DS" : "CS";
		}
		
		$array['VALID_TO_DATE']=@$Sql_Result['ReqDate'] !='' ? date('Ymd',strtotime(@$Sql_Result['ReqDate'])) : date('Ymd');
		$array['APPROVE_STATUS']=@$Sql_Result['Status_Text'];
		$array['RBM_EMP_VENDOR']=@$_SESSION['EmpID'];
		$array['DBM_EMP_VENDOR']=@$_SESSION['EmpID'];
		$array['RBM_MAIL_STATUS']='No';
		$array['DBM_MAIL_STATUS']='No';
		$array['Season_code']=@$Sql_Result['Season_Code'];
		$array['PLACE']=@$Sql_Result['Customer_Address'];
	//	$array['PLACE']=str_replace("&", " ", @$User_Input['PLACE'][$key]);
		$array['MOBILE_NO']=@$Sql_Result['MOBILE_NO'];
		$Sap_Data_Array[]=$array;
				}
			$url="http://192.168.162.213:8081/Sales_Indent/PRD/ZIN_RFC_ERAIN_UPDATE_QUOT_DAS.php";
			$SAP_Json_Data=json_encode($Sap_Data_Array);
			// $Post_To_SAP_Dets=Post_SAP_Data($SAP_Json_Data,$url);
		}

$details=sqlsrv_query($conn,"EXEC Sales_Indent_Report_Details_With_Crop @Sales_Indent_Id='".$Last_Insert_id."'");
			
				$mail=new Send_Mail();
				$Employee_Name=Get_Employee_Details($conn,$_SESSION['EmpID']);

				$subject="Sales Indent Request (With In Limit) From ".$Employee_Name." -reg.";
				$Type="C&F_Supply";
				if($SupplyType==1){
					$Type="Direct_Supply";
				}else if($SupplyType==2){
					$Type="C&F_Supply";
				}
				$Mail_Dets=Get_Mail_Recipient_Details_New(@$_SESSION['EmpID'],@$ProductDivision,@$Type,@$CropId);
				$Plant_Mail_Dets=Get_Mail_Recipient_Details_For_Plant($PlantId);
				
				$To_Mail=@$Mail_Dets['Level_2'];
				$CC_Mail=@$Mail_Dets['Level_1'];
				$cc=array($CC_Mail);
				$to=array($To_Mail);

				//print_r($cc);
				//print_r($to);
				
				if($CurrentStatus == 1){
					$subject="Sales Indent Request (With In Limit) From ".$Employee_Name." -reg.";
				}else if($CurrentStatus == 2){
					$subject="Sales Indent Request(Limit Exceed) From ".$Employee_Name." -reg.";
				}else if($CurrentStatus == 5){
					$subject="Sales Indent Approved(With In Limit) From ".$Employee_Name."-reg.";
					foreach($Plant_Mail_Dets as $value){
					array_push($to,$value);
				}
				}


				

				$bcc=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
				//$to=array('sathish.r@rasiseeds.com');
				//$bcc=array("saravanan.r@rasiseeds.com");
				$message=$mail->Generate_Mail_Tempalte($details);
				// $mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
				/* Send Mail For Indidual Indent No End here*/
			}
		}


		//same user stay on different roles example level 1 and level 2 that time created indent move to the end approval status  
		if($CurrentStatus == 1 || $CurrentStatus == 2) {

				foreach($input_array as $key=>$sub_array)
				{
					foreach ($sub_array as $sk => $sval) {
						$CropId= $sval['CropId'];
						$material = $sval['MaterialCode'];
						$get_user_level = user_role_levels($CropId,$Data['SupplyType'],$_SESSION['EmpID']);
						$current_status = 0;
						//limit exceeds role based approval move on status code functionality 
						if($CurrentStatus == 2 && ($Data['SupplyType'] == '1' || $Data['SupplyType'] == '2')) {
							if(($get_user_level['level_1']  == $get_user_level['level_2']) && ($get_user_level['level_2'] != $get_user_level['level_3'])) {
								$current_status = '3'; 
							} elseif(($get_user_level['level_1']  == $get_user_level['level_2']) && ($get_user_level['level_2'] == $get_user_level['level_3'])) {
								$current_status = '4'; 
							}
						} 
						//with in limit direct supply type role based approval move on status code functionality 
						elseif($CurrentStatus == 1 && $Data['SupplyType'] == '1') {
							// if(($get_user_level['level_1']  == $get_user_level['level_2']) && ($get_user_level['level_2'] != $get_user_level['level_3'])) {
							if(($get_user_level['level_1']  == $get_user_level['level_2']) && ($get_user_level['level_2'] != $get_user_level['level_3'])) {
							
								$current_status = '6'; 
							}
						}

						//only update for same level role users condition
						if($current_status != 0) {
							$role_sql    = "UPDATE Sales_Indent_Material_Details SET CurrentStatus = '".$current_status."' where SalesIndentId = (select SalesIndentId from Sales_Indent where ReqId = '".$Data['ReqId']."') and MaterialCode = '".$material."'";
		       				$role_result =sqlsrv_query($conn,$role_sql);
						}

					}
				}

		}

		echo "<script>
		Swal.fire(
		{
			title:'Success!',
			text:'Indent created succssfully.',
			icon:'success',
			confirmButtonColor:'#038edc',
		}
		).then(function(e){
			if(e) {
				window.location.href ='Indent_creation.php'
			}
			});
			</script>";
// echo "<script>window.location.href ='Indent_creation.php'</script>";
EXIT;
		
	    $SQL="EXEC Sales_Indent_Insert_Details @Document_No='".$Document_No."',@ReqId='".$ReqId."',@ReqDate='".$ReqDate."',@RequestBy='".$RequestBy."',@ProductDivision='".$ProductDivision."',@CropId='".$CropId."',@ZoneId='".$ZoneId."',@RegionId='".$RegionId."',@TerritoryId='".$TerritoryId."',@CustomerCode='".$CustomerCode."',@SupplyType='".$SupplyType."',@PlantId='".$PlantId."',@CreatedBy='".$CreatedBy."',@CreatedAt='".$CreatedAt."',@CurrentStatus='".$CurrentStatus."',@RejectionStatus='".$RejectionStatus."',@Remark='".$Remark."',@SaleOrderType='".$SaleOrderType."',@QuotationType='".$QuotationType."',@Last_Insert_Id='',@Address='".$Address."'";
        $Result=sqlsrv_query($conn,$SQL);
		$Last_Insert_id_details=sqlsrv_fetch_array($Result);
		$Last_Insert_id=@$Last_Insert_id_details['Last_Insert_Id'];
		$count=0;
		foreach(@$Data['MaterialCode'] as $key=>$value)
		{
			$PlantId=@$Data['PlantName'][$key];
			$season=@$Data['season'][$key];
			$StorageLocation=@$Data['StorageLocation'][$key];
			$MaterialCode=@$Data['MaterialCode'][$key];
			$QtyInPkt=@$Data['QtyInPkt'][$key];
			$QtyInBag=@$Data['QtyInBag'][$key];
			$QtyInKg=@$Data['QtyInKg'][$key];
			$MaterialQtyInPkt=@$Data['MaterialQtyInPkt'][$key];
			$MaterialQtyInKg=@$Data['MaterialQtyInKg'][$key];
			$SQL="EXEC Sales_Indent_Insert_Material_Details @SalesIndentId=".$Last_Insert_id.",@PlantId='".$PlantId."',@season='".$season."',@StorageLocation='".$StorageLocation."',@MaterialCode='".$MaterialCode."',@QtyInPkt='".$QtyInPkt."',@QtyInBag='".$QtyInBag."',@QtyInKg='".$QtyInKg."',@QtyInKgs='".$QtyInPkt."',@CreatedBy='".$CreatedBy."',@CreatedAt='".$CreatedAt."',@CurrentStatus='1',@RejectionStatus='1',@Remark='',@MaterialQtyInPkt='".$MaterialQtyInPkt."',@MaterialQtyInKg='".$MaterialQtyInKg."'";
			if(sqlsrv_query($conn,$SQL)){
			}
		}
		
		$details=sqlsrv_query($conn,"EXEC Sales_Indent_Details @Sales_Indent_Id='".$Last_Insert_id."'");
		$mail=new Send_Mail();
		$subject="Sales Indent Validation Request -reg.";
		$cc=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
		$to=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
		$bcc=array('sathish.r@rasiseeds.com',"sathish.r@rasiseeds.com");
		$message=$mail->Generate_Mail_Tempalte($details);
		// $mail->Send_Mail_Details($subject,$message,$to,$cc,$bcc);
		
		echo "<script>window.location.href ='sales_indent_report.php'</script>";
		
		
		
		/*Insert Header Details End Here*/


?>