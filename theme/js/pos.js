
$("#item_search").autocomplete({
  source: function(data, cb){
    $.ajax({
      autoFocus: true,
      url: $("#base_url").val() + "pos/get_json_items_list",
      method: "GET",
      dataType: "json",
      data: {
        name: data.term,
      },
      success: function (res) {
        var result;
        if (res.length == 1) {
          us = res[0];
          if(parseInt(us.stock) <= 0){
            toastr["warning"](us.stock+" Items in Stock!! ");
            $("#item_search").val('');
            $("#item_search").empty().removeClass('ui-autocomplete-loading');
            return false;
          }
          var item_id = us.id;
          if(document.getElementById("tr_item_id_"+item_id)){
            toastr["warning"]("Item Already Added");
            $("#item_search").val('');
            $("#item_search").empty().removeClass('ui-autocomplete-loading');
            return false;
          } else {
            $("#item_search").val('');
            $("#item_search").empty().removeClass('ui-autocomplete-loading');
            return_row_with_data(item_id);
          }
        } else if (res.length > 1)  {
          result = $.map(res, function (el) {
            return {
              label: el.item_code + "--[Qty:" + el.stock + "] --" + el.item_name, value: "",
              id: el.id,
              item_name: el.item_name,
              stock: el.stock,
            };
          });
        } else {
          $("#item_search").val('');
          $("#item_search").empty().removeClass('ui-autocomplete-loading');
          result = [
            {
              label: "No Records Found " + data.term,
              value: "",
            },
          ];
        }
        cb(result);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $("#item_search").val('');
        $("#item_search").empty().removeClass('ui-autocomplete-loading');
      },
    });
  },
	search: function (e, u) {
	},
	select: function (e, u) {
    $("#item_search").val('');
    $("#item_search").empty().removeClass('ui-autocomplete-loading');
		if(parseInt(u.item.stock) <= 0){
			toastr["warning"](u.item.stock+" Items in Stock!! ");
			return false;
		}
		var item_id = u.item.id;
		if(document.getElementById("tr_item_id_"+item_id)){
      toastr["warning"]("Item Already Added");
      return false;
    } else {
      return_row_with_data(item_id);
    }
	},
});



