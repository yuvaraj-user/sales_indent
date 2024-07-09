
  $(document).on("change",".crop_id,.material_id",function()
  {
    
    $(this).closest('tr').find(".QtyInBag").val("");
    $(this).closest('tr').find(".QtyInPkt").val("");
    $(this).closest('tr').find(".QtyInKg").val("");
    $(this).closest('tr').find(".Price").val("");
    $(this).closest('tr').find(".Total_Price").val("");
    $(this).closest('tr').find(".Grand_Total_Price").val("");
    $(this).closest('tr').find(".Product_QtyInKg").val("");
    $(this).closest('tr').find(".Product_QtyInPkt").val("");
  });

  function Limit_Exceeds_Validation()
  {
    var customer_limit=$(".Customer_Credit_Limit").val();
    customer_limit=parseFloat(customer_limit);
    var Total_Price=0;
    $(".Grand_Total_Price").each(function(){
      Total_Price_Calculation($(this));
      var Curren_Total_price=$(this).closest('tr').find(".Grand_Total_Price").val();
      if(Curren_Total_price !='' && !isNaN(Curren_Total_price))
      {
        Total_Price=parseFloat(Total_Price)+parseFloat(Curren_Total_price);
      }
    });

    var error_count=0;
    if(Total_Price <= customer_limit){
      error_count=0;
      $(".Limit_Exceed").val(0);
     // $(".limit_exceeds_error").html("");
    }else{
      $(".Limit_Exceed").val(1)
     // $(".limit_exceeds_error").html("Customer Limit Is Exceeds");
      error_count++;
    }
    console.log(error_count);
    return error_count;

  }

  $(document).on("change",".quot_type,.sale_type",function(){
show_details();
  });

  function show_details(){
    var quotation_type =$(".quot_type").val();
    var Sales_Order_type  =$(".sale_type").val();
    console.log({quotation_type,Sales_Order_type});
    if(Sales_Order_type !='' && quotation_type !=''){

$(".Sales_Indent_Div").css("display","flex")
    }else{
      $(".Sales_Indent_Div").css("display","none")
    }  

  }

 function Get_Material_Price_Details(Customer,Product_Division,Material,Current_Date)
  {
    var price=0;
    $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:{"Action":"Get_Material_Price_Details","Customer":Customer,"Product_Division":Product_Division,"Material":Material,"Current_Date":Current_Date},
       async:false,
      success: function(data){
        var result=JSON.parse(data);
        price= result.Price;
        }
      });

      return price;
  }


  function Get_Material_Price_Details_multiple(Customer,Product_Division,Material,Current_Date,region_id)
  {
    var price=0;
    $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:{"Action":"Get_Material_Price_Details_multiple","Customer":Customer,"Product_Division":Product_Division,"Material":Material,"Current_Date":Current_Date,region_id},
       async:false,
      success: function(data){
        var result=JSON.parse(data);
        price= result.Price;
        }
      });

      return price;
  }

  $(document).on("change",".material_id",function(){
    var product_division = $(".product_division").val();
    var customer_id=$(".customer_id").val();
    var material_id=$(this).val();
    var Current_Date="<?php echo date('Y-m-d') ?>";
    var price=Get_Material_Price_Details(customer_id,product_division,material_id,Current_Date);
    $(this).closest("tr").find(".Price").val(price);
  })





  function Get_Customer_Balance_Details(customer_id)
  {
    $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:{"Action":"Get_Customer_Balance_Details","customer_id":customer_id},
       async:false,
      success: function(data){
        var result=JSON.parse(data);
         $(".Customer_Balance").val(result.BALANCE);
         $(".Customer_Credit_Limit").val(result.CREDIT_LIMIT);
        $('.sales_indent_material_card').show();

        }
      });
  }



    $(document).on("change",".customer_id",function(){
      var customer_id=$(this).val();
      if(customer_id !='' && customer_id !='0'){
        Get_Customer_Balance_Details(customer_id);
      }

  });

  

  function show_details_material(){
    var customer_id =$(".customer_id").val();
    var supply_type  =$(".supply_type").val();
    var plant_id  =$(".plant_id").val();
    var product_division = $(".product_division").val();

    console.log({customer_id,supply_type,plant_id});
    if(customer_id !='' && supply_type !='' && plant_id !='' && product_division !='ras'){

$(".Sales_Indent_Div_Material").css("display","flex")
    }else{
      $(".Sales_Indent_Div_Material").css("display","none")
    }

  }


   $(document).on("change",".crop_id",function(){
  //show_details_crop();
  });

  function show_details_crop(){
    var crop_id =$(".crop_id").val();
    if(crop_id !=''){

$(".Sales_Indent_Div_Crop").css("display","flex")
    }else{
      $(".Sales_Indent_Div_Crop").css("display","none")
     // $(".Sales_Indent_Div_Material").css("display","none")
    } 

  }


  $(document).ready(function(){
     $('.js-example-basic-single').select2();
     var product_division = $(".product_division").val();
     var supply_type = $(".supply_type").val(); 

     //alert(product_division); 

if(product_division=='fcm'){
$(".Get_Data").css("display","none")

}else if(product_division=='ras'){
$(".Get_Data").css("display","block")

}

     show_details_material();
     role_based_filter(product_division,"Get_Zone_Details",0,0,0,0,0);
     
     var zone_id = $(".zone_id").val();
     var region_id = $(".region_id").val();
     var territory_id = $(".territory_id").val();
     var crop_code = $(".crop_id").val();
     Get_Quotation_Type_Details(product_division,supply_type);
     Get_Sales_Order_Type_Details(product_division,supply_type);
     Get_Direct_And_CNF_Plant_Details(product_division,region_id,supply_type,0);
     get_customer_details(product_division,zone_id,region_id,territory_id);
     show_details();
     Set_Discount();
     var product_division=$(".product_division").val();
     var crop_code=$(".crop_id").val();
    // alert(crop_code);
     var zone_id=$(".zone_id").val();
     var crop_details = Get_Crop_Details(product_division);
     var plant_details=  Get_Direct_And_CNF_Plant_Details(product_division,region_id,supply_type,1);

     var plant_id = $(".plant_id").val();

   //  alert(plant_id);

     Get_Storage_Location_Details(product_division,region_id,supply_type,plant_id,0);
     var Storage_details=  Get_Storage_Location_Details(product_division,region_id,supply_type,plant_id,1);


     var Customer_Id=$(".customer_id").val();
     get_material_details(product_division,crop_code,zone_id,region_id,Customer_Id,0);
  });
 




  $(document).on("change",".zone_id,.region_id,.territory_id,.product_division",function(){
    var product_division = $(".product_division").val();
   var zone_id = $(".zone_id").val();
     var region_id = $(".region_id").val();
     var territory_id = $(".territory_id").val();
     get_customer_details(product_division,zone_id,region_id,territory_id);
   
  });

