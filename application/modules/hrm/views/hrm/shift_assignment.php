<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="background-color:#B2BEB5">
                <div class="panel-title">
                    <h4><?php echo $title ?> </h4>
                </div>
            </div>

            <div class="panel-body" style="background-color:#B2BEB5">


                <div class="form-group row">
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label">Select Year<i class="text-danger"> * </i>:</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="year" required name="year" tabindex="4" onchange="monthAndYearSelector()">
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label">Select Month<i class="text-danger"> * </i>:</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="month" required name="month" tabindex="4" onchange="monthAndYearSelector()">
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
                </div>


                <div class="form-group row">
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label">Select Date(s)<i class="text-danger"> * </i>:</label>
                    <div class="col-sm-4">
                        <input type="text" id="multiDatePicker" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label">Shift<i class="text-danger"> * </i>:</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="shift" required name="shift" tabindex="4">
                            <option value=""></option>
                            <?php if ($shifts) { ?>
                                <?php foreach ($shifts as $shift) { ?>
                                    <?php if ($shift['id'] == 1) { ?>
                                        <option value="<?php echo $shift['id'] ?>">
                                            <?php echo "No Shift" ?></option>
                                    <?php } else { ?>

                                        <option value="<?php echo $shift['id'] ?>">
                                            <?php echo date("h:i A", strtotime($shift['stime'])) . ' - ' . date("h:i A", strtotime($shift['etime'])) ?></option>

                            <?php }
                                }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label">Employee(s)<i class="text-danger"> * </i>:</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="employee" multiple required name="employee" tabindex="4">
                            <option value=""></option>
                            <?php if ($employees) { ?>
                                <?php foreach ($employees as $employee) { ?>
                                    <option value="<?php echo $employee['id'] ?>">
                                        <?php echo $employee['first_name'] . '-' . $employee['last_name'] ?></option>

                            <?php }
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="calenderevent" class="col-sm-2 text-right col-form-label">Select OT<i class="text-danger"> * </i>:</label>
                    <div class="col-sm-4">
                        <select class="form-control" id="ot" required name="ot" tabindex="4">
                            <option value=""></option>
                            <option value="1">Single OT</option>
                            <option value="2">Double OT</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">

                    <div class="col-sm-6 text-right">


                        <button class="btn btn-success " onclick="save()">
                            <?php echo (empty($calenderevent->id) ? display('save') : display('update')) ?></button>



                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function save() {
        let employee = document.getElementById("employee");
        let employees = Array.from(employee.selectedOptions).map(option => option.value);
        let dates = document.getElementById("multiDatePicker").value.split(",");

        if (document.getElementById("year").value == "") {
            alert("Please select year")
            return;
        }

        if (document.getElementById("month").value == "") {
            alert("Please select month")
            return;
        }

        if (dates[0] == "") {
            alert("Please select any date(s)")
            return;
        }

        if (document.getElementById("shift").value == "") {
            alert("Please select shift")
            return;
        }



        if (employees.length == 0) {
            alert("Please select any employee(s)")
            return;
        }


        if (document.getElementById("ot").value == "") {
            alert("Please select OT")
            return;
        }



        $.ajax({
            type: "post",
            url: $('#base_url').val() + 'hrm/hrm/save_shift',
            data: {
                shift: document.getElementById("shift").value,
                year: document.getElementById("year").value,
                month: document.getElementById("month").value,
                ot: document.getElementById("ot").value,
                employees: employees,
                dates: dates

            },
            success: function(data1) {
                alert("Saved Successfully")
                location.reload();

            }
        });




    }

    let datePicker = flatpickr("#multiDatePicker", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        minDate: "2025-03-01",
        maxDate: "2025-03-31",
        defaultDate: ["2025-03-10"],
        onMonthChange: function(selectedDates, dateStr, instance) {
            instance.changeMonth(2);
        },
        disableMobile: "true"
    });

    populateYearDropdown();

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

        monthAndYearSelector();
    }

    function monthAndYearSelector() {

        let month = document.getElementById("month").value;
        let year = document.getElementById("year").value;

        let firstDay = `${year}-${month}-01`;

        let lastDay = `${year}-${month}-${ new Date(year, month, 0).getDate()}`;

        // Update Flatpickr settings
        datePicker.set("minDate", firstDay);
        datePicker.set("maxDate", lastDay);
        datePicker.clear();
        datePicker.jumpToDate(firstDay);
    }
</script>