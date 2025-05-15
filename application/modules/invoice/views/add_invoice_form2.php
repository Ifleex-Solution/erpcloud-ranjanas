<!-- Invoice js -->
<script src="<?php echo base_url() ?>my-assets/js/admin_js/invoice.js" type="text/javascript"></script>
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->


<style>
    .highlight {
        background-color: #007BFF;
        color: white;
    }

    .modal-dialog {
        position: absolute;
        left: 0;
        margin-left: 250px;
    }

    .modal-dialog1 {
        position: absolute;
        left: 0;
        margin-left: 500px;
    }
</style>


<!--Add Invoice -->
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">

            <div class="panel-body" style="background-color:#B2BEB5">
                <?php echo form_open_multipart('invoice/invoice/bdtask_manual_sales_insert', array('class' => 'form-vertical', 'id' => 'insert_sale', 'name' => 'insert_sale', 'onsubmit' => 'return validateFormWrapper()')) ?>

                <div class="row">
                    <form class="form-inline" style="display: flex; align-items: center; width: 100%;">
                        <div class="col-sm-1">

                            <label for="date" class="col-form-label"><?php echo display('date') ?> <i class="text-danger">*</i></label>
                        </div>

                        <div class="col-sm-2">

                            <?php
                            date_default_timezone_set('Asia/Colombo');

                            $date = date('Y-m-d');
                            ?>
                            <input class=" form-control" readonly type="text" size="50" name="invoice_date" id="date" required value="<?php echo html_escape($date); ?>" />
                        </div>
                        <!-- <div class="col-sm-2">
                            <input type="text" id="add_item" class="form-control" placeholder="Barcode or QR-code scan here">
                        </div> -->
                        <!-- <div class="col-sm-2">
                            <label class="mr-3 ml-3">OR</label>
                        </div> -->
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="barcodeInput" placeholder="Scan barcode here..." autofocus>
                        </div>
                        <!-- <p id="result"></p> -->

                        <div class="col-sm-1">

                            <label>Emp Name</label>
                        </div>

                        <div class="col-sm-3">
                            <input type="hidden" class="form-control" id="emp_name1" placeholder="" readonly>

                            <input type="text" class="form-control" id="emp_name" placeholder="" readonly>
                            <input type="hidden" class="form-control" id="emp_id" placeholder="" readonly>

                        </div>
                    </form>
                </div>

                <br />
                <div class="row">
                    <div class="col-sm-1">
                        <label class="col-form-label">Invoice Id</label>
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="empId" placeholder="" readonly>
                    </div>
                </div>
                <br />

                <div class="table-responsive">
                    <div class="row">
                        <div class="col-sm-10">
                            <table class="table-bordered" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field"><?php echo display('item_information') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center invoice_fields"><?php echo display('unit') ?></th>
                                        <th class="text-center invoice_fields">Sold By</th>

                                        <th class="text-center invoice_fields"><?php echo display('quantity') ?> <i class="text-danger">*</i>
                                        </th>
                                        <th class="text-center invoice_fields">Meter
                                        </th>
                                        <th class="text-center product_field"><?php echo display('rate') ?> <i class="text-danger">*</i></th>
                                        <th class="text-center product_field">Discount type </th>

                                        <th class="text-center product_field">Dis LKR / Percentage </th>


                                        <th class="text-center product_field"><?php echo display('dis_val') ?> </th>
                                        <th class="text-center product_field"><?php echo display('total') ?>
                                        </th>

                                    </tr>
                                </thead>
                                <tbody id="addinvoiceItem">

                                </tbody>

                            </table>
                            <br />
                            <table class="table-bordered" id="invoiceTable">
                                <thead>
                                    <tr>
                                        <th>Item No</th>
                                        <th>Description</th>
                                        <th>S/B</th>
                                        <th>Qty</th>
                                        <th>Meter</th>

                                        <th>Rate</th>
                                        <th>Dis T</th>
                                        <th>Dis L/ P</th>
                                        <th>Dis. Value</th>
                                        <th>Total</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Rows will be inserted here -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><b>Total Qty: </b><span id="total_qty"></span></td>
                                        <td colspan="8" class="text-right"><b><?php echo display('grand_total') ?>:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="grandTotal" class="form-control text-right grandTotalamnt" autocomplete="off”  name=" grand_total_price" value="0.00" />
                                        </td>
                                    </tr>

                                    <tr>

                                    </tr>
                                </tfoot>
                            </table>
                            <input type="hidden" name="finyear" value="<?php echo financial_year(); ?>">
                            <p hidden id="old-amount"><?php echo 0; ?></p>
                            <p hidden id="pay-amount"></p>
                            <p hidden id="change-amount"></p>
                        </div>
                        <div class="col-sm-2">
                            <P><b>SPACE-PAYMENTS</P>
                            <P>F2-SALES SUMMARY</P>
                            <P>F4-MANAGE SALES</P>
                            <P>F5-REFRESH</P>
                            <P>F8-EXCHANGE ITEMMODE</P>
                            <P>F9-COMMISION MODE</P>

                        </div>
                    </div>
                    <br />



                </div>

                <?php echo form_close() ?>
            </div>

        </div>
    </div>


</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 1000px;">
            <div class="modal-header" style="background-color:#B2BEB5">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Manage Sales</h4>
            </div>
            <div class="modal-body" style="background-color:#B2BEB5">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body" style="background-color:#B2BEB5">
                                <div class="col-sm-15">
                                    <?php echo form_open('', array('class' => 'form-inline', 'method' => 'get', 'autocomplete' => 'off')) ?>
                                    <?php
                                    date_default_timezone_set('Asia/Colombo');

                                    $today = date('Y-m-d');
                                    ?>
                                    <div class="form-group">
                                        <label class="" for="from_date"><?php echo display('start_date') ?></label>
                                        <input type="text" name="from_date" class="form-control datepicker" id="from_date" value="<?php echo html_escape($today); ?>"
                                            placeholder="<?php echo display('start_date') ?>">
                                    </div>

                                    <div class="form-group">
                                        <label class="" for="to_date"><?php echo display('end_date') ?></label>
                                        <input type="text" name="to_date" class="form-control datepicker" id="to_date"
                                            placeholder="<?php echo display('end_date') ?>" value="<?php echo html_escape($today); ?>" autocomplete="off">
                                    </div>

                                    <div class="form-group">

                                        <?php if ($this->permission1->method('manage_invoice', 'view')->access()) { ?>
                                            <label for="empid" class="mr-2 mb-0">Emp Id</label>
                                            <div class="input-group mr-4" style="width: 150px;">
                                                <input type="password" tabindex="4" class="form-control" name="empid" id="empid" autocomplete="new-password">
                                            </div>
                                        <?php } ?>

                                        <?php if (!$this->permission1->method('manage_invoice', 'view')->access()) { ?>
                                            <input type="hidden" tabindex="4" class="form-control" name="empid" id="empid" value="123">
                                        <?php } ?>
                                    </div>




                                    <button type="button" id="btn-filter"
                                        class="btn btn-success"><?php echo display('find') ?></button>

                                    <?php echo form_close() ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                </div>
                <!-- Manage Invoice report -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-bd lobidrag">
                            <div class="panel-heading" style="background-color:#B2BEB5">
                                <div class="panel-title">
                                    <span><?php echo display('manage_invoice') ?></span>
                                </div>
                            </div>
                            <div class="panel-body" style="background-color:#B2BEB5">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered" cellspacing="0" width="100%" id="InvList2">
                                        <thead>
                                            <tr>
                                                <th><?php echo display('invoice_no') ?></th>
                                                <th><?php echo display('sale_by') ?></th>
                                                <th><?php echo display('date') ?></th>
                                                <th><?php echo display('total_amount') ?></th>
                                                <th class="text-center"><?php echo display('action') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>

                                    </table>


                                </div>


                            </div>
                        </div>
                        <input type="hidden" id="total_invoice" value="<?php echo $total_invoice; ?>" name="">

                    </div>

                    <div id="add0" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <strong><?php echo display('delivery_note') ?></strong>
                                </div>
                                <div class="modal-body" id="invoice_note_show">


                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>





