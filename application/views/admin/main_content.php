<!-- Morris.js charts -->
<script src="<?php echo base_url() ?>assets/plugins/raphael/raphael.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/morris/morris.min.js"></script>
<!-- / Chart.js Script -->
<script src="<?php echo base_url(); ?>asset/js/chart.min.js" type="text/javascript"></script>

<style type="text/css">
    .datepicker {
        z-index: 1151 !important;
    }

    .mt-sm {
        font-size: 14px;
    }

    .close-btn {
        font-weight: 100;
        position: absolute;
        right: 10px;
        top: -10px;
        display: none;
    }

    .close-btn i {
        font-weight: 100;
        color: #89a59e;
    }

    .report:hover .close-btn {
        display: block;
    }

    .mt-lg:hover .close-btn {
        display: block;
    }
</style>
<?php
echo message_box('success');
echo message_box('error');

$all_report = $this->db->where('report', 1)->order_by('order_no', 'ASC')->get('tbl_dashboard')->result();
if ($this->session->userdata('user_type') == 1) {
    $where = array('report' => 0, 'status' => 1);
} else {
    $where = array('report' => 0, 'status' => 1, 'for_staff' => 1);
}
$all_order_data = $this->db->where($where)->order_by('order_no', 'ASC')->get('tbl_dashboard')->result();;
?>
<div class="dashboard">
    <!--        ******** transactions ************** -->
    <?php if ($this->session->userdata('user_type') == 1) { ?>
        <div id="report_menu" class="row">

            <?php if (!empty($all_report)) {
                foreach ($all_report as $v_report) {
                    if ($v_report->name == 'income_expenses_report' && $v_report->status == 1) { ?>
                        <div class="<?= $v_report->col ?> report" id="<?= $v_report->id ?>">
                            <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_report->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_report->id)); ?>
                            <div class="panel report_menu">
                                <div class="row row-table row-flush">
                                    <div class="col-xs-6 bb br">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center text-info">
                                                <em class="fa fa-plus fa-2x"></em>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0" id="today_income">

                                                    </h4>
                                                    <p class="mb0 text-muted"><?= lang('income_today') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/transactions/deposit"
                                                              class="mt0 mb0"><?= lang('more_info') ?> <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 bb">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center text-danger">
                                                <em class="fa fa-minus fa-2x"></em>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0" id="today_expense"></h4>
                                                    <p class="mb0 text-muted"><?= lang('expense_today') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/transactions/expense"
                                                              class=" small-box-footer"><?= lang('more_info') ?>
                                                            <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row-table row-flush">
                                    <div class="col-xs-6 br">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center text-info">
                                                <em class="fa fa-plus fa-2x"></em>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0" id="total_income"></h4>
                                                    <p class="mb0 text-muted"><?= lang('total_income') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/transactions/deposit"
                                                              class="mt0 mb0"><?= lang('more_info') ?> <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center text-danger">
                                                <em class="fa fa-minus fa-2x"></em>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0" id="total_expense"></h4>
                                                    <p class="mb0 text-muted"><?= lang('total_expense') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/transactions/expense"
                                                              class="small-box-footer"><?= lang('more_info') ?>
                                                            <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }


                    if ($v_report->name == 'invoice_payment_report' && $v_report->status == 1) {
                        ?>
                        <!--        ******** Sales ************** -->
                        <div class="<?= $v_report->col ?> report" id="<?= $v_report->id ?>">
                            <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_report->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_report->id)); ?>
                            <div class="panel report_menu">
                                <div class="row row-table row-flush">
                                    <div class="col-xs-6 bb br">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center ">
                                                <em class="fa fa-shopping-cart fa-2x"></em>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0" id="today_invo_amount"></h4>
                                                    <p class="mb0 text-muted"><?= lang('invoice_today') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/invoice/manage_invoice"
                                                              class="mt0 mb0"><?= lang('more_info') ?> <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 bb">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center text-purple">
                                                <em class="fa fa-money fa-2x"></em>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0" id="payment_today"></h4>
                                                    <p class="mb0 text-muted"><?= lang('payment_today') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/invoice/all_payments"
                                                              class=" small-box-footer"><?= lang('more_info') ?>
                                                            <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row-table row-flush">
                                    <div class="col-xs-6 br">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center text-purple">
                                                <em class="fa fa-money fa-2x"></em>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0" id="paid_invo_amount"></h4>
                                                    <p class="mb0 text-muted"><?= lang('paid_amount') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/invoice/all_payments"
                                                              class="mt0 mb0"><?= lang('more_info') ?> <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center text-danger">
                                                <em class="fa fa-usd fa-2x"></em>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0" id="client_outstanding"></h4>
                                                    <p class="mb0 text-muted"><?= lang('due_amount') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/invoice/manage_invoice"
                                                              class="small-box-footer"><?= lang('more_info') ?>
                                                            <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if ($v_report->name == 'ticket_tasks_report' && $v_report->status == 1) { ?>
                        <!--        ******** Ticket ************** -->
                        <div class="<?= $v_report->col ?> report" id="<?= $v_report->id ?>">
                            <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_report->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_report->id)); ?>
                            <div class="panel report_menu">
                                <div class="row row-table row-flush">
                                    <div class="col-xs-6 bb br">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center text-danger">
                                                <em class="fa fa-tasks fa-2x"></em>
                                            </div>

                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0" id="tasks_in_progress"><?php

                                                        //echo count($this->db->where('task_status', 'in_progress')->get('tbl_task')->result());
                                                        ?></h4>
                                                    <p class="mb0 text-muted"><?= lang('in_progress') . ' ' . lang('task') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/tasks/all_task"
                                                              class="mt0 mb0"><?= lang('more_info') ?> <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-6 bb">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center text-danger">
                                                <em class="fa fa-ticket fa-2x"></em>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0"
                                                        id="tickets_open"> <?php //echo count($this->db->where('status', 'open')->get('tbl_tickets')->result()); ?></h4>
                                                    <p class="mb0 text-muted"><?= lang('open') . ' ' . lang('tickets') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/tickets"
                                                              class=" small-box-footer"><?= lang('more_info') ?>
                                                            <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row row-table row-flush">
                                    <div class="col-xs-6 br">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center text-danger">
                                                <em class="fa fa-bug fa-2x"></em>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0" id="bugs_in_progress"><?php
                                                        //echo count($this->db->where('bug_status', 'in_progress')->get('tbl_bug')->result());
                                                        ?></h4>
                                                    <p class="mb0 text-muted"><?= lang('in_progress') . ' ' . lang('bugs') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/bugs"
                                                              class="mt0 mb0"><?= lang('more_info') ?> <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="row row-table row-flush">
                                            <div class="col-xs-2 text-center text-danger">
                                                <em class="fa fa-folder-open-o fa-2x"></em>
                                            </div>
                                            <div class="col-xs-10">
                                                <div class="text-center">
                                                    <h4 class="mt-sm mb0" id="project_in_progress"><?php
                                                        //echo count($this->db->where('project_status', 'in_progress')->get('tbl_project')->result());
                                                        ?></h4>
                                                    <p class="mb0 text-muted"><?= lang('in_progress') . ' ' . lang('project') ?></p>
                                                    <small><a href="<?= base_url() ?>admin/projects"
                                                              class="small-box-footer"><?= lang('more_info') ?>
                                                            <i
                                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
            } ?>
        </div>
    <?php } ?>
    <div class="clearfix visible-sm-block "></div>

    <div id="menu" class="row">
        <?php if (!empty($all_order_data)) {
            foreach ($all_order_data as $v_order) {
                ?>
                <?php if ($v_order->name == 'overdue_report' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id)); ?>
                    <section class="panel panel-custom menu">
                        <aside class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class=""><a href="#projects" id="overdue_projects"
                                                data-toggle="tab"><?= lang('overdue') . ' ' . lang('project') ?>
                                        <strong class="pull-right "
                                                id="overdue_projects_total">(<?php //echo $project_overdue ?>)</strong>
                                    </a></li>
                                <li class=""><a href="#tasks" id="overdue_tasks"
                                                data-toggle="tab"><?= lang('overdue') . ' ' . lang('tasks') ?>
                                        <strong class="pull-right " id="overdue_tasks_total">(<?php //echo$task_overdue ?>
                                            )</strong>
                                    </a></li>
                                <li class=""><a href="#invoice" id="overdue_invoices"
                                                data-toggle="tab"><?= lang('overdue') . ' ' . lang('invoice') ?>
                                        <strong class="pull-right " id="overdue_invoices_total"> (<span
                                                    id="invoice_overdue_no"> </span>)<?php //echo $invoice_overdue ?>
                                        </strong>
                                    </a></li>
                                <li class=""><a href="#estimate" id="expired_estimate"
                                                data-toggle="tab"><?= lang('expired') . ' ' . lang('estimate') ?>
                                        <strong class="pull-right "
                                                id="expired_estimate_total">(<?php //echo $estimate_overdue ?>)</strong>
                                    </a></li>
                                <li class=""><a href="#bugs" id="unconfirmed_bugs"
                                                data-toggle="tab"><?= lang('unconfirmed') . ' ' . lang('bugs') ?>
                                        <strong class="pull-right "
                                                id="unconfirmed_bugs_total">(<?php //echo $bug_unconfirmed ?>)</strong>
                                    </a></li>
                                <li class=""><a href="#recent_opportunities" id="overdue_opportunities"
                                                data-toggle="tab"><?= lang('overdue') . ' ' . lang('opportunities') ?>
                                        <strong class="pull-right "
                                                id="overdue_opportunities_total">(<?php //echo $opportunity_overdue ?>
                                            )</strong>
                                    </a></li>
                            </ul>


                            <section class="scrollable">
                                <div class="tab-content">
                                    <div class="tab-pane " id="projects">

                                        <?php
                                        //$this->load->view("admin/dashboard/overdue_projects_old");
                                        $this->load->view("admin/dashboard/overdue_projects");
                                        ?>
                                    </div>
                                    <div class="tab-pane" id="tasks">
                                        <table class="table table-striped m-b-none text-sm"
                                               id="datatable_overdue_tasks">
                                            <thead>
                                            <tr>
                                                <th><?= lang('task_name') ?></th>
                                                <th><?= lang('end_date') ?></th>
                                                <th><?= lang('status') ?></th>
                                                <th class="col-options no-sort col-md-1"><?= lang('action') ?></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="invoice">
                                        <table class="table table-striped m-b-none text-sm"
                                               id="datatable_overdue_invoices">
                                            <thead>
                                            <tr>
                                                <th><?= lang('invoice') ?></th>
                                                <th class="col-date"><?= lang('due_date') ?></th>
                                                <th><?= lang('client_name') ?></th>
                                                <th class="col-currency"><?= lang('due_amount') ?></th>
                                                <th><?= lang('status') ?></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="estimate">
                                        <table class="table table-striped m-b-none text-sm"
                                               id="datatable_expired_estimate">
                                            <thead>
                                            <tr>
                                                <th><?= lang('estimate') ?></th>
                                                <th><?= lang('due_date') ?></th>
                                                <th><?= lang('client_name') ?></th>
                                                <th><?= lang('amount') ?></th>
                                                <th><?= lang('status') ?></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="bugs">
                                        <table class="table table-striped m-b-none text-sm"
                                               id="datatable_unconfirmed_bugs">
                                            <thead>
                                            <tr>
                                                <th><?= lang('bug_title') ?></th>
                                                <th><?= lang('status') ?></th>
                                                <th><?= lang('priority') ?></th>
                                                <?php if ($this->session->userdata('user_type') == '1') { ?>
                                                    <th><?= lang('reporter') ?></th>
                                                <?php } ?>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="tab-pane" id="recent_opportunities">
                                        <table class="table table-striped m-b-none text-sm"
                                               id="datatable_overdue_opportunities">
                                            <thead>
                                            <tr>
                                                <th><?= lang('opportunity_name') ?></th>
                                                <th><?= lang('state') ?></th>
                                                <th><?= lang('stages') ?></th>
                                                <th><?= lang('expected_revenue') ?></th>
                                                <th><?= lang('next_action') ?></th>
                                                <th><?= lang('next_action_date') ?></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </section>
                        </aside>
                        <?php
                        if ($this->session->userdata('user_type') == '1') { ?>
                            <footer class="panel-footer bg-white no-padder">
                                <div class="row text-center no-gutter">
                                    <div class="col-xs-2 b-r b-light">
    <span
            class="h4 font-bold m-t block"
            id="completed_projects">
    </span>
                                        <small class="text-muted m-b block"><?= lang('complete_projects') ?></small>
                                    </div>
                                    <div class="col-xs-2 b-r b-light">
    <span
            class="h4 font-bold m-t block"
            id="completed_tasks">
    </span>
                                        <small class="text-muted m-b block"><?= lang('complete_tasks') ?></small>
                                    </div>
                                    <div class="col-xs-2">
    <span
            class="h4 font-bold m-t block"
            id="total_invoice_amount">
    </span>
                                        <small
                                                class="text-muted m-b block"><?= lang('total') . ' ' . lang('invoice_amount') ?></small>

                                    </div>
                                    <div class="col-xs-2">
    <span
            class="h4 font-bold m-t block"
            id="total_estimate_amount">
    </span>
                                        <small
                                                class="text-muted m-b block"><?= lang('total') . ' ' . lang('estimate') ?></small>

                                    </div>
                                    <div class="col-xs-2">
    <span
            class="h4 font-bold m-t block"
            id="total_resolved_bugs">
    </span>
                                        <small
                                                class="text-muted m-b block"><?= lang('resolved') . ' ' . lang('bugs') ?></small>

                                    </div>
                                    <div class="col-xs-2">
    <span
            class="h4 font-bold m-t block"
            id="total_won_opportunities">
    </span>
                                        <small
                                                class="text-muted m-b block"><?= lang('won') . ' ' . lang('opportunities') ?></small>

                                    </div>
                                </div>
                            </footer>
                        <?php } ?>
                    </section>
                </div>
            <?php } ?>

            <?php if ($v_order->name == 'finance_overview' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id));
                    ?>
                    <div id="finance_overview_div">
                    </div>


                </div>
            <?php } ?>
            <?php

            ?>
            <?php if ($v_order->name == 'goal_report' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id)); ?>
                    <div id="goal_report_div">
                    </div>
                </div>
            <?php } ?>

                <style type="text/css">
                    .dragger {
                        background: url(../assets/img/dragger.png) 0px 11px no-repeat;
                        cursor: pointer;
                    }

                    .table > tbody > tr > td {
                        vertical-align: initial;
                    }
                </style>
            <?php if ($v_order->name == 'todo_list' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id)); ?>
                    <div id="to_do_list_div">
                    </div>
                </div>
            <?php } ?>
            <?php /*Comment in my JavaScript*/ ?>
            <?php include_once 'assets/js/sales.php'; ?>
                <script type="text/javascript">
                    $(document).ready(function () {
                        init_items_sortable(true);
                    });
                </script>
            <?php /*Comment in my JavaScript*/ ?>
            <?php if ($v_order->name == 'my_project' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id)); ?>

                    <div id="my_projects_div">

                    </div>
                </div>
            <?php } ?>
            <?php include_once 'assets/admin-ajax.php'; ?>
            <?php if ($v_order->name == 'my_tasks' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id)); ?>
                    <div id="my_task_div"></div>
                </div>
            <?php } ?>
            <?php if ($v_order->name == 'announcements' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id)); ?>
                    <div id="announcements_div"></div>
                </div>
            <?php } ?>

            <?php if ($this->session->userdata('user_type') == '1') { ?>
            <?php if ($v_order->name == 'payments_report' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id)); ?>
                    <div id="payments_report_div"></div>
                </div>
            <?php } ?>

                <?php if ($v_order->name == 'income_expense' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id)); ?>
                    <div id="income_vs_expense_div"></div>

                </div>
            <?php } ?>


                <?php if ($v_order->name == 'income_report' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id)); ?>
                    <div id="income_report_div"></div>


                </div>


            <?php } ?>


                <?php if ($v_order->name == 'expense_report' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id)); ?>
                    <div id="expense_report_div"></div>

                </div>
            <?php } ?>

                <?php if ($v_order->name == 'recently_paid_invoices' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id)); ?>
                    <div id="recently_paid_invoices_div"></div>

                </div>
            <?php } ?>


            <?php } ?>
            <?php if ($v_order->name == 'recent_activities' && $v_order->status == 1) { ?>
                <div class="<?= $v_order->col ?> mt-lg" id="<?= $v_order->id ?>">
                    <?php echo ajax_anchor(base_url("admin/settings/save_dashboard/$v_order->id" . '/0'), "<i class='fa fa-times-circle'></i>", array("class" => "close-btn", "title" => lang('inactive'), "data-fade-out-on-success" => "#" . $v_order->id));
                    ?>
                    <div id="recent_activities_div"></div>
                </div>
            <?php }
            }
        }
        ?>
    </div>
