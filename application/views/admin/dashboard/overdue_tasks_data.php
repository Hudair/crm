<?php
/*$task_overdue = 0;

if (!empty($task_all_info)):
    foreach ($task_all_info as $v_task_info):
        $due_date = $v_task_info->due_date;
        $due_time = strtotime($due_date);
        if (strtotime(date('Y-m-d')) > $due_time && $v_task_info->task_progress < 100) {
            $task_overdue += count($v_task_info->task_id);
        }
    endforeach;
endif;*/

foreach ($task_all_info as $_key => $v_task) {
    if (!empty($v_task)) {
        $action = null;
        $checkbox = null;
        $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $v_task->task_id));
        $can_delete = $this->tasks_model->can_action('tbl_task', 'delete', array('task_id' => $v_task->task_id));
        if ($v_task->task_progress == 100) {
            $c_progress = 100;
        } elseif ($v_task->task_status == 'completed') {
            $c_progress = 100;
        } else {
            $c_progress = 0;
        }
        $sub_array = array();
        if (!empty($created) || !empty($edited)) {
            $checkbox .= '<div class="is_complete checkbox c-checkbox"><label><input type="checkbox" value="' . $v_task->task_id . '"  data-id="' . $v_task->task_id . '" style="position: absolute"' . (($c_progress >= 100) ? 'checked' : null) . '><span class="fa fa-check"></span></label></div>';
        }

        //$sub_array[] = $checkbox;
        $name = null;
        $name .= '<a  ' . ($v_task->task_status == "completed" ? 'style="text-decoration: line-through;"' : '') . ' class="text-info" href="' . base_url() . 'admin/tasks/view_task_details/' . $v_task->task_id . '">' . $v_task->task_name . '</a>';
        if (strtotime(date('Y-m-d')) > strtotime($v_task->due_date) && $c_progress < 100) {
            $name .= '<span class="label label-danger pull-right">' . lang("overdue") . '</span>';
        }
        $name .= '<div class="progress progress-xs progress-striped active"><div class="progress-bar progress-bar-' . (($c_progress >= 100) ? "success" : "primary") . '"data-toggle = "tooltip" data-original-title = "' . $c_progress . '%" style = "width:' . $c_progress . '%" ></div></div>';
        if (isset($v_task->sub_task_id)) {
            $name .= '<small ><a class="text-danger" href="' . base_url() . 'admin/tasks/view_task_details/' . $v_task->sub_task_id . '">' . lang('sub_tasks') . ': ' . get_any_field('tbl_task', array('task_id' => $v_task->sub_task_id), 'task_name') . '</a> </small>';
        }
        $sub_array[] = $name;
        /*        if (!empty($v_task->category_id)) {
            $category = '<span class="tags">' . $v_task->customer_group . '</span>';
        } else {
            $category = '';
        }*/
        // $sub_array[] = $category;
        //$sub_array[] = get_tags($v_task->tags, true);
        $disabled = null;
        if ($v_task->task_status == 'completed') {
            $label = 'success';
            $disabled = 'disabled';
        } elseif ($v_task->task_status == 'not_started') {
            $label = 'info';
        } elseif ($v_task->task_status == 'deferred') {
            $label = 'danger';
        } else {
            $label = 'warning';
        }
        $change_status = null;
        $ch_url = base_url() . 'admin/tasks/change_status/';
        $tasks_status = array_reverse($this->tasks_model->get_statuses());
        $change_status .= '<div class="btn-group">
        <button class="btn btn-xs btn-default dropdown-toggle"
                data-toggle="dropdown">
            <span class="caret"></span></button>
        <ul class="dropdown-menu animated zoomIn">';
        foreach ($tasks_status as $v_status) {
            $change_status .= '<li><a href="' . $ch_url . $v_task->task_id . '/' . ($v_status['value']) . '">' . lang($v_status['value']) . '</a></li>';
        }
        $change_status .= '</ul></div>';

        $sub_array[] = strftime(config_item('date_format'), strtotime($v_task->due_date));
        $sub_array[] = '<span class="label label-' . $label . '">' . lang($v_task->task_status) . '</span>' . ' ' . $change_status;
        $assigned = null;
        if ($v_task->permission != 'all') {
            $get_permission = json_decode($v_task->permission);
            if (!empty($get_permission)) :
                foreach ($get_permission as $permission => $v_permission) :
                    $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                    if (!empty($user_info)) {
                        if ($user_info->role_id == 1) {
                            $label = 'circle-danger';
                        } else {
                            $label = 'circle-success';
                        }
                        $assigned .= '<a href="#" data-toggle="tooltip"
                                                               data-placement="top"
                                                               title="' . fullname($permission) . '"><img
                                                                    src="' . base_url() . staffImage($permission) . '"
                                                                    class="img-circle img-xs" alt="">
                                                                    <span class="custom-permission circle ' . $label . '  circle-lg"></span>
                                                            </a>';
                    }
                endforeach;
            endif;
        } else {
            $assigned .= '<strong>' . lang("everyone") . '</strong><i title="' . lang('permission_for_all') . '" class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"></i>';
        };
        if (!empty($can_edit) && !empty($edited)) {
            $assigned .= ' <span data-placement="top" data-toggle="tooltip" title="' . lang('add_more') . '"><a data-toggle="modal" data-target="#myModal" href="' . base_url() . 'admin/tasks/update_users/' . $v_task->task_id . '" class="text-default ml"><i class="fa fa-plus"></i></a></span>';
        };

        if (!empty($can_edit) && !empty($edited)) {
            $action .= btn_edit('admin/tasks/all_task/' . $v_task->task_id) . ' ';
        }
        if (!empty($can_delete) && !empty($deleted)) {
            $action .= ajax_anchor(base_url("admin/tasks/delete_task/$v_task->task_id"), "<i class='btn btn-xs btn-danger fa fa-trash-o'></i>", array("class" => "", "title" => lang('delete'), "data-fade-out-on-success" => "#table_" . $_key)) . ' ';
        }
        if (timer_status('tasks', $v_task->task_id, 'on')) {
            $action .= '<a class="btn btn-xs btn-danger" data-toggle="tooltip" title=' . lang('stop_timer') . '
       href="' . base_url() . 'admin/tasks/tasks_timer/off/' . $v_task->task_id . '"><i class="fa fa-clock-o fa-spin"></i></a>';
        } else {
            $action .= '<a class="btn btn-xs btn-success ' . $disabled . '"  data-toggle="tooltip" title=' . lang('start_timer') . '
       href="' . base_url() . 'admin/tasks/tasks_timer/on/' . $v_task->task_id . '"><i class="fa fa-clock-o"></i></a>';
        }
        $sub_array[] = $action;
        $data[] = $sub_array;
    }
}

$today = date('Y-m-d h:i:s');
$where = array('tbl_task.task_status !=' => 'completed', 'tbl_task.due_date <' => $today);
render_table($data, $where);