<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog1">
        <div class="modal-content" style="width: 700px;">
            <div class="modal-header" style="background-color:#B2BEB5">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Sales Summmary</h4>
            </div>
            <div class="modal-body" style="background-color:#B2BEB5">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body" style="background-color:#B2BEB5">
                                <div class="col-sm-15">
                                    <?php echo form_open('', array('class' => 'form-inline', 'method' => 'get', 'autocomplete' => 'off')) ?>
                                    <?php
                                    date_default_timezone_set('Asia/Colombo');
                                    $today = date('Y-m-d');
                                    ?>
                                    <div class="form-group">
                                        <label class="" for="from_date1"><?php echo display('start_date'); ?></label>
                                        <input class="datepicker form-control" id="from_date1" name="from_date1" type="text" name="invoice_date" id="date" required value="<?php echo html_escape($date); ?>" />


                                    </div>

                                    <div class="form-group">
                                        <label class="" for="to_date1"><?php echo display('end_date'); ?></label>
                                        <input class="datepicker form-control" id="to_date1" name="to_date1" type="text" name="invoice_date" id="date" required value="<?php echo html_escape($date); ?>" />

                                    </div>






                                    <button type="button" id="btn-gettotalsale"
                                        class="btn btn-success"><?php echo display('find') ?></button>

                                    <?php echo form_close() ?>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                </div>
                <!-- Manage Invoice report -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-bd lobidrag">
                            <div class="panel-heading" style="background-color:#B2BEB5">

                            </div>
                            <div class="panel-body" style="background-color:#B2BEB5">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered" cellspacing="0" width="100%" id="InvListTotalSales">
                                        <thead>
                                            <tr>
                                                <!-- <th>From Date</th>
                                                <th>To Date</th> -->
                                                <th>Total Invoice Count</th>
                                                <th>Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>

                                    </table>


                                </div>


                            </div>
                        </div>
                        <input type="hidden" id="total_invoice" value="<?php echo $total_invoice; ?>" name="">

                    </div>

                    <div id="add0" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <strong><?php echo display('delivery_note') ?></strong>
                                </div>
                                <div class="modal-body" id="invoice_note_show">


                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="editInvoiceModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editInvoiceLabel" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInvoiceLabel">Edit Invoice</h5>
            </div>
            <div class="modal-body">
                <input class="form-control" type="text" id="searchInput_100" placeholder="Employee Id..." onkeyup="handleEmployeeEditKeyPress(event,100)" autocomplete='off' />
                <input type='text' name='employee_id[]' id='employeeId_100' hidden />
                <input type='text' name='invoiceId[]' id='invoiceId_100' hidden />

                <div id='searchResults_100' style='width: 400px; background-color: #f1f1f1; max-height: 150px; overflow-y: auto; border: 1px solid #ddd; position: absolute;'></div>
                <br />
                <button type="button" class="btn btn-primary" onclick="handleEditEmployee()">Update</button>

            </div>
        </div>
    </div>
</div>



<div id="passwordmodel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="passwordmodel" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInvoiceLabel">Enter Password</h5>
            </div>
            <div class="modal-body">
                <input class="form-control" type="password" id="password" placeholder="Password..." onkeyup="checkUser(event)" />

            </div>
        </div>
    </div>
</div>
<script>
    $('body').addClass("sidebar-mini sidebar-collapse");

    const barcodeInput = document.getElementById('barcodeInput');

    barcodeInput.addEventListener('input', function() {
        if (barcodeInput.value.length == 6) {
            getItem(barcodeInput.value)

        }

    });

    barcodeInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            let num = barcodeInput.value.toString().padStart(6, '0');
            getItem(num)


            // alert(`product not found`);
        }
    });

    function getItem(product_id) {
        var tableBody = document.getElementById('normalinvoice').getElementsByTagName('tbody')[0];
        tableBody.innerHTML = '';
        var invoice_edit_page = $("#invoice_edit_page").val();
        $.ajax({
            type: "post",
            async: false,
            url: $('#base_url').val() + 'invoice/invoice/invoice_setup',
            data: {
                product_id: product_id,
                csrf_test_name: invoice_edit_page
            },
            success: function(data) {

                if (data != "") {
                    if (arrItem.length < rowcount) {

                        addinvoice('addinvoiceItem', JSON.parse(data));

                    } else {
                        alert("You Can't add more then 10 items")
                    }
                }


            },
            error: function() {
                alert('Request Failed, Please check your code and try again!');
            }
        });
    }
</script>



