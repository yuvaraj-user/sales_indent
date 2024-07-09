<?php 
require_once __DIR__ . "/../vendor/autoload.php";
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;


Class Send_Mail{

    public $conn;
    public $Created_At;
    public $Created_By;
    function __construct() 
    {

    }

    public function Send_Mail_Details($subject,$message,$to,$cc,$bcc=array())
    {


      //  $to=array('gopinath.m@rasiseeds.com');
      ///  $cc=array('sathish.r@rasiseeds.com');
     ///   $bcc=array('sathishkumaritnsit@gmail.com');
       $mail = new PHPMailer;
        //$mail->SMTPDebug = 2;                           
        $mail->isSMTP();        
        $mail->Host = "rasiseeds-com.mail.protection.outlook.com";
        $mail->SMTPAuth = false;                      
        $mail->Port = 25;                    
        $mail->From = "desk@rasiseeds.com";
        $mail->FromName = "desk@rasiseeds.com";
       foreach($to as $key => $val){   
             $mail->addAddress($val); 
        }
        foreach($bcc as $key => $val){   // To Mail ids
          $mail->addBCC($val); 
        }
        foreach($cc as $key => $val){   // To Mail ids
          $mail->addCC($val); 
        }
        $mail->Subject  = $subject;
        $mail->IsHTML(true);
        $mail->Body = $message;
        if(!$mail->send())
        {
         echo "Mailer Error: " . $mail->ErrorInfo;
         return false;
        }
        else
        {
         return true;
        }
    }

    public function Generate_Mail_Tempalte($data,$Type="")
    {
      //  if($Type == "Approve"){Approved_Date)
        $Message="";
        $Message.="<table border='0'>
    <tbody>
<tr>
<td colspan='2' align='left'><p style='margin:0;'>Dear User(s),</br></br>

Please give approval for below Indent.</p></td>
</tr>
         </tbody>
         </table><table  style='border-collapse:collapse;margin-bottom:10.0pt; padding: 4pt 5pt;border: 1pt solid #999;mso-border-alt: 1px solid #999; width: 100%;mso-margin-bottom-alt:10pt;'>
<thead>
<tr>
<th style='padding: 4pt 4pt;border: 1pt solid #999; mso-border-alt: 1px solid #999;'><p style='margin:0;'>SL.No.</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Indent No</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Indent Date</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Expected Date</p></th>

<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Plant</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Plant Name</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Customer Code</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Customer Name</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>State Name</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Item Name</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Indent Qty</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>UOM</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Qty In Kgs</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Emp Code</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Emp Name</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Dealer Name</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Place</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Mobile No</p></th>

<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>IndentStatus</p></th>
";
$Message.="</tr>
</thead>
<tbody>";
$i=1;
while($result=sqlsrv_fetch_array($data)){

   // echo "<pre>";print_r($result);
    $Req_Date="";
    if($Type == "Approve"){
        $Req_Date=$result['Approved_Date'];
    }else{
        $Req_Date=$result['ReqDate'];
    }
 //   $end = new DateTime($Req_Date);

 //   $tempDate = DateTime::createFromFormat('j-M-Y', $Req_Date);
//echo $tempDate->format('Y-m-d H:i:s');exit();
//$newDate = DateTime::createFromFormat("l dS F Y", $Req_Date);
//$newDate = $newDate->format('d/m/Y'); // for example
   /* if($Type == "Approve"){
        $Req_Date=$result['Approved_Date'];
    }else{
        $Req_Date=$result['ReqDate'];
    }*/
$Message.= " <tr>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$i."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['ReqId']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$Req_Date."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Expected_date']."</p></td>

<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['PlantId']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['PlantName']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CustomerCode']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CustomerName']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['State_Name']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['MaterialCode']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".round($result['QtyInBag'],2)."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Pak</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".round($result['QtyInKg'],2)."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['RequestBy']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['EMPLNAME']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'></p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Customer_Address']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'> ".@$result['Customer_Tel_Number']."</p></td>

<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['Status_Text']."</p></td>
";
$i++;
}
$Message.="</tr></tbody></table>";