$(document).ready(function(){
  $('.plant_name').select2();
  $('.plant_id').change(function(){
    var value = $(this).val();
    $('.plant_name').val(value);
    $('.plant_name').select2().trigger('change');
  });
});


 function get_material_details(product_division,crop_code,zone_id,region_id,Customer_Id,status){

  //alert('Hai');
  var output="";
  if(Customer_Id !='' && Customer_Id !=0){

     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:{"Action":"Get_Material_Details_Based_On_Customer","product_division":product_division,"crop_code":crop_code,"zone_id":zone_id,Customer_Id:Customer_Id,region_id:region_id},
       async:false,
      success: function(data){
         result=JSON.parse(data);
         output=result.data;
        
        }
      });
    }
      if(status == 0){
        $('.material_id').html(output);

        
        }else{
      return output;
        }



}
  $(document).on("change",".supply_type",function(){
   var Product_Division=$(".product_division").val();
   var Region_Code = $(".region_id").val();
   var supply_type = $(this).val(); 
   Get_Direct_And_CNF_Plant_Details(Product_Division,Region_Code,supply_type,0);
   //show_details_material();
  });

   $(document).on("change",".product_division,.region_id",function(){
   var Product_Division=$(".product_division").val();
   var Region_Code = $(".region_id").val();
   var supply_type = $(".supply_type").val(); 
   Get_Direct_And_CNF_Plant_Details(Product_Division,Region_Code,supply_type,0);
  // show_details_material();
  });

    $(document).on("change",".customer_id,.supply_type",function(){
   show_details_material();
  });



     $(document).on("change",".plant_id",function(){

     // alert("hai");

       var product_division=$(".product_division").val();
   var region_id = $(".region_id").val();
   var supply_type = $(".supply_type").val(); 
   var plant_id = $(this).val(); 

//   alert(plant_id);

       Get_Storage_Location_Details(product_division,region_id,supply_type,plant_id,0);

show_details_material();
  

  });




function Get_Direct_And_CNF_Plant_Details(Product_Division,Region_Code,Supply_Type,status)
{
  var output="";
  if(Supply_Type =='2'){
    Supply_Type='CNF';
  }else{
    Supply_Type='Direct';
  }
  $.ajax ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_STO_Plant_Details","product_division":Product_Division,"Region_Code":Region_Code
      ,"Type":Supply_Type,Statement_Type:'Sales_Indnet'},
       async:false,
      success: function(data){
         var result=JSON.parse(data);
         output=result.data;
        }
      });

  if(status == 0){
        $('.plant_id').html(output);
        $('.plant_name').html(output);
        }else{
      return output;
        }

}

  function get_plant_details(supply_type,status){
var output="";
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Plant_Details","supply_type":supply_type},
       async:false,
      success: function(data){
         result=JSON.parse(data);
         output=result.data;
        
        }
      });
      if(status == 0){
        $('.plant_id').html(output);
        $('.plant_name').html(output);
        }else{
      return output;
        }
  }



  function Get_Storage_Location_Details(product_division,region_id,Supply_Type,plant_id,status)
{

 // alert("Hai11");
  var output="";
  if(Supply_Type =='2'){
    Supply_Type='CNF';
  }else{
    Supply_Type='Direct';
  }
  $.ajax ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Storage_Location_Details","product_division":product_division,"region_id":region_id
      ,"Type":Supply_Type,Statement_Type:'Sales_Indnet',"plant_id":plant_id},
       async:false,
      success: function(data){
         var result=JSON.parse(data);
         output=result.data;
        }
      });

  if(status == 0){
        $('.Storage_Location').html(output);
        $('.Storage_Location').html(output);
        }else{
      return output;
        }

}





   function get_customer_details(product_division,zone_code,region_code,territory_code){
console.log({product_division,zone_code,region_code,territory_code});

    if(product_division !='' && product_division !='0' && zone_code !='' && zone_code !='0'&& region_code !='' && region_code !='0' && territory_code !='' && territory_code !='0'){
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Customer_Details","product_division":product_division,"zone_code":zone_code,"region_code":region_code,"territory_code":territory_code},
       async:false,
      success: function(data){
        
        var result=JSON.parse(data);
        $('.customer_id').html(result.data);

        }
      });
    }
  }

