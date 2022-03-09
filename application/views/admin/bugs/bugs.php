<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php include_once 'assets/admin-ajax.php'; ?>

<div id="bugs_state_report_div">
    <?php //$this->load->view("admin/bugs/bugs_state_report"); ?>
</div>


<?php
$created = can_action('58', 'created');
$edited = can_action('58', 'edited');
$deleted = can_action('58', 'deleted');
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

                    <li class="b_status" id="assigned_to_me"><a href="#"><?php echo lang('assigned_to_me'); ?></a></li>
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
                                    <li class="filter_by" id="<?= $v_reporter->user_id ?>" search-type="from_reporter">
                                        <a href="#"><?php echo fullname($v_reporter->user_id); ?></a>
                                    </li>
                                <?php }
                            }
                            ?>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                    <li class="dropdown-submenu pull-left " id="to_account">
                        <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('staff'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left to_account"
                            style="">
                            <?php
                            if (count($assign_user) > 0) { ?>
                                <?php foreach ($assign_user as $v_staff) {
                                    ?>
                                    <li class="filter_by" id="<?= $v_staff->user_id ?>"
                                        search-type="by_staff">
                                        <a href="#"><?php echo fullname($v_staff->user_id); ?></a>
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
                <li class="<?= $active == 1 ? 'active' : '' ?>"><a href="#task_list"
                                                                   data-toggle="tab"><?= lang('all_bugs') ?></a></li>
                <li class="<?= $active == 2 ? 'active' : '' ?>"><a href="#assign_task"
                                                                   data-toggle="tab"><?= lang('new_bugs') ?></a></li>
            </ul>
            <div class="tab-content bg-white">
                <!-- Stock Category List tab Starts -->
                <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="task_list" style="position: relative;">
                    <?php } else { ?>
                    <div class="panel panel-custom">
                        <header class="panel-heading ">
                            <div class="panel-title"><strong><?= lang('all_bugs') ?></strong></div>
                        </header>
                        <?php } ?>
                        <div class="box" style="border: none; padding-top: 15px;" data-collapsed="0">
                            <div class="box-body">
                                <!-- Table -->
                                <table class="table table-striped DataTables " id="DataTables" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <th><?= lang('bug_title') ?></th>
                                        <th><?= lang('date') ?></th>
                                        <th><?= lang('status') ?></th>
                                        <th><?= lang('severity') ?></th>
                                        <?php if ($this->session->userdata('user_type') == '1') { ?>
                                            <th><?= lang('reporter') ?></th>
                                        <?php } ?>
                                        <th><?= lang('assigned_to') ?></th>
                                        <?php $show_custom_fields = custom_form_table(6, null);
                                        if (!empty($show_custom_fields)) {
                                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                                if (!empty($c_label)) {
                                                    ?>
                                                    <th><?= $c_label ?> </th>
                                                <?php }
                                            }
                                        }
                                        ?>
                                        <?php if (!empty($edited) || !empty($deleted)) { ?>
                                            <th><?= lang('action') ?></th>
                                        <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        list = base_url + "admin/bugs/bugsList";
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
                                            table_url(base_url + "admin/bugs/bugsList/" + filter_by + search_type);
                                        });
                                        <?php }?>
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                        <!-- Add Stock Category tab Starts -->
                        <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="assign_task"
                             style="position: relative;">
                            <div class="box" style="border: none; padding-top: 15px;" data-collapsed="0">
                                <div class="panel-body">
                                    <form data-parsley-validate="" novalidate=""
                                          action="<?php echo base_url() ?>admin/bugs/save_bug/<?php if (!empty($bug_info->bug_id)) echo $bug_info->bug_id; ?>"
                                          method="post" class="form-horizontal">


                                        <div class="form-group">
                                            <label class="col-sm-3 control-label"><?= lang('issue_#') ?><span
                                                    class="required">*</span></label>
                                            <div class="col-sm-5">
                                                <input type="text" class="form-control" style="width:260px" value="<?php
                                                $this->load->helper('string');
                                                if (!empty($bug_info)) {
                                                    echo $bug_info->issue_no;
                                                } else {
                                                    echo strtoupper(random_string('alnum', 7));
                                                }
                                                ?>" name="issue_no">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label"><?= lang('bug_title') ?><span
                                                    class="required">*</span></label>
                                            <div class="col-sm-5">
                                                <input type="text" name="bug_title" required class="form-control"
                                                       value="<?php if (!empty($bug_info->bug_title)) echo $bug_info->bug_title; ?>"/>
                                            </div>
                                        </div>
                                        <?php
                                        if (!empty($bug_info->project_id)) {
                                            $project_id = $bug_info->project_id;
                                        } elseif (!empty($project_id)) {
                                            $project_id = $project_id; ?>
                                            <input type="hidden" name="un_project_id" required class="form-control"
                                                   value="<?php echo $project_id ?>"/>
                                        <?php }
                                        if (!empty($bug_info->opportunities_id)) {
                                            $opportunities_id = $bug_info->opportunities_id;
                                        } elseif (!empty($opportunities_id)) {
                                            $opportunities_id = $opportunities_id; ?>
                                            <input type="hidden" name="un_opportunities_id" required
                                                   class="form-control"
                                                   value="<?php echo $opportunities_id ?>"/>
                                        <?php }
                                        ?>
                                        <div class="form-group" id="border-none">
                                            <label for="field-1"
                                                   class="col-sm-3 control-label"><?= lang('related_to') ?> </label>
                                            <div class="col-sm-5">
                                                <select name="related_to" class="form-control" id="check_related"
                                                        onchange="get_related_moduleName(this.value)">
                                                    <option
                                                        value="0"> <?= lang('none') ?> </option>
                                                    <option
                                                        value="project" <?= (!empty($project_id) ? 'selected' : '') ?>> <?= lang('project') ?> </option>
                                                    <option
                                                        value="opportunities" <?= (!empty($opportunities_id) ? 'selected' : '') ?>> <?= lang('opportunities') ?> </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group" id="related_to">
                                        </div>
                                        <?php
                                        if (!empty($project_id)):?>
                                            <div class="form-group <?= !empty($project_id) ? '' : 'company' ?>">
                                                <label for="field-1"
                                                       class="col-sm-3 control-label"><?= lang('project') ?>
                                                    <span
                                                        class="required">*</span></label>
                                                <div class="col-sm-5">
                                                    <select name="project_id" style="width: 100%"
                                                            class="select_box <?= !empty($project_id) ? '' : 'company' ?>"
                                                            required="1">
                                                        <?php
                                                        $all_project = $this->bugs_model->get_permission('tbl_project');
                                                        if (!empty($all_project)) {
                                                            foreach ($all_project as $v_project) {
                                                                ?>
                                                                <option value="<?= $v_project->project_id ?>" <?php
                                                                if (!empty($project_id)) {
                                                                    echo $v_project->project_id == $project_id ? 'selected' : '';
                                                                }
                                                                ?>><?= $v_project->project_name ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div id="milestone"></div>
                                            </div>
                                        <?php endif ?>
                                        <?php if (!empty($opportunities_id)): ?>
                                            <div class="form-group <?= !empty($opportunities_id) ? '' : 'company' ?>">
                                                <label for="field-1"
                                                       class="col-sm-3 control-label"><?= lang('opportunities') ?>
                                                    <span class="required">*</span></label>
                                                <div class="col-sm-5">
                                                    <select name="opportunities_id" style="width: 100%"
                                                            class="select_box <?= !empty($opportunities_id) ? '' : 'company' ?>"
                                                            required="1">
                                                        <?php
                                                        if (!empty($all_opportunities_info)) {
                                                            foreach ($all_opportunities_info as $v_opportunities) {
                                                                ?>
                                                                <option
                                                                    value="<?= $v_opportunities->opportunities_id ?>" <?php
                                                                if (!empty($opportunities_id)) {
                                                                    echo $v_opportunities->opportunities_id == $opportunities_id ? 'selected' : '';
                                                                }
                                                                ?>><?= $v_opportunities->opportunity_name ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php endif ?>
                                        <div class="form-group" id="border-none">
                                            <label for="field-1" class="col-sm-3 control-label"><?= lang('reporter') ?>
                                                <span
                                                    class="required">*</span></label>
                                            <div class="col-sm-5">
                                                <select name="reporter" style="width: 100%" class="select_box"
                                                        required="">
                                                    <?php
                                                    $type = $this->uri->segment(4);
                                                    if (!empty($type) && !is_numeric($type)) {
                                                        $ex = explode('_', $type);
                                                        if ($ex[0] == 'c') {
                                                            $primary_contact = $ex[1];
                                                        }
                                                    }
                                                    $reporter_info = $this->db->get('tbl_users')->result();
                                                    if (!empty($reporter_info)) {
                                                        foreach ($reporter_info as $key => $v_reporter) {
                                                            $users_info = $this->db->where(array("user_id" => $v_reporter->user_id))->get('tbl_account_details')->row();
                                                            if (!empty($users_info)) {
                                                                if ($v_reporter->role_id == 1) {
                                                                    $role = lang('admin');
                                                                } elseif ($v_reporter->role_id == 2) {
                                                                    $role = lang('client');
                                                                } else {
                                                                    $role = lang('staff');
                                                                }
                                                                ?>
                                                                <option value="<?= $users_info->user_id ?>" <?php
                                                                if (!empty($bug_info->reporter)) {
                                                                    echo $v_reporter->user_id == $bug_info->reporter ? 'selected' : '';
                                                                } else if (!empty($primary_contact) && $primary_contact == $users_info->user_id) {
                                                                    echo 'selected';
                                                                }
                                                                ?>><?= $users_info->fullname . ' (' . $role . ')'; ?></option>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('priority') ?> <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-lg-5">
                                                <div class=" ">
                                                    <select name="priority" class="form-control">
                                                        <?php
                                                        $priorities = $this->db->get('tbl_priority')->result();
                                                        if (!empty($priorities)) {
                                                            foreach ($priorities as $v_priorities):
                                                                ?>
                                                                <option value="<?= $v_priorities->priority ?>" <?php
                                                                if (!empty($bug_info) && $bug_info->priority == $bug_info->priority) {
                                                                    echo 'selected';
                                                                }
                                                                ?>><?= ($v_priorities->priority) ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('severity') ?> <span
                                                    class="text-danger">*</span> </label>
                                            <div class="col-lg-5">
                                                <div class=" ">
                                                    <select name="severity" class="form-control">
                                                        <?php
                                                        $severity = array('minor', 'major', 'show_stopper', 'must_be_fixed');
                                                        if (!empty($severity)) {
                                                            foreach ($severity as $v_severity):
                                                                ?>
                                                                <option value="<?= $v_severity ?>" <?php
                                                                if (!empty($bug_info) && $bug_info->severity == $v_severity) {
                                                                    echo 'selected';
                                                                }
                                                                ?>><?= lang($v_severity) ?></option>
                                                                <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="field-1"
                                                   class="col-sm-3 control-label"><?= lang('description') ?> </label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control textarea_" name="bug_description"><?php if (!empty($bug_info->bug_description)) echo $bug_info->bug_description; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="field-1"
                                                   class="col-sm-3 control-label"><?= lang('reproducibility') ?> </label>
                                            <div class="col-sm-7">
                                                <textarea class="form-control textarea"
                                                          name="reproducibility"><?php if (!empty($bug_info->reproducibility)) echo $bug_info->reproducibility; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group" id="border-none">
                                            <label for="field-1"
                                                   class="col-sm-3 control-label"><?= lang('bug_status') ?>
                                                <span
                                                    class="required">*</span></label>
                                            <div class="col-sm-5">

                                                <select name="bug_status" class="form-control" required>
                                                    <option
                                                        value="unconfirmed" <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'unconfirmed' ? 'selected' : '' ?>> <?= lang('unconfirmed') ?> </option>
                                                    <option
                                                        value="confirmed" <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'confirmed' ? 'selected' : '' ?>> <?= lang('confirmed') ?> </option>
                                                    <option
                                                        value="in_progress" <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'in_progress' ? 'selected' : '' ?>> <?= lang('in_progress') ?> </option>
                                                    <option
                                                        value="resolved" <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'resolved' ? 'selected' : '' ?>> <?= lang('resolved') ?> </option>
                                                    <option
                                                        value="verified" <?php if (!empty($bug_info->bug_status)) echo $bug_info->bug_status == 'verified' ? 'selected' : '' ?>> <?= lang('verified') ?> </option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php if (!empty($project_id)): ?>
                                            <div class="form-group">
                                                <label for="field-1"
                                                       class="col-sm-3 control-label"><?= lang('visible_to_client') ?>
                                                    <span class="required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input data-toggle="toggle" name="client_visible" value="Yes" <?php
                                                    if (!empty($bug_info) && $bug_info->client_visible == 'Yes') {
                                                        echo 'checked';
                                                    }
                                                    ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"
                                                           data-onstyle="success" data-offstyle="danger"
                                                           type="checkbox">
                                                </div>
                                            </div>
                                        <?php endif ?>
                                        <?php
                                        if (!empty($bug_info)) {
                                            $bug_id = $bug_info->bug_id;
                                            $permissionL = $bug_info->permission;
                                        } else {
                                            $bug_id = null;
                                            $permissionL = null;
                                        }
                                        ?>
                                        <?= custom_form_Fields(6, $bug_id); ?>
                                        <?= get_permission(3, 8, $assign_user, $permissionL, lang('assined_to')); ?>

                                        <div class="btn-bottom-toolbar text-right">
                                            <?php
                                            if (!empty($bug_info)) { ?>
                                                <button type="submit"
                                                        class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                                                <button type="button" onclick="goBack()"
                                                        class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
                                            <?php } else {
                                                ?>
                                                <button type="submit"
                                                        class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                                            <?php }
                                            ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php }else{ ?>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {  ins_data(base_url+'admin/bugs/bugs_state_report')   });
</script>