<script>
    var count = 0;
    let arr = [];
    let arrItem = [];

    var tabindex = 1;
    let rowcount = 10;


    function updateTable(arrItem) {
        var tableBody = document.getElementById('invoiceTable').getElementsByTagName('tbody')[0];
        tableBody.innerHTML = ''; // Clear any existing rows

        // First loop: populate the table rows with data from arrItem
        let i = 1;
        let total = 0;
        let totalqty = 0;


        arrItem.forEach(function(item) {
            item.i = i;
            var row = document.createElement('tr');

            if (item.commisionmode === "no") {
                if (item.qty > 0) {
                    row.innerHTML = `
            <td style="padding: 2px; height: 20px;width:120px;">
                <input type='text' value='${item.productid}' readonly class='form-control' style='height: 20px; font-size: 12px; padding: 2px;'>
            </td>
            <td style="padding: 2px; height: 20px;width:190px;">
                <input type='text'  name='description[]' class='form-control' value='${item.productname}' readonly style='height: 20px;  font-size: 12px;  padding: 2px;'>
            </td>
            <td style="padding: 2px; height: 20px;width:120px;">
                <input type='text' name='sb[]' class='form-control' value='${item.sb}' readonly  style='height: 20px;  font-size: 12px;  padding: 2px;'>
            </td>
            <td style="padding: 2px; height: 20px;width:50px;">
                <input type='number' name='qty[]' class="form-control" value='${item.qty}' readonly  min='0' style='height: 20px;  font-size: 12px; padding: 2px;'>
            </td>
             <td style="padding: 2px; height: 20px;width:50px;">
                <input type='number' name='meter[]' class="form-control" value='${item.meter}' readonly  min='0' style='height: 20px;  font-size: 12px; padding: 2px;'>
            </td>
            <td style="padding: 2px; height: 20px;width:120px;">
                <input type='number' name='rate[]' class="form-control" value='${item.rate}' readonly   min='0' style='height: 20px; font-size: 12px; text-align: right;'>
            </td>
            <td style="padding: 2px; height: 20px;width:40px;">
                <input type='text' name='discount_type[]' class='form-control' value='${item.discount_type}' readonly  style='height: 20px;  font-size: 12px;  padding: 2px; '>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;">
                <input type='text' name='discount_lkr[]' class="form-control" value='${item.discount}'  readonly style='height: 20px;  font-size: 12px;  text-align: right;'>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;">
                <input type='number' name='discount_value[]' class="form-control" value='${item.discount_value}' readonly style='height: 20px;  font-size: 12px;  text-align: right;'>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;">
                <input type='number' name='total[]' class="form-control"  value='${item.total}'   readonly style='height: 20px;  font-size: 12px; text-align: right;  '>
            </td> `;
                    if (invoice_ID == 0) {
                        row.innerHTML = row.innerHTML + `<td style="padding: 2px; height: 20px; width: 70px;">
    <a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-info" onclick="editRow1(${item.i})">
        <i class="fa fa-pencil" ></i>
    </a>
                        <a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-danger" onclick="deleteRow(${item.i})">
        <i class="fa fa-trash" ></i>
    </a>
     
</td>`
                    } else {
                        row.innerHTML = row.innerHTML + `<td style="padding: 2px; height: 20px; width: 50px;">
    <a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-info" onclick="editRow(${item.invoiceId})">
        <i class="fa fa-pencil" ></i>
    </a>
</td>`
                    }
                } else {

                    row.innerHTML = `
            <td style="padding: 2px; height: 20px;width:80px;background-color:#f9f9c1;">
                <input type='text' value='${item.productid}' readonly class='form-control' style='height: 20px; font-size: 12px; padding: 2px;background-color:#f9f9c1;'>
            </td>
            <td style="padding: 2px; height: 20px;width:140px;background-color:#f9f9c1;">
                <input type='text'  name='description[]' class='form-control' value='${item.productname}' readonly style='height: 20px;  font-size: 12px;  padding: 2px;background-color:#f9f9c1;'>
            </td>
            <td style="padding: 2px; height: 20px;width:100px;background-color:#f9f9c1;">
                <input type='text' name='sb[]' class='form-control' value='${item.sb}' readonly  style='height: 20px;  font-size: 12px;  padding: 2px;background-color:#f9f9c1;'>
            </td>
            <td style="padding: 2px; height: 20px;width:50px;background-color:#f9f9c1;">
                <input type='number' name='qty[]' class="form-control" value='${item.qty}' readonly  min='0' style='height: 20px;  font-size: 12px; padding: 2px;background-color:#f9f9c1;'>
            </td>
            <td style="padding: 2px; height: 20px;width:50px;background-color:#f9f9c1;">
                <input type='number' name='meter[]' class="form-control" value='${item.meter}' readonly  min='0' style='height: 20px;  font-size: 12px; padding: 2px;background-color:#f9f9c1;'>
            </td>
            <td style="padding: 2px; height: 20px;width:120px;background-color:#f9f9c1;">
                <input type='number' name='rate[]' class="form-control" value='${item.rate}' readonly   min='0' style='height: 20px; font-size: 12px; text-align: right;background-color:#f9f9c1;'>
            </td>
            <td style="padding: 2px; height: 20px;width:40px;background-color:#f9f9c1;">
                <input type='text' name='discount_type[]' class='form-control' value='${item.discount_type}' readonly  style='height: 20px;  font-size: 12px;  padding: 2px;background-color:#f9f9c1; '>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;background-color:#f9f9c1;">
                <input type='text' name='discount_lkr[]' class="form-control" value='${item.discount}'  readonly style='height: 20px;  font-size: 12px;  text-align: right;background-color:#f9f9c1;'>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;background-color:#f9f9c1;">
                <input type='number' name='discount_value[]' class="form-control" value='${item.discount_value}' readonly style='height: 20px;  font-size: 12px;  text-align: right;background-color:#f9f9c1;'>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;background-color:#f9f9c1;">
                <input type='number' name='total[]' class="form-control"  value='${item.total}'   readonly style='height: 20px;  font-size: 12px; text-align: right;background-color:#f9f9c1;  '>
            </td>
                    `;

                    if (invoice_ID == 0) {
                        row.innerHTML = row.innerHTML + `<td style="padding: 2px; height: 20px; width: 50px;">
       <a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-info" onclick="editRow1(${item.i})">
        <i class="fa fa-pencil" ></i>
    </a>
                        <a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-danger" onclick="deleteRow(${item.i})">
        <i class="fa fa-trash" ></i>
    </a>
   
</td>`
                    } else {
                        row.innerHTML = row.innerHTML + `<td style="padding: 2px; height: 20px; width: 50px;">
    <a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-info" onclick="editRow(${item.invoiceId})">
        <i class="fa fa-pencil" ></i>
    </a>
</td>`
                    }
                }
            } else {
                if (item.qty > 0) {
                    row.innerHTML = `
            <td style="padding: 2px; height: 20px;width:120px;background-color:#b2e0b2;">
                <input type='text' value='${item.productid}' readonly class='form-control' style='height: 20px; font-size: 12px; padding: 2px;background-color:#b2e0b2;'>
            </td>
            <td style="padding: 2px; height: 20px;width:190px;background-color:#b2e0b2;">
                <input type='text'  name='description[]' class='form-control' value='${item.productname}' readonly style='height: 20px;  font-size: 12px;  padding: 2px;background-color:#b2e0b2;'>
            </td>
            <td style="padding: 2px; height: 20px;width:120px;background-color:#b2e0b2;">
                <input type='text' name='sb[]' class='form-control' value='${item.sb}' readonly  style='height: 20px;  font-size: 12px;  padding: 2px;background-color:#b2e0b2;'>
            </td>
            <td style="padding: 2px; height: 20px;width:50px;background-color:#b2e0b2;">
                <input type='number' name='qty[]' class="form-control" value='${item.qty}' readonly  min='0' style='height: 20px;  font-size: 12px; padding: 2px;background-color:#b2e0b2;'>
            </td>
             <td style="padding: 2px; height: 20px;width:50px;background-color:#b2e0b2;">
                <input type='number' name='meter[]' class="form-control" value='${item.meter}' readonly  min='0' style='height: 20px;  font-size: 12px; padding: 2px;background-color:#b2e0b2;'>
            </td>
            <td style="padding: 2px; height: 20px;width:120px;background-color:#b2e0b2;">
                <input type='number' name='rate[]' class="form-control" value='${item.rate}' readonly   min='0' style='height: 20px; font-size: 12px; text-align: right;background-color:#b2e0b2;'>
            </td>
            <td style="padding: 2px; height: 20px;width:40px;background-color:#b2e0b2;">
                <input type='text' name='discount_type[]' class='form-control' value='${item.discount_type}' readonly  style='height: 20px;  font-size: 12px;  padding: 2px;background-color:#b2e0b2; '>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;background-color:#b2e0b2;">
                <input type='text' name='discount_lkr[]' class="form-control" value='${item.discount}'  readonly style='height: 20px;  font-size: 12px;  text-align: right;background-color:#b2e0b2;'>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;background-color:#b2e0b2;">
                <input type='number' name='discount_value[]' class="form-control" value='${item.discount_value}' readonly style='height: 20px;  font-size: 12px;  text-align: right;background-color:#b2e0b2;'>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;background-color:#b2e0b2;">
                <input type='number' name='total[]' class="form-control"  value='${item.total}'   readonly style='height: 20px;  font-size: 12px; text-align: right;background-color:#b2e0b2;  '>
            </td> `;
                    if (invoice_ID == 0) {
                        row.innerHTML = row.innerHTML + `<td style="padding: 2px; height: 20px; width: 50px;">
      <a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-info" onclick="editRow1(${item.i})">
        <i class="fa fa-pencil" ></i>
    </a>
                        <a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-danger" onclick="deleteRow(${item.i})">
        <i class="fa fa-trash" ></i>
    </a>
   
</td>`
                    } else {
                        row.innerHTML = row.innerHTML + `<td style="padding: 2px; height: 20px; width: 50px;">
    <a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-info" onclick="editRow(${item.invoiceId})">
        <i class="fa fa-pencil" ></i>
    </a>
</td>`
                    }
                } else {

                    row.innerHTML = `
            <td style="padding: 2px; height: 20px;width:80px;background-color:#ed7988;">
                <input type='text' value='${item.productid}' readonly class='form-control' style='height: 20px; font-size: 12px; padding: 2px;background-color:#ed7988;'>
            </td>
            <td style="padding: 2px; height: 20px;width:140px;background-color:#ed7988;">
                <input type='text'  name='description[]' class='form-control' value='${item.productname}' readonly style='height: 20px;  font-size: 12px;  padding: 2px;background-color:#ed7988;'>
            </td>
            <td style="padding: 2px; height: 20px;width:100px;background-color:#ed7988;">
                <input type='text' name='sb[]' class='form-control' value='${item.sb}' readonly  style='height: 20px;  font-size: 12px;  padding: 2px;background-color:#ed7988;'>
            </td>
            <td style="padding: 2px; height: 20px;width:50px;background-color:#ed7988;">
                <input type='number' name='qty[]' class="form-control" value='${item.qty}' readonly  min='0' style='height: 20px;  font-size: 12px; padding: 2px;background-color:#ed7988;'>
            </td>
            <td style="padding: 2px; height: 20px;width:50px;background-color:#ed7988;">
                <input type='number' name='meter[]' class="form-control" value='${item.meter}' readonly  min='0' style='height: 20px;  font-size: 12px; padding: 2px;background-color:#ed7988;'>
            </td>
            <td style="padding: 2px; height: 20px;width:120px;background-color:#ed7988;">
                <input type='number' name='rate[]' class="form-control" value='${item.rate}' readonly   min='0' style='height: 20px; font-size: 12px; text-align: right;background-color:#ed7988;'>
            </td>
            <td style="padding: 2px; height: 20px;width:40px;background-color:#ed7988;">
                <input type='text' name='discount_type[]' class='form-control' value='${item.discount_type}' readonly  style='height: 20px;  font-size: 12px;  padding: 2px;background-color:#ed7988; '>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;background-color:#ed7988;">
                <input type='text' name='discount_lkr[]' class="form-control" value='${item.discount}'  readonly style='height: 20px;  font-size: 12px;  text-align: right;background-color:#ed7988;'>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;background-color:#ed7988;">
                <input type='number' name='discount_value[]' class="form-control" value='${item.discount_value}' readonly style='height: 20px;  font-size: 12px;  text-align: right;background-color:#ed7988;'>
            </td>
            <td style="padding: 2px; height: 20px;width:130px;background-color:#ed7988;">
                <input type='number' name='total[]' class="form-control"  value='${item.total}'   readonly style='height: 20px;  font-size: 12px; text-align: right;background-color:#ed7988;  '>
            </td>
                    `;

                    if (invoice_ID == 0) {
                        row.innerHTML = row.innerHTML + `<td style="padding: 2px; height: 20px; width: 50px;">
    <a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-info" onclick="editRow1(${item.i})">
        <i class="fa fa-pencil" ></i>
    </a>
                        <a href="#" style="margin-left: 5px;"    class="btn btn-xs btn-danger" onclick="deleteRow(${item.i})">
        <i class="fa fa-trash" ></i>
    </a>
    
</td>`
                    } else {
                        row.innerHTML = row.innerHTML + `<td style="padding: 2px; height: 20px; width: 50px;">
    <a href="#" style="margin-left: 5px;"  class="btn btn-xs btn-info" onclick="editRow(${item.invoiceId})">
        <i class="fa fa-pencil" ></i>
    </a>
</td>`
                    }
                }

            }


            tableBody.appendChild(row);
            total = parseFloat(item.total) + total;
            totalqty = totalqty + parseInt(item.qty)

            i++;
        });

        document.getElementById("total_qty").innerHTML = totalqty;
        $("#grandTotal").val(parseFloat(total).toFixed(2));

        // Second loop: create empty rows to make a total of 14 rows
        for (i; i <= rowcount; i++) {
            var row = document.createElement('tr');
            row.innerHTML = `
    <td style="padding: 2px; height: 20px;width:120px;">
        <input type='text'   readonly class='form-control' style='height: 20px; font-size: 12px; padding: 2px;'>
    </td>
    <td style="padding: 2px; height: 20px;width:190px;">
        <input type='text' name='description[]' class='form-control' readonly style='height: 20px; font-size: 12px; padding: 2px;'>
    </td>
    <td style="padding: 2px; height: 20px;width:120px; ">
        <input type='text' name='sb[]' class='form-control' readonly style='height: 20px; font-size: 12px; padding: 2px;'>
    </td>
    <td style="padding: 2px; height: 20px; width:50px;">
        <input type='number' name='qty[]' class='form-control' readonly min='0' style='height: 20px; font-size: 12px; padding: 2px;'>
    </td>
    <td style="padding: 2px; height: 20px; width:50px;">
        <input type='number' name='meter[]' class='form-control' readonly min='0' style='height: 20px; font-size: 12px; padding: 2px;'>
    </td>
    <td style="padding: 2px; height: 20px;width:120px;">
        <input type='number' name='rate[]' class='form-control' readonly min='0' style='height: 20px; font-size: 12px; padding: 2px;'>
    </td>
    <td style="padding: 2px; height: 20px;width:40px;">
        <input type='text' name='discount_type[]' class='form-control' readonly style='height: 20px; font-size: 12px; padding: 2px;'>
    </td>
    <td style="padding: 2px; height: 20px;width:130px;">
        <input type='text' name='discount_lkr[]' class='form-control' readonly style='height: 20px; font-size: 12px; padding: 2px;'>
    </td>
    <td style="padding: 2px; height: 20px;width:130px;">
        <input type='number' name='discount_value[]' class='form-control' readonly style='height: 20px; font-size: 12px; padding: 2px;'>
    </td>
    <td style="padding: 2px; height: 20px;width:150px;">
        <input type='number' name='total[]' class='form-control'  readonly style='height: 20px; font-size: 12px; padding: 2px;'>
    </td>
    <td style="padding: 2px; height: 20px;width:70px;"></td>
`;
            tableBody.appendChild(row);
        }


    }
    let invoice_id = 0;
    let invoice_i = 0;

    function editRow(invoiceId) {
        invoice_i = 0;
        invoice_id = invoiceId;
        $("#passwordmodel").modal('show');


        // $("#editInvoiceModel").modal('show');

        $('#passwordmodel').on('shown.bs.modal', function() {

            let element2 = document.getElementById("password");
            element2.focus();
            element2.select();
        });


    }

    function deleteRow(invoiceId) {
        if (confirm("Do you want to remove this product")) {
            let indexToRemove = arrItem.findIndex(item => item.i == invoiceId);

            if (indexToRemove !== -1) {
                // If item is found, remove it using splice
                arrItem.splice(indexToRemove, 1); // Remove 1 item at the found index
            }
            updateTable(arrItem)
            let element2 = document.getElementById("barcodeInput");
            element2.focus();
            element2.select();

        }


    }

    function editRow1(invoiceId) {
        invoice_id = 0;

        invoice_i = invoiceId;

        let item = invoice_id == 0 ? arrItem.find(item => item.i == invoice_i) : arrItem.find(item => item.invoiceId == invoice_id);




        $("#searchInput_100").val(item.sb);
        $("#editInvoiceModel").modal('show');


        $('#editInvoiceModel').on('shown.bs.modal', function() {
            let element2 = document.getElementById("searchInput_100");


            document.getElementById('employeeId_100').value = "";

            element2.focus();
            element2.select();
        });

    }




    window.onload = updateTable(arrItem);



    var mode = "+"
    var commisionmode = false;

    document.addEventListener('keydown', function(event) {
        // Check if Shift is pressed and the key is '+'
        if (event.key === 'F8') {
            if (mode == "-") {
                mode = "+"
                alert("Mode Changed to +");
            } else {
                mode = "-"
                alert("Mode Changed to -");
            }

        }

        if (event.key === 'F2') {
            dataTableForSale();
            $("#exampleModal2").modal('show');

        }
        if (event.key === 'F4') {
            dataTable().ajax.reload();
            $("#exampleModal").modal('show');

        }
        if (event.key === 'F5') {
            location.reload()

        }
        if (event.key === 'F9') {
            if (commisionmode) {
                commisionmode = false;
                alert("Commission Mode Disabled ");
            } else {
                commisionmode = true;
                alert("Commission Mode Enabled");
            }

        }
        if (event.code === "Space") {
            event.preventDefault();
            let element2 = document.getElementById("grandTotal");
            element2.focus();
            element2.select();
            // $("#exampleModal").modal('show');

        }
        if (event.shiftKey && event.key === 'S') {



        }
    });
    enteredCount = 0;
    $('#grandTotal').keydown(function(e) {
        // Check if the pressed key is "Enter"
        if (e.key !== "Enter") {
            e.preventDefault();
        }
        if (e.key === "Enter") {
            // Prevent the default behavior for Enter key (like form submission)

            // Only proceed if the total is not "0.00"
            if ($('#grandTotal').val() != "0.00") {
                // Only allow logic if enteredCount is 0 (i.e., the user has not confirmed already)
                if (enteredCount == 0) {
                    enteredCount = enteredCount + 1; // Increment enteredCount to prevent multiple confirmations



                    // Proceed with confirmation logic based on invoice_ID
                    if (invoice_ID == 0) { // New record
                        if (confirm("Do You Want To Proceed Further?")) {
                            if (confirm("Are you sure you want to save this record?")) {
                                //  setTimeout(function() {
                                saverecord(); // Save the record after a delay
                                //  }, 500);
                            } else {
                                enteredCount = 0;

                            }
                        } else {
                            enteredCount = 0;
                        }
                    } else { // Existing record (update)
                        if (confirm("Do You Want To Proceed Further?")) {
                            if (confirm("Are you sure you want to update this record?")) {
                                //  setTimeout(function() {
                                saverecord(); // Update the record after a delay
                                //  }, 500);
                            } else {
                                enteredCount = 0;

                            }
                        } else {
                            enteredCount = 0;
                        }
                    }
                }
            } else {
                // Show an alert if the total is "0.00"
                alert("Total shouldn't be empty");
            }
        }
    });
    $(document).ready(function() {
        getNextId();
    });

    function getNextId() {
        $.ajax({
            type: "get",
            url: $('#base_url').val() + 'invoice/invoice/empNextNumber',
            data: {
                items: arrItem,

            },
            success: function(data1) {
                datas = JSON.parse(data1);
                $('#empId').val(datas)
            }
        });
    }


    function saverecord() {
        // setTimeout(function() {
        if (invoice_ID == 0) {
            $.ajax({
                type: "post",
                url: $('#base_url').val() + 'invoice/invoice/sales_insertemp',
                data: {
                    items: arrItem,
                    grandTotal: $('#grandTotal').val(),
                    date: $('#date').val(),

                },
                success: function(data1) {

                    datas = JSON.parse(data1);
                    if (data1 === "Invalid UserId") {
                        alert("Invalid UserId")
                        return
                    }
                    // if (confirm("Do You Want To Print?")) {
                    dataTableForSale();

                    var tableBody = document.getElementById('normalinvoice').getElementsByTagName('tbody')[0];
                    tableBody.innerHTML = '';
                    arrItem = [];
                    updateTable(arrItem);
                    $('#grandTotal').val("0.00");
                    $("#emp_name").val("")
                    $("#emp_name1").val("")
                    $("#emp_id").val("")
                    mode = "+"
                    commisionmode = false;
                    let element2 = document.getElementById("barcodeInput");
                    element2.focus();
                    element2.select();
                    enteredCount = 0;
                    $("#barcodeInput").prop("disabled", false);
                    getNextId();

                    printRawHtml(datas.details);
                    // } else {
                    //     location.reload();
                    // }
                }
            });

        } else {
            $.ajax({
                type: "post",
                url: $('#base_url').val() + 'invoice/invoice/sales_updateemp',
                data: {
                    items: arrItem,

                },
                success: function(data1) {

                    datas = JSON.parse(data1);
                    // if (confirm("Do You Want To Print?")) {
                    dataTableForSale()
                    var tableBody = document.getElementById('normalinvoice').getElementsByTagName('tbody')[0];
                    tableBody.innerHTML = '';
                    arrItem = [];
                    mode = "+"
                    commisionmode = false;
                    updateTable(arrItem)
                    $('#grandTotal').val("0.00")
                    $("#emp_name").val("")
                    $("#emp_name1").val("")
                    $("#emp_id").val("")
                    invoice_ID = 0;
                    let element2 = document.getElementById("barcodeInput");
                    element2.focus();
                    element2.select();
                    enteredCount = 0;

                    $("#barcodeInput").prop("disabled", false);
                    getNextId();


                    printRawHtml(datas.details);
                    // } else {
                    //     location.reload();
                    // }
                }
            });
        }

        // }, 1000); //
    }


    function addinvoice(t, data) {
        tabindex = 1;
        var row = $("#normalinvoice tbody tr").length;
        count = count + 1;
        var tab1 = 0;
        var tab2 = 0;
        var tab3 = 0;
        var tab4 = 0;
        var tab5 = 0;
        var tab6 = 0;
        var tab7 = 0;
        var tab8 = 0;
        var limits = 500;
        var taxnumber = $("#txfieldnum").val();
        var tbfild = '';
        for (var i = 0; i < taxnumber; i++) {
            var taxincrefield = '<input id="total_tax' + i + '_' + count + '" class="total_tax' + i + '_' + count + '" type="hidden"><input id="all_tax' + i + '_' + count + '" class="total_tax' + i + '" type="hidden" name="tax[]">';
            tbfild += taxincrefield;
        }
        if (count == limits)
            alert("You have reached the limit of adding " + count + " inputs");
        else {


            e = document.createElement("tr");
            tab1 = tabindex + 1;
            tab2 = tabindex + 2;
            tab3 = tabindex + 3;
            tab4 = tabindex + 4;
            tab5 = tabindex + 5;
            tab6 = tabindex + 6;
            tab7 = tabindex + 7;
            tab8 = tabindex + 8;
            tabindex = tabindex + 9;

            if (mode === "+") {
                var row1 = "<td '><input type='text' name='product_name' class='form-control' placeholder='Product Name' id='" + "product_name_" + count +
                    "' required tabindex='" + tab1 + "' readonly='readonly'><input type='hidden' class='common_product autocomplete_hidden_value  product_id_" + count +
                    "' name='product_id[]'  id='product_id_" + count + "'/></td><td><input class='form-control text-right common_name unit_" + count +
                    " valid'  id='unit_type_" + count + "' value='None' readonly='' aria-invalid='false' type='text'></td>" +
                    "<td><div style='position: relative; display: inline-block;'><input class='form-control' style='width:70px'  type='text' id='searchInput_" + count + "' placeholder='Employee Id...' onkeyup='handleEmployeeKeyPress(event," + count + ")'  autocomplete='off' /><input type='text' name='employee_id[]' id='employeeId_" + count + "' hidden /><div id='searchResults_" + count + "' style='  width: 100%;  max-height: 150px;  overflow-y: auto; border: 1px solid #ddd; position: absolute;  top: 100%;  left: 0;  z-index: 1000;  background-color: #fff;border-radius: 4px;'></div></div></td>" +
                    "<td><input type='number' style='width:70px' name='product_quantity[]' value='1' required='required' " +
                    "onkeyup='bdtask_invoice_quantitycalculate(" + count + ", event);' " +
                    "oninput='this.value = this.value.replace(/[^0-9]/g, \"\");' " +
                    "step='1' onchange='bdtask_invoice_quantitycalculate(" + count + ", event);' " +
                    "id='total_qntt_" + count + "' class='common_qnt total_qntt_" + count +
                    " form-control text-right' placeholder='0' min='0' tabindex='" + tab3 + "' />" +
                    "</td>"
                if (data.unit == "m") {
                    row1 = row1 + "<td> <input type='number' style='width:80px' name='product_quantitymeter[]' value='1' required='required' onkeyup='bdtask_invoice_quantitycalculate(" +
                        count + ",event);' onchange='bdtask_invoice_quantitycalculate(" + count + ",event);' id='product_quantitymeter_" + count + "' class='common_qnt total_qntt_" + count + " form-control text-right'  placeholder='0.00' min='0' tabindex='" + tab4 + "'/></td>";

                } else {
                    row1 = row1 + "<td> <input type='number' style='width:80px' name='product_quantitymeter[]' value='0.0' required='required'  readonly='readonly' onkeyup='bdtask_invoice_quantitycalculate(" +
                        count + ",event);' onchange='bdtask_invoice_quantitycalculate(" + count + ",event);' id='product_quantitymeter_" + count + "' class='common_qnt total_qntt_" + count + " form-control text-right'  placeholder='0.00' min='0' tabindex='" + tab4 + "' /></td>";

                }

                if (data.price == null) {
                    row1 = row1 + "<td><input type='number'  name='product_rate[]' onkeyup='bdtask_invoice_quantitycalculate(" +
                        count + ",event);' onchange='bdtask_invoice_quantitycalculate(" + count + ",event);' tabindex='" + tab5 + "' id='price_item_" + count + "' class='common_rate price_item" +
                        count + " form-control text-right' required placeholder='0.00' min='0' /></td>"

                } else {
                    row1 = row1 + "<td><input type='text' readonly='readonly' name='product_rate[]' onkeyup='bdtask_invoice_quantitycalculate(" +
                        count + ",event);' onchange='bdtask_invoice_quantitycalculate(" + count + ",event);' id='price_item_" + count + "' class='common_rate price_item" +
                        count + " form-control text-right' required placeholder='0.00' min='0' tabindex='" + tab5 + "' /></td>"

                }

                row1 = row1 + "<td><input type='text' name='discount_type[]' onkeyup='bdtask_invoice_quantitycalculate(" +
                    count + ",event);'  id='discount_type_" + count + "' class='form-control'  tabindex='" + tab6 +
                    "' /></td><td><input type='number' class='form-control text-right common_discount'  tabindex='" + tab7 + "' placeholder='0.00' min='0' onkeyup='bdtask_invoice_quantitycalculate(" + count + ",event);'  value='' name='discount[]' id='discount_" + count + "'  ></td><td><input type='text' name='discountvalue[]'  id='discount_value_" + count +
                    "' class='form-control text-right common_discount' placeholder='0.00' min='0' readonly /></td><td class='text-right'><input class='common_total_price total_price form-control text-right' type='text' name='total_price[]' id='total_price_" +
                    count + "' value='0.00' readonly='readonly'/></td>" + tbfild + "<input type='hidden' id='all_discount_" + count +
                    "' class='total_discount dppr' name='discount_amount[]'/><button  style='text-align: right;' class='btn btn-danger' type='button' value='Delete' onclick='deleteRow_invoice(this," + count + ")'><i class='fa fa-close'></i></button></td>",
                    e.innerHTML = row1;
                document.getElementById(t).appendChild(e);

            } else {
                var row2 = "<td '><input type='text' name='product_name' class='form-control' placeholder='Product Name' id='" + "product_name_" + count +
                    "' required tabindex='" + tab1 + "' readonly='readonly'><input type='hidden' class='common_product autocomplete_hidden_value  product_id_" + count +
                    "' name='product_id[]'  id='product_id_" + count + "'/></td><td><input class='form-control text-right common_name unit_" + count +
                    " valid'  id='unit_type_" + count + "' value='None' readonly='' aria-invalid='false' type='text'></td>" +
                    "<td><div style='position: relative; display: inline-block;'><input class='form-control' type='text' style='width:70px' id='searchInput_" + count + "'  placeholder='Employee Id...' onkeyup='handleEmployeeKeyPress(event," + count + ")'  autocomplete='off' /><input type='text' name='employee_id[]' id='employeeId_" + count + "' hidden /><div id='searchResults_" + count + "' style='width: 100%;  max-height: 150px;  overflow-y: auto; border: 1px solid #ddd; position: absolute;  top: 100%;  left: 0;  z-index: 1000;  background-color: #fff;border-radius: 4px;'></div></div></td>" +
                    "<td><input type='number' style='width:70px' name='product_quantity[]' value='-1' required='required' " +
                    "onkeyup='bdtask_invoice_quantitycalculate(" + count + ", event);' " +
                    "oninput='this.value = this.value.replace(/[^0-9]/g, \"\");' " +
                    "step='1' onchange='bdtask_invoice_quantitycalculate(" + count + ", event);' " +
                    "id='total_qntt_" + count + "' class='common_qnt total_qntt_" + count +
                    " form-control text-right' placeholder='0' min='0' tabindex='" + tab3 + "' />" +
                    "</td>"
                if (data.unit == "m") {
                    row2 = row2 + "<td> <input type='number' style='width:80px' name='product_quantitymeter[]' value='-1' required='required' onkeyup='bdtask_invoice_quantitycalculate(" +
                        count + ",event);' onchange='bdtask_invoice_quantitycalculate(" + count + ",event);' id='product_quantitymeter_" + count + "' class='common_qnt total_qntt_" + count + " form-control text-right'  placeholder='0.00' min='0' tabindex='" + tab4 + "'/></td>";

                } else {
                    row2 = row2 + "<td> <input type='number' style='width:80px' name='product_quantitymeter[]' value='0.0' required='required'  readonly='readonly' onkeyup='bdtask_invoice_quantitycalculate(" +
                        count + ",event);' onchange='bdtask_invoice_quantitycalculate(" + count + ",event);' id='product_quantitymeter_" + count + "' class='common_qnt total_qntt_" + count + " form-control text-right'  placeholder='0.00' min='0' tabindex='" + tab4 + "' /></td>";

                }

                if (data.price == null) {
                    row2 = row2 + "<td><input type='text'  name='product_rate[]' onkeyup='bdtask_invoice_quantitycalculate(" +
                        count + ",event);' onchange='bdtask_invoice_quantitycalculate(" + count + ",event);' tabindex='" + tab5 + "' id='price_item_" + count + "' class='common_rate price_item" +
                        count + " form-control text-right' required placeholder='0.00' min='0' /></td>"

                } else {
                    row2 = row2 + "<td><input type='text' readonly='readonly' name='product_rate[]' onkeyup='bdtask_invoice_quantitycalculate(" +
                        count + ",event);' onchange='bdtask_invoice_quantitycalculate(" + count + ",event);' id='price_item_" + count + "' class='common_rate price_item" +
                        count + " form-control text-right' required placeholder='0.00' min='0' tabindex='" + tab5 + "' /></td>"

                }

                row2 = row2 + "<td><input type='text' name='discount_type[]' onkeyup='bdtask_invoice_quantitycalculate(" +
                    count + ",event);'  id='discount_type_" + count + "' class='form-control'  tabindex='" + tab6 +
                    "' /></td><td><input type='number' class='form-control text-right common_discount'  tabindex='" + tab7 + "' placeholder='0.00' min='0' onkeyup='bdtask_invoice_quantitycalculate(" + count + ",event);'  value='' name='discount[]' id='discount_" + count + "'  ></td><td><input type='text' name='discountvalue[]'  id='discount_value_" + count +
                    "' class='form-control text-right common_discount' placeholder='0.00' min='0'  readonly /></td><td class='text-right'><input class='common_total_price total_price form-control text-right' type='text' name='total_price[]' id='total_price_" +
                    count + "' value='0.00' readonly='readonly'/></td>" + tbfild + "<input type='hidden' id='all_discount_" + count +
                    "' class='total_discount dppr' name='discount_amount[]'/><button  style='text-align: right;' class='btn btn-danger' type='button' value='Delete' onclick='deleteRow_invoice(this," + count + ")'><i class='fa fa-close'></i></button></td>",

                    e.innerHTML = row2;
                document.getElementById(t).appendChild(e);

            }




            let element2 = document.getElementById("searchInput_" + count);
            $("#discount_type_" + count).val("Percentage");
            $("#discount_" + count).val(0)
            $("#searchInput_" + count).val($('#emp_name1').val())
            $("#employeeId_" + count).val($('#emp_id').val())

            element2.focus();
            element2.select();
            $("#price_item_" + count).val(data.price);
            $("#product_id_" + count).val(data.product_id);
            $("#unit_type_" + count).val(data.unit);
            $("#product_name_" + count).val(data.product_name);

            var quantity = $("#total_qntt_" + count).val();
            var price_item = $("#price_item_" + count).val();
            var price = quantity * price_item;


            $("#total_price_" + count).val(price);
            $('#barcodeInput').val("");

            var total = 0;
        }
    }

    function deleteRow_invoice(t, count) {
        var tableBody = document.getElementById('normalinvoice').getElementsByTagName('tbody')[0];
        tableBody.innerHTML = '';


    }

    function isElementFrozen(element) {

        // Check if the element is disabled or readonly
        if (element.disabled || element.readOnly) {
            return true;
        }

        // Check if the element is hidden (either display: none or visibility: hidden)
        if (window.getComputedStyle(element).display === 'none' ||
            window.getComputedStyle(element).visibility === 'hidden') {
            return true;
        }

        // Check if the element is focusable
        if (element.tabIndex === -1) {
            return true;
        }

        return false;
    }




    function bdtask_invoice_quantitycalculate(item, e) {
        if (e.key === 'Enter' || e.key === 'ArrowLeft') {
            e.preventDefault();
            count = 1;
            let tcount = 0;
            if (e.key === 'ArrowLeft') {
                tcount = parseInt(e.target.tabIndex) - count
            } else {
                tcount = parseInt(e.target.tabIndex) + count

            }
            let nextElement = document.querySelector('[tabindex="' + tcount + '"]');
            if (e.key === 'ArrowLeft') {
                if (tcount == 3) {
                    let element2 = document.getElementById("searchInput_" + item);
                    element2.focus();
                    element2.select();
                    return;
                }
            }
            if (tcount == 9) {
                if ($("#employeeId_" + item).val() != "") {
                    let disType = $("#discount_type_" + item).val() === "Percentage" ? "P" : "A";
                    let itemIndex = -1;
                    if (disType === "P") {
                        itemIndex = arrItem.findIndex(p => p.productid === $("#product_id_" + item).val() &&
                            p.empId === $("#employeeId_" + item).val() &&
                            p.discount_type == disType &&
                            p.discount == parseFloat($("#discount_" + item).val()).toFixed(2));
                    } else {
                        itemIndex = arrItem.findIndex(p => p.productid === $("#product_id_" + item).val() &&
                            p.empId === $("#employeeId_" + item).val() &&
                            p.discount_type == disType);
                    }



                    if (itemIndex !== -1) {
                        let disval = $("#discount_value_" + item).val() === "" ? "0.0" : $("#discount_value_" + item).val()
                        let qty = parseInt($("#total_qntt_" + item).val()) + parseInt(arrItem[itemIndex].qty)
                        let discount_value = parseFloat(disval) + parseFloat(arrItem[itemIndex].discount_value)
                        let total = parseFloat($("#total_price_" + item).val()) + parseFloat(arrItem[itemIndex].total)
                        let meter = parseFloat($("#product_quantitymeter_" + item).val()) + parseFloat(arrItem[itemIndex].meter)


                        if ($("#discount_type_" + item).val() === "Amount") {
                            arrItem[itemIndex].discount = discount_value === "" ? 0.00 : discount_value.toFixed(2);
                        }

                        arrItem[itemIndex].qty = qty;
                        arrItem[itemIndex].discount_value = discount_value === "" ? 0.00 : discount_value.toFixed(2);
                        arrItem[itemIndex].total = total.toFixed(2);
                        arrItem[itemIndex].meter = meter.toFixed(2);


                    } else {
                        arrItem.push({
                            invoiceId: item,
                            productid: $("#product_id_" + item).val(),
                            productname: $("#product_name_" + item).val(),
                            sb: $("#searchInput_" + item).val(),
                            empId: $("#employeeId_" + item).val(),
                            commisionmode: commisionmode ? "yes" : "no",
                            qty: $("#total_qntt_" + item).val(),
                            meter: $("#product_quantitymeter_" + item).val(),
                            rate: parseFloat($("#price_item_" + item).val()).toFixed(2),
                            discount_type: $("#discount_type_" + item).val() === "Percentage" ? "P" : "A",
                            discount: $("#discount_" + item).val() === "" ? 0.00 : parseFloat($("#discount_" + item).val()).toFixed(2),
                            discount_value: $("#discount_value_" + item).val() === "" ? 0.00 : parseFloat($("#discount_value_" + item).val()).toFixed(2),
                            total: parseFloat($("#total_price_" + item).val()).toFixed(2)

                        });

                    }



                    let element2 = document.getElementById("barcodeInput");
                    element2.focus();
                    element2.select();
                    $("#employeeId_" + item).val("");
                    var tableBody = document.getElementById('normalinvoice').getElementsByTagName('tbody')[0];
                    tableBody.innerHTML = '';
                    updateTable(arrItem)
                } else {
                    alert("Employee Id Missing")
                }
                return;
            }
            var dis_type = $("#discount_type_" + item).val();

            if (tcount == 8) {
                if (dis_type === "p")
                    $("#discount_type_" + item).val("Percentage");
                else if (dis_type === "a")
                    $("#discount_type_" + item).val("Amount");
                else
                    $("#discount_type_" + item).val("Percentage");

            }

            if (nextElement && !isElementFrozen(nextElement)) {
                nextElement.focus(); // Move focus to the next element
                nextElement.select(); // Select the text in the input field
            } else {

                if (e.key === 'ArrowLeft') {
                    count = count + 1;

                    tcount = parseInt(e.target.tabIndex) - count
                } else {
                    count = count + 1;

                    tcount = parseInt(e.target.tabIndex) + count

                }
                let nextElement = document.querySelector('[tabindex="' + tcount + '"]');

                if (nextElement && !isElementFrozen(nextElement)) {
                    nextElement.focus(); // Move focus to the next element
                    nextElement.select(); // Select the text in the input field
                } else {
                    if (e.key === 'ArrowLeft') {
                        count = count + 1;

                        tcount = parseInt(e.target.tabIndex) - count
                    } else {
                        count = count + 1;

                        tcount = parseInt(e.target.tabIndex) + count

                    }
                    let nextElement = document.querySelector('[tabindex="' + tcount + '"]');

                    if (nextElement && !isElementFrozen(nextElement)) {
                        nextElement.focus(); // Move focus to the next element
                        nextElement.select(); // Select the text in the input field
                    }
                }
            }
        } else {
            var quantity = '';
            if ($("#unit_type_" + item).val() == "m") {
                quantity = $("#product_quantitymeter_" + item).val()
            } else {
                quantity = $("#total_qntt_" + item).val()

            }
            var price_item = $("#price_item_" + item).val();
            var invoice_discount = $("#invoice_discount").val();
            var discount = $("#discount_" + item).val();
            var total_discount = $("#total_discount_" + item).val();
            var dis_type = $("#discount_type_" + item).val();




            if (quantity > 0 || discount > 0) {
                if (dis_type === "Percentage") {
                    var price = quantity * price_item;
                    var dis = +(price * discount / 100);

                    var temp = price - dis;
                    $("#discount_type_" + item).val("Percentage");

                    if (commisionmode) {
                        $("#discount_value_" + item).val(dis);
                        $("#all_discount_" + item).val(dis);
                        var temp = price + dis;
                        $("#total_price_" + item).val(temp);
                    } else {
                        $("#discount_value_" + item).val(dis);
                        $("#all_discount_" + item).val(dis);
                        var temp = price - dis;
                        let intValue = Math.floor(temp);
                        let lastDigit = intValue % 10;

                        if (mode == "-") {
                            if (lastDigit == -1) {
                                temp = intValue - 4;

                            } else if (lastDigit == -2) {
                                temp = intValue - 3;

                            } else if (lastDigit == -3) {
                                temp = intValue - 2;

                            } else if (lastDigit == -4) {
                                temp = intValue - 1;

                            } else if (lastDigit == -5) {
                                temp = intValue;

                            } else {
                                temp = Math.round(temp / 10) * 10;
                            }
                        } else {
                            if (lastDigit == 1) {
                                temp = intValue + 4;

                            } else if (lastDigit == 2) {
                                temp = intValue + 3;

                            } else if (lastDigit == 3) {
                                temp = intValue + 2;

                            } else if (lastDigit == 4) {
                                temp = intValue + 1;

                            } else if (lastDigit == 5) {
                                temp = intValue;

                            } else {
                                temp = Math.round(temp / 10) * 10;
                            }
                        }
                        $("#total_price_" + item).val(temp);
                    }



                } else if (dis_type === "Amount") {
                    var price = quantity * price_item;
                    var dis = discount;
                    $("#discount_value_" + item).val(dis);
                    $("#all_discount_" + item).val(dis);


                    if (commisionmode) {
                        if (mode == "-") {
                            var temp = parseFloat(price) - parseFloat(dis);
                            $("#discount_value_" + item).val("-" + dis);
                            $("#total_price_" + item).val(temp);
                            $("#discount_type_" + item).val("Amount");
                        } else {

                            var temp = parseFloat(price) + parseFloat(dis);
                            $("#total_price_" + item).val(temp);
                            $("#discount_type_" + item).val("Amount");
                        }
                    } else {
                        if (mode == "-") {
                            var temp = parseFloat(price) + parseFloat(dis);
                            $("#total_price_" + item).val(temp);
                            $("#discount_value_" + item).val("-" + dis);
                            $("#discount_type_" + item).val("Amount");
                        } else {
                            var temp = parseFloat(price) - parseFloat(dis);
                            $("#total_price_" + item).val(temp);
                            $("#discount_type_" + item).val("Amount");
                        }
                    }




                } else {
                    var total_price = quantity * price_item;
                    $("#total_price_" + item).val(total_price);
                }
            } else {
                var n = quantity * price_item;
                $("#total_price_" + item).val(n)
            }

        }
        var invoice_edit_page = $("#invoice_edit_page").val();
        var preload_pay_view = $("#preload_pay_view").val();
        $("#add_new_payment").empty();
        $("#pay-amount").text('0');
        $("#dueAmmount").val(0);
        if (mode === "-") {
            if ($("#total_qntt_" + item).val() === "")
                $("#total_qntt_" + item).val("-")
            else if (!$("#total_qntt_" + item).val().includes("-")) {
                $("#total_qntt_" + item).val("-" + $("#total_qntt_" + item).val())
            }

            if ($("#unit_type_" + item).val() == "m") {
                if ($("#product_quantitymeter_" + item).val() === "") {
                    $("#product_quantitymeter_" + item).val("-")
                } else if (!$("#product_quantitymeter_" + item).val().includes("-")) {
                    $("#product_quantitymeter_" + item).val("-" + $("#product_quantitymeter_" + item).val())

                }
            }

        }
    }