function return_row_with_data(item_id, qty = null){
  $("#item_search").addClass('ui-autocomplete-loader-center');
	var base_url=$("#base_url").val().trim();
	$.post(base_url+"pos/return_row_with_data/"+item_id,{},function(result){
    result = createRow(result, item_id, qty);
    $("#pos-form-tbody").append(result);
    final_total();
    $("#item_search").removeClass('ui-autocomplete-loader-center');
  });
}
function createRow(values, item_id, qty = null) {
  var result = JSON.parse(values);
  item_name  = result.item_name;
  stock = result.stock;
  mrp  = result.mr_price;
  sales_price  = result.sales_price;
  item_cost  = result.purchase_price;
  item_tax_amt = result.vat_amt;
  profit_margin  = result.profit_margin;
  sales_price_temp = result.sales_price;

  if (result.discount_type == 1) {
    discount = round((result.mr_price * result.discount) / 100, 2);
  } else {
    discount = result.discount;
  }

  var quantity ='<div class="input-group input-group-sm"><span class="input-group-btn"><button onclick="decrement_qty('+item_id+')" type="button" class="btn btn-default btn-flat"><i class="fa fa-minus text-danger"></i></button></span>';

  if (qty !== null && qty !== undefined) {
    quantity +='<input value="'+qty+'" class="form-control" onkeyup="item_qty_input('+item_id+')" id="item_qty_'+item_id+'" name="item_qty_'+item_id+'">';
  } else {
    quantity +='<input value="1" class="form-control" onkeyup="item_qty_input('+item_id+')" id="item_qty_'+item_id+'" name="item_qty_'+item_id+'">';
    qty = 1;
  }

  quantity +='<input type="hidden" value="'+item_tax_amt+'" class="form-control" id="item_tax_'+item_id+'">';
  quantity +='<input type="hidden" value="'+discount+'" name="dis_hide_'+item_id+'" id="dis_hide_'+item_id+'">';
  quantity +='<input type="hidden" value="'+mrp+'" name="mrp_hide_'+item_id+'" id="mrp_hide_'+item_id+'">';
  quantity +='<input type="hidden" value="'+profit_margin+'" name="profit_hide_'+item_id+'" id="profit_hide_'+item_id+'">';
  quantity +='<input type="hidden" value="'+profit_margin+'" name="profit_row_'+item_id+'" id="profit_row_'+item_id+'">';
  quantity +='<input type="hidden" value="'+discount+'" name="dis_row_'+item_id+'" id="dis_row_'+item_id+'">';
  quantity +='<input type="hidden" value="'+sales_price+'" id="sales_price_'+item_id+'" name="sales_price_'+item_id+'">';
  quantity +='<span class="input-group-btn"><button onclick="increment_qty('+item_id+')" type="button" class="btn btn-default btn-flat"><i class="fa fa-plus text-success"></i></button></span></div>';

  var sub_total       =(parseFloat(qty)*parseFloat(sales_price)).toFixed(2); //Initial
  var remove_btn      ='<a class="fa fa-fw fa-trash-o text-red" style="cursor: pointer;font-size: 20px;" onclick="removerow('+item_id+')" title="Delete Item?"></a>';


  var str=' <tr id="row_'+item_id+'" data-row='+item_id+' data-item-id='+item_id+'>';/*item id*/
  str+='<td id="td_'+item_id+'_0">'+ item_name +'</td>';/* td_0_0 item name*/
  str+='<td id="td_'+item_id+'_1">'+ stock +'</td>';/* td_0_1 item available qty*/
  str+='<td id="td_'+item_id+'_8">'+ mrp +'</td>';/* td_0_8 item MRP*/
  str+='<td id="td_'+item_id+'_2">'+ quantity +'</td>';/* td_0_2 item available qty*/

  adis ='<input type="number" value="0" class="form-control" onkeyup="make_subtotal('+item_id+')" id="item_adis_'+item_id+'" name="item_adis_'+item_id+'" ></input>';

  str+='<td id="td_'+item_id+'_3" class="text-right">'+ sales_price +'</td>';/* td_0_3 item sales price*/
  str+='<td id="td_'+item_id+'_6" class="text-right">'+ discount +'</td>';/* td_0_3 item discount*/
  str+='<td id="td_'+item_id+'_7" class="text-right" style="display:none">'+ adis +'</td>';
  str+='<td id="td_'+item_id+'_4" class="text-right">'+ sub_total +'</td>';/* td_0_4 item sub_total */
  str+='<td id="td_'+item_id+'_5">'+ remove_btn +'</td>'; /* td_0_5 item gst_amt */
  str+='<input type="hidden" name="tr_item_id_'+item_id+'" id="tr_item_id_'+item_id+'" value="'+item_id+'">';
  str+='<input type="hidden" name="item_id_array[]" value="'+item_id+'">';
  str+='<input type="hidden" id="tr_sales_price_temp_'+item_id+'" name="tr_sales_price_temp_'+item_id+'" value="'+sales_price_temp+'">';
  str+='</tr>';
  return str;
}
function removerow(id){//id=Rowid
  $("#row_"+id).remove();
  final_total();
}
function increment_qty(item_id){
  var item_qty=$("#item_qty_"+item_id).val();
  var stock=$("#td_"+item_id+"_1").html();
  if(parseInt(item_qty)<parseInt(stock)){
    item_qty=parseFloat(item_qty)+1;
    $("#item_qty_"+item_id).val(item_qty);
  }
  make_subtotal(item_id);
}
function decrement_qty(item_id) {
  var item_qty = $("#item_qty_" + item_id).val();
  if (item_qty <= 1) {
    $("#item_qty_" + item_id).val(1);
    return;
  }
  $("#item_qty_" + item_id).val(parseFloat(item_qty) - 1);
  make_subtotal(item_id);
}
function item_qty_input(item_id){
  var item_qty=$("#item_qty_"+item_id).val();
  var stock=$("#td_"+item_id+"_1").html();
  if(stock<=0){
    toastr["warning"]("item Not Available in stock!");
    // return;
  }
  if(parseInt(item_qty)>parseInt(stock)){
    $("#item_qty_"+item_id).val(stock);
    toastr["warning"]("Oops! You have only "+stock+" items in Stock");
    // return;
  }
  if(item_qty<=0){
    $("#item_qty_"+item_id).val(1);
    toastr["warning"]("You must have atlease one Quantity");
    //return;
  }
  make_subtotal(item_id);
}

