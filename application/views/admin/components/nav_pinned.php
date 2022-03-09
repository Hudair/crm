<?php
$all_pinned_details = $this->db->where('user_id', $this->session->userdata('user_id'))->get('tbl_pinaction')->result();
if (!empty($all_pinned_details)) {
    foreach ($all_pinned_details as $v_pinned_details) {
        $pinned_details[$v_pinned_details->module_name] = $this->db->where('pinaction_id', $v_pinned_details->pinaction_id)->get('tbl_pinaction')->result();
    }
}
if (!empty($pinned_details)) {
    ?>
    <li class="nav-heading"><?= lang('pinned') . ' ' . lang('list') ?>
        <span class="badge bg-primary pull-right mr-sm"><?= count($all_pinned_details) ?></span></li>
    <?php foreach ($pinned_details as $module => $v_module_info) {
        if (!empty($v_module_info)) {
            foreach ($v_module_info as $v_module) { ?>
                <?php if ($v_module->module_name == 'project') {
                    $project_info = $this->db->where('project_id', $v_module->module_id)->get('tbl_project')->row();
                    if (!empty($project_info)) {
                        $progress = $this->items_model->get_project_progress($project_info->project_id);
                        ?>
                        <li class="pinned_list">
                            <a title="<?= lang('pinned') . ' ' . lang($module) ?>"
                               data-placement="top" data-toggle="tooltip"
                               href="<?= base_url() ?>admin/projects/project_details/<?= $project_info->project_id ?>">
                                <span style="font-size: 12px;"><?= $project_info->project_name ?></span>
                                <div class="progress progress-xxs mb-lg ">
                                    <div
                                        class="progress-bar progress-bar-<?php echo ($progress >= 100) ? 'success' : 'primary'; ?>"
                                        style="width: <?= $progress ?>%;">
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
                <?php if ($v_module->module_name == 'tasks') {
                    $task_info = $this->db->where('task_id', $v_module->module_id)->get('tbl_task')->row();
                    if (!empty($task_info)) {
                        ?>
                        <li class="pinned_list mb">
                            <a title="<?= lang('pinned') . ' ' . lang($module) ?>"
                               data-placement="top" data-toggle="tooltip"
                               href="<?= base_url() ?>admin/tasks/view_task_details/<?= $task_info->task_id ?>">
                                <span style="font-size: 12px;"><?= $task_info->task_name ?></span>
                                <div class="progress progress-xxs mb-lg ">
                                    <div
                                        class="progress-bar progress-bar-<?php echo ($task_info->task_progress >= 100) ? 'success' : 'primary'; ?>"
                                        style="width: <?= $task_info->task_progress ?>%;">
                                    </div>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
                <?php if ($v_module->module_name == 'invoice') {
                    $invoice_info = $this->db->where('invoices_id', $v_module->module_id)->get('tbl_invoices')->row();
                    if (!empty($invoice_info)) {
                        ?>
                        <li class="pinned_list mb">
                            <a title="<?= lang('pinned') . ' ' . lang($module) ?>"
                               data-placement="top" data-toggle="tooltip"
                               href="<?= base_url() ?>admin/invoice/manage_invoice/invoice_details/<?= $invoice_info->invoices_id ?>">
                                <span style="font-size: 12px;"><?= $invoice_info->reference_no ?></span>
                                <?php
                                $payment_status = $this->invoice_model->get_payment_status($invoice_info->invoices_id);
                                if (strtotime($invoice_info->due_date) < strtotime(date('Y-m-d')) && $payment_status != lang('fully_paid')) {
                                    $text = 'text-danger';
                                } else {
                                    $text = '';
                                } ?>
                                <div style="font-size: 8px;margin-top: -3px">
                                    <?= lang('overdue') ?>
                                    :<span
                                        class="<?= $text ?>"><?= strftime(config_item('date_format'), strtotime($invoice_info->due_date)) ?></span>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
                <?php if ($v_module->module_name == 'estimates') {
                    $estimates_info = $this->db->where('estimates_id', $v_module->module_id)->get('tbl_estimates')->row();
                    if (!empty($estimates_info)) {
                        ?>
                        <li class="pinned_list mb">
                            <a title="<?= lang('pinned') . ' ' . lang($module) ?>"
                               data-placement="top" data-toggle="tooltip"
                               href="<?= base_url() ?>admin/estimates/index/estimates_details/<?= $estimates_info->estimates_id ?>">
                                                <span
                                                    style="font-size: 12px;"><?= $estimates_info->reference_no ?></span>
                                <?php
                                if (strtotime($estimates_info->due_date) < strtotime(date('Y-m-d')) && $estimates_info->status == 'Pending') {
                                    $text = 'text-danger';
                                } else {
                                    $text = '';
                                } ?>
                                <div style="font-size: 8px;margin-top: -3px">
                                    <?= lang('expired') ?>
                                    :<span
                                        class="<?= $text ?>"><?= strftime(config_item('date_format'), strtotime($estimates_info->due_date)) ?></span>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>

                <?php if ($v_module->module_name == 'tickets') {
                    $tickets_info = $this->db->where('tickets_id', $v_module->module_id)->get('tbl_tickets')->row();
                    if (!empty($tickets_info)) {
                        if ($tickets_info->status == 'open') {
                            $s_label = 'danger';
                        } elseif ($tickets_info->status == 'closed') {
                            $s_label = 'success';
                        } else {
                            $s_label = 'default';
                        }
                        ?>
                        <li class="pinned_list mb">
                            <a title="<?= lang('pinned') . ' ' . lang($module) ?>"
                               data-placement="top" data-toggle="tooltip"
                               href="<?= base_url() ?>admin/tickets/index/tickets_details/<?= $tickets_info->tickets_id ?>">
                                <span style="font-size: 12px;"><?= $tickets_info->subject ?></span>
                                <div style="font-size: 8px;margin-top: -3px">
                                    <?= lang('status') ?>
                                    :<span
                                        class="text-<?= $s_label ?>"><?= lang($tickets_info->status) ?></span>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
                <?php if ($v_module->module_name == 'leads') {
                    $leads_info = $this->db->where('leads_id', $v_module->module_id)->get('tbl_leads')->row();
                    if (!empty($leads_info)) {
                        $lead_status = $this->db->where('lead_status_id', $leads_info->lead_status_id)->get('tbl_lead_status')->row();
                        ?>
                        <li class="pinned_list mb">
                            <a title="<?= lang('pinned') . ' ' . lang($module) ?>"
                               data-placement="top" data-toggle="tooltip"
                               href="<?= base_url() ?>admin/leads/leads_details/<?= $leads_info->leads_id ?>">
                                <span style="font-size: 12px;"><?= $leads_info->lead_name ?></span>
                                <div style="font-size: 8px;margin-top: -3px">
                                    <?= lang('status') ?>
                                    :<span><?= $lead_status->lead_status ?></span>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
                <?php if ($v_module->module_name == 'bugs') {
                    $bugs_info = $this->db->where('bug_id', $v_module->module_id)->get('tbl_bug')->row();
                    if (!empty($bugs_info)) {
                        $reporter = $this->db->where('user_id', $bugs_info->reporter)->get('tbl_users')->row();
                        if ($bugs_info->bug_status == 'unconfirmed') {
                            $b_label = 'warning';
                        } elseif ($bugs_info->bug_status == 'confirmed') {
                            $b_label = 'info';
                        } elseif ($bugs_info->bug_status == 'in_progress') {
                            $b_label = 'primary';
                        } else {
                            $b_label = 'success';
                        }
                        ?>
                        <li class="pinned_list mb">
                            <a title="<?= lang('pinned') . ' ' . lang($module) ?>"
                               data-placement="top" data-toggle="tooltip"
                               href="<?= base_url() ?>admin/bugs/view_bug_details/<?= $bugs_info->bug_id ?>">
                                <span style="font-size: 12px;"><?= $bugs_info->bug_title ?></span>
                                <div style="font-size: 8px;margin-top: -3px">
                                    <?= lang('status') ?>
                                    :<span
                                        class="text-<?= $b_label ?>"><?= lang("$bugs_info->bug_status") ?></span>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
            <?php }
        }
    }
}
?>