</script>

<script>
    // Sample data to search through
    let employees = [

    ];

    let currentIndex = -1;

    $(document).ready(function() {
        getAllEmployees();
    });

    function getAllEmployees() {
        $.ajax({
            type: "post",
            url: $('#base_url').val() + 'invoice/invoice/getAllEmployees',
            data: {
                chequeno: $('#chequeno').val(),
                effectivedate: $('#effectivedate').val(),
                chequereceiveddate: $('#chequereceiveddate').val(),
                amount: $('#amount').val()
            },
            success: function(data1) {
                employees = JSON.parse(data1);


            }
        });
    }

    function handleEmployeeKeyPress(event, count) {

        const inputElement = document.getElementById('searchInput_' + count);
        const query = inputElement.value;
        const results = employees.filter(employee => employee.first_name.toLowerCase().includes(query));


        const isLetter = /^[a-zA-Z]$/.test(event.key);

        if (isLetter) {
            inputElement.value = query.slice(0, -1);
            return

        }



        if (event.key === 'ArrowDown') {
            $("#employeeId_" + count).val("");
            // Move down in the list
            if (currentIndex < results.length - 1) {
                currentIndex++;
                highlightItem(currentIndex);
            }
        } else if (event.key === 'ArrowUp') {
            $("#employeeId_" + count).val("");
            // Move up in the list
            if (currentIndex > 0) {
                currentIndex--;
                highlightItem(currentIndex);
            }
        } else if (event.key === 'Enter') {
            if (document.getElementById('employeeId_' + count).value != "") {
                let element2 = document.getElementById('total_qntt_' + count)
                element2.focus();
                element2.select();

            }
            // Select the highlighted item
            if (currentIndex >= 0 && currentIndex < results.length) {
                // Place the selected item in the input box
                document.getElementById('searchInput_' + count).value = results[currentIndex].first_name + " ";
                $("#emp_name").val(results[currentIndex].last_name)
                $("#emp_name1").val(results[currentIndex].first_name)

                $("#emp_id").val(results[currentIndex].id)
                document.getElementById('searchInput_' + count).select()
                document.getElementById('employeeId_' + count).value = results[currentIndex].id;
                // Clear the search results
                clearResults(count);
            }


        } else if (event.key === "Backspace") {
            $("#employeeId_" + count).val("");
            document.getElementById('employeeId_' + count).value = "";

        } else {
            // For other keys, just filter and show results
            $("#employeeId_" + count).val("");

            currentIndex = -1;
            displayResults(results, count);

        }



    }

    function handleEditEmployee() {
        if (document.getElementById('employeeId_' + 100).value != "") {
            let itemIndex = invoice_id == 0 ? arrItem.findIndex(item => item.i == invoice_i) : arrItem.findIndex(item => item.invoiceId == invoice_id);
            if (itemIndex !== -1) {
                arrItem[itemIndex].sb = document.getElementById('searchInput_' + 100).value;
                arrItem[itemIndex].empId = document.getElementById('employeeId_' + 100).value;
            }
            var tableBody = document.getElementById('normalinvoice').getElementsByTagName('tbody')[0];
            tableBody.innerHTML = '';
            updateTable(arrItem);
            $("#editInvoiceModel").modal('hide');

        } else {
            alert("Please enter the employee id")
        }
    }

    function handleEmployeeEditKeyPress(event, count) {
        const query = document.getElementById('searchInput_' + count).value.toLowerCase();
        const results = employees.filter(employee => employee.first_name.toLowerCase().includes(query));

        if (event.key === 'ArrowDown') {
            // Move down in the list
            if (currentIndex < results.length - 1) {
                currentIndex++;
                highlightItem(currentIndex);
            }
        } else if (event.key === 'ArrowUp') {
            // Move up in the list
            if (currentIndex > 0) {
                currentIndex--;
                highlightItem(currentIndex);
            }
        } else if (event.code === 'Space') {

            if (currentIndex >= 0 && currentIndex < results.length) {
                // Place the selected item in the input box
                document.getElementById('searchInput_' + count).value = results[currentIndex].first_name + " ";
                // $("#emp_name").val(results[currentIndex].last_name)

                document.getElementById('searchInput_' + count).select()
                document.getElementById('employeeId_' + count).value = results[currentIndex].id;
                // Clear the search results
                clearResults(count);
            }
        } else if (event.key === 'Enter') {

            if (document.getElementById('employeeId_' + count).value != "") {
                let itemIndex = invoice_id == 0 ? arrItem.findIndex(item => item.i == invoice_i) : arrItem.findIndex(item => item.invoiceId == invoice_id);
                if (itemIndex !== -1) {
                    arrItem[itemIndex].sb = document.getElementById('searchInput_' + count).value;
                    arrItem[itemIndex].empId = document.getElementById('employeeId_' + count).value;
                }
                var tableBody = document.getElementById('normalinvoice').getElementsByTagName('tbody')[0];
                tableBody.innerHTML = '';
                updateTable(arrItem);
                $("#editInvoiceModel").modal('hide');

            }
            // Select the highlighted item
            if (currentIndex >= 0 && currentIndex < results.length) {
                // Place the selected item in the input box
                document.getElementById('searchInput_' + count).value = results[currentIndex].first_name + " ";
                document.getElementById('searchInput_' + count).select()
                document.getElementById('employeeId_' + count).value = results[currentIndex].id;
                // Clear the search results
                clearResults(count);
            }
        } else if (event.key === "Backspace") {
            document.getElementById('employeeId_' + count).value = "";

        } else if (event.key === "Tab") {
            document.getElementById('employeeId_' + count).value = "";

        } else {
            // For other keys, just filter and show results
            currentIndex = -1;
            displayResults(results, count);

        }



    }

    function displayResults(results, count) {
        const searchResultsDiv = document.getElementById('searchResults_' + count);
        searchResultsDiv.innerHTML = ''; // Clear previous results
        if (results.length === 0) {
            searchResultsDiv.innerHTML = '<p>No results found</p>';
        } else {
            results.forEach((item, index) => {
                const resultItem = document.createElement('div');
                resultItem.classList.add('resultItem');
                resultItem.textContent = item.first_name + " ";
                $("#emp_name").val(item.last_name)
                $("#emp_name1").val(item.first_name)

                $("#emp_id").val(item.id)


                resultItem.setAttribute('data-index', index);
                searchResultsDiv.appendChild(resultItem);
            });
        }
        currentIndex = 0
        highlightItem(0);

    }

    function highlightItem(index) {
        const items = document.querySelectorAll('.resultItem');
        items.forEach((item, idx) => {
            if (idx === index) {
                item.classList.add('highlight');
            } else {
                item.classList.remove('highlight');
            }
        });
    }

    function printRawHtml(view) {
        printJS({
            printable: view,
            type: 'raw-html',
            onPrintDialogClose: function() {
                printJobComplete(); // Call the function you need to execute after the print dialog closes
                // location.reload(); // Reload the page
            }
        });



    }

    function printJobComplete() {
        // location.reload()

    }

    function clearResults(count) {
        // Clear the results div
        document.getElementById('searchResults_' + count).innerHTML = '';
    }

    // function viewResult() {
    $(document).ready(function() {
        "use strict";

        dataTable();
        dataTableForSale();


    });

    $('#btn-filter').click(function() {
        dataTable();

    });

    function dataTable() {
        $('#InvList2').DataTable().destroy();
        var csrf_test_name = $('[name="csrf_test_name"]').val();
        var base_url = $('#base_url').val();
        var total_invoice = $("#total_invoice").val();
        var currency = $("#currency").val();
        return $('#InvList2').DataTable({
            responsive: true,

            "aaSorting": [
                [0, "desc"]
            ],
            "columnDefs": [{
                    "bSortable": false,
                    "aTargets": [0, 2, 3, 4]
                },

            ],
            'processing': true,
            'serverSide': true,


            'lengthMenu': [
                [10, 25, 50, 100, 250, 500, 1000],
                [10, 25, 50, 100, 250, 500, 1000]
            ],
            'serverMethod': 'post',
            'ajax': {
                'url': base_url + 'invoice/invoice/CheckInvoiceListemp',
                "data": function(data) {
                    data.fromdate = $('#from_date').val();
                    data.todate = $('#to_date').val();
                    data.empid = $('#empid').val();
                    data.csrf_test_name = csrf_test_name;
                }
            },
            'columns': [{
                    data: 'invoice'
                },
                {
                    data: 'salesman'
                },
                {
                    data: 'final_date'
                },
                {
                    data: 'total_amount',
                    class: "total_sale text-right",
                    render: $.fn.dataTable.render.number(',', '.', 2, '', ' Rs')
                },
                {
                    data: 'button'
                },
            ],


        });


    }

    let invoice_ID = 0;

    function editInvoice(invoiceId, type) {
        $.ajax({
            type: "post",
            url: $('#base_url').val() + 'invoice/invoice/get_salesbyinvoiceidemp',
            data: {
                invoice_id: invoiceId,

            },
            success: function(data1) {
                datas = JSON.parse(data1);
                arrItem = datas;
                invoice_ID = datas[0].invoice_id
                $("#barcodeInput").prop("disabled", true);




                updateTable(datas)
                $("#exampleModal").modal('hide');

            }
        });
    }

    function reprintInvoice(invoiceId, type) {
        if (confirm("Do you want to reprint this record")) {
            $.ajax({
                type: "post",
                url: $('#base_url').val() + 'invoice/invoice/reprintgod',
                data: {
                    invoice_id: invoiceId,

                },
                success: function(data1) {
                    datas = JSON.parse(data1);
                    printRawHtml(datas.details);

                }
            });
        }

    }

    function checkUser(event) {
        if (event.key === 'Enter') {
            if ($("#password").val() == "cashier123") {

                let item = invoice_id == 0 ? arrItem.find(item => item.i == invoice_i) : arrItem.find(item => item.invoiceId == invoice_id);




                $("#searchInput_100").val(item.sb);
                $("#passwordmodel").modal('hide');
                $("#password").val("");
                $("#editInvoiceModel").modal('show');


                $('#editInvoiceModel').on('shown.bs.modal', function() {
                    let element2 = document.getElementById("searchInput_100");


                    document.getElementById('employeeId_100').value = "";

                    element2.focus();
                    element2.select();
                });
            } else {
                alert("Invalid password")
            }


        }


    }


    $('#btn-gettotalsale').click(function() {
        dataTableForSale();

    });



    function dataTableForSale() {
        $('#InvListTotalSales').DataTable().destroy();
        var csrf_test_name = $('[name="csrf_test_name"]').val();
        var base_url = $('#base_url').val();
        var total_invoice = $("#total_invoice").val();
        var currency = $("#currency").val();


        return $.ajax({
            type: "POST",
            url: base_url + 'invoice/invoice/getInvoiceSummary', // Call to get summary
            data: {
                csrf_test_name: csrf_test_name,
                fromdate: $('#from_date1').val(),
                todate: $('#to_date1').val(),
            },
            success: function(summary) {
                var decodedSummary = JSON.parse(summary);
                var invoiceCount = decodedSummary.invoice_count || 0;
                var totalAmount = parseFloat(decodedSummary.total_amount) || 0.00;
                $('#InvListTotalSales').DataTable({
                    responsive: true,
                    searching: false,
                    data: [{
                        "total_invoice_count": invoiceCount,
                        "total_amount": totalAmount
                    }],
                    columns: [{
                            data: 'total_invoice_count',
                            render: function(data) {
                                return data;
                            }
                        },
                        {
                            data: 'total_amount',
                            class: "text-right",
                            render: $.fn.dataTable.render.number(',', '.', 2, '', ' Rs') // Format the amount
                        }
                    ]
                });
            },
            error: function() {
                console.error('Could not fetch summary data.');
            }
        });

    }
    // }
</script>