function final_total(){
  var total=0;
  var item_qty=0;
  var total_tax=0;
  var tot_profit = 0;
  var dis_row = 0;
  var discount_input=$("#discount_input").val();
  var discount_type=$("#discount_type").val();

  $("#pos-form-tbody tr").each(function(index, el) {
    item_id = $(el).attr('data-row');
    total = parseFloat(total)+ + +parseFloat($("#td_"+item_id+"_4").html()).toFixed(2);
    item_qty = parseFloat(item_qty)+ + +parseFloat($("#item_qty_"+item_id).val()).toFixed(2);
    item_tem_qty = parseFloat($("#item_qty_"+item_id).val()).toFixed(2);
    total_tax += (parseFloat($("#item_tax_"+item_id).val()).toFixed(2))*item_tem_qty;
    tot_profit += (parseFloat($("#profit_row_"+item_id).val()));
    dis_row += (parseFloat($("#dis_row_"+item_id).val()));
  });

  total = parseFloat(total).toFixed(2);
  if(discount_type == 'in_percentage'){
    discount_amt = parseFloat((total * discount_input) / 100);
  }else{ //in_fixed
    discount_amt = parseFloat(discount_input);
  }
  // console.log(total + " " + discount_amt + " " + total_tax + " " + tot_profit + " " + dis_row);
  set_total(item_qty, total, discount_amt, total-discount_amt, total_tax, tot_profit, dis_row);
}
function set_total(tot_qty=0, tot_amt=0, tot_disc=0, tot_grand=0, tot_tax = 0, tot_profit = 0, dis_row = 0){
  $(".tot_qty   ").html(tot_qty);
  $(".tot_amt   ").html(parseFloat(tot_amt).toFixed(2));
  $(".tot_disc  ").html(parseFloat(tot_disc).toFixed(2));
  $(".tot_tax  ").html(parseFloat(tot_tax).toFixed(2));
  $(".tot_grand ").html((parseFloat(tot_grand) + parseFloat(tot_tax)).toFixed(2));
  $("#tot_profit  ").val(parseFloat(tot_profit).toFixed(2));
  $("#item_tot_dis  ").val(parseFloat(dis_row).toFixed(2));
}
function make_subtotal(item_id){
  var sales_price     = $("#sales_price_"+item_id).val();
  var item_qty        = $("#item_qty_"+item_id).val();

  var mrp_price       = parseFloat($("#td_"+item_id+"_8").html()).toFixed(2);
  var tot_mrp_price   = parseFloat(item_qty)*parseFloat(mrp_price).toFixed(2);
  var dis_hide        = $("#dis_hide_"+item_id).val();
  var profit_hide     = $("#profit_hide_"+item_id).val();
  var dis_ahide       = (isNaN(parseFloat($("#item_adis_"+item_id).val().trim()))) ? 0 :parseFloat($("#item_adis_"+item_id).val().trim());

  var total_discount  = parseFloat(item_qty * dis_hide).toFixed(2);
  $("#td_"+item_id+"_6").html(parseFloat(total_discount).toFixed(2));
  $("#dis_row_"+item_id).val(total_discount);

  var total_profit    = parseFloat(item_qty * profit_hide).toFixed(2);
  $("#profit_row_"+item_id).val(total_profit);

  var tot_sales_price = parseFloat(item_qty)*parseFloat(sales_price).toFixed(2);
  var subtotal        = parseFloat(tot_sales_price).toFixed(2);
  final_sales_price = (parseFloat(dis_ahide) + parseFloat(total_discount) + parseFloat(subtotal)).toFixed(2);
  if (parseFloat(final_sales_price) > parseFloat(tot_mrp_price)) {
    $("#item_adis_"+item_id).val(0);
    return false;
  } else {
    $("#td_"+item_id+"_4").html(parseFloat(subtotal).toFixed(2));
  }
  final_total();
}
//DISCOUNT UPDATE
$(".discount_update").click(function () {
  final_total();
  $('#discount-modal').modal('toggle');
});


