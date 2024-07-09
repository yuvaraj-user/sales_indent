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
		/*Insert Header Details Start Here */
		
  function Generate_DocumentNo($Emp_Id,$id='0'){
  global $conn;
  $Emp_Id=$Emp_Id !=''? strtoupper($Emp_Id) : "";
  $Doc_No_Auto_Generation_Sql="Sales_Indent_STO_Generate_Document_No @Emp_Id='".$Emp_Id."',@Id=".$id."";
  $Doc_No_Auto_Generation_Dets=sqlsrv_query($conn,$Doc_No_Auto_Generation_Sql);
  $Doc_No_Auto_Generation_Result = sqlsrv_fetch_array($Doc_No_Auto_Generation_Dets);
  return $Anp_Doc_No_Generation_Id=$Doc_No_Auto_Generation_Result['PrimaryId'];
}

 function Get_Employee_Details($Emp_Id)
{
	global $conn;
  $Employee_Name_Sql    = sqlsrv_query($conn,"SPANP_Get_Employee_Name @Emp_Id='".@$Emp_Id."'");  
  $Employee_Name_Dets   = sqlsrv_fetch_array($Employee_Name_Sql);
  $Employee_Name   = $Employee_Name_Dets['Employee_Name'];
  return $Employee_Name;
}

function Get_STO_Mail_Recipient_Details($Emp_Id,$product_division,$CropId){
	global $conn;
  $Mail_Sql="STO_Indnet_Recipient_Details_WITH_Crop @Emp_Id='".$Emp_Id."',@ProductDivision='".@$product_division."',@CropId='".@$CropId."'";
$Mail_Connection=sqlsrv_query($conn,$Mail_Sql);
$Mail_Details=sqlsrv_fetch_array($Mail_Connection);
return array("Level_1"=>@$Mail_Details['Level_1'],"Level_2"=>@$Mail_Details['Level_2'],"Level_3"=>@$Mail_Details['Level_3']);
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

/*
1=>Waiting For validate (With In Limit)
5=>Waiting For approval (With In Limit)
2=>Waiting For Recommendation (Limit Exceed)
3=>Waiting For Validate (Limit Exceed)
7=>Waiting For Approval (Limit Exceed)
4=>Completed */


$Doc_No=Generate_DocumentNo($_SESSION['EmpID'],0);
		$Data=$_POST;
		// echo "<pre>";print_r($Data);
		// exit();
		//exit;

		$Document_No=@$Data['Document_No'];
		$SaleOrderType=@$Data['SaleOrderType'];
		$QuotationType=@$Data['QuotationType'];
		$ReqId=@$Doc_No;
		$ReqDate=@$Data['ReqDate'];
		$ReqDate=!empty($ReqDate) ? date('Y-m-d',strtotime(@$ReqDate)) :date('Y-m-d');
		$RequestBy=@$_SESSION['EmpID'];
		$ProductDivision=@$Data['ProductDivision'];
		$CropId=@$Data['CropId'];
		$ZoneId=@$Data['ZoneId'];
		$RegionId=@$Data['RegionId']; 
		$TerritoryId=@$Data['TerritoryId'];
		$Distribution_Channel=@$Data['Distribution_Channel'];
		$Purchasing_organization=@$Data['Purchasing_organization'];
		$purchase_type=@$Data['purchase_type'];
		$Company_code=@$Data['Company_code'];
		$SupplyType=@$Data['SupplyType'];
		$PlantId=@$Data['PlantId'];
		$Receiving_Plant=@$Data['Receiving_plant_id'];
		$Address=@$Data['Address'];
		$Limit_Exceed_Status=@$Data['Limit_Exceed_Status'];
		$Expected_Date=@$Data['Expected_Date'];
		$CreatedBy=@$_SESSION['EmpID'];
		$CreatedAt=date('Y-m-d H:i:s');


		if($ProductDivision=='ras'){

$CurrentStatus="5";

		}else{

			$CurrentStatus="1";	
		}


		if($Limit_Exceed_Status >0 && $ProductDivision=='ras'){
			$CurrentStatus="3";
		}
	
		$RejectionStatus="1";
		if($Limit_Exceed_Status >0 && $ProductDivision=='fcm'){
			$CurrentStatus="2";
		}
		$Remark=@$Data['Remark'];
	     $SQL="EXEC Sales_Indent_STO_Insert_Details @Document_No='".$Document_No."',@ReqId='".$ReqId."',@ReqDate='".$ReqDate."',@RequestBy='".$RequestBy."',@ProductDivision='".$ProductDivision."',@CropId='".$CropId."',@ZoneId='".$ZoneId."',@RegionId='".$RegionId."',@TerritoryId='".$TerritoryId."',@Distribution_Channel='".$Distribution_Channel."',@Purchasing_organization='".$Purchasing_organization."',@purchase_type='".$purchase_type."',@Company_code='".$Company_code."',@SupplyType='".$SupplyType."',@PlantId='".$PlantId."',@CreatedBy='".$CreatedBy."',@CreatedAt='".$CreatedAt."',@CurrentStatus='".$CurrentStatus."',@RejectionStatus='".$RejectionStatus."',@Remark='".$Remark."',@Last_Insert_Id='',@Address='".$Address."',@Receiving_Plant='".$Receiving_Plant."',@Limit_Exceed='".$Limit_Exceed_Status."'";
        $Result=sqlsrv_query($conn,$SQL);
		$Last_Insert_id_details=sqlsrv_fetch_array($Result);
		$Last_Insert_id=@$Last_Insert_id_details['Last_Insert_Id'];


		 $SQL="Update Sales_Indent_STO SET Expected_Date='".$Expected_Date."' where SalesIndentId='".@$Last_Insert_id."'";
    $Result=sqlsrv_query($conn,$SQL);


    
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
			$PlanQty=@$Data['PlanQty'][$key];
			$Limit_Exceed=@$Data['Limit_Exceed'][$key];
		    $SQL="EXEC Sales_Indent_STO_Insert_Material_Details @SalesIndentId=".$Last_Insert_id.",@PlantId='".$PlantId."',@season='".$season."',@StorageLocation='".$StorageLocation."',@MaterialCode='".$MaterialCode."',@QtyInPkt='".$QtyInPkt."',@QtyInBag='".$QtyInBag."',@QtyInKg='".$QtyInKg."',@QtyInKgs='".$QtyInPkt."',@CreatedBy='".$CreatedBy."',@CreatedAt='".$CreatedAt."',@CurrentStatus='".$CurrentStatus."',@RejectionStatus='1',@Remark='',@MaterialQtyInPkt='".$MaterialQtyInPkt."',@MaterialQtyInKg='".$MaterialQtyInKg."',@PlanQty='".$PlanQty."',@Limit_Exceed='".@$Limit_Exceed."'";
			if(sqlsrv_query($conn,$SQL)){
			}
		}

