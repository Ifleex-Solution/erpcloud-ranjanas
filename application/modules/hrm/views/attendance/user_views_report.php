<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="background-color:#B2BEB5">
                <div class="panel-title">
                    <h4><?php echo display('datewise_report') ?></h4>
                </div>
                <!-- Back Button -->

            </div>

            <div class="panel-body" style="background-color:#B2BEB5">
                <div class="row">

                    <table width="100%" class="datatable table  table-bordered" >
                        <caption>
                            <center>
                                <?php echo display('from') . ' -'  . $from_date. '&nbsp;&nbsp;&nbsp;'. display('to') . ' -' . $to_date ?>
                            </center>
                        </caption>
                        <thead>
                            <tr>
                                <th><?php echo display('Sl') ?></th>
                                <th><?php echo display('employee_name') ?></th>
                                <th><?php echo display('date') ?></th>
                                <th><?php echo display('sign_in') ?></th>
                                <th><?php echo display('sign_out') ?></th>
                                <th>Stay Time (h)</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($query)) { ?>

                                <?php $sl = 1; ?>
                                <?php foreach ($query as $que) {
                                ?>
                                    <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                                        <td><?php echo $sl; ?></td>
                                        <td><?php echo html_escape($que['first_name']) . ' ' . html_escape($que['last_name']); ?>
                                        </td>
                                        <td><?php echo html_escape($que['date']); ?></td>
                                        <td><?php
                                            if (!empty($que['sign_in'])) {
                                                echo html_escape(date("h:i A", strtotime($que['sign_in'])));
                                            } else {
                                                echo '';
                                            }
                                            ?></td>
                                        <td><?php
                                            if (!empty($que['sign_out'])) {
                                                echo html_escape(date("h:i A", strtotime($que['sign_out'])));
                                            } else {
                                                echo '';
                                            }
                                            ?></td>

<td>
                                            <?php
                                            if (!empty($que['sign_out'])) {
                                                $sign_hour = strtotime($que['sign_out']) - strtotime($que['sign_in']);
                                                $s_hours = floor($sign_hour / 3600);
                                                $s_minutes = floor(($sign_hour % 3600) / 60);
                                                $s_secounds = $sign_hour % 60;

                                                $sign_hour = str_pad($s_hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($s_minutes, 2, '0', STR_PAD_LEFT) . ':' . str_pad($s_secounds, 2, '0', STR_PAD_LEFT);
                                                list($hours, $minutes, $seconds) = explode(":", $sign_hour);
                                                $total_seconds += ($hours * 3600) + ($minutes * 60) + $seconds;
                                            } else {
                                                $sign_hour =null;
                                                $total_seconds += 0;
                                            }
                                            ?>

                                            <?php echo html_escape($sign_hour); ?>
                                        </td>

                                    </tr>
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