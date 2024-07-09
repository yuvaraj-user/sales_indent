<?php
include '../auto_load.php';
include 'Sales_Indent_Summary_Details.php';
$Summary_Details=new Sales_Indent_Summary_Details($conn);

$Action=@$_POST['Action'];
if($Action=="Get_Zone_Wise_Summary")
{
	$result=$Summary_Details->Get_Zone_Wise_Summary_Details(@$_POST);
	echo json_encode($result);exit;
}

if($Action=="Get_Crop_Wise_Summary")
{
	$result=$Summary_Details->Get_Crop_Wise_Summary_Details(@$_POST);
	echo json_encode($result);exit;
}
if($Action=="Get_Region_Wise_Summary")
{
	$result=$Summary_Details->Get_Region_Wise_Summary_Details(@$_POST);
	echo json_encode($result);exit;
}
if($Action=="Get_Territory_Wise_Summary")
{
	$result=$Summary_Details->Get_Territory_Wise_Summary_Details(@$_POST);
	echo json_encode($result);exit;
}
if($Action=="Get_Variety_Wise_Summary")
{
	$result=$Summary_Details->Get_Variety_Wise_Summary_Details(@$_POST);
	echo json_encode($result);exit;
}
if($Action=="get_indent_sumary_details")
{
	$result=$Summary_Details->get_indent_sumary_details(@$_POST);
	echo json_encode($result);exit;
}


 ?>