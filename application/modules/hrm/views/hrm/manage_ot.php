<input type="hidden" name="baseUrl" class="baseUrl" value="<?php echo base_url(); ?>" />

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#B2BEB5">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Update OT</h4>
            </div>
            <div class="modal-body" style="background-color:#B2BEB5">

                <div class="form-group row">
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label">Shift<i class="text-danger"> * </i>:</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="shift_insert" required name="shift" tabindex="4">

                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label">Employee(s)<i class="text-danger"> * </i>:</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="employee_insert" multiple required name="employee" tabindex="4">

                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label">Select OT<i class="text-danger"> * </i>:</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="ot_insert" required name="ot" tabindex="4">

                        </select>
                    </div>
                </div>
                <div class="form-group row">

                    <div class="col-sm-12 text-right">


                        <button class="btn btn-success " onclick="save()">
                            <?php echo  display('update') ?></button>



                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="background-color:#B2BEB5">
                <div class="panel-title">
                    <h4><?php echo display('manage_ot') ?></h4>
                </div>
            </div>
            <div class="panel-body" style="background-color:#B2BEB5">
                <div class="form-group row">
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label">Select Year<i class="text-danger"> * </i>:</label>
                    <div class="col-sm-2">
                        <select class="form-control" id="year" required name="year" tabindex="4">
                        </select>
                    </div>
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label">Select Month<i class="text-danger"> * </i>:</label>
                    <div class="col-sm-2">
                        <select class="form-control" id="month" required name="month" tabindex="4">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <button class="btn btn-success " onclick="search()">
                        <?php echo  display('search') ?></button>



                </div>

                <div class="form-group row">

                </div>
                <div class="table-responsive">
                    <table id="otTable" class="table table-bordered datatable" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th> Employee(s)</th>
                                <th>Shift Time</th>
                                <th>OT</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
