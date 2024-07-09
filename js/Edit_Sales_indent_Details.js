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

$(document).on("click",".remove_btn",function(){
	var SalesIndentId=$(this).closest("tr").find(".SalesIndentId").val();
  var closest=$(this).closest("tr");
  if(confirm('Are You Sure Want to Delete This?')){
    if($(".remove_btn").length >1)
    {
     $.ajax 
     ({
      type: "POST",
      url: "Common_Ajax.php",
      data:{"Action":"Delete_Material_Details","SalesIndentId":SalesIndentId},
      async:false,
      success: function(data)
      {
       result=JSON.parse(data);
       if(result.status == 1)
       {
        closest.remove();
        alert("Deleted Successfully..");
      }else
      {
        alert("Something Went wrong");
      }

    }
  });


   }else{
     alert("Altest One Row Present in table");
   }
 }

})

// $(document).ready(function(){

//  $('.js-example-basic-single').select2();
//  var product_division = $(".product_division").val();
//  var supply_type = $(".supply_type").val();  
//  var crop_id=$(".cropid").val();
//  role_based_filter(product_division,"Get_Zone_Details",0,0,0,crop_id,0);
//  var PlantId="<?php echo $Header_data['PlantId'];?>";
//  console.log(PlantId);
//  get_plant_details(supply_type,PlantId,0);
//  var zone_id = $(".zone_id").val();
//  var region_id = $(".region_id").val();
//  var territory_id = $(".territory_id").val();
//  var crop_code = $(".crop_id").val();
//  var customer_id='<?php echo @$Header_data["CustomerCode"]?>';
//  get_customer_details(product_division,zone_id,region_id,territory_id,customer_id);
//  $('.js-example-basic-single').select2();
//  $(".Material_Code").each(function(key,value){
//   var Material_Code=$(this).closest("tr").find(".Material_Code").val();
//   var result="";
//   result= get_material_details(product_division,crop_code,zone_id,1,Material_Code);
//   $(this).closest("tr").find(".material_id").html(result);
//   $(this).closest("tr").find(".material_id").select2();
//   var material_id=$(this).val();
//   var region_id=$(".region_id").val();
//   var user_input={};
//   user_input.material_id=material_id;
//   user_input.region_id=region_id;
//   var result=Get_Season_Code_Details(user_input);
//   $(this).closest('tr').find(".season").html(result);
// });



// });
$(document).on("change",".supply_type",function(){
 get_plant_details($(this).val(),0,0);

});

$(document).on("change",".supply_type",function(){

 get_plant_details($(this).val(),0,0);
   //var plant_name = $("#plant_id").text();
   //$("#plant_name").val(plant_name);

});





$(document).on("change",".zone_id,.region_id,.territory_id",function(){
  var product_division = $(".product_division").val();
  var zone_id = $(".zone_id").val();
  var region_id = $(".region_id").val();
  var territory_id = $(".territory_id").val();
  get_customer_details(product_division,zone_id,region_id,territory_id,0);

});

$(document).ready(function(){
  $('.plant_name').select2();
  $('.plant_id').change(function(){
    var value = $(this).val();
    $('.plant_name').val(value);
    $('.plant_name').select2().trigger('change');
  });
});


function get_material_details(product_division,crop_code,zone_id,status,Material_Code,region_id){
  var output="";
  $.ajax 
  ({
    type: "POST",
    url: "Common_Ajax.php",
    data:{"Action":"Get_Material_Details","product_division":product_division,"crop_code":crop_code,"zone_id":zone_id,"Material_Code":Material_Code,region_id:region_id},
    async:false,
    success: function(data){
     result=JSON.parse(data);
     output=result.data;

   }
 });
  if(status == 0){
    $('.material_id').html(output);

  }else{
    return output;
  }



}

