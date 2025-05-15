<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="background-color:#B2BEB5">
                <div class="panel-title">
                    <h4>Timesheet Report</h4>
                </div>
            </div>
            <div class="panel-body" style="background-color:#B2BEB5">
                <div class="row">
                    <table width="100%" class="datatable table  table-bordered ">
                        <caption>
                            <center>
                                <h2><?php echo display('Date') . ' : ' . $date ?></h2>
                            </center>
                        </caption>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th><?php echo display('employee_name') ?></th>
                                <th>Date</th>
                                <th><?php echo display('check_in') ?></th>
                                <th> Check_out</th>
                                <th>Break In - Break Out</th>
                                <th>Stay Time (h) </th>
                                <th>Break Time (h) </th>
                                <th>Working Time (h)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($records)) { ?>
                                <?php $sl = 1; ?>
                                <?php foreach ($records as $index => $row): ?>
                                    <tr>
                                        <td><?= $row['employee_id'] ?></td>
                                        <td><?= $row['employee_name'] ?></td>
                                        <td><?= date("Y-m-d", strtotime($row['date'])) ?></td>
                                        <td><?= $row['check_in'] ?></td>
                                        <td><?= $row['check_out'] ?></td>
                                        <td><?= $row['break_in_out'] ?></td>
                                        <td><?= $row['working_hours'] ?></td>
                                        <td><?= $row['break_hours'] ?></td>
                                        <td><?= $row['stay_time'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>