echo "<script>";
echo "let shifts=" . json_encode($shifts) . ";";
echo "let employees=" . json_encode($employees) . ";";
echo "</script>";
?>
<script>
    let otDate = "";
    let year = "";
    let month = "";
    let oldot = "";
    let oldshift = "";
    let ots = [];
    let otlist = [];

    $(document).ready(function() {
        populateYearDropdown();

    });


    function populateYearDropdown() {
        let currentYear = new Date().getFullYear();

        var $yearDropdown = $('#year');
        $yearDropdown.empty();
        $yearDropdown.append('<option value="" disabled selected>Select Year</option>');
        let years = [currentYear - 2, currentYear - 1, currentYear, currentYear + 1, currentYear + 2];
        $yearDropdown.append('<option value="' + years[0] + '">' + years[0] + '</option>');
        $yearDropdown.append('<option value="' + years[1] + '">' + years[1] + '</option>');
        $yearDropdown.append('<option value="' + years[2] + '">' + years[2] + '</option>');
        $yearDropdown.append('<option value="' + years[3] + '">' + years[3] + '</option>');
        $yearDropdown.append('<option value="' + years[4] + '">' + years[4] + '</option>');
        $yearDropdown.val(currentYear)

        var $monthDropdown = $('#month');
        let currentMonth = new Date().getMonth() + 1
        if (currentMonth < 10) {
            $monthDropdown.val("0" + currentMonth)
        } else {
            $monthDropdown.val(currentMonth)
        }

        search();

    }



    function search() {
        if (document.getElementById("year").value == "") {
            alert("Please select year")
            return;
        }

        if (document.getElementById("month").value == "") {
            alert("Please select month")
            return;
        }

        $.ajax({
            url: $('#base_url').val() + 'hrm/hrm/manage_ot',
            type: 'POST',
            data: {
                month: document.getElementById("month").value,
                year: document.getElementById("year").value
            },
            success: function(response) {
                ots = [];
                otlist = [];
                month = document.getElementById("month").value;
                year = document.getElementById("year").value;
                ots = JSON.parse(response);
                if ($.fn.DataTable.isDataTable("#otTable")) {
                    $("#otTable").DataTable().clear().destroy(); // Destroy existing table
                }
                for (let i = 0; i < ots.length; i++) {
                    let exists = otlist.find(ot => ot.date === ots[i]['date'] &&
                        ot.shift === ots[i]['shift'] && ot.ot === ots[i]['ot']);
                    if (exists) {
                        exists.name = exists.name + "<br/>" + ots[i]['first_name'] + "-" + ots[i]['last_name'];
                    } else {
                        if( ots[i]['stime']=="00:00:00"){
                            otlist.push({
                            date: ots[i]['date'],
                            name: ots[i]['first_name'] + "-" + ots[i]['last_name'],
                            shiftTime: "No Shift",
                            ot: ots[i]['ot'],
                            otType: ots[i]['ot'] == 1 ? "Single OT" : "Double OT",
                            shift: ots[i]['shift']

                        });
                        }else{ 
                            otlist.push({
                            date: ots[i]['date'],
                            name: ots[i]['first_name'] + "-" + ots[i]['last_name'],
                            shiftTime: ots[i]['stime'] + "-" + ots[i]['etime'],
                            ot: ots[i]['ot'],
                            otType: ots[i]['ot'] == 1 ? "Single OT" : "Double OT",
                            shift: ots[i]['shift']

                        });

                        }
                       
                    }
                }


                $('#otTable').DataTable({
                    data: otlist, // Bind otlist data
                    dom: "'<'col-sm-4'l><'col-sm-4 text-center'><'col-sm-4'>Bfrtip",
                    pageLength: 100, 
                    buttons: [{
                        extend: "copy",
                        exportOptions: {
                            columns: [0, 1, 2, 3] //Your Colume value those you want
                        },
                        className: "btn-sm prints"
                    }, {
                        extend: "csv",
                        title: "Manage OT",
                        exportOptions: {
                            columns: [0, 1, 2, 3] //Your Colume value those you want print
                        },
                        className: "btn-sm prints"
                    }, {
                        extend: "excel",
                        exportOptions: {
                            columns: [0, 1, 2, 3] //Your Colume value those you want print
                        },
                        title: "Manage OT",
                        className: "btn-sm prints"
                    }, {
                        extend: "pdf",
                        exportOptions: {
                            columns: [0, 1, 2, 3] //Your Colume value those you want print
                        },
                        title: "Manage OT",
                        className: "btn-sm prints"
                    }, {
                        extend: "print",
                        exportOptions: {
                            columns: [0, 1, 2, 3] //Your Colume value those you want print
                        },
                        title: "<center>ProductList</center>",
                        className: "btn-sm prints"
                    }],
                    columns: [{
                            data: 'date'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'shiftTime'
                        },
                        {
                            data: 'otType'
                        },
                        {
                            render: function(data, type, row) {
                                return '<button class="btn btn-primary" onclick="handleTableClick(\'' + row.date + '\',\'' + row.shift + '\',\'' + row.ot + '\')"><i class="fa fa-pencil" aria-hidden="true"></i></button>' +
                                    '<button style="margin-left:10px" class="btn btn-danger" onclick="deleteClick(\'' + row.date + '\',\'' + row.shift + '\',\'' + row.ot + '\')"><i class="fa fa-trash" aria-hidden="true"></i></button>';

                            }
                        }
                    ]
                });

            },
            error: function(error) {
                console.log(error);
            }
        });



    }

    function deleteClick(date, shift, ot1) {

        if (confirm("Are you sure?")) {
            $.ajax({
                type: "post",
                url: $('#base_url').val() + 'hrm/hrm/delete_shift',
                data: {
                    shift: shift,
                    ot: ot1,
                    date: date
                },
                success: function(data1) {
                    alert("Deleteed Successfully")
                    location.reload();

                }
            });
        }




    }

    function handleTableClick(date, shift, ot1) {


        $("#exampleModal").modal('show');
        let exists = ots.filter(ot => ot.date === date && ot.shift === shift && ot.ot == ot1);

        otDate = date;
        oldot = ot1;
        oldshift = shift;

        var $shiftDropdown = $('#shift_insert');
        $shiftDropdown.empty();
        $shiftDropdown.append('<option value="" disabled selected>Select shift</option>');
        $.each(shifts, function(index, shift) {
            if(shift.id==1){
                $shiftDropdown.append('<option value="' + shift.id + '">No Shift</option>');

            }else{
                $shiftDropdown.append('<option value="' + shift.id + '">' + shift.stime + "-" + shift.etime + '</option>');

            }
        });
        $shiftDropdown.val(shift);



        var $employeeDropdown = $('#employee_insert');
        $employeeDropdown.empty();
        $employeeDropdown.append('<option value="" disabled selected>Select Employee</option>');
        $.each(employees, function(index, employee) {
            $employeeDropdown.append('<option value="' + employee.id + '">' + employee.first_name + "-" + employee.last_name + '</option>');
        });
        $employeeDropdown.val(exists.map(exist => exist.employee));


        var $otDropdown = $('#ot_insert');
        $otDropdown.empty();
        $otDropdown.append('<option value="" disabled selected>Select OT</option>');
        $otDropdown.append('<option value="1">Single OT</option>');
        $otDropdown.append('<option value="2">Double OT</option>');
        $otDropdown.val(ot1);



    }

    function save() {
        let employee = document.getElementById("employee_insert");
        let employees = Array.from(employee.selectedOptions).map(option => option.value);
        if (document.getElementById("shift_insert").value == "") {
            alert("Please select shift")
            return;
        }



        if (employees.length == 0) {
            alert("Please select any employee(s)")
            return;
        }


        if (document.getElementById("ot_insert").value == "") {
            alert("Please select OT")
            return;
        }

        console.log(document.getElementById("ot_insert").value)



        $.ajax({
            type: "post",
            url: $('#base_url').val() + 'hrm/hrm/update_shift',
            data: {
                shift: document.getElementById("shift_insert").value,
                year: year,
                month: month,
                ot: document.getElementById("ot_insert").value,
                employees: employees,
                date: otDate,
                oldot: oldot,
                oldshift: oldshift
            },
            success: function(data1) {
                alert("Updated Successfully")
                location.reload();

            }
        });




    }
</script>