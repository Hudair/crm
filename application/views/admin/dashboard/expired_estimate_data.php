<?php
$data = Array();
if (!empty($all_estimates_info)) {
    foreach ($all_estimates_info as $v_estimates) {
        $sub_array = Array();
        //if (strtotime($v_estimates->due_date) < strtotime(date('Y-m-d')) && $v_estimates->status == 'Pending') {
        if ($v_estimates->status == 'Pending') {
            $label = "info";
        } elseif ($v_estimates->status == 'Accepted') {
            $label = "success";
        } else {
            $label = "danger";
        }

        $reference_no = '<a class="text-info"
    href="' . base_url() . '>admin/estimates/index/estimates_details/' . $v_estimates->estimates_id . '">' . $v_estimates->reference_no . '</a>';

        $due_date = strftime(config_item('date_format'), strtotime($v_estimates->due_date));

        $due_date = '<span class="label label-danger ">' . lang('expired') . '</span>';


        $client_name = client_name($v_estimates->client_id);

        $amount = display_money($this->estimates_model->estimate_calculation('estimate_amount', $v_estimates->estimates_id), client_currency($v_estimates->client_id));

        $status = '<span
     class="label label-' . $label . '">' . lang(strtolower($v_estimates->status)) . '</span>';

        //}
        $sub_array[] = $reference_no;
        $sub_array[] = $due_date;
        $sub_array[] = $client_name;
        $sub_array[] = $amount;
        $sub_array[] = $status;
        $data[] = $sub_array;

    }
}
render_table($data, $whereClauses);
exit;
