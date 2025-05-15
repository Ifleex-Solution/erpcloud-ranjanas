<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="background-color:#B2BEB5">
                <!-- Back Button -->

                <div class="panel-title">
                    <h4><?php echo display('datewise_report'); ?></h4>
                </div>

            </div>
            <div class="panel-body" style="background-color:#B2BEB5">
                <div class="row">
                    <table width="100%" class="datatable table table-bordered " style="background-color:#B2BEB5">
                        <caption>

                            <center>
                                <?php echo display('from') . ' -' . $from_date . '&nbsp;&nbsp;&nbsp;' .  ' ' . display('to') . ' -' . $to_date ?>
                            </center>
                        </caption>
                        <thead>
                            <tr>
                                <th><?php echo display('Sl') ?></th>
                                <th><?php echo display('employee_name') ?></th>
                                <th><?php echo display('date') ?></th>
                                <th><?php echo display('break_in') ?></th>
                                <th><?php echo display('break_out') ?></th>
                                <th>Break Time (h) </th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($records)) { ?>
                                <?php $sl = 1; ?>
                                <?php foreach ($records as $record) { ?>
                                    <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                                        <td><?php echo $sl; ?></td>
                                        <td><?php echo html_escape($record['first_name']) . ' ' . html_escape($record['last_name']); ?>
                                        </td>
                                        <td><?php echo html_escape($record['date']); ?></td>
                                        <td><?php
                                            if (!empty($record['break_in'])) {
                                                echo html_escape(date("h:i A", strtotime($record['break_in'])));
                                            } else {
                                                echo '';
                                            }
                                            ?></td>
                                        <td><?php
                                            if (!empty($record['break_out'])) {
                                                echo html_escape(date("h:i A", strtotime($record['break_out'])));
                                            } else {
                                                echo '';
                                            }
                                            ?></td>

                                        <td>
                                            <?php
                                            if (!empty($record['break_out'])) {
                                                $break_hour = strtotime($record['break_out']) - strtotime($record['break_in']);
                                                $b_hours = floor($break_hour / 3600);
                                                $b_minutes = floor(($break_hour % 3600) / 60);
                                                $b_secounds = $break_hour % 60;

                                                $break_hour = str_pad($b_hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($b_minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($b_secounds, 2, '0', STR_PAD_LEFT);
                                                list($hours, $minutes, $seconds) = explode(":", $break_hour);
                                                $total_seconds += ($hours * 3600) + ($minutes * 60) + $seconds;
                                            } else {
                                                $break_hour = null;
                                                $total_seconds += 0;
                                            }
                                            ?>

                                            <?php echo html_escape($break_hour); ?>
                                        </td>
                                    </tr>
                                    <?php $sl++; ?>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <td colspan="5" class="text-right"><strong>Total Stay Time:</strong></td>
                            <td><strong>
                                    <?php
                                    // Convert the total seconds back into hh:mm:ss format
                                    $total_hours = floor($total_seconds / 3600);
                                    $total_minutes = floor(($total_seconds % 3600) / 60);
                                    $total_seconds_left = $total_seconds % 60;

                                    echo sprintf("%02d:%02d:%02d", $total_hours, $total_minutes, $total_seconds_left);
                                    ?>
                                </strong></td>
                        </tfoot>
                    </table> <!-- /.table-responsive -->

                </div>
            </div>
        </div>
    </div>

</div>