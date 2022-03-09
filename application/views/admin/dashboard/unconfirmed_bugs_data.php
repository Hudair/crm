<?php
$data = array();
if (!empty($all_bugs_info)):foreach ($all_bugs_info as $key => $v_bugs):
    // if ($v_bugs->bug_status == 'unconfirmed') {
    $sub_array = array();
    $reporter = $this->db->where('user_id', $v_bugs->reporter)->get('tbl_users')->row();

    if ($reporter->role_id == '1') {
        $badge = 'danger';
    } elseif ($reporter->role_id == '2') {
        $badge = 'info';
    } else {
        $badge = 'primary';
    }


    if ($v_bugs->bug_status == 'resolve') {
        $style = 'text-decoration: line-through;';
    } else {
        $style = '';
    }


    $bug_title = '<a class="text-info" style="' . $style . '"
                   href="' . base_url() . 'admin/bugs/view_bug_details/' . $v_bugs->bug_id . '">' . $v_bugs->bug_title . '</a>';

    if ($v_bugs->bug_status == 'unconfirmed') {
        $label = 'warning';
    } elseif ($v_bugs->bug_status == 'confirmed') {
        $label = 'info';
    } elseif ($v_bugs->bug_status == 'in_progress') {
        $label = 'primary';
    } else {
        $label = 'success';
    }

    $status = '<span
                    class="label label-' . $label . '">' . lang("$v_bugs->bug_status") . '</span>';

    if ($v_bugs->priority == 'High') {
        $plabel = 'danger';
    } elseif ($v_bugs->priority == 'Medium') {
        $plabel = 'info';
    } else {
        $plabel = 'primary';
    }

    $priority = '<span
                    class="badge btn-' . $plabel . '">' . ucfirst($v_bugs->priority) . '</span>';

    $reporter = '';
    if ($this->session->userdata('user_type') == '1') {
        $reporter = '<span
        class="badge btn-' . $badge . '">' . fullname($v_bugs->reporter) . '</span>';
    }
    // }

    $sub_array[] = $bug_title;
    $sub_array[] = $status;
    $sub_array[] = $priority;
    if ($this->session->userdata('user_type') == '1') {
        $sub_array[] = $reporter;
    }
    $data[] = $sub_array;

endforeach;
endif;

render_table($data, $whereClauses);
exit;

