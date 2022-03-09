<?php
foreach ($all_project as $key => $v_project) {
if (!empty($v_project)) {
$action = null;
$progress = $v_project->final_progress;
$sub_array = array();
$name = null;
$name .= '<a class="text-info" href="' . base_url() . 'admin/projects/project_details/' . $v_project->project_id . '">' . $v_project->project_name . '</a>';
if (strtotime(date('Y-m-d')) > strtotime($v_project->end_date) && $progress < 100) {
$name .= '<span class="label label-danger pull-right">' . lang("overdue") . '</span>';
}
$name .= '<div class="progress progress-xs progress-striped active"><div class="progress-bar progress-bar-' . (($progress <= 100) ? "success" : "primary") . '"data-toggle = "tooltip" data-original-title = "' . $progress . '%" style = "width:' . $progress . '%" ></div></div>';
$sub_array[] = $name;

$sub_array[] = $v_project->client_name;
$sub_array[] = strftime(config_item('date_format'), strtotime($v_project->end_date));
$statusss = null;
if (!empty($v_project->project_status)) {
if ($v_project->project_status == 'completed') {
$statusss = "<span class='label label-success'>" . lang($v_project->project_status) . "</span>";
} elseif ($v_project->project_status == 'in_progress') {
$statusss = "<span class='label label-primary'>" . lang($v_project->project_status) . "</span>";
} elseif ($v_project->project_status == 'cancel') {
$statusss = "<span class='label label-danger'>" . lang($v_project->project_status) . "</span>";
} else {
$statusss = "<span class='label label-warning'>" . lang($v_project->project_status) . "</span>";
}
}
$change_status = null;
if (!empty($can_edit) && !empty($edited)) {
$ch_url = base_url() . 'admin/projects/change_status/';
$change_status = '<div class="btn-group">
    <button class="btn btn-xs btn-default dropdown-toggle"
            data-toggle="dropdown">
        <span class="caret"></span></button>
    <ul class="dropdown-menu animated zoomIn">
        <li>
            <a href="' . $ch_url . $v_project->project_id . '/started' . '">' . lang('started') . '</a>
        </li>
        <li>
            <a href="' . $ch_url . $v_project->project_id . '/in_progress' . '">' . lang('in_progress') . '</a>
        </li>
        <li>
            <a href="' . $ch_url . $v_project->project_id . '/cancel' . '">' . lang('cancel') . '</a>
        </li>
        <li>
            <a href="' . $ch_url . $v_project->project_id . '/on_hold' . '">' . lang('on_hold') . '</a>
        </li>
        <li>
            <a href="' . $ch_url . $v_project->project_id . '/completed' . '">' . lang('completed') . '</a>
        </li>
    </ul>
</div>';
}
$sub_array[] = $statusss . ' ' . $change_status;

$action .= btn_view('admin/projects/project_details/' . $v_project->project_id) . ' ';

$sub_array[] = $action;
$data[] = $sub_array;
}
}

$today = date('Y-m-d h:i:s');
$where = array('tbl_project.project_status !=' => 'completed', 'tbl_project.end_date <' => $today);
render_table($data, $where);
