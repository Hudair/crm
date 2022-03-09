<div class="panel panel-custom menu" style="height: 437px;overflow-y: scroll;">
    <header class="panel-heading mb0">
        <h3 class="panel-title"><?= lang('my_project') ?></h3>
    </header>
    <div class="table-responsive">
        <table class="table table-striped m-b-none text-sm">
            <thead>
            <tr>
                <th><?= lang('project_name') ?></th>
                <th><?= lang('end_date') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($my_projects)) {
                foreach ($my_projects as $v_my_project): ?>
                    <tr>
                        <td>
                            <a class="text-info"
                               href="<?= base_url() ?>admin/projects/project_details/<?= $v_my_project->project_id ?>"><?= $v_my_project->project_name ?></a>
                            <?php if (strtotime(date('Y-m-d')) > strtotime($v_my_project->end_date) && $v_my_project->progress < 100) { ?>
                                <span
                                        class="label label-danger pull-right"><?= lang('overdue') ?></span>
                            <?php } ?>

                            <div class="progress progress-xs progress-striped active">
                                <div
                                        class="progress-bar progress-bar-<?php echo ($v_my_project->progress >= 100) ? 'success' : 'primary'; ?>"
                                        data-toggle="tooltip"
                                        data-original-title="<?= $v_my_project->progress ?>%"
                                        style="width: <?= $v_my_project->progress; ?>%"></div>
                            </div>

                        </td>
                        <td><?= strftime(config_item('date_format'), strtotime($v_my_project->end_date)) ?></td>

                    </tr>
                <?php

                endforeach;
            }
            ?>

            </tbody>
        </table>
    </div><!-- ./box-body -->
</div>