// cash payment modal
$('#show_cash_modal').click(function (e) {
	if($(".items_table tr").length==1){
    toastr["error"]("Please Select Items from List!!");
		return;
  }else{
		$("#check_multiple_balance").val('sin');
		$(".payment-row").remove();
    	adjust_payments();
    	$("#add_payment_row,#payment_type_1").parent().hide();
    	$("#amount_1").focus();
    	$("#amount_1").parent().parent().removeClass('col-md-6').addClass('col-md-12');
    	$('#multiple-payments-modal').modal('toggle');
    }
	$("#amount_1").val($(".sales_div_tot_payble ").html());
	$("#amount_1").trigger("onkeyup");
});
// multi payment modal
$('.show_payments_modal').click(function (e) {
	if($(".items_table tr").length==1){
    toastr["error"]("Please Select Items from List!!");
		return;
  }else{
		$("#check_multiple_balance").val('mul');
		$(".payment-row").remove();
    	adjust_payments();
    	$("#add_payment_row,#payment_type_1").parent().show();
    	$("#amount_1").parent().parent().removeClass('col-md-12').addClass('col-md-6');
    	$('#multiple-payments-modal').modal('toggle');
    }
	$("#amount_1").val(0);
	$("#amount_1").trigger("onkeyup");
});
$('#add_payment_row').click(function (e) {
	//table should not be empty
	if($(".items_table tr").length==1){
    toastr["error"]("Please Select Items from List!!");
		return;
  }else{
    /*BUTTON LOAD AND DISABLE START*/
    var this_id=this.id;
    var this_val=$(this).html();
    $("#"+this_id).html('<i class="fa fa-spinner fa-spin"></i> Please Wait..');
    $("#"+this_id).attr('disabled',true);
    /*BUTTON LOAD AND DISABLE END*/

    var payment_row_count = get_id_value("payment_row_count");
    var temp_id = Number(payment_row_count) + 1;
    var get_row = create_sub_pay_row(temp_id);

    $(".payments_div").parent().append(get_row);
    $("#payment_row_count").val(parseInt(payment_row_count)+1);

    /*BUTTON LOAD AND DISABLE START*/
    $("#"+this_id).html(this_val);
    $("#"+this_id).attr('disabled',false);
    adjust_payments();
  }
});
function remove_row(id){
	$(".payments_div_"+id).html('');
	adjust_payments();
}