function Get_Sales_Order_Type_Details(Product_Division,Supply_Type)
{
  $.ajax 
    ({
    type: "POST",
    url: "Common_Ajax.php",
     data:{"Action":"Get_Sales_Order_Type_Details","Supply_Type":Supply_Type,"Product_Division":Product_Division},
     async:false,
    success: function(data){
      var result=JSON.parse(data);
      $(".sale_type").html(result.data);
      }
    });
}

function Get_Quotation_Type_Details(Product_Division,Supply_Type)
{
    $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:{"Action":"Get_Quotation_Type_Details","Supply_Type":Supply_Type,"Product_Division":Product_Division},
       async:false,
      success: function(data){
        var result=JSON.parse(data);
        $(".quot_type").html(result.data);
        }
      });
}

  $(document).on("change",".product_division,.supply_type",function(){
    var Product_Division=$(".product_division").val();
    var Supply_Type=$(".supply_type").val();
    Get_Quotation_Type_Details(Product_Division,Supply_Type);
    Get_Sales_Order_Type_Details(Product_Division,Supply_Type);
  });


  function role_based_filter(product_division,action_type,zone_code,region_code,territory_code,crop_code,variety_code)
  {
      $.ajax 
      ({
      type: "POST",
      url: "Role_Based_Location_Dimensions_for_Sales_Mis.php",
      data:{"product_division":product_division,"action_type":action_type,"zone_code":zone_code,"region_code":region_code,"territory_code":territory_code,"crop_code":crop_code,"variety_code":variety_code},
       async:false,
      success: function(data){
        var zone_dets="0";
        var region_dets="0";
        var territory_dets="0";
        var crop_dets="0";
        var quotation_dets="0";
        var saleorder_dets="0";
       
        var result=JSON.parse(data);
        if(action_type == "Get_Zone_Details"){
          zone_dets="1";
          region_dets="1";
          territory_dets="1"; 
          quotation_dets="1"; 
          crop_dets="1"; 
        }else if(action_type == "Get_Region_Details"){
          zone_dets="0";
          region_dets="1";
         
        }else if(action_type == "Get_Territory_Details"){
          zone_dets="0";
          region_dets="0";
          territory_dets="1";
        }else if(action_type == "Get_Crop_Details"){
    
          crop_dets="1";
        }
        
         
        
        if(crop_dets == "1"){
          $(".crop_id").html(result.crop_details);
        }

        if(quotation_dets == "1"){
          $(".quot_type").html(result.quotation_details);
        }

        if(saleorder_dets == "1"){
          $(".sale_type").html(result.saleorder_details);
        }

         if(zone_dets == "1"){
          $(".zone_id").html(result.zone_details);
        }

        if(region_dets == "1"){
          $(".region_id").html(result.region_details);
        }
        if(territory_dets == "1"){
          $(".territory_id").html(result.territory_details);
        }

        }
      });    
  }

  $(document).on("change",".product_division",function(){
    var zone_id=0;
    var region_id=0;
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division").val();
    role_based_filter(product_division,"Get_Zone_Details",zone_id,region_id,territory_id,crop_code,variety_code);

    if(product_division=='fcm'){
$(".Get_Data").css("display","none")

}else if(product_division=='ras'){
$(".Get_Data").css("display","block")

}

RemoveRequired()

  });



  function RemoveRequired() {
 // $(".QtyInBag").removeClass("required_for_valid");
 // $(".QtyInBag").toggleClass('required_for_valid required_for_valid_remove');
  //$(".QtyInBag").toggleClass('required_for_valid required_for_valid_remove');

  $('.QtyInBag').removeClass('required_for_valid').addClass('required_for_valid_removeMain');


}





  $(document).on("change",".zone_id",function(){
    var zone_id=$(this).val();
    var region_id=0;
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division").val();
    role_based_filter(product_division,"Get_Region_Details",zone_id,region_id,territory_id,crop_code,variety_code);
     role_based_filter(product_division,"Get_Territory_Details",zone_id,region_id,territory_id,crop_code,variety_code);
  });


  $(document).on("change",".region_id",function(){
    var zone_id=$(".zone_id").val();
    var region_id=$(this).val();
    var territory_id=0;
    var crop_code=0;
    var variety_code=0;
    var product_division = $(".product_division").val();
    role_based_filter(product_division,"Get_Territory_Details",zone_id,region_id,territory_id,crop_code,variety_code);

     var territory_id=$(".territory_id").val();

    get_customer_details(product_division,zone_id,region_id,territory_id);

    
  });


   /*$(document).on("change",".product_division,.crop_id,.zone_id",function(){
    var product_division = $(".product_division").val();
   var zone_id = $(".zone_id").val();
     var crop_code = $(".crop_id").val();
     
   
     get_material_details(product_division,crop_code,zone_id,0);
   
  }); */

   $(document).on("change",".customer_id",function(){
    var product_division = $(".product_division").val();
    var zone_id = $(".zone_id").val();
    var region_id = $(".region_id").val();
    var crop_code = $(".crop_id").val();
    var Customer_Id=$(this).val();
    var material_dets=get_material_details(product_division,crop_code,zone_id,region_id,Customer_Id,0);
    $(".material_id").html(material_dets);
  });


   $(document).on("change",".crop_id",function(){
     
     //alert('Corp');
     
    var product_division = $(".product_division").val();
    var zone_id = $(".zone_id").val();
    var region_id = $(".region_id").val();
    var crop_code = $(this).val();
    var Customer_Id=$(".customer_id").val();
    var material_dets=get_material_details(product_division,crop_code,zone_id,region_id,Customer_Id,1);
  //alert(material_dets);
    $(this).closest('tr').find(".material_id").html(material_dets);

   








   
  });


   $(document).on("change",".material_id",function(){
    $(this).closest("tr").find(".QtyInBag").removeAttr("readonly");
    $(this).closest("tr").find(".QtyInBag").removeClass("textbox-grey");

    var material_id=$(this).val();
    var curren_tr=$(this).closest("tr");
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Product_Based_Qty_Details","material_id":material_id},
       async:false,
      success: function(result){
        //QtyInPkt,QtyInKg
        var data=JSON.parse(result);
        curren_tr.find(".Product_QtyInPkt").val(data.QtyInPkt);
        curren_tr.find(".Product_QtyInKg").val(data.QtyInKg);
      }
    });
   });