function get_plant_details(supply_type,Plant_Code,status){
  var output="";
  $.ajax 
  ({
    type: "POST",
    url: "Common_Ajax.php",
    data:{"Action":"Get_Plant_Details","supply_type":supply_type,"Plant_Code":Plant_Code},
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

function get_customer_details(product_division,zone_code,region_code,territory_code,CustomerCode){
  if(product_division !='' && product_division !='0' && zone_code !='' && zone_code !='0'&& region_code !='' && region_code !='0' && territory_code !='' && territory_code !='0'){
   $.ajax 
   ({
    type: "POST",
    url: "Common_Ajax.php",
    data:{"Action":"Get_Customer_Details","product_division":product_division,"zone_code":zone_code,"region_code":region_code,"territory_code":territory_code,"CustomerCode":CustomerCode},
    async:false,
    success: function(data){

      var result=JSON.parse(data);
      $('.customer_id').html(result.data);

    }
  });
 }
}




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
      var variety_dets="0";
      var result=JSON.parse(data);
      if(action_type == "Get_Zone_Details"){
        zone_dets="1";
        region_dets="1";
        territory_dets="1"; 
        crop_dets="1"; 
        variety_dets="1"; 
      }else if(action_type == "Get_Region_Details"){
        zone_dets="0";
        region_dets="1";
        territory_dets="1";
      }else if(action_type == "Get_Territory_Details"){
        zone_dets="0";
        region_dets="0";
        territory_dets="1";
      }else if(action_type == "Get_varirty_Details"){
       variety_dets="1"; 
     }


     if(crop_dets == "1"){
      $(".crop_id").html(result.crop_details);
    }

    if(variety_dets == "1"){
      $(".hybrid_id").html(result.variety_details);
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
});
$(document).on("change",".zone_id",function(){
  var zone_id=$(this).val();
  var region_id=0;
  var territory_id=0;
  var crop_code=0;
  var variety_code=0;
  var product_division = $(".product_division").val();
  role_based_filter(product_division,"Get_Region_Details",zone_id,region_id,territory_id,crop_code,variety_code);
});

$(document).on("change",".crop_id",function(){
  var zone_id=0;
  var region_id=0;
  var territory_id=0;
  var crop_code=$(this).val();
  var variety_code=0;
  var product_division = $(".product_division").val();
  role_based_filter(product_division,"Get_varirty_Details",zone_id,region_id,territory_id,crop_code,variety_code);
});

$(document).on("change",".region_id",function(){
  var zone_id=$(".zone_id").val();
  var region_id=$(this).val();
  var territory_id=0;
  var crop_code=0;
  var variety_code=0;
  var product_division = $(".product_division").val();
  role_based_filter(product_division,"Get_Territory_Details",zone_id,region_id,territory_id,crop_code,variety_code);
});
$(document).on("change",".product_division,.crop_id,.zone_id",function(){
  var product_division = $(".product_division").val();
  var zone_id = $(".zone_id").val();
  var crop_code = $(".crop_id").val();
  var region_id = $(".region_id").val();


  get_material_details(product_division,crop_code,zone_id,0,0,region_id);

});

$(document).on("change",".material_id",function(){
  $(this).closest("tr").find(".QtyInBag").removeAttr("readonly");
  var material_id=$(".material_id").val();
  var product_division = $(".product_division").val();
  var curren_tr=$(this).closest("tr");
  var date = "<?php echo date('Y-m-d'); ?>";
  var customer_code = $('.customer_id').val();
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

  Get_Material_Price_Details(customer_code,product_division,material_id,date,curren_tr);
});


$(document).on("submit",".Sales_Indent_Submit",function(){
  var duplication_count= check_duplication_row();
  var error_count=validation();
  if(error_count == 0 && duplication_count== 0){
    return true;
  }else{
    return false;
  }
})

$(document).on("change",".material_id",function(){
  var material_id=$(this).val();
  var region_id=$(".region_id").val();
  var user_input={};
  user_input.material_id=material_id;
  user_input.region_id=region_id;
  var result=Get_Season_Code_Details(user_input);
  $(this).closest('tr').find(".season").html(result);

})

/*
   $(document).ready(function() {
 
  $('#MaterialCode').change(function() {
    if( $(this).val() == 0) {
          $('#QtyInPkt').prop( "disabled", true );
    } else {       
      $('#QtyInPkt').prop( "disabled", false );
    }
  });
 
});
 */
/*
  function add_row(){
    var supply_type=$(".supply_type").val();
      var product_division=$(".product_division").val();
      var crop_code=$(".crop_id").val();
      var zone_id=$(".zone_id").val();

    //var plant_details=get_plant_details(supply_type,1);
    var material_details=get_material_details(product_division,crop_code,zone_id,1,0);

      var markup = "<tr><td class='srn_no'></td><td><div> <select class='js-example-basic-singles col-xs-12 material_id required_for_valid'  error-msg='Material Field is Required' name='MaterialCode[]' >"+ material_details+"</select><label class='error_msg text-danger'></label></div></td><td><div><input type='text' class='form-control right max_charater required_for_valid only_numbers QtyInBag required_for_valid' error-msg='Quantity_Bag Field is Required ' name='QtyInBag[]' readonly ><label class='error_msg text-danger'></div></td> <td><div><input type='text' class='form-control right max_charater required_for_valid only_numbers QtyInPkt ' error-msg='Pkts Field is Required' name='QtyInPkt[]' readonly><input type='hidden'  class='form-control right Product_QtyInPkt' name='QtyInPkt[]' value='0' readonly ><input type='hidden'  class='form-control right Product_QtyInKg' name='QtyInPkt[]' value='0' readonly ></div></td> <td><div><input type='text' class='form-control right max_charater  only_numbers QtyInKg' name='QtyInKg[]' readonly ></div></td><td> <button class='delete btn-sm btn-danger' onclick ='delete_user($(this))'>X</button></tr>";
      $("table tbody").append(markup);
       var result="";
      s_no();
    
      $('.js-example-basic-singles').select2();
    }
    */

function delete_user(row)
{
  row.closest('tr').remove();
  s_no();
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

function Get_Season_Code_Details(user_input,Season)
{
  var region_id=user_input.region_id;
  var material_id=user_input.material_id;
  var option="<option value=''> Select Season </option>";
  $.ajax 
  ({
    type: "POST",
    url: "Common_Ajax.php",
    data:{"Action":"Get_Season_Code_Details","region_id":region_id,"material_id":material_id,"Season":Season},
    async:false,
    success: function(result){
      var data=JSON.parse(result);
      option=data.data;
    }
  });

  return option;

}


$(document).on("change keyup",".required_for_valid" ,function(){
  var current_val=$(this).val();
  var error_msg=$(this).attr("error-msg");
  if(current_val == ''){
    $(this).closest("div").find(".error_msg").html(error_msg);
  }else{
    $(this).closest("div").find(".error_msg").html("");
  }
})


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


function validation(){
  var error_count=0;
  $(".required_for_valid").each(function(){
    var current_val=$(this).val();
    var error_msg=$(this).attr("error-msg");
    if(current_val == ''){
      console.log(error_msg);
      error_count++;
      $(this).closest("div").find(".error_msg").html(error_msg);
    }else{
      $(this).closest("div").find(".error_msg").html("");
    }
  });
  return error_count;
}

$(document).on("submit",".Sales_Indent_Submit",function(){
  var error_count=validation();
  console.log(error_count);
  if(error_count == 0){
    return true;
  }else{
    return false;
  }
})

$(document).on("keyup", ".QtyInBag", function() {
  var bag = parseInt($(this).val());
  var pkts=$(this).closest("td").find(".Product_QtyInPkt").val();
  var kg=$(this).closest("td").find(".Product_QtyInKg").val();
  var QtyInPkt = bag * pkts;
  if(isNaN(QtyInPkt))
  {
    QtyInPkt=0;
  }
  var QtyInKg = QtyInPkt * kg;

 var price=$(this).closest('tr').find(".Product_Price").val();
 var totalPrice=parseFloat(price)*parseFloat(QtyInPkt);
  totalPrice=parseFloat(totalPrice);
  var Discount=$(this).closest("tr").find(".Discount").val(); /*Discount Amount (In Fcm Default 5% and Cotton 0% ) */
  Discount=parseInt(Discount);
  discount_amount = 0;
  if(Discount !='' && Discount >0)
    {
      if(isNaN(totalPrice))
      {
        totalPrice=0;
      }
      discount_amount=totalPrice*(Discount/100);
    }
  var Grand_Total_Price=parseFloat(totalPrice)-parseFloat(discount_amount);
    

  $(this).closest("tr").find(".QtyInPkt").val(QtyInPkt);
  $(this).closest("tr").find(".QtyInKg").val(QtyInKg);
  $(this).closest("tr").find(".Total_Price").val(totalPrice.toFixed(2));
  $(this).closest("tr").find(".Grand_Total_Price").val(Grand_Total_Price.toFixed(2));

});


 function Get_Material_Price_Details(Customer,Product_Division,Material,Current_Date,curren_tr)
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
        $(curren_tr).find('.Price').val(price);
        $(curren_tr).find('.Product_Price').val(price);
        }
      });

      return price;
  }