return $Message;

    }

     public function Generate_Mail_Tempalte_STO($data,$Type="")
    {
       
        $Message="";
        $Message.="<table border='0'>
    <tbody>
<tr>
<td colspan='2' align='left'><p style='margin:0;'>Dear User(s),</br></br>

Please give approval for below Indent.</p></td>
</tr>
         </tbody>
         </table><table  style='border-collapse:collapse;margin-bottom:10.0pt; padding: 4pt 5pt;border: 1pt solid #999;mso-border-alt: 1px solid #999; width: 100%;mso-margin-bottom-alt:10pt;'>
<thead>
<tr>
<th style='padding: 4pt 4pt;border: 1pt solid #999; mso-border-alt: 1px solid #999;'><p style='margin:0;'>SL.No.</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Indent No</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Indent Date</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Expected Date</p></th>

<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>From Plant</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>To Plant</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>To Plant Name</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Item Name</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Indent Qty</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>UOM</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Qty In Kgs</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Emp Code</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Emp Name</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Dealer Name</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Place</p></th>
<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Mobile No</p></th>

<th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>IndentStatus</p></th>";
$Message.="</tr>
</thead>
<tbody>";
$i=1;

while($result=sqlsrv_fetch_array($data)){
     $Req_Date="";
    if($Type == "Approve"){
        $Req_Date=@$result['Approved_Date'];
    }else{
        $Req_Date=@$result['ReqDate'];
    }
$Message.= " <tr>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$i."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['ReqId']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$Req_Date."</p></td>

<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Expected_date']."</p></td>

<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['PlantId']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Receiving_Plant']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Receiving_Plant_Name']."</p></td>



<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['MaterialCode']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".round($result['QtyInBag'],2)."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Pak</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".round($result['QtyInKg'],2)."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['RequestBy']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['EMPLNAME']."</p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'></p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'></p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'></p></td>
<td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$result['Status_Text']."</p></td>";
$i++;
}
$Message.="</tr></tbody></table>";
return $Message;

    }

    public function Generate_Mail_Tempalte_For_Credit_Limit_Exceed($data)
{
    $Message="";
    $Message.="<table border='0'>
        <tbody>
        <tr>
        <td colspan='2' align='left'><p style='margin:0;'>Dear User(s),</br></br>

        Please Change the Below Customer's Credit Limit.</p></td>
        </tr>
        </tbody>
        </table><table  style='border-collapse:collapse;margin-bottom:10.0pt; padding: 4pt 5pt;border: 1pt solid #999;mso-border-alt: 1px solid #999; width: 100%;mso-margin-bottom-alt:10pt;'>
        <thead>
        <tr>
        <th style='padding: 4pt 4pt;border: 1pt solid #999; mso-border-alt: 1px solid #999;'><p style='margin:0;'>SL.No.</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Indent No</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Customer Code</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Customer Name</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>District</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Postal Code</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Actual Customer Credit Limit</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Actual Customer Balance</p></th>
        
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Current Exceed Amt</p></th>";
    $Message.="</tr></thead><tbody>";
    $i=1;
    $count=0;




 //   echo "<pre>";print_r($data);exit;
    while($result=sqlsrv_fetch_array($data))

           //  print_r($result);exit();

     //   echo "<pre>";print_r($result);exit;
    {
        $count++;
        $Message.= " <tr>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$i."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['ReqId']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CustomerCode']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Customer_Name']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['District']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Post_Code']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Actual_Customer_Credit_Limit']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Actual_Customer_Balance']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Current_Price']."</p></td>";
        $i++;
    }
    $Message.="</tr></tbody></table>";
    if($count >0)
    {
        return $Message;
    }else{
        $Message="";
        return $Message;
    }
}