function check_duplication_row(){
    var duplicate_count=0;
    var error_count=0;
    $(".material_id").each(function(){
      duplicate_count=0;
      var material_id=$(this).val();
      $(".material_id").each(function(){
        
        var Current_material_id=$(this).val(); 
        if(material_id==Current_material_id){
          duplicate_count++;
        }
      });

      if(duplicate_count>1){
        error_count++;
        $(this).closest("tr").addClass('duplicate_row');
      }else{
        $(this).closest("tr").removeClass('duplicate_row');
      }

    });
if(error_count >0){
  //alert("Some Values Are Duplicate.Please Check It.");
$(".duplicate_error").html("Some Material Are Duplicate.Please Check It.");
}else{
  $(".duplicate_error").html("");
}
return error_count;
}

function Get_Season_Code_Details(user_input)
{
  var region_id=user_input.region_id;
  var material_id=user_input.material_id;
  var crop_id=user_input.crop_id;
  var option="<option value=''> Select Season </option>";
   $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Season_Code_Details","region_id":region_id,"material_id":material_id,"crop_id":crop_id},
       async:false,
      success: function(result){
        var data=JSON.parse(result);
        option=data.data;
      }
    });

      return option;

}




function Get_Season_Code_Details_multiple(user_input)
{
  var region_id=user_input.region_id;
  var material_id=user_input.material_id;
  var crop_id=user_input.crop_id;
  var option="<option value=''> Select Season </option>";
   $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Season_Code_Details_multiple","region_id":region_id,"material_id":material_id,"crop_id":crop_id},
       async:false,
      success: function(result){
        var data=JSON.parse(result);
        option=data.data;
      }
    });

      return option;

}




function Get_Crop_Details(product_division)
{

  var product_division=product_division;
  
  var option="<option value=''> Select Crop </option>";
   $.ajax 
      ({
      type: "POST",
      url: "Role_Based_Location_Dimensions_for_Sales_Mis.php",
      data:{"action_type":"Get_Crop_Details","product_division":product_division},
       async:false,
      success: function(result){
        var data=JSON.parse(result);
        option=data.crop_details;
      }
    });

      return option;

}



function Get_Crop_Details_sub(product_division,crop_id)
{

  var product_division=product_division;
  var crop_code=crop_id;
  
  var option="<option value=''> Select Crop </option>";
   $.ajax 
      ({
      type: "POST",
      url: "Role_Based_Location_Dimensions_for_Sales_Mis.php",
      data:{"action_type":"Get_Crop_Details_sub","product_division":product_division,"crop_code":crop_code},
       async:false,
      success: function(result){
        var data=JSON.parse(result);
        option=data.crop_details;
      }
    });

      return option;

}



