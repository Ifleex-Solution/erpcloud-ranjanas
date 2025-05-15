<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading" style="background-color:#B2BEB5">
                <div class="panel-title">
                    <h4><?php echo $title ?> </h4>
                </div>
            </div>
            <div class="panel-body" style="background-color:#B2BEB5">


                <!-- Back Button -->

                <table class="datatable table table-bordered ">
                    <thead>
                        <tr>
                            <th><?php echo display('Sl') ?></th>
                            <th><?php echo display('name') ?></th>
                            <th><?php echo display('date') ?></th>
                            <th><?php echo display('break_in') ?></th>
                            <th><?php echo display('break_out') ?></th>
                            <th>Break Time (h) </th>
                            <th><?php echo display('action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($break_list == FALSE): ?>
                            <tr>
                                <td colspan="7" class="text-center">There are currently No Attendance</td>
                            </tr>
                        <?php else: ?>
                            <?php $sl = 1; ?>
                            <?php foreach ($break_list as $row): ?>
                                <tr class="<?php echo ($sl & 1) ? "odd gradeX" : "even gradeC" ?>">
                                    <td><?php echo $sl; ?></td>
                                    <td><?php echo html_escape($row['first_name']) . ' ' . html_escape($row['last_name']); ?>
                                    </td>
                                    <td><?php echo html_escape($row['date']); ?></td>
                                    <td><?php
                                        if (!empty($row['break_in'])) {
                                            echo html_escape(date("h:i A", strtotime($row['break_in'])));
                                        } else {
                                            echo '';
                                        }
                                        ?></td>
                                    <td><?php
                                        if (!empty($row['break_out'])) {
                                            echo html_escape(date("h:i A", strtotime($row['break_out'])));
                                        } else {
                                            echo '';
                                        }
                                        ?></td>

                                    <td>
                                        <?php
                                        if (!empty($row['break_out'])) {
                                            $break_hour = strtotime($row['break_out']) - strtotime($row['break_in']);
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
                                    <td class="center">
                                        <?php if ($this->permission1->method('manage_attendance', 'update')->access()): ?>
                                            <a href="<?php echo base_url("edit_break/" . $row['break_id']) ?>"
                                                class="btn btn-s btn-info">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($this->permission1->method('manage_attendance', 'delete')->access()): ?>
                                            <a href="<?php echo base_url("hrm/attendance/delete_break/" . $row['break_id']) ?>"
                                                class="btn btn-s btn-danger"
                                                onclick="return confirm('<?php echo display('are_you_sure') ?>')">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $sl++; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                   
                </table>
                <div class="text-right"><?php echo $links ?></div>
            </div>
        </div>
    </div>
</div>