// payment modal submit
$('.make_sale').click(function (e) {
	var pa_amt = parseFloat($(".sales_div_tot_payble").text());
	var p_amt = parseFloat($(".sales_div_tot_paid").text());
	var check_pt = $("#check_multiple_balance").val();
	if ((check_pt == "mul") && (p_amt != pa_amt)) {
		toastr["warning"]("Payable Amount and Paid Amount should be same!!");
		return false;
	}
	var base_url=$("#base_url").val().trim();
  if($(".items_table tr").length==1){
    toastr["warning"]("Empty Sales List!!");
    return;
  }

  var tot_qty=$(".tot_qty").text();
  var tot_amt=$(".tot_amt").text();
  var tot_disc=$(".tot_disc").text();
  var tot_grand=$(".tot_grand").text();
  var paid_amt=$(".sales_div_tot_paid").text();
  var balance=parseFloat($(".sales_div_tot_balance").text());

  if(document.getElementById("sales_id")){
    var command = 'update';
  } else{
    var command = 'save';
  }
  var this_btn='make_sale';

  $("#"+this_btn).attr('disabled',true);  //Enable Save or Update button
  e.preventDefault();
  var data = new Array(2);
  data= new FormData($('#posForm')[0]);//form name
  if(!xss_validation(data)){ return false; }
  $(".box").append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
  $.ajax({
    type: 'POST',
    url: base_url+'pos/pos_save_update?command='+command+'&tot_qty='+tot_qty+'&tot_amt='+tot_amt+'&tot_disc='+tot_disc+'&tot_grand='+tot_grand+"&paid_amt="+paid_amt+'&balance='+balance,
    data: data,
    cache: false,
    contentType: false,
    processData: false,
    success: function(result){
      result=result.trim().split("<<<###>>>");
      if(result[0]=="success")
      {
        if(window.open(base_url+"pos/print_invoice_pos/"+result[1], "_blank", "scrollbars=1,resizable=1,height=300,width=450")){
          window.location.reload();
          return true;
        }
      }
      else if(result[0]=="failed")
      {
        toastr['error']("Sorry! Failed to save Record.Try again");
      }
      else
      {
        alert(result);
      }

      $("#"+this_btn).attr('disabled',false);  //Enable Save or Update button
      $(".overlay").remove();
      window.location.reload();
    }
  });
});
function adjust_payments(){
  var item_id=0;
  var total=0;
  var item_qty=0;
  var total_tax=0;
  var discount_input=$("#discount_input").val();
  var discount_type=$("#discount_type").val();
  var tot_profit = 0;
  var dis_row = 0;
  var tem_amt = 0;

  $("#pos-form-tbody tr").each(function(index, el) {
    item_id = $(el).attr('data-row');
    total = parseFloat(total)+ + +parseFloat($("#td_"+item_id+"_4").html()).toFixed(2);
    item_qty = parseFloat(item_qty)+ + +parseFloat($("#item_qty_"+item_id).val()).toFixed(2);
    item_tem_qty = parseFloat($("#item_qty_"+item_id).val()).toFixed(2);
    total_tax += (parseFloat($("#item_tax_"+item_id).val()).toFixed(2))*item_tem_qty;
    tot_profit += (parseFloat($("#profit_row_"+item_id).val()));
    dis_row += (parseFloat($("#dis_row_"+item_id).val()));
  });

  tem_amt = parseFloat(total).toFixed(2);
  total = parseFloat(total).toFixed(2);
  total = (parseFloat(total) + parseFloat(total_tax)).toFixed(2);

  var payments_row = get_id_value("payment_row_count");
  var paid_amount = 0;
  for (var i = 1; i <= payments_row; i++) {
    if(document.getElementById("amount_"+i)){
      paid_amount = parseFloat(paid_amount)+parseFloat((get_id_value("amount_"+i)=='')? 0 : get_id_value("amount_"+i));
    }
  }

  //RIGHT SIDE DIV
  if(discount_type == 'in_percentage'){
    discount_amt = parseFloat((tem_amt * discount_input) / 100);
  }else{ //in_fixed
    discount_amt = parseFloat(discount_input);
  }

  var change_return = 0;
  var balance = total - discount_amt - paid_amount;
  if(balance < 0){
    change_return = Math.abs(parseFloat(balance));
  }

  balance = parseFloat(balance).toFixed(2);
  $(".sales_div_tot_qty  ").html(item_qty);
  $(".sales_div_tot_amt  ").html(parseFloat(total).toFixed(2));
  $(".sales_div_tot_discount ").html(parseFloat(discount_amt).toFixed(2));
  $(".sales_div_tot_payble ").html(parseFloat(total-discount_amt).toFixed(2));
  $(".sales_div_tot_paid ").html(parseFloat(paid_amount).toFixed(2));
  $(".sales_div_tot_balance ").html(balance);
  $(".sales_div_change_return ").html((change_return).toFixed(2));
}
function get_id_value(id){
	return $("#"+id).val().trim();
}
function calculate_payments(){
	adjust_payments();
	var total_amt = $("#amount_1").val();
	var ret_amt =  $(".sales_div_change_return ").html();
	var paid_amt =  $(".sales_div_tot_paid ").html();
	if (total_amt < 0 || total_amt > 9999999) {
		$("#amount_1").val('');
		$(".sales_div_change_return ").html(0);
		$(".sales_div_tot_paid ").html(0);
		toastr["error"]("Please Enter Valid Amount!");
		failed.currentTime = 0;
		failed.play();
		return false;
  }
}