</div>
<?php

// $income_report_order = get_row('tbl_dashboard', array('name' => 'income_report') + $where);
// $expense_report_order = get_row('tbl_dashboard', array('name' => 'expense_report') + $where);
//$income_expense_order = get_row('tbl_dashboard', array('name' => 'income_expense') + $where);
//$payments_report_order = get_row('tbl_dashboard', array('name' => 'payments_report') + $where);
//$finance_overview_order = get_row('tbl_dashboard', array('name' => 'finance_overview') + $where);
// $goal_report_order = get_row('tbl_dashboard', array('name' => 'goal_report') + $where);
?>


<script type="text/javascript">
    $(function () {
        $("#report_menu").sortable({
            connectWith: ".report_menu",
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            stop: function (event, ui) {
                var id = JSON.stringify(
                    $("#report_menu").sortable(
                        'toArray',
                        {
                            attribute: 'id'
                        }
                    )
                );
                var formData = {
                    'report_menu': id
                };
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url()?>admin/settings/save_dashboard/',
                    data: formData,
                    dataType: 'json',
                    encode: true,
                    success: function (res) {
                        if (res) {
                        } else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })

            }
        });
        $(".report_menu").disableSelection();

        $("#menu").sortable({
            connectWith: ".menu",
            placeholder: 'ui-state-highlight',
            forcePlaceholderSize: true,
            stop: function (event, ui) {
                var mid = JSON.stringify(
                    $("#menu").sortable(
                        'toArray',
                        {
                            attribute: 'id'
                        }
                    )
                );
                var formData = {
                    'menu': mid
                };
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url()?>admin/settings/save_dashboard/',
                    data: formData,
                    dataType: 'json',
                    encode: true,
                    success: function (res) {
                        if (res) {
                        } else {
                            alert('There was a problem with AJAX');
                        }
                    }
                })
            }
        });
        $(".menu").disableSelection();
    });
