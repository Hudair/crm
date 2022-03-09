<table class="table table-striped m-b-none text-sm" id="datatable_overdue_projects" >
    <thead>
    <tr>
        <th><?= lang('project_name') ?></th>
        <th><?= lang('client') ?></th>
        <th><?= lang('end_date') ?></th>
        <th><?= lang('status') ?></th>
        <th class="col-options no-sort"><?= lang('action') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (!empty($all_project)) {
        foreach ($all_project as $v_project):
            $progress = $v_project->final_progress;
                    ?>
                    <td>
                        <a class="text-info"
                           href="<?= base_url() ?>admin/projects/project_details/<?= $v_project->project_id ?>"><?= $v_project->project_name ?></a>
                        <?php if (strtotime(date('Y-m-d')) > strtotime($v_project->end_date) && $progress < 100) { ?>
                            <span
                                class="label label-danger pull-right"><?= lang('overdue') ?></span>
                        <?php } ?>

                        <div
                            class="progress progress-xs progress-striped active">
                            <div
                                class="progress-bar progress-bar-<?php echo ($progress >= 100) ? 'success' : 'primary'; ?>"
                                data-toggle="tooltip"
                                data-original-title="<?= $progress ?>%"
                                style="width: <?= $progress; ?>%"></div>
                        </div>

                    </td>
                    <td><?=   $v_project->client_name; ?></td>

                    <td><?= strftime(config_item('date_format'), strtotime($v_project->end_date)) ?></td>

                    <td><?php
                        if (!empty($v_project->project_status)) {
                            if ($v_project->project_status == 'completed') {
                                $status = "<span class='label label-success'>" . lang($v_project->project_status) . "</span>";
                            } elseif ($v_project->project_status == 'in_progress') {
                                $status = "<span class='label label-primary'>" . lang($v_project->project_status) . "</span>";
                            } elseif ($v_project->project_status == 'cancel') {
                                $status = "<span class='label label-danger'>" . lang($v_project->project_status) . "</span>";
                            } else {
                                $status = "<span class='label label-warning'>" . lang($v_project->project_status) . "</span>";
                            }
                            echo $status;
                        }
                        ?>      </td>
                    <td>
                        <?= btn_view(base_url() . 'admin/projects/project_details/' . $v_project->project_id) ?>
                    </td>
                </tr>
                <?php
           // }
        endforeach;
    }
    ?>

    </tbody>
</table>




<script type="text/javascript">
    $(document).ready(function () {
        //alert('salam');
        //var filter_by = '';
        //list = base_url + "admin/dashboard/rendering/projects/" + filter_by;
    });

</script>