$(document).on("change",".material_id",function(){
  var material_id=$(this).val();
  var region_id=$(".region_id").val();
  var crop_id=$(".crop_id").val();
  var user_input={};
user_input.material_id=material_id;
user_input.region_id=region_id;
user_input.crop_id=crop_id;

 //Set_Discount();
var result=Get_Season_Code_Details(user_input);
$(this).closest('tr').find(".season").html(result);



  var product_division = $(".product_division").val();
    var customer_id=$(".customer_id").val();
    var material_id=$(this).val();
    var Current_Date= '<?php echo date("Y-m-d"); ?>';
    console.log(Current_Date);
    if(product_division =="fcm"){
  var discount =  Get_Prodcut_and_Zone_Based_Discount(customer_id,product_division,material_id,Current_Date);
   // $(this).closest('tr').find(".crop_name_test").html(result);
    $(this).closest("tr").find(".Discount").val(discount);

    }else{

     $(this).closest("tr").find(".Discount").val(0);
    }





//$(this).closest('tr').find(".crop_name_test").html(result);
  var crop_details_sub = Get_Crop_Details_sub(product_division,crop_id);




})

  function add_row(){
    var supply_type=$(".supply_type").val();
      var product_division=$(".product_division").val();
      var crop_code=$(".crop_id").val();

      var crop_code_new=$("#crop_value").text();

      var zone_id=$(".zone_id").val();
      var Customer_Id=$(".customer_id").val();

    //  alert(product_division);


      if(product_division =='ras'){

 var crop_details = Get_Crop_Details(product_division);
      }

if(supply_type ==1 && product_division =='fcm'){

  //$(".crop_id").prop('disabled', 'disabled');
  // $(".crop_id").prop("readonly", true);

  // $(".crop_id").select2({disabled:'readonly'});

  //$(".crop_id").attr('readonly', true);
  $(".crop_id").attr("style", "pointer-events: none;");
//$(".crop_id").css("display","none");

  //document.getElementById( '.crop_id' ).style.display = 'none';
   var crop_details = Get_Crop_Details_sub(product_division,crop_code);
  

}else if(supply_type ==1 && product_division =='frg'){

  //$(".crop_id").prop('disabled', 'disabled');
  // $(".crop_id").prop("readonly", true);

  // $(".crop_id").select2({disabled:'readonly'});

  //$(".crop_id").attr('readonly', true);
  $(".crop_id").attr("style", "pointer-events: none;");
//$(".crop_id").css("display","none");

  //document.getElementById( '.crop_id' ).style.display = 'none';
   var crop_details = Get_Crop_Details_sub(product_division,crop_code);
  

}else{
 $(".crop_id").attr('enabled','enabled');
 var crop_details = Get_Crop_Details(product_division);

}


   

    
   
    var region_id = $(".region_id").val();

    //var plant_details=get_plant_details(supply_type,1);
    var material_details=get_material_details(product_division,crop_code,zone_id,region_id,Customer_Id);

      var markup = "<tr><td class='srn_no'></td><td><div><select class='js-example-basic-singles col-xs-12 crop_id required_for_valid' error-msg='Crop Field is Required' name='CropId[]' >"+ crop_details+"</select><small class='error-msg text-danger mt-1 lh-base'></small></div></td><td><div> <select class='js-example-basic-singles col-xs-12 material_id required_for_valid'  error-msg='Material Field is Required' name='MaterialCode[]' >"+ material_details+"</select><input type='hidden'  class='form-control right Product_QtyInPkt' name='MaterialQtyInPkt[]' value='0' readonly ><input type='hidden'  class='form-control right Product_QtyInKg' name='MaterialQtyInKg[]' value='0' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td> <td><div><select class='js-example-basic-singles season required_for_valid' error-msg='Season Field is Required' name='season[]' ><option value='' selected>Select Season </option></select><small class='error-msg text-danger mt-1 lh-base'></small></div></td><td><div>  <input type='hidden'  class='form-control right only_numbers max_charater location' error-msg='Pkts Field is Required' value='SE01' name='StorageLocation[]' readonly ><input type='text' class='form-control right max_charater required_for_valid only_numbers QtyInBag required_for_valid textbox-grey' error-msg='Quantity_Bag Field is Required ' name='QtyInBag[]' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td> <td><div><input type='text' class='form-control right max_charater required_for_valid only_numbers QtyInPkt textbox-grey' error-msg='Pkts Field is Required' name='QtyInPkt[]' readonly></div></td> <td><div><input type='text' class='form-control right max_charater  only_numbers QtyInKg textbox-grey' name='QtyInKg[]' readonly ></div></td>  <td> <div><input type='text'  class='form-control right only_numbers max_charater Price textbox-grey' error-msg='Price Per Pkts Field is Required' name='Price[]' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td><td> <div><input type='text'  class='form-control right only_numbers max_charater Total_Price textbox-grey' error-msg='Total price  Field is Required' name='Total_Price[]' style='width:110%' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td> <td> <div><input type='text'  class='form-control right only_numbers max_charater Discount textbox-grey' error-msg='Discount  Field is Required' name='Discount[]' style='width:110%' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td><td> <div><input type='text'  class='form-control right only_numbers max_charater Grand_Total_Price textbox-grey' error-msg='Grand Total price  Field is Required' name='Grand_Total_Price[]' style='width:110%' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td><td><div class='d-flex mt-2'><button type='button' onclick ='add_row()' class='btn btn-sm btn-success'><i class='fas fa-plus-circle'></i></button> <button class='btn delete btn-sm btn-danger ms-1' onclick ='delete_user($(this))'><i class='fas fa-trash'></i></button></div></tr>";
      $("table tbody").append(markup);
      Set_Discount();
    
      
      s_no();
    
      $('.js-example-basic-singles').select2();
    }

     function delete_user(row)
  {
    if($(".delete").length >1)
    {
      row.closest('tr').remove();

   

      s_no();
    }else{






      alert("Atleast One Row Present");
    }
   
  }


  function remove_option(){
      var optionValues = [];
      $('.cls_adv_to option').each(function(){
        if($.inArray(this.value, optionValues) >-1){
          $(this).remove();
        }else{
          optionValues.push(this.value);
        }
    });
  }

  function s_no(){
    var sno = 1;
    $(".srn_no").each(function(key,index){
       $(this).html((sno));   
    sno++;
    });
  }
  
  $(document).on("change keyup",".required_for_valid" ,function(){
    var current_val=$(this).val();
    var error_msg=$(this).attr("error-msg");
    if(current_val == ''){
      $(this).closest("div").find(".error_msg").html(error_msg);
       $(this).closest("td").find(".error-msg").html(error_msg);
    }else{
      $(this).closest("div").find(".error_msg").html("");
      $(this).closest("td").find(".error-msg").html("");
    }
  })
  
  function validation(){
    var error_count=0;
    $(".required_for_valid").each(function(){
      var current_val=$(this).val();

    var error_msg=$(this).attr("error-msg");
    if(current_val == ''){
      error_count++;
      $(this).closest("div").find(".error_msg").html(error_msg);
      $(this).closest("td").find(".error-msg").html(error_msg);
    }else{
      $(this).closest("div").find(".error_msg").html("");
      $(this).closest("td").find(".error-msg").html("");
    }
    });
    return error_count;
  }
  
  $(document).on("submit",".Sales_Indent_Submit",function(){



var product_division=$(".product_division").val();


    var duplication_count= check_duplication_row();
    var error_count=validation();
    var limit_exceeds_count=Limit_Exceeds_Validation();

//alert(error_count);


    if(product_division=='ras'){
     

var Qty_validation=Qty_Validation_Pricing_Only();

    }else if(product_division=='fcm'){

        

       var Qty_validation=Qty_Validation();
    }
  
  else if(product_division=='frg'){

        

       var Qty_validation=Qty_Validation();
    }
   

    
    limit_exceeds_count=0;
    if(error_count == 0 && duplication_count== 0 && limit_exceeds_count== 0){
      if(Qty_validation == 0){
        return true;
      }else{
        return false;
      }
      
    }else{
      return false;
    }
  });


  function Qty_Validation(){
    var Count=0
    /* QtyInBag Validation Start Here */
    $(".QtyInBag").each(function(){
      if(parseInt($(this).val()) <=0)
      {
        $(this).closest("div").find(".error_msg").html("Please Enter Valid Qty");
        Count++;
      }else{
        $(this).closest("div").find(".error_msg").html("");
      }
    });
    /* QtyInBag Validation Start Here */


    /* QtyInPkt Validation Start Here */
    $(".QtyInPkt").each(function(){
      if(parseInt($(this).val()) <=0)
      {
        $(this).closest("div").find(".error_msg").html("Please Enter Valid Qty");
        Count++;
      }else{
        $(this).closest("div").find(".error_msg").html("");
      }
    });
    /* QtyInPkt Validation Start Here */

    /* QtyInKg Validation Start Here */
    $(".QtyInKg").each(function(){
      if(parseInt($(this).val()) <=0)
      {
        $(this).closest("div").find(".error_msg").html("Please Enter Valid Qty");
        Count++;
      }else{
        $(this).closest("div").find(".error_msg").html("");
      }
    });
    /* QtyInKg Validation Start Here */

    /* Price Validation Start Here */
    $(".Price").each(function(){
      if(parseInt($(this).val()) <=0)
      {
        $(this).closest("div").find(".error_msg").html("Please Enter Valid Qty");
        Count++;
      }else{
        $(this).closest("div").find(".error_msg").html("");
      }
    });
    /* Price Validation Start Here */

    /* Total Price Validation Start Here */
    $(".Total_Price").each(function(){
      if(parseInt($(this).val()) <=0)
      {
        $(this).closest("div").find(".error_msg").html("Please Enter Valid Qty");
        Count++;
      }else{
        $(this).closest("div").find(".error_msg").html("");
      }
    });
    /* Total Price Validation Start Here */

return Count;

  }




  function Qty_Validation_Pricing_Only(){
    var Count=0
    

    /* Price Validation Start Here */
    $(".Price").each(function(){
      if(parseInt($(this).val()) <=0)
      {
        $(this).closest("div").find(".error_msg").html("Please Enter Valid Qty");
        Count++;
      }else{
        $(this).closest("div").find(".error_msg").html("");
      }
    });
    /* Price Validation Start Here */



return Count;

  }