// hold item sale
$('#hold_invoice').click(function (e) {
	if($(".items_table tr").length==1){
    toastr["error"]("Please Select Items from List!!");
		return;
  }

	swal({
		title: "Hold Invoice ?",icon: "warning",buttons: true,dangerMode: true,
		content: {
			element: "input",
      attributes: {
				placeholder: "Please Enter Reference Number!",
				type: "text",
				inputAttributes: {
				  maxlength: '5'
				}
			},
		},
	}).then(name => {
		//If input box blank Throw Error
		if (!name.trim()){ throw null; return false; }
		var reference_id = name;
		/* ********************************************************** */
		var base_url=$("#base_url").val().trim();

		//RETRIVE ALL DYNAMIC HTML VALUES
      var tot_qty=$(".tot_qty").text();
      var tot_amt=$(".tot_amt").text();
      var tot_disc=$(".tot_disc").text();
      var tot_grand=$(".tot_grand").text();
		  var this_id=this.id;//id=save or id=update
			e.preventDefault();
			data = new FormData($('#posForm')[0]);//form name
			if(!xss_validation(data)){ return false; }

			$(".box").append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
			$("#"+this_id).attr('disabled',true);  //Enable Save or Update button
			$.ajax({
				type: 'POST',
				url: base_url+'pos/hold_invoice?command='+this_id+'&tot_qty='+tot_qty+'&tot_amt='+tot_amt+'&tot_disc='+tot_disc+'&tot_grand='+tot_grand+"&reference_id="+reference_id,
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				success: function(result){
					$("#hidden_invoice_id").val('');
					result = result.trim().split("<<<###>>>");
					if(result[0]=="success")
					{
						$('#pos-form-tbody').html('');
						final_total();
					}
					else if(result[0]=="failed")
					{
						toastr['error']("Sorry! Failed to save Record.Try again");
					}
					else
					{
						alert(result);
					}
					$("#"+this_id).attr('disabled',false);  //Enable Save or Update button
					$(".overlay").remove();
					window.location.reload();
				}
			});
			/* ********************************************************** */
	}).catch(err => {
	  toastr['error']("Failed!! Invoice Not Saved! <br/>Please Enter Reference Number");
	});//swal end
}); //hold_invoice end
function hold_invoice_delete(invoice_id){
  swal({ title: "Are you sure?",icon: "warning",buttons: true,dangerMode: true,}).then((sure) => {
    if(sure) {//confirmation start
      var base_url=$("#base_url").val().trim();
      $.post(base_url+"pos/hold_invoice_delete/"+invoice_id,{},function(result){
        result=result.trim();
        if(result=='success'){
          toastr["success"]("Success! Invoice Deleted!!");
        }else{
          toastr['error']("Failed to Delete Invoice! Try again!!");
        }
        window.location.reload();
      });
    } //confirmation sure
  }); //confirmation end
}
function hold_invoice_edit(invoice_id){
	swal({ title: "Are you sure? Want To Edit",icon: "warning",buttons: true,dangerMode: true,}).then((sure) => {
		if(sure) {//confirmation start
			var base_url=$("#base_url").val().trim();
			$.post(base_url+"pos/hold_invoice_edit?invoice_id="+invoice_id,{},function(result){
				$("#hidden_invoice_id").val(invoice_id);
				var data = jQuery.parseJSON(result)
				if(data.length > 0){
					//	Make empty table list
          $('#pos-form-tbody').html('');
          for(k=0;k<data.length;k++){
            var item_id = data[k]['item_id'];
            var qty = data[k]['item_qty'];
            return_row_with_data(item_id, qty);
          }
					final_total();
				}
			});
		} //confirmation sure
	}); //confirmation end
}