</script>

<script>
    $(document).ready(function () {
        ins_data(base_url + 'admin/invoice/invo_data');

        ins_data(base_url + 'admin/dashboard/data_rendering');
        ins_data(base_url + 'admin/dashboard/income_expenses');
        ins_data(base_url + 'admin/dashboard/different_overdue_total');
        ins_data(base_url + 'admin/dashboard/different_completed_total');

        ins_data(base_url + 'admin/dashboard/finance_overview');
        ins_data(base_url + 'admin/dashboard/my_projects');
        ins_data(base_url + 'admin/dashboard/goal_report');
        ins_data(base_url + 'admin/dashboard/to_do_list');
        ins_data(base_url + 'admin/dashboard/my_task');
        ins_data(base_url + 'admin/dashboard/announcements');
        ins_data(base_url + 'admin/dashboard/payments_report');
        ins_data(base_url + 'admin/dashboard/income_vs_expense');
        ins_data(base_url + 'admin/dashboard/income_report');
        ins_data(base_url + 'admin/dashboard/expense_report');
        ins_data(base_url + 'admin/dashboard/recently_paid_invoices');
        ins_data(base_url + 'admin/dashboard/recent_activities');

        $("#overdue_projects").on("click", function(){
            fire_datatable(base_url + "admin/dashboard/rendering/overdue_projects/", 'datatable_overdue_projects');
        });

        $("#overdue_tasks").on("click", function(){
            fire_datatable(base_url + "admin/dashboard/rendering/overdue_tasks/", 'datatable_overdue_tasks');

        });

        $("#overdue_invoices").on("click", function(){
            fire_datatable(base_url + "admin/dashboard/rendering/overdue_invoices/", 'datatable_overdue_invoices');
        });

        $("#overdue_opportunities").on("click", function(){
            fire_datatable(base_url + "admin/dashboard/rendering/overdue_opportunities/", 'datatable_overdue_opportunities');

        });

        $("#expired_estimate").on("click", function(){
            fire_datatable(base_url + "admin/dashboard/rendering/expired_estimate/", 'datatable_expired_estimate');

        });

        $("#unconfirmed_bugs").on("click", function(){
            fire_datatable(base_url + "admin/dashboard/rendering/unconfirmed_bugs/", 'datatable_unconfirmed_bugs');

        });

    });

    $(document).on('click', '#finance_overview_btn', function (e) {
        e.preventDefault();
        var year = $("#finance_overview_year").val();
        var formData = {
            'finance_overview': year
        };
        ins_data(base_url + 'admin/dashboard/finance_overview', formData);
    });

    $(document).on('click', '#goal_report_btn', function (e) {
        e.preventDefault();
        var goal_month = $("#goal_month_id").val();
        var formData = {
            'goal_month': goal_month
        };
        ins_data(base_url + 'admin/dashboard/goal_report', formData);
    });

    $(document).on('click', '#payments_report_btn', function (e) {
        e.preventDefault();
        var payment_year = $("#payment_year_id").val();
        var formData = {
            'yearly': payment_year
        };
        ins_data(base_url + 'admin/dashboard/payments_report', formData);
    });

    $(document).on('click', '#income_vs_expense_btn', function (e) {
        e.preventDefault();
        var month = $("#income_vs_expense_month_id").val();
        var formData = {
            'month': month
        };
        ins_data(base_url + 'admin/dashboard/income_vs_expense', formData);
    });

    $(document).on('click', '#income_report_btn', function (e) {
        e.preventDefault();
        var Income = $("#income_report_id").val();
        var formData = {
            'Income': Income
        };
        ins_data(base_url + 'admin/dashboard/income_report', formData);
    });

    $(document).on('click', '#expense_report_btn', function (e) {
        e.preventDefault();
        var year = $("#expense_report_id").val();
        var formData = {
            'year': year
        };
        ins_data(base_url + 'admin/dashboard/expense_report', formData);
    });

</script>
<script src="<?= base_url() ?>assets/plugins/jquery-ui/jquery-u.min.js"></script>

