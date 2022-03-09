<?php

$my_task = $this->admin_model->my_permission('tbl_task', $this->session->userdata('user_id'));
//echo $this->db->last_query(); exit();
//echo '<pre>'; print_r($my_task); exit();
?>


<div class="panel panel-custom menu" style="height: 437px;overflow-y: scroll;">
    <header class="panel-heading mb0">
        <h3 class="panel-title"><?= lang('my_tasks') ?></h3>
    </header>
    <div class="table-responsive">
        <table class="table table-striped m-b-none text-sm">
            <thead>
            <tr>
                <th data-check-all>

                </th>
                <th><?= lang('task_name') ?></th>
                <th><?= lang('end_date') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($my_task)): foreach ($my_task as $v_my_task):
                if ($v_my_task->task_status == 'not_started' || $v_my_task->task_status == 'in_progress' || $v_my_task->task_progress < 100) {
                    $due_date = $v_my_task->due_date;
                    $due_time = strtotime($due_date);
                    ?>
                    <tr>
                        <td class="col-sm-1">
                            <div class="complete checkbox c-checkbox">
                                <label>
                                    <input type="checkbox" data-id="<?= $v_my_task->task_id ?>"
                                           style="position: absolute;" <?php
                                    if ($v_my_task->task_progress >= 100) {
                                        echo 'checked';
                                    }
                                    ?>>
                                    <span class="fa fa-check"></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <a class="text-info"
                               href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_my_task->task_id ?>">
                                <?php echo $v_my_task->task_name; ?></a>
                            <?php if (strtotime(date('Y-m-d')) > $due_time && $v_my_task->task_progress < 100) { ?>
                                <span
                                        class="label label-danger pull-right"><?= lang('overdue') ?></span>
                            <?php } ?>

                            <div class="progress progress-xs progress-striped active">
                                <div
                                        class="progress-bar progress-bar-<?php echo ($v_my_task->task_progress >= 100) ? 'success' : 'primary'; ?>"
                                        data-toggle="tooltip"
                                        data-original-title="<?= $v_my_task->task_progress ?>%"
                                        style="width: <?= $v_my_task->task_progress; ?>%"></div>
                            </div>

                        </td>

                        <td>
                            <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                        </td>


                    </tr>
                    <?php
                }
            endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div><!-- ./box-body -->

</div>