
<style type="text/css">
.datepicker {
z-index: 1151 !important;
}

.mt-sm {
font-size: 14px;
}
</style>
<?php
echo message_box('success');
echo message_box('error');
$curency = $this->admin_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
$leave_info = $this->db->where('attendance_status', '3')->get('tbl_attendance')->result();

?>
<div class="dashboard row">

<div class="full-calender">
<div class="clearfix visible-sm-block "></div>

<div class="col-sm-12 mt-lg">
<div class="panel panel-custom ">
<div class="panel-heading mb0 pt-sm pb-sm">
<div class="panel-title ">
<h4><?= lang('calendar') ?>
<div class="pull-right ">
<?php if (admin_head()) { ?>
<div class="pull-right ml">
<a data-toggle="modal" data-target="#myModal"
   href="<?= base_url() ?>admin/calendar/calendar_settings"
   class="text-default ml"><i class="fa fa-cogs"></i></a>
</div>
<?php } ?>
<div class="pull-left">
<div class="btn-group ">
<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
    <?php
    if (!empty($searchType)) {
        echo lang($searchType);
    } else {
        echo lang('all');
    }
    ?>
    <span class="caret"></span></button>
<ul class="dropdown-menu pull-right animated zoomIn">
    <li>
        <a href="<?= base_url() ?>admin/calendar/index/search/all"><?= lang('all') ?></a>
    </li>
    <?php if (config_item('project_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/projects"><?= lang('project') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('milestone_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/milestones"><?= lang('milestone') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('tasks_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/tasks"><?= lang('tasks') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('bugs_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/bugs"><?= lang('bugs') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('invoice_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/invoices"><?= lang('invoice') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('payments_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/payments"><?= lang('payments') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('estimate_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/estimates"><?= lang('estimate') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('opportunities_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/opportunities"><?= lang('opportunities') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('leads_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/leads"><?= lang('leads') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('goal_tracking_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/goal"><?= lang('goal_tracking') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('holiday_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/holiday"><?= lang('holiday') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('absent_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/absent"><?= lang('absent') ?></a>
        </li>
    <?php } ?>
    <?php if (config_item('on_leave_on_calendar') == 'on') { ?>
        <li>
            <a href="<?= base_url() ?>admin/calendar/index/search/on_leave"><?= lang('on_leave') ?></a>
        </li>
    <?php } ?>
</ul>
</div>
</div>
</div>
</h4>

</div>
</div>



<div class="content-body">
<div class="mt-lg calendar-content-body" id="my_calendar"></div>


</div>
</div>
</div>

</div>
<?php
$this->load->view("admin/calendar/my_calendar");

?>
<!--Calendar-->


