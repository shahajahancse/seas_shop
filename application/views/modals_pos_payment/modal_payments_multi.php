<div class="modal fade" id="multiple-payments-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header header-custom">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center">Payments</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- LEFT HAND -->
          <div class="col-md-8">
            <div>
              <?php
                $atleast_one_payments = 'true';
                if(isset($sales_id) && $sales_id!='') { //For Save Operation or for new entry
                  $q22=$this->db->query("select payment,payment_type,payment_note from db_salespayments where sales_id='$sales_id'");
                  if($q22->num_rows()>0){
                    $atleast_one_payments = 'false';
                    $i=0;
                    foreach ($q22->result() as $res22) { $i++; ?>
                      <input type="hidden" name="payment_row_count" id='payment_row_count' value="<?= $i;?>">
                      <div class="col-md-12  payments_div">
                        <div class="box box-solid bg-gray">
                          <div class="box-body">
                            <div class="row">

                              <div class="col-md-6">
                                <div class="">
                                  <label for="amount_<?= $i;?>">Amount</label>
                                  <input type="text" class="form-control text-right payment only_currency" value='<?= $res22->payment;?>' id="amount_<?= $i;?>" name="amount_<?= $i;?>" placeholder="" onkeyup="calculate_payments()">
                                    <span id="amount_<?= $i;?>_msg" style="display:none" class="text-danger"></span>
                                </div>
                            </div>
                              <div class="col-md-6">
                                <div class="">
                                  <label for="payment_type_<?= $i;?>">Payment Type</label>
                                  <select class="form-control" id='payment_type_<?= $i;?>' name="payment_type_<?= $i;?>">
                                    <?php
                                      $q1=$this->db->query("select * from db_paymenttypes where status=1");
                                      if($q1->num_rows()>0){
                                          foreach($q1->result() as $res1){
                                            $selected=($res22->payment_type==$res1->payment_type) ? 'selected' : '';
                                          echo "<option $selected value='".$res1->payment_type."'>".$res1->payment_type ."</option>";
                                        }
                                      }
                                      else{
                                          echo "No Records Found";
                                      }
                                    ?>
                                  </select>
                                  <span id="payment_type_<?= $i;?>_msg" style="display:none" class="text-danger"></span>
                                </div>
                              </div>
                              <div class="clearfix"></div>
                            </div>

                            <div class="row">
                              <div class="col-md-12">
                                  <div class="">
                                    <label for="payment_note_<?= $i;?>">Payment Note</label>
                                    <textarea type="text" class="form-control" id="payment_note_<?= $i;?>" name="payment_note_<?= $i;?>" placeholder="" ><?= $res22->payment_note;?></textarea>
                                    <span id="payment_note_<?= $i;?>_msg" style="display:none" class="text-danger"></span>
                                  </div>
                              </div>
                              <div class="clearfix"></div>
                            </div>
                          </div>
                        </div>
                      </div><!-- col-md-12 -->
                    <?php } //foreach()
                  } else{
                    $atleast_one_payments ='true';
                  }
                }
              ?>
              <?php if($atleast_one_payments=='true'){ ?>
                <input type="hidden" name="payment_row_count" id='payment_row_count' value="1">
                <div class="col-md-12  payments_div">
                  <div class="box box-solid bg-gray">
                    <div class="box-body">
                      <div class="row">

                        <div class="col-md-6">
                          <div class="">
                            <label for="amount_1">Amount</label>
                            <input type="text" class="form-control text-right payment" id="amount_1" name="amount_1" placeholder="" onkeyup="calculate_payments()">
                              <span id="amount_1_msg" style="display:none" class="text-danger"></span>
                          </div>
                      </div>
                        <div class="col-md-6">
                          <div class="">
                            <label for="payment_type_1">Payment Type</label>
                            <select class="form-control" id='payment_type_1' name="payment_type_1">
                              <?php
                                $q1=$this->db->query("select * from db_paymenttypes where status=1");
                                if($q1->num_rows()>0){
                                    foreach($q1->result() as $res1){
                                    echo "<option value='".$res1->payment_type."'>".$res1->payment_type ."</option>";
                                  }
                                }
                                else{
                                    echo "No Records Found";
                                }
                                ?>
                            </select>
                            <span id="payment_type_1_msg" style="display:none" class="text-danger"></span>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="">
                            <label for="payment_note_1">Payment Note</label>
                            <textarea type="text" class="form-control" id="payment_note_1" name="payment_note_1" placeholder="" ></textarea>
                            <span id="payment_note_1_msg" style="display:none" class="text-danger"></span>
                          </div>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                    </div>
                  </div>
                </div><!-- col-md-12 -->
              <?php } ?>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="col-md-12">
                  <div class="col-md-12">
                    <button type="button" class="btn btn-primary btn-block" id="add_payment_row">Add Payment Row</button>
                  </div>
                </div>
              </div>
            </div>
          </div><!-- col-md-9 -->


          <!-- RIGHT HAND -->
          <div class="col-md-4">
            <div class="col-md-12">
              <div class="box box-solid bg-blue">
                <div class="box-body">
                  <div class="row ">
                    <div class="col-md-12 border-custom-bottom">
                      <span class="col-md-6 text-right text-bold ">Total Items:</span>
                      <span class="col-md-6 text-right text-bold  custom-font-size sales_div_tot_qty">0.00</span>
                    </div>
                  </div>

                  <div class="row ">
                    <div class="col-md-12 border-custom-bottom">
                      <span class="col-md-6 text-right text-bold ">Total:</span>
                      <span class="col-md-6 text-right text-bold  custom-font-size sales_div_tot_amt">0.00</span>
                    </div>
                  </div>
                  <!--  -->
                  <div class="row ">
                    <div class="col-md-12 border-custom-bottom">
                      <span class="col-md-6 text-right text-bold ">Discount(-):</span>
                      <span class="col-md-6 text-right text-bold  custom-font-size sales_div_tot_discount">0.00</span>
                    </div>
                  </div>
                  <!--  -->
                  <div class="row bg-red">
                    <div class="col-md-12 border-custom-bottom">
                      <span class="col-md-6 text-right text-bold ">Total Payable:</span>
                      <span class="col-md-6 text-right text-bold  custom-font-size sales_div_tot_payble">0.00</span>
                    </div>
                  </div>
                  <!--  -->
                  <div class="row ">
                    <div class="col-md-12 border-custom-bottom">
                      <span class="col-md-6 text-right text-bold ">Total Paying:</span>
                      <span class="col-md-6 text-right text-bold  custom-font-size sales_div_tot_paid">0.00</span>
                    </div>
                  </div>
                  <!--  -->
                  <!--  -->
                  <div class="row ">
                    <div class="col-md-12 border-custom-bottom">
                      <span class="col-md-6 text-right text-bold ">Balance:</span>
                      <span class="col-md-6 text-right text-bold  custom-font-size sales_div_tot_balance">0.00</span>
                    </div>
                  </div>
                  <!--  -->
                  <div class="row ">
                    <div class="col-md-12 bg-orange">
                      <span class="col-md-6 text-right text-bold ">Change Return:</span>
                      <span class="col-md-6 text-right text-bold  custom-font-size sales_div_change_return">0.00</span>
                    </div>
                  </div>
                  <!--  -->

                </div>
                      <!-- /.box-body -->
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</button>
        <button type="button" class="btn bg-maroon btn-lg make_sale btn-lg">Complete <i class="fa  fa-check "></i></button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script>
  function rowRem(id){//id=Rowid
    $(".payments_div_"+id).remove();
    failed.currentTime = 0;
    failed.play();
    final_total();
}
</script>


  <?php
    $q1 = $this->db->query("select * from db_paymenttypes where status=1")->result();
  ?>

