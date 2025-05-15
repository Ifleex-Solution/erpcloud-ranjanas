<!-- Sales report -->
<style>
    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none;
    }
</style>
<div class="row">
    <div class="col-sm-15">
        <div class="panel panel-default" style="background-color:#B2BEB5">
            <h3 style="margin-left: 30px;">OT Report</h3>
            <br />
            <div class="panel-body" style="margin-left: 120px;">


                <?php
                date_default_timezone_set('Asia/Colombo');

                $today = date('Y-m-d');
                ?>

                <div class="form-group" style="display: flex; gap: 20px;">
                    <div>
                        <label for="from_date">Year: </label>
                        <select class="form-control" id="year" required name="year" tabindex="4">
                        </select>
                    </div>
                    <div id="to_date_container">
                        <div>
                            <label for="to_date">Month:</label>
                            <select class="form-control" id="month" required name="month" tabindex="4">
                                <option value=""></option>
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
                </div>


                <div class="form-group">
                    <?php if ($this->permission1->method('todays_sales_report', 'view')->access() && !$this->session->userdata('isAdmin')) { ?>
                        <label for="empid" class="mr-2 mb-0">Emp Id</label>
                        <div class="input-group mr-4" style="width: 200px;">
                            <select tabindex="4" class="form-control" name="empid" id="empid" style="width: 100%;">
                                <option value="">Select Employee ID</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="All">All</option>
                            </select>
                        </div>
                    <?php } else { ?>
                        <input type="hidden" tabindex="4" class="form-control" name="empid" id="empid" value="123">
                    <?php } ?>
                </div>


                <button type="button" id="btn-filter" class="btn btn-success" onclick="search()">
                    Generate Report
                </button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="baseUrl2" id="baseUrl2" class="baseUrl" value="<?php echo base_url(); ?>" />

<script src="<?php echo base_url('my-assets/js/admin_js/sales_report.js') ?>" type="text/javascript"></script>
<script>
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

        let dates = getStartAndEndDate(document.getElementById("year").value, document.getElementById("month").value);

        let startdate = new Date(dates.start);
        startdate.setDate(startdate.getDate() + 1);

        let enddate = new Date(dates.end);
        enddate.setDate(enddate.getDate() + 1);

        let diffTime = enddate - startdate;
        let diffDays = diffTime / (1000 * 60 * 60 * 24);
        const month = document.getElementById("month");
        const monthText = month.options[month.selectedIndex].text;

        $.ajax({
            url: $('#base_url').val() + 'hrm/hrm/otreport_data',
            type: 'POST',
            data: {
                month: document.getElementById("month").value,
                year: document.getElementById("year").value,
                sdate: startdate.toISOString().split('T')[0],
                edate: enddate.toISOString().split('T')[0]
            },
            success: function(response) {
                otlist = [];
                datas = JSON.parse(response);
                datas.employees.forEach((employee) => {
                    ots = []
                    for (let i = 0; i <= diffDays; i++) {
                        let date = i + 1;
                        date = date < 9 ? document.getElementById("year").value + "-" + document.getElementById("month").value + "-" + "0" + date : document.getElementById("year").value + "-" + document.getElementById("month").value + "-" + date;
                        let findotemployee = datas.ot_data.find(ot =>
                            ot.date === date && ot.employee == employee.id);
                        if (findotemployee) {

                            let findatemployee = datas.attendance_data.find(at =>
                                at.date === date && at.employee_zktec_id == employee.employee_zktec_id);

                            if (findatemployee) {
                                let shifttime = getTimeDiffstring(findotemployee.stime, findotemployee.etime);
                                let otTime = getTimeDiff(shifttime, findatemployee.staytime);


                                if (otTime.hours >= 0) {
                                    if (otTime.minutes < 30) {
                                        otTime.minutes = 0;
                                    } else if (otTime.minutes >= 30) {
                                        otTime.minutes = 5;
                                    }
                                } else {
                                    otTime.hours = 0;
                                    otTime.minutes = 0;
                                }

                                let ottime =
                                    ots.push({
                                        date: i + 1,
                                        ot: findotemployee.ot,
                                        staytime: findatemployee.staytime,
                                        hours: otTime.hours,
                                        minutes: otTime.minutes,


                                    });

                            } else {
                                ots.push({
                                    date: i + 1,
                                    ot: findotemployee.ot,
                                    staytime: "",
                                    hours: 0,
                                    minutes: 0,
                                });
                            }


                        } else {
                            ots.push({
                                date: i + 1,
                                ot: "",
                                staytime: "",
                                hours: 0,
                                minutes: 0,
                            });
                        }
                    }
                  



                    let ot = {
                        first_name: employee.first_name,
                        last_name: employee.last_name,
                        basicsalary: employee.basicsalary,
                        otdays: ots,
                        totaldays: ots.length,
                        year: document.getElementById("year").value,
                        month: monthText,
                    }

                    otlist.push(ot)
                });

                // console.log(otlist)


                if (otlist.length == 0) {

                    alert("There is no data available to generate the OT Report")

                } else {

                    const formData = new FormData();
                    formData.append("otlist", JSON.stringify(otlist));




                    fetch($('#base_url').val() + "report/report/generate_otreport", {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest' // Optional: indicates it's an AJAX call
                            },
                            body: formData // Send JSON directly in the body
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("Failed to download report");
                            }
                            return response.blob();
                        })
                        .then(blob => {
                            const url = window.URL.createObjectURL(blob);
                            const link = document.createElement('a');
                            link.href = url;
                            link.download = "OTReport_"+monthText+"_"+document.getElementById("year").value+".xlsx";
                            document.body.appendChild(link);
                            link.click();
                            link.remove();
                            window.URL.revokeObjectURL(url);
                        })
                        .catch(error => {
                            console.error("Download error:", error);
                        });
                }
            },
            error: function(error) {
                console.log(error);
            }
        });



    }

    function getTimeDiff(stime, etime) {

        let start = new Date("1970-01-01T" + stime);
        let end = new Date("1970-01-01T" + etime);



        let diffMs = end - start;

        let diffHrs = Math.floor(diffMs / (1000 * 60 * 60)); // hours
        let diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60)); // minutes

        return {
            hours: diffHrs,
            minutes: diffMins
        };
    }

    function getTimeDiffstring(stime, etime) {
        let start = new Date("1970-01-01T" + stime);
        let end = new Date("1970-01-01T" + etime);

        let diffMs = end - start;

        let diffHrs = Math.floor(diffMs / (1000 * 60 * 60));
        let diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
        let diffSecs = Math.floor((diffMs % (1000 * 60)) / 1000);

        // Pad with leading zeros if needed
        let h = String(diffHrs).padStart(2, '0');
        let m = String(diffMins).padStart(2, '0');
        let s = String(diffSecs).padStart(2, '0');

        return `${h}:${m}:${s}`;
    }


    function getStartAndEndDate(year, month) {
        month = parseInt(month);
        const startDate = new Date(year, month - 1, 1);
        const endDate = new Date(year, month, 0);
        return {
            start: startDate.toISOString().split('T')[0],
            end: endDate.toISOString().split('T')[0]
        };
    }
</script>