public function Generate_Mail_Tempalte_For_Credit_Limit_Exceed_New($data)
{
    $Message="";
    $Message.="<table border='0'>
        <tbody>
        <tr>
        <td colspan='2' align='left'><p style='margin:0;'>Dear User(s),</br></br>

        Please Change the Below Customer's Credit Limit.</p></td>
        </tr>
        </tbody>
        </table><table  style='border-collapse:collapse;margin-bottom:10.0pt; padding: 4pt 5pt;border: 1pt solid #999;mso-border-alt: 1px solid #999; width: 100%;mso-margin-bottom-alt:10pt;'>
        <thead>
        <tr>
        <th style='padding: 4pt 4pt;border: 1pt solid #999; mso-border-alt: 1px solid #999;'><p style='margin:0;'>SL.No.</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Indent No</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Customer Code</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Customer Name</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>District</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Postal Code</p></th>
       
        
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Existing Credit Limit</p></th>

        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Utilized Credit Limit</p></th>


        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Available Credit Limit</p></th>


        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Approved Indent Value</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>New Credit Limit</p></th>";

    $Message.="</tr></thead><tbody>";
    $i=1;
    $count=0;




 //   echo "<pre>";print_r($data);exit;
    while($result=sqlsrv_fetch_array($data))

           //  print_r($result);exit();

     //   echo "<pre>";print_r($result);exit;
    {
        $count++;
        $Message.= " <tr>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$i."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['ReqId']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CustomerCode']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Customer_Name']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['District']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Post_Code']."</p></td>
       
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CREDIT_LIMIT_EXISTING']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CREDIT_LIMIT_UTILIZE']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CREDIT_LIMIT_AVAILABLE']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['APPROVED_VALUE']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CREDIT_LIMIT_NEW']."</p></td>
        ";
        $i++;
    }
    $Message.="</tr></tbody></table>";
    if($count >0)
    {
        return $Message;
    }else{
        $Message="";
        return $Message;
    }
}









public function Generate_Mail_Tempalte_For_Credit_Limit_Exceed_New_changed($data)
{
    // echo "<pre>";print_r($data);exit;
    $Message="";

    /* Table Header Start Here*/
    $Message.="<table border='0'>
        <tbody>
        <tr>
        <td colspan='2' align='left'><p style='margin:0;'>Dear User(s),</br></br>

        Please Change the Below Customer's Credit Limit.</p></td>
        </tr>
        </tbody>
        </table><table  style='border-collapse:collapse;margin-bottom:10.0pt; padding: 4pt 5pt;border: 1pt solid #999;mso-border-alt: 1px solid #999; width: 100%;mso-margin-bottom-alt:10pt;'>
        <thead>
        <tr>
        <th style='padding: 4pt 4pt;border: 1pt solid #999; mso-border-alt: 1px solid #999;'><p style='margin:0;'>SL.No.</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Indent No</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Customer Code</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Customer Name</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>District</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Postal Code</p></th>
       
        
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Existing Credit Limit</p></th>

        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Utilized Credit Limit</p></th>


        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Available Credit Limit</p></th>


        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>Approved Indent Value</p></th>
        <th style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>New Credit Limit</p></th>";

    $Message.="</tr></thead><tbody>";

    /*  Table Header END HERE */
    $i=1;
    $count=0;




 $resarr = array();
    while($result=sqlsrv_fetch_array($data))
{





 $resarr[] =$result['FINAL'];

 /*  FInal Value Greater The Zero  */

 
        if($result['FINAL']!=0){

 $count++;
        $Message.= " <tr>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".$i."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['ReqId']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CustomerCode']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Customer_Name']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['District']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['Post_Code']."</p></td>
       
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CREDIT_LIMIT_EXISTING']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CREDIT_LIMIT_UTILIZE']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CREDIT_LIMIT_AVAILABLE']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['APPROVED_VALUE']."</p></td>
        <td style='padding: 6pt 6pt;border: 1pt solid #999;mso-border-alt: 1px solid #999;'><p style='margin:0;'>".@$result['CREDIT_LIMIT_NEW']."</p></td>
        ";
       


      
    $i++;
      
 
    $Message.="</tr>";
    


       }



      } 

 $Message.="</tbody></table>";
    

      if($count >0)
    {
        return $Message;
    }else{
      
       // return $Message;
    }  

 /*  FInal Value Greater The Zero  END HERE */

}










}



?>