$details=sqlsrv_query($conn,"EXEC Sales_Indent_STO_Details_With_Limit @STO_Indent_Id='".$Last_Insert_id."',@Emp_Id =0,@Product_Division =0,@Dcode =0,@Plant_id =0,@Zone_id =0,@Region_Id =0,@Terrirory_Id =0,@QuotationType =0,@SaleOrderType =0,@From_Date =NULL,@To_Date =NULL,@Status =0,@STO_Indent_Material_Id =0,@Length =10,@Offset =0,@CropId='".$CropId."'");
/*
echo "EXEC Sales_Indent_STO_Details_With_Limit @STO_Indent_Id='".$Last_Insert_id."',@Emp_Id =0,@Product_Division =0,@Dcode =0,@Plant_id =0,@Zone_id =0,@Region_Id =0,@Terrirory_Id =0,@QuotationType =0,@SaleOrderType =0,@From_Date =NULL,@To_Date =NULL,@Status =0,@STO_Indent_Material_Id =0,@Length =10,@Offset =0,@CropId='".$CropId."'";*/

$mail=new Send_Mail();
$Employee_Name=Get_Employee_Details($_SESSION['EmpID']);
$Mail_Dets=Get_STO_Mail_Recipient_Details(@$_SESSION['EmpID'],@$ProductDivision,@$CropId);

//print_r($Mail_Dets);
$To_Mail=@$Mail_Dets['Level_2'];
$CC_Mail=@$Mail_Dets['Level_1'];
$cc=array($CC_Mail);
$to=array($To_Mail);
$subject="STO Indent Request From ".$Employee_Name." -reg.";
if($Limit_Exceed_Status >0){
$subject="STO Indent Request (Limit Exceed) From ".$Employee_Name." -reg.";
}

$BCC_Mail=array("gopinath.m@rasiseeds.com","saravanan.r@rasiseeds.com",'sathish.r@rasiseeds.com','saravanakumaran.n@rasiseeds.com');
// $message=$mail->Generate_Mail_Tempalte_STO($details);
// $mail->Send_Mail_Details($subject,$message,$to,$cc,$BCC_Mail);


//same user stay on different roles example level 1 and level 2 that time created indent move to the end approval status  
if($CurrentStatus == 1 || $CurrentStatus == 2) {

	foreach($Data['MaterialCode'] as $key=>$value) {
		$CropId= $Data['CropId'];
		$material = $value;
		$get_user_level = sto_user_role_levels($CropId,$_SESSION['EmpID']);
		$current_status = 0;

		//limit exceeds role based approval move on status code functionality 
		if($CurrentStatus == 2) {
			if(($get_user_level['level_1']  == $get_user_level['level_2']) && ($get_user_level['level_2'] != $get_user_level['level_3'])) {
				$current_status = '3'; 
			} elseif(($get_user_level['level_1']  == $get_user_level['level_2']) && ($get_user_level['level_2'] == $get_user_level['level_3'])) {
				$current_status = '7'; 
			}
		} 

		//with in limit exceeds role based approval move on status code functionality 
		elseif ($CurrentStatus == 1) {
			if($get_user_level['level_1']  == $get_user_level['level_2']) {
				$current_status = '5'; 
			} 
		}

		//only update for same level role users condition
		if($current_status != 0) {
			$role_sql    = "UPDATE Sales_Indent_STO_Material_Details SET CurrentStatus = '".$current_status."' where SalesIndentId = (select SalesIndentId from Sales_Indent_STO where ReqId = '".$Data['ReqId']."') and MaterialCode = '".$material."'";
			$role_result =sqlsrv_query($conn,$role_sql);
		}
	}
}



echo "<script>
Swal.fire(
{
	title:'Success!',
	text:'STO created succssfully.',
	icon:'success',
	confirmButtonColor:'#038edc',
}
).then(function(e){
	if(e) {
		window.location.href ='Sales_indent_Sto_Report.php'
	}
	});
	</script>";
	EXIT;
// echo "<script>window.location.href ='Sales_indent_Sto_Report.php'</script>";
		
// exit();		
		
		/*Insert Header Details End Here*/


?>