<script>
  function create_sub_pay_row(rowcount) {
    var paymentTypes = <?php echo json_encode($q1); ?>;
    var get_row = `
      <div class="col-md-12 payment-row payments_div_${rowcount}">
        <div class="box box-solid bg-gray">
          <div class="box-header">
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" onclick="rowRem(${rowcount})">
                <i class="fa fa-times fa-2x"></i>
              </button>
            </div>
          </div>
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                <div>
                  <label for="amount_${rowcount}">Amount</label>
                  <input type="text" class="form-control text-right payment-amount only-currency"
                         id="amount_${rowcount}" name="amount_${rowcount}" placeholder=""
                         onkeyup="calculate_payments()">
                  <span id="amount_${rowcount}_msg" class="text-danger" style="display:none"></span>
                </div>
              </div>
              <div class="col-md-6">
                <div>
                  <label for="payment_type_${rowcount}">Payment Type</label>
                  <select class="form-control" id="payment_type_${rowcount}" name="payment_type_${rowcount}">
                    <option value="">Select</option>
                    ${paymentTypes.map(type => `<option value="${type.payment_type}">${type.payment_type}</option>`).join('')}
                  </select>
                  <span id="payment_type_${rowcount}_msg" class="text-danger" style="display:none"></span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div>
                  <label for="payment_note_${rowcount}">Payment Note</label>
                  <textarea class="form-control" id="payment_note_${rowcount}" name="payment_note_${rowcount}" placeholder=""></textarea>
                  <span id="payment_note_${rowcount}_msg" class="text-danger" style="display:none"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
    return get_row;
  }
</script>
