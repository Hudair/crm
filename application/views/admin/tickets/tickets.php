<?= message_box('success'); ?>
<?= message_box('error'); ?>
<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-tagsinput/fm.tagator.jquery.js"></script>


<div id="tickets_state_report_div">
<?php //$this->load->view("admin/tickets/tickets_state_report"); ?>
</div>

<?php
$created = can_action(6, 'created');
$edited = can_action(6, 'edited');
$deleted = can_action(6, 'deleted');

if (!empty($created) || !empty($edited)){
?>
<div class="row">
    <div class="col-sm-12">
        <?php $is_department_head = is_department_head();
        if ($this->session->userdata('user_type') == 1 || !empty($is_department_head)) { ?>
            <div class="btn-group pull-right btn-with-tooltip-group _filter_data filtered" data-toggle="tooltip"
                 data-title="<?php echo lang('filter_by'); ?>">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu group animated zoomIn"
                    style="width:300px;">
                    <li class="filter_by all_filter"><a href="#"><?php echo lang('all'); ?></a></li>
                    <li class="divider"></li>

                    <li class="filter_by" id="assigned_to_me"><a href="#"><?php echo lang('assigned_to_me'); ?></a></li>
                    <?php if (admin()) { ?>
                        <li class="filter_by" id="everyone"
                            search-type="by_staff">
                            <a href="#"><?php echo lang('assigned_to') . ' ' . lang('everyone'); ?></a>
                        </li>
                    <?php } ?>
                    <li class="dropdown-submenu pull-left  " id="from_account">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('project'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left from_account"
                            style="">
                            <?php
                            $project_info = $this->items_model->get_permission('tbl_project');
                            if (!empty($project_info)) {
                                foreach ($project_info as $v_project) {
                                    ?>
                                    <li class="filter_by" id="<?= $v_project->project_id ?>" search-type="by_project">
                                        <a href="#"><?php echo $v_project->project_name; ?></a>
                                    </li>
                                <?php }
                            }
                            ?>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                    <li class="dropdown-submenu pull-left  " id="from_reporter">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('reporter'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left from_reporter"
                            style="">
                            <?php
                            $reporter_info = $this->db->get('tbl_users')->result();;
                            if (!empty($reporter_info)) {
                                foreach ($reporter_info as $v_reporter) {
                                    ?>
                                    <li class="filter_by" id="<?= $v_reporter->user_id ?>" search-type="by_reported">
                                        <a href="#"><?php echo fullname($v_reporter->user_id); ?></a>
                                    </li>
                                <?php }
                            }
                            ?>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                    <li class="dropdown-submenu pull-left " id="to_account">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('department'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left to_account"
                            style="">
                            <?php
                            $department_info = get_result('tbl_departments');
                            if (count($department_info) > 0) { ?>
                                <?php foreach ($department_info as $v_department) {
                                    ?>
                                    <li class="filter_by" id="<?= $v_department->departments_id ?>"
                                        search-type="by_department">
                                        <a href="#"><?php echo $v_department->deptname; ?></a>
                                    </li>
                                <?php }
                                ?>
                                <div class="clearfix"></div>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php } ?>
        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : ''; ?>"><a href="#manage"
                                                                    data-toggle="tab"><?= lang('tickets') ?></a>
                </li>
                <li class="<?= $active == 2 ? 'active' : ''; ?>"><a href="#new" id="form_tab"
                                                                    data-toggle="tab"><?= lang('new_ticket') ?></a>
                </li>
            </ul>
            <style type="text/css">
                .custom-bulk-button {
                    display: initial;
                }
            </style>
            <div class="tab-content bg-white">
                <!-- ************** general *************-->
                <div class="tab-pane <?= $active == 1 ? 'active' : ''; ?>" id="manage">
                    <?php } else { ?>
                    <div class="panel panel-custom">
                        <header class="panel-heading ">
                            <div class="panel-title"><strong><?= lang('tickets') ?></strong></div>
                        </header>
                        <?php } ?>
                        <div class="table-responsive">
                            <table class="table table-striped DataTables bulk_table" id="DataTables" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <?php if (!empty($deleted)) { ?>
                                        <th data-orderable="false">
                                            <div class="checkbox c-checkbox">
                                                <label class="needsclick">
                                                    <input id="select_all" type="checkbox">
                                                    <span class="fa fa-check"></span></label>
                                            </div>
                                        </th>
                                    <?php } ?>
                                    <th><?= lang('ticket_code') ?></th>
                                    <th><?= lang('subject') ?></th>
                                    <th class="col-date"><?= lang('date') ?></th>
                                    <?php if ($this->session->userdata('user_type') == '1') { ?>
                                        <th><?= lang('reporter') ?></th>
                                    <?php } ?>
                                    <th><?= lang('department') ?></th>
                                    <th><?= lang('tags') ?></th>
                                    <th><?= lang('status') ?></th>
                                    <?php $show_custom_fields = custom_form_table(7, null);
                                    if (!empty($show_custom_fields)) {
                                        foreach ($show_custom_fields as $c_label => $v_fields) {
                                            if (!empty($c_label)) {
                                                ?>
                                                <th><?= $c_label ?> </th>
                                            <?php }
                                        }
                                    }
                                    ?>

                                    <th><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        list = base_url + "admin/tickets/ticketsList";
                                        bulk_url = base_url + "admin/tickets/bulk_delete";
                                        <?php if (admin_head()) { ?>
                                        $('.filtered > .dropdown-toggle').on('click', function () {
                                            if ($('.group').css('display') == 'block') {
                                                $('.group').css('display', 'none');
                                            } else {
                                                $('.group').css('display', 'block')
                                            }
                                        });
                                        $('.all_filter').on('click', function () {
                                            $('.to_account').removeAttr("style");
                                            $('.from_account').removeAttr("style");
                                            $('.from_reporter').removeAttr("style");
                                        });
                                        $('.from_account li').on('click', function () {
                                            if ($('.to_account').css('display') == 'block') {
                                                $('.to_account').removeAttr("style");
                                                $('.from_reporter').removeAttr("style");
                                                $('.from_account').css('display', 'block');
                                            } else if ($('.from_reporter').css('display') == 'block') {
                                                $('.to_account').removeAttr("style");
                                                $('.from_reporter').removeAttr("style");
                                                $('.from_account').css('display', 'block');
                                            } else {
                                                $('.from_account').css('display', 'block')
                                            }
                                        });

                                        $('.to_account li').on('click', function () {
                                            if ($('.from_account').css('display') == 'block') {
                                                $('.from_account').removeAttr("style");
                                                $('.from_reporter').removeAttr("style");
                                                $('.to_account').css('display', 'block');
                                            } else if ($('.from_reporter').css('display') == 'block') {
                                                $('.from_reporter').removeAttr("style");
                                                $('.from_account').removeAttr("style");
                                                $('.to_account').css('display', 'block');
                                            } else {
                                                $('.to_account').css('display', 'block');
                                            }
                                        });
                                        $('.from_reporter li').on('click', function () {
                                            if ($('.to_account').css('display') == 'block') {
                                                $('.to_account').removeAttr("style");
                                                $('.to_account').removeAttr("style");
                                                $('.from_reporter').css('display', 'block');
                                            } else if ($('.from_account').css('display') == 'block') {
                                                $('.to_account').removeAttr("style");
                                                $('.from_account').removeAttr("style");
                                                $('.from_reporter').css('display', 'block');
                                            } else {
                                                $('.from_reporter').css('display', 'block');
                                            }
                                        });
                                        $('.filter_by').on('click', function () {
                                            $('.filter_by').removeClass('active');
                                            $('.group').css('display', 'block');
                                            $(this).addClass('active');
                                            var filter_by = $(this).attr('id');
                                            if (filter_by) {
                                                filter_by = filter_by;
                                            } else {
                                                filter_by = '';
                                            }
                                            var search_type = $(this).attr('search-type');
                                            if (search_type) {
                                                search_type = '/' + search_type;
                                            } else {
                                                search_type = '';
                                            }
                                            table_url(base_url + "admin/tickets/ticketsList/" + filter_by + search_type);
                                        });
                                        <?php }?>

                                        $('.filter_by_type').on('click', function () {
                                            var filter_by = $(this).attr('id');
                                            if (filter_by) {
                                                filter_by = filter_by;
                                            } else {
                                                filter_by = '';
                                            }
                                            table_url(base_url + "admin/tickets/ticketsList/" + filter_by);
                                        });
                                    });
                                </script>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                        <div class="tab-pane <?= $active == 2 ? 'active' : ''; ?>" id="new">
                            <?php $this->load->view("admin/tickets/new_ticket"); ?>
                        </div>
                    <?php } else { ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {  ins_data(base_url+'admin/tickets/tickets_state_report');   });
</script>

<script>
    $(document).ready(function () {  $("#form_tab").on("click", function(){
        if($('#new_form').length){   }
       // else{ins_data(base_url+'admin/tickets/new_ticket_form');}
    }); });
</script>