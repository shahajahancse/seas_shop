<!DOCTYPE html>
<html>
<head>
	<!-- TABLES CSS CODE -->
	<?php include"comman/code_css_form.php"; ?>
	<style type="text/css">
		body{
			font-family: arial;
			font-size: 13px;
			font-weight: bold;
			padding-top:15px;
		}

		@media print {
			.no-print { display: none; }
		}
	</style>
</head>
<body><!--  onload="window.print();" -->
	<!-- Company info -->
	<?php
		$CI =& get_instance();
		$q1=$this->db->query("select * from db_company where id=1 and status=1");
		$res1=$q1->row();
		$company_name		=$res1->company_name;
		$company_mobile		=$res1->mobile;
		$company_phone		=$res1->phone;
		$company_email		=$res1->email;
		$company_country	=$res1->country;
		$company_state		=$res1->state;
		$company_city		=$res1->city;
		$company_address	=$res1->address;
		$company_postcode	=$res1->postcode;
		$company_gst_no		=$res1->gst_no;//Goods and Service Tax Number (issued by govt.)
		$company_vat_number		=$res1->vat_no;//Goods and Service Tax Number (issued by govt.)
		//dd(5555);

	?>

	<!-- Customer info end -->
	<!-- Sales info -->
	<?php
  		$q3=$this->db->query("
			SELECT a.customer_name,a.mobile,a.phone,a.gstin,a.tax_number,a.email,
			a.opening_balance,a.country_id,a.state_id,
			a.postcode,a.address,b.sales_date,b.created_time,b.reference_no,
			b.sales_code,b.sales_note,
			coalesce(b.grand_total,0) as grand_total,
			coalesce(b.subtotal,0) as subtotal,
			coalesce(b.paid_amount,0) as paid_amount,
			coalesce(b.other_charges_input,0) as other_charges_input,
			other_charges_tax_id,
			coalesce(b.other_charges_amt,0) as other_charges_amt,
			discount_to_all_input,
			b.discount_to_all_type,
			coalesce(b.tot_discount_to_all_amt,0) as tot_discount_to_all_amt,
			coalesce(b.round_off,0) as round_off,
			b.payment_status

			FROM db_customers a,
			db_sales b
			WHERE
			a.`id`=b.`customer_id` AND
			b.`id`='$sales_id'
        ");  /*GROUP BY  b.`customer_code`*/
		$res3=$q3->row();

		$customer_name=$res3->customer_name;
		$customer_mobile=$res3->mobile;
		$customer_phone=$res3->phone;
		$customer_email=$res3->email;
		$customer_country=$res3->country_id;
		$customer_state=$res3->state_id;
		$customer_address=$res3->address;
		$customer_postcode=$res3->postcode;
		$customer_gst_no=$res3->gstin;
		$customer_tax_number=$res3->tax_number;
		$customer_opening_balance=$res3->opening_balance;
		$sales_date=show_date($res3->sales_date);
		$reference_no=$res3->reference_no;
		$created_time=show_time($res3->created_time);
		$sales_code=$res3->sales_code;
		$sales_note=$res3->sales_note;

		$subtotal=$res3->subtotal;
		$grand_total=$res3->grand_total;
		$other_charges_input=$res3->other_charges_input;
		$other_charges_tax_id=$res3->other_charges_tax_id;
		$other_charges_amt=$res3->other_charges_amt;
		$paid_amount=$res3->paid_amount;
		$discount_to_all_input=$res3->discount_to_all_input;
		$discount_to_all_type=$res3->discount_to_all_type;
		//$discount_to_all_type = ($discount_to_all_type=='in_percentage') ? '%' : 'Fixed';
		$tot_discount_to_all_amt=$res3->tot_discount_to_all_amt;
		$round_off=$res3->round_off;
		$payment_status=$res3->payment_status;

		if($discount_to_all_input>0){
			$str="($discount_to_all_input%)";
		}else{
			$str="(Fixed)";
		}

		if (!empty($customer_country)) {
			$q = $this->db->query("select country from db_country where id = ?", array($customer_country));
			if ($q->num_rows() > 0) {
				$row = $q->row();
				$customer_country = $row->country;
			}
		}
		if (!empty($customer_state)) {
			$q = $this->db->query("select state from db_states where id = ?", array($customer_state));
			if ($q->num_rows() > 0) {
				$row = $q->row();
				$customer_state = $row->state;
			}
		}
    ?>
	<!-- Sales info end -->

	<style>
		.class1 {
			font-size: 11px; text-align: left;padding-left: 2px; padding-right: 2px;
		}
		.class2 {
			font-size: 11px; text-align: right;padding-left: 2px; padding-right: 2px;
		}
		.class3 {
			padding-left: 2px; padding-right: 2px;
		}
		.class4 {
			padding-left: 2px; padding-right: 2px;
		}
		.classr {
			text-align: right;
		}
	</style>

	<table width="50%" align="center">
		<!-- invoice header -->
		<tr>
			<td align="center">
				<span>
					<strong><?= $company_name; ?></strong><br>
					<?php echo (!empty(trim($company_address))) ? $this->lang->line('company_address')."".$company_address."<br>" : '';?>
					<?= $company_city; ?>
					<?php echo (!empty(trim($company_postcode))) ? "-".$company_postcode : '';?>
					<br>
					<?php echo (!empty(trim($company_gst_no))) ? $this->lang->line('gst_number').": ".$company_gst_no."<br>" : '';?>
					<?php echo (!empty(trim($company_vat_number))) ? $this->lang->line('vat_number').": ".$company_vat_number."<br>" : '';?>
					<?php if(!empty(trim($company_mobile)))
							{
								echo $this->lang->line('phone').": ".$company_mobile;
								if(!empty($company_phone)){
									echo ",".$company_phone;
								}
								echo "<br>";
							}

					?>
				</span>
			</td>
		</tr>
		<tr>
			<td align="center"><strong>-----------------<?= $this->lang->line('invoice'); ?>-----------------</strong></td>
		</tr>
		<tr>
			<td>
				<table width="100%">
					<tr>
						<td width="40%"><?= $this->lang->line('invoice'); ?></td>
						<td><b>#<?= $sales_code; ?></b></td>
					</tr>
					<tr>
						<td><?= $this->lang->line('name'); ?></td>
						<td><?= $customer_name; ?></td>
					</tr>
					<tr>
						<td><?= $this->lang->line('date').":".$sales_date; ?></td>
						<td style="text-align: right;"><?= $this->lang->line('time').":".$created_time; ?></td>
					</tr>
				</table>
			</td>
		</tr>
		<?php
		?>
		<!-- invoice header end -->
		<!-- invoice items details and summation table -->
		<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="0"  >
					<thead>
						<tr style="border-top-style: dashed;border-bottom-style: dashed;border-width: 0.1px;">
							<th class="class1">#</th>
							<th class="class1"><?= $this->lang->line('description'); ?></th>
							<th class="class2">MRP</th>
							<th class="class2">QTY</th>
							<!-- <th class="class2"><?= $this->lang->line('price'); ?></th> -->
							<th class="class2"><?= $this->lang->line('total'); ?></th>
							<!-- <th class="class2">Discount</th>
							<th class="class2">Net Pay</th> -->
						</tr>
					</thead>
					<!-- invoice items details -->
					<tbody style="border-bottom-style: dashed;border-width: 0.1px;">
						<?php
							$i=0;
							$total_mrp=0;
							$item_dis=0;
							$additional_dis=0;
							$total_price=0;
							$total_tax_amt=0;

							$q2=$this->db->query("select b.item_name,a.mr_price,a.sales_qty,a.unit_total_cost,a.price_per_unit,a.tax_amt,c.tax,a.discount_amt,a.add_dis_tot,a.additional_dis,a.total_cost from db_salesitems a,db_items b,db_tax c where c.id=a.tax_id and b.id=a.item_id and a.sales_id='$sales_id'");
			            	foreach ($q2->result() as $res2) {
								//dd($res2);
								$mrp_cost = ($res2->mr_price*$res2->sales_qty);
								$dis_cost = $res2->discount_amt;

								echo "<tr style='border-top-style: dashed;border-width: 0.1px;'>";
								echo "<td class='class3' valign='top'>".++$i."</td>";
								echo "<td class='class3'>".$res2->item_name."</td>";
								echo "<td class='classr class3'>".$res2->mr_price."</td>";
								echo "<td class='classr class3'>".$res2->sales_qty."</td>";
								echo "<td class='classr class3'>".number_format(($mrp_cost),2,'.','')."</td>";
								echo "</tr>";
								echo "<tr style='border-bottom-style: dashed;border-width: 0.1px;'>";
								echo "<td class='class3' valign='top'></td>";
								echo "<td class='class3'>Discount</td>";
								echo "<td class='classr class3'></td>";
								echo "<td class='classr class3'></td>";
								echo "<td class='classr class3'>".number_format(($dis_cost),2,'.','')."</td>";
								echo "</tr>";
								echo "<tr>";
								echo "<td class='class3' valign='top'></td>";
								echo "<td class='class3'>SubTotal</td>";
								echo "<td class='classr class3'></td>";
								echo "<td class='classr class3'></td>";
								echo "<td class='classr class3'>".number_format(($mrp_cost-$dis_cost),2,'.','')."</td>";
								echo "</tr>";
								// echo  $res2->tax_amt;

								$total_mrp += $mrp_cost;
								$item_dis += $dis_cost;
								$additional_dis += $res2->add_dis_tot;
								$total_price += ($mrp_cost - $dis_cost - $res2->add_dis_tot);
								$total_tax_amt += $res2->tax_amt;
							}
						?>
				   </tbody>
					<!-- invoice items end -->
				   <!-- invoice summation start -->
					<tfoot>
						<!-- <tr><td colspan="5"><hr></td></tr>    -->
						<tr >
							<!-- <?= $this->lang->line('before_tax'); ?> -->
							<td style="  class3" colspan="4" align="right">Total</td>
							<td style=" padding-left: 2px; padding-right: 2px;" align="right"><?= number_format(($total_mrp),2,'.','');?></td>
						</tr>
						<tr >
							<td style=" padding-left: 2px; padding-right: 2px;" colspan="4" align="right">Item Discount</td>
							<td style=" padding-left: 2px; padding-right: 2px;" align="right"><?= number_format(($item_dis),2,'.','');?></td>
						</tr>
						<tr >
							<td style=" padding-left: 2px; padding-right: 2px;" colspan="4" align="right">Over All Discount</td>
							<td style=" padding-left: 2px; padding-right: 2px;" align="right"><?= number_format(($additional_dis),2,'.','');?></td>
						</tr>
						<tr style="border-top-style: dashed;border-width: 0.1px;">
							<td style=" padding-left: 2px; padding-right: 2px;" colspan="4" align="right">Total Amount</td>
							<td style=" padding-left: 2px; padding-right: 2px;" align="right"><?=  number_format($total_price,2,'.','');?></td>
						</tr>
						<tr style="border-top-style: dashed;border-width: 0.1px;">
							<td style=" padding-left: 2px; padding-right: 2px;" colspan="4" align="right">Vat</td>
							<td style=" padding-left: 2px; padding-right: 2px;" align="right"><?= number_format(($total_tax_amt),2,'.','');?></td>
						</tr>

						<tr style="border-top-style: dashed;border-width: 0.1px;">
							<td style=" padding-left: 2px; padding-right: 2px;" colspan="4" align="right">Grand total</td>
							     <td style="padding-left: 2px; padding-right: 2px;" align="right">
                                <?= number_format(($total_price + $total_tax_amt), 2, '.', ''); ?>
                            </td>
                        </tr>




						<!-- <tr><td style="border-bottom-style: dashed;border-width: 0.1px;" colspan="5"></td></tr>   -->


						<!-- change_return_status -->
						<?php if(change_return_status()) {
							$change_return_amount = get_change_return_amount($sales_id); ?>
							<tr>
								<td style=" padding-left: 2px; padding-right: 2px;" colspan="4" align="right"><?= $this->lang->line('paid_amount'); ?></td>
								<td style=" padding-left: 2px; padding-right: 2px;" align="right"><?= number_format($paid_amount+$change_return_amount,2,'.',''); ?></td>
							</tr>
							<tr>
								<td style=" padding-left: 2px; padding-right: 2px;" colspan="4" align="right"><?= $this->lang->line('refund'); ?></td>
								<td style=" padding-left: 2px; padding-right: 2px;" align="right"><?= number_format($change_return_amount,2,'.',''); ?></td>
							</tr>
						<?php }
						else{ ?>
							<tr>
							<td style=" padding-left: 2px; padding-right: 2px;" colspan="4" align="right"><?= $this->lang->line('paid_amount'); ?></td>
							<td style=" padding-left: 2px; padding-right: 2px;" align="right"><?= number_format($paid_amount,2,'.',''); ?></td>
						</tr>

						<?php } ?>
						<tr>
							<td style=" padding-left: 2px; padding-right: 2px;" colspan="4" align="right"><?= $this->lang->line('due_amount'); ?></td>
							<td style=" padding-left: 2px; padding-right: 2px;" align="right"><?= number_format($grand_total-$paid_amount,2,'.',''); ?></td>
						</tr>

						<tr>
							<td colspan="5" align="center">----------<?= $this->lang->line('thanks_you_visit_again'); ?>----------</td>
						</tr>

						<tr>
							<td colspan="5" align="center">
								<div style="display:inline-block;vertical-align:middle;line-height:16px !important;">
									<img class="center-block" style=" width: 100%; opacity: 1.0" src="<?php echo base_url();?>barcode/<?php echo $sales_code;?>">
								</div>
							</td>
						</tr>
					</tfoot>
				   <!-- invoice summation end -->
				</table>
			</td>
		</tr>
		<!-- invoice items details and summation table -->
	</table>
	<!-- invoice print button -->
	<center>
		<div class="row no-print">
			<div class="col-md-12">
				<div class="col-md-2 col-md-offset-5 col-xs-4 col-xs-offset-4 form-group">
					<button type="button" id="" class="btn btn-block btn-success btn-xs" onclick="window.print();" title="Print">Print</button>
					<?php if(isset($_GET['redirect'])){ ?>
						<a href="<?= base_url().$_GET['redirect'];?>">
							<button type="button" class="btn btn-block btn-danger btn-xs" title="Back">Back</button>
						</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</center>
</body>
</html>
