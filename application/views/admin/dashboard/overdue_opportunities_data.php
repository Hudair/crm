<?php
if (!empty($all_opportunity)) {
    $data = Array();
    foreach ($all_opportunity as $v_opportunity) {
        $sub_array = Array();
        $opportunities_state_info = $this->db->where('opportunities_state_reason_id', $v_opportunity->opportunities_state_reason_id)->get('tbl_opportunities_state_reason')->row();
        if ($opportunities_state_info->opportunities_state == 'open') {
            $label = 'primary';
        } elseif ($opportunities_state_info->opportunities_state == 'won') {
            $label = 'success';
        } elseif ($opportunities_state_info->opportunities_state == 'suspended') {
            $label = 'info';
        } else {
            $label = 'danger';
        }

        $probability = $v_opportunity->probability;
        $success = ($v_opportunity->probability >= 100) ? 'success' : 'primary';

        $oppo_name = '<a class="text-info"
    href="<?= base_url() ?>admin/opportunities/opportunity_details/<?= $v_opportunity->opportunities_id ?>">' . $v_opportunity->opportunity_name . '</a>';
        if (strtotime(date('Y-m-d')) > strtotime($v_opportunity->close_date) && $v_opportunity->probability < 100) {
            $oppo_name .= '<span
    class="label label-danger pull-right">' . lang('overdue') . '</span>';
        }
        $oppo_name .= '<div
    class="progress progress-xs progress-striped active">
    <div
    class="progress-bar progress-bar-' . $success . ' "
    data-toggle="tooltip"
    data-original-title="' . lang('probability') . ' ' . $v_opportunity->probability . '%"
    style="width: ' . $probability . '%">
    </div>
    </div>';
        $oppo_state = '<span
    class="label label-' . $label . '">' . lang($opportunities_state_info->opportunities_state) . '</span>';
        $oppo_stage = lang($v_opportunity->stages);

        $oppo_expect_revenue = '';

        if (!empty($v_opportunity->expected_revenue)) {
            $oppo_expect_revenue = display_money($v_opportunity->expected_revenue, default_currency());
        }

        $oppo_next_action = $v_opportunity->next_action;
        $oppo_next_action_date = strftime(config_item('date_format'), strtotime($v_opportunity->next_action_date));

        $sub_array[] = $oppo_name;
        $sub_array[] = $oppo_state;
        $sub_array[] = $oppo_stage;
        $sub_array[] = $oppo_expect_revenue;
        $sub_array[] = $oppo_next_action;
        $sub_array[] = $oppo_next_action_date;
        $data[] = $sub_array;
    }
    render_table($data, $whereClauses);
}

exit;
?>