$(document).on("change",".product_division",function(){
  Set_Discount();
})

  function Set_Discount()
  {
     var product_division=$(".product_division").val();




   //  $(".Discount").val(0);
    /* if(product_division == "fcm")
     {
      //$(".Discount").val(5);
//Get_Prodcut_and_Zone_Based_Discount(customer_id,product_division,material_id,Current_Date)
//alert("Hai");
      Get_Prodcut_and_Zone_Based_Discount(customer_id,product_division,material_id,Current_Date);

     // alert(discount);
    //$(this).closest("tr").find(".Discount").val(discount);

   // $(".Discount").val(discount);



     }else{
       $(".Discount").val(0);
     }*/
     $(".Grand_Total_Price").each(function(){
      Total_Price_Calculation($(this));
     });
  }



/*

function Get_Prodcut_and_Zone_Based_Discount(product_division,zone_id)
{

  var product_division=product_division;
  var zone_id=zone_id;
  
  var option="<option value=''> Select Crop </option>";
   $.ajax 
      ({
      type: "POST",
      url: "Auto_Fill_Details.php",
      data:{"action_type":"Get_Prodcut_and_Zone_Based_Discount","product_division":product_division,"zone_id":zone_id},
       async:false,
      success: function(result){
        var data=JSON.parse(result);
        option=data.crop_details;
      }
    });

      return option;

}



*/
 function Get_Prodcut_and_Zone_Based_Discount(Customer,Product_Division,Material,Current_Date)
  {



  //$(this).closest("tr").find(".QtyInBag").removeAttr("readonly");
   // var material_id=$(this).val();
    var curren_tr=$(this).closest("tr");

    var discount=0;
    $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
       data:{"Action":"Get_Prodcut_and_Zone_Based_Discount","Customer":Customer,"Product_Division":Product_Division,"Material":Material,"Current_Date":Current_Date},
       async:false,
      success: function(data){
        var result=JSON.parse(data);
        discount= result.discount;

      //  alert(discount);


        //curren_tr.find(".Discount").val(discount);


        }
      });

      return discount;
  }


  function Total_Price_Calculation(current_element)
  {
    var bag=current_element.closest("tr").find(".QtyInBag").val();/*Get User Input For How Many Bag U Want?*/


    if(bag>0){

      

var bag=current_element.closest("tr").find(".QtyInBag").val();

    }else{

var bag=current_element.closest("tr").find(".QtyInBag").val('');

    }


    
    var pkts=current_element.closest("tr").find(".Product_QtyInPkt").val(); /*Get How Many Packts in Single Bag */
    var kg=current_element.closest("tr").find(".Product_QtyInKg").val();/*Get Kg Weight */
    var Discount=current_element.closest("tr").find(".Discount").val(); /*Discount Amount (In Fcm Default 5% and Cotton 0% ) */
    Discount=parseInt(Discount);
    var QtyInPkt = bag * pkts;

    if(isNaN(QtyInPkt))
    {
      QtyInPkt=0;
    }
    var QtyInKg = QtyInPkt * kg;
    var price=current_element.closest('tr').find(".Price").val();
    price=parseFloat(price);
    var totalPrice=price*parseFloat(QtyInPkt);
    totalPrice=parseFloat(totalPrice);
    if(isNaN(totalPrice))
      {
        totalPrice=0;
      }
    var discount_amount=0;
    if(Discount !='' && Discount >0)
    {
      if(isNaN(totalPrice))
      {
        totalPrice=0;
      }
      discount_amount=totalPrice*(Discount/100);
    }
    var Grand_Total_Price=parseFloat(totalPrice)-parseFloat(discount_amount);
    
    // console.log({bag,pkts,kg,QtyInKg,QtyInPkt,totalPrice,Grand_Total_Price});
    current_element.closest("tr").find(".Total_Price").val(totalPrice.toFixed(2));
    current_element.closest("tr").find(".QtyInPkt").val(QtyInPkt);
    current_element.closest("tr").find(".QtyInKg").val(QtyInKg);
    current_element.closest("tr").find(".Grand_Total_Price").val(Grand_Total_Price.toFixed(2));

  }



  $(document).on("keyup", ".QtyInBag", function() {
    Total_Price_Calculation($(this));

    var Grand_Total_Price=$(".Grand_Total_Price").val();
    var limit_exceeds_count=Limit_Exceeds_Validation();
    if(limit_exceeds_count==1){
      alert("Limit Exceed");
    }
    return false;
            var bag = parseInt($(this).val());
            var pkts=$(this).closest("tr").find(".Product_QtyInPkt").val();
            console.log("pkts ==" + pkts);
            console.log("bag ==" + bag);
            var kg=$(this).closest("tr").find(".Product_QtyInKg").val();
            var QtyInPkt = bag * pkts;
            if(isNaN(QtyInPkt))
            {
              QtyInPkt=0;
            }
 var QtyInKg = QtyInPkt * kg;
 var price=$(this).closest('tr').find(".Price").val();
 var totalPrice=parseFloat(price)*parseFloat(QtyInPkt);
totalPrice=parseFloat(totalPrice);
console.log({bag,pkts,kg,QtyInKg,QtyInPkt,totalPrice});
$(this).closest("tr").find(".Total_Price").val(totalPrice.toFixed(2));
$(this).closest("tr").find(".QtyInPkt").val(QtyInPkt);
$(this).closest("tr").find(".QtyInKg").val(QtyInKg);
        });

   
// $(document).on("keyup",".QtyInBag",function(){

  
//   var Grand_Total_Price=$(".Grand_Total_Price").val();

//   var limit_exceeds_count=Limit_Exceeds_Validation();

//  // alert(limit_exceeds_count);


//   if(limit_exceeds_count==1){

// alert("Limit Exceed");



//   }
//  // alert(Grand_Total_Price);

// });  
  


 function get_Material_Count_details(product_division,crop_code,zone_id,status){
  var output="";

  


     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax_multiple.php",
       data:{"Action":"get_Material_Count_details","product_division":product_division,"crop_code":crop_code,"zone_id":zone_id},
       async:false,
      success: function(data){
         result=JSON.parse(data);
      //   output=result.data;

        let Material_Count=result.data.Material_Count;
          $('.rasiCount_material').val(Material_Count);
        
        }
      })
      



}



 function get_Material_Append_details(product_division,crop_code,zone_id,status,customer_id,Current_Date,region_id){
       
  var output="";
  var returnData="";
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax_multiple.php",
      dataType: "JSON",
       data:{"Action":"get_Material_Append_details","product_division":product_division,"crop_code":crop_code,"zone_id":zone_id},
       async:false,
      success: function(result){

        console.log("New" +result);
     //material_id 
    // alert(result.Hybrid_wise_dets[1].Hybrid);
      returnData = result.Hybrid;

       console.log("New123" +returnData); 
      
      result.Hybrid_wise_dets.forEach(function(item,index) {
           // do something with `item`
          // console.log(Get_Material_Price_Details_multiple(customer_id,product_division,item.Hybrid,Current_Date,region_id));
          // console.log(item);

           $("#Material"+index).val(item.Hybrid).select2();

               var price=Get_Material_Price_Details_multiple(customer_id,product_division,item.Hybrid,Current_Date,region_id);
              $("#Price"+index).val(price);
      });
       //$('.material_id').val(returnData);

   //console.log("New123" +returnData); 
       /*

       var toAppend = '';
           $.each(result,function(i,o){
           toAppend += '<option>'+o.result+'</option>';
          });

           //alert(result);
          // alert("Hai");
          console.log("Value" +toAppend);
         $('.material_id').append(toAppend);
    

          */



    
    }


  });
 // return returnData;





}


$(document).on("click",".Get_Data",function(){
    var product_division = $(".product_division").val();
    var zone_id = $(".zone_id").val();
    var crop_code = $(".crop_id").val();
    var Customer_Id=$(this).val();

   
get_Material_Count_details(product_division,crop_code,zone_id,status)

$(".CottonMaterial").css("display","none")
$(".Sales_Indent_Div_Material").css("display","flex")

     rCount = $(".rasiCount_material").val();


for(i=1;i<=rCount-1;i++){



        var supply_type=$(".supply_type").val();
      var product_division=$(".product_division").val();
      var crop_code=$(".crop_id").val();

      var crop_code_new=$("#crop_value").text();

      var zone_id=$(".zone_id").val();
      var Customer_Id=$(".customer_id").val();

  


      if(product_division =='ras'){
        var crop_details = Get_Crop_Details(product_division);
      }

      if(supply_type ==1 && product_division =='fcm'){

       
        $(".crop_id").attr("style", "pointer-events: none;");

         var crop_details = Get_Crop_Details_sub(product_division,crop_code);
        

      }else{
       $(".crop_id").attr('enabled','enabled');
       var crop_details = Get_Crop_Details(product_division);

      }


   

 
   

    var region_id = $(".region_id").val();
    
    var material_details=get_material_details(product_division,crop_code,zone_id,region_id,Customer_Id);

      var markup = "<tr><td class='srn_no'></td><td><div><select class='js-example-basic-singles col-xs-12 crop_id required_for_valid' error-msg='Crop Field is Required' name='CropId[]' >"+ crop_details+"</select><small class='error-msg text-danger mt-1 lh-base'></small></div></td><td><div> <select class='js-example-basic-singles col-xs-12 material_id required_for_valid' id='Material"+i+"'  error-msg='Material Field is Required' name='MaterialCode[]' >"+ material_details+"</select><input type='hidden'  class='form-control right Product_QtyInPkt' name='MaterialQtyInPkt[]' value='0' readonly ><input type='hidden'  class='form-control right Product_QtyInKg' name='MaterialQtyInKg[]' value='0' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td> <td><div><select class='js-example-basic-singles season required_for_valid' error-msg='Season Field is Required' name='season[]' ><option value='' selected>Select Season </option></select> <small class='error-msg text-danger mt-1 lh-base'></small></div></td><td><div>  <input type='hidden'  class='form-control right only_numbers max_charater location' error-msg='Pkts Field is Required' value='SE01' name='StorageLocation[]' readonly ><input type='text' class='form-control right max_charater required_for_valid only_numbers QtyInBag' id='RemoveRequired1' error-msg='Quantity_Bag Field is Required '  name='QtyInBag[]' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td> <td><div><input type='text' class='form-control right max_charater required_for_valid only_numbers QtyInPkt textbox-grey' error-msg='Pkts Field is Required' name='QtyInPkt[]' readonly></div></td> <td><div><input type='text' class='form-control right max_charater  only_numbers QtyInKg textbox-grey' name='QtyInKg[]' readonly ></div></td>  <td> <div><input type='text'  class='form-control right only_numbers max_charater Price textbox-grey' error-msg='Price Per Pkts Field is Required'  name='Price[]' id='Price"+i+"' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td><td> <div><input type='text'  class='form-control right only_numbers max_charater Total_Price textbox-grey' error-msg='Total price  Field is Required' name='Total_Price[]' style='width:110%' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td> <td> <div><input type='text'  class='form-control right only_numbers max_charater Discount textbox-grey' error-msg='Discount  Field is Required' name='Discount[]' style='width:110%' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td><td> <div><input type='text'  class='form-control right only_numbers max_charater Grand_Total_Price textbox-grey' error-msg='Grand Total price  Field is Required' name='Grand_Total_Price[]' style='width:110%' readonly ><small class='error-msg text-danger mt-1 lh-base'></small></div></td><td><div class='d-flex mt-2'><button type='button' onclick ='add_row()' class='btn btn-sm btn-success'><i class='fas fa-plus-circle'></i></button> <button class='btn delete btn-sm btn-danger ms-1' onclick ='delete_user($(this))'><i class='fas fa-trash'></i></button></div></tr>";
      // $("table tbody").empty();

      $("table tbody").append(markup);
      Set_Discount();
    
      
      s_no();

        $('.QtyInBag').removeClass('required_for_valid').addClass('required_for_valid_removeclass'); 
        $('.QtyInPkt').removeClass('required_for_valid').addClass('required_for_valid_removeclass'); 
    
      $('.js-example-basic-singles').select2();
              }

    var customer_id=$(".customer_id").val();
    var Current_Date="<?php echo date('Y-m-d') ?>";
  var region_id=$(".region_id").val();
           
        get_Material_Append_details(product_division,crop_code,zone_id,0,customer_id,Current_Date,region_id);
   


$(".QtyInBag").removeAttr("readonly");
 //var material_id=$(this).val();
 var material_id = $(".material_id").val();
    //var material_id=$(this).closest("tr").find(".material_id").val();
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Product_Based_Qty_Details","material_id":material_id},
       async:false,
      success: function(result){
        //QtyInPkt,QtyInKg
        var data=JSON.parse(result);
        $(".Product_QtyInPkt").val(data.QtyInPkt);
        $(".Product_QtyInKg").val(data.QtyInKg);
      }
    });





// var product_division = $(".product_division").val();
//     var customer_id=$(".customer_id").val();
//     var material_id=$(".material_id").val();
//     var Current_Date="<?php echo date('Y-m-d') ?>";
//   var region_id=$(".region_id").val();

    // var price=Get_Material_Price_Details_multiple(customer_id,product_division,material_id,Current_Date,region_id);
    // $(".Price").val(price);


  var region_id=$(".region_id").val();
  var crop_id=$(".crop_id").val();
  var user_input={};
user_input.material_id=material_id;
user_input.region_id=region_id;
user_input.crop_id=crop_id;


      var result=Get_Season_Code_Details_multiple(user_input);
$(".season").html(result);


  });


function MaterialDetails(){


// alert("Hai");
    // $(this).closest("tr").find(".QtyInBag").removeAttr("readonly");
    var material_id=$(this).val();
    var curren_tr=$(this).closest("tr");
     $.ajax 
      ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Get_Product_Based_Qty_Details","material_id":material_id},
       async:false,
      success: function(result){
        //QtyInPkt,QtyInKg
        var data=JSON.parse(result);
        curren_tr.find(".Product_QtyInPkt").val(data.QtyInPkt);
        curren_tr.find(".Product_QtyInKg").val(data.QtyInKg);
      }
    });

}
