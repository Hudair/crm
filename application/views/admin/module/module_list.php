<?= message_box('success'); ?>
<?= message_box('error'); ?>

<?php echo form_open_multipart(base_url('admin/my_module/upload'), ['class' => 'form-horizontal', 'id' => 'module_install_form']); ?>
<div class="panel panel-custom mt-lg update_upload_new_module ">
    <header class="panel-heading ">
        <div class="panel-title"><strong><?= lang('upload_module') ?></strong></div>
    </header>
    <div class="panel-body">
        <div class="col-sm-12 block text-center">
            <p class="text-md well p-sm font-medium"><?= lang('module_upload_info');
                                                        $type =   $this->session->userdata('type');
                                                        if (!empty($type)) { ?>
            <div class="alert alert-<?= $type == 'error' ? 'danger' : 'success' ?>">
                <?php echo $this->session->userdata('message');
                                                            $this->session->unset_userdata('type');
                                                            $this->session->unset_userdata('message');
                ?>
            </div>
        <?php }
        ?>

        </p>
        </div>
        <div class="form-group">
            <label for="field-1" class="col-sm-3 control-label"><?= lang('module') . ' ' . lang('file') ?> <span class="text-danger">*</span></label>
            <div class="col-sm-6">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <span class="btn btn-default btn-file"><span class="fileinput-new"><?= lang('select_file') ?></span>
                        <span class="fileinput-exists"><?= lang('change') ?></span>
                        <input type="file" name="module">
                    </span>
                    <span class="fileinput-filename"></span>
                    <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">&times;</a>

                </div>
                <div id="msg_pdf" style="color: #e11221"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-2">
                <button type="submit" id="file-save-button" class="btn btn-primary btn-block"><?= lang('upload') ?></button>
            </div>
        </div>
    </div>
</div>

<?php echo form_close(); ?>

<div class="panel panel-custom mt-lg">
    <header class="panel-heading ">
        <div class="panel-title"><strong><?= lang('all_module') ?></strong>
            <div class="pull-right">
                <?php
                $system_name = 'upload_new_module';
                echo '<span style="cursor:pointer" class="btn btn-primary" name="' . $system_name . '" onclick="show_update_option(this)" data-show="update_' . $system_name . '">' . lang('upload_new_module') . '</span>'; ?>
            </div>
        </div>
    </header>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped" id="datatable_action" width="100%">
                <thead>
                    <tr>
                        <th>
                            <?php echo lang('module'); ?>
                        </th>
                        <th>
                            <?php echo lang('description'); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <?php

                    foreach ($modules as $module) {
                        $system_name = $module['system_name'];
                        $database_upgrade_is_required = $this->module->is_database_upgrade_required($system_name);
                    ?>
                        <tr class="<?php if ($module['activated'] === 1 && !$database_upgrade_is_required) {
                                        // echo ' alert-info';
                                    } ?><?php if ($database_upgrade_is_required) {
                                            echo ' text-warning';
                                        } ?>">
                            <td data-order="<?php echo $system_name; ?>">
                                <p>
                                    <b>
                                        <?php
                                        $moduleName = $module['headers']['module_name'];
                                        if (isset($module['headers']['uri'])) {
                                            echo '<a target="_blank" href="' . $module['headers']['uri'] . '">' . $moduleName . '</a>';
                                        } else {
                                            echo $moduleName;
                                        } ?>
                                    </b>
                                </p>
                                <?php
                                $actions = [];
                                $versionRequirementMet = $this->module->is_minimum_version_requirement_met($system_name);
                                if ($module['activated'] === 0 && $versionRequirementMet) {
                                    array_unshift($actions, '<a href="' . base_url('admin/my_module/activate/' . $system_name) . '">' . lang('activate') . '</a>');
                                }

                                if ($module['activated'] === 1) {
                                    array_unshift($actions, '<a href="' . base_url('admin/my_module/deactivate/' . $system_name) . '">' . lang('deactivate') . '</a>');
                                }

                                if ($database_upgrade_is_required) {
                                    $actions[] = '<a href="' . base_url('admin/my_module/upgrade_database/' . $system_name) . '" class="text-warning">' . lang('upgrade_database') . '</a>';
                                }

                                if ($module['activated'] === 0) {
                                    $actions[] = btn_delete(base_url('admin/my_module/uninstall/' . $system_name), lang('uninstall'));
                                }
                                echo implode('&nbsp;|&nbsp;', $actions);

                                if (!$versionRequirementMet) {
                                    echo '<div class="label label-danger ">';
                                    echo lang('module_requires_at_least', $module['headers']['requires_at_least']);
                                    if ($module['activated'] === 0) {
                                        echo ' Hence, this cannot be activated';
                                    }
                                    echo '</div>';
                                }
                                $newVersionData = $this->module->new_version_available($system_name);
                                if (!empty($newVersionData)) {
                                    echo '<div class="label label-success ml">';
                                    echo lang('module_new_version_available', $module['headers']['module_name']);
                                    $version_actions = [];
                                    if (isset($newVersionData['changelog']) && !empty($newVersionData['changelog'])) {
                                        $version_actions[] = '<a href="' . $newVersionData['changelog'] . '" target="_blank">' . lang('module_changelog', $newVersionData['new_version']) . '</a>';
                                    } else {
                                        $version_actions[] = lang('module_version', wordwrap($newVersionData['new_version'], 1, '.', true));
                                    }
                                    $version_actions[] = '</div> | <span> <span style="cursor:pointer" class="text-danger" name="' . $system_name . '" onclick="show_update_option(this)" data-show="update_' . $system_name . '">' . lang('update') . '</span>';
                                    echo implode('&nbsp;', $version_actions);
                                    echo '</span>';
                                    echo '<div class="update_' . $system_name . ' mt hidden">                                            
                                            <input required type="text" value="" placeholder="' . lang('enter_your') . ' ' . lang('envato_username') . '" class="form-control ml" style="width:40% !important" name="buyer_' . $system_name . '"/> 
                                            <input required type="text" value="" placeholder="' . lang('enter_your') . ' ' . lang('purchase_key') . '" class="form-control mr"  style="width:40% !important" name="purchase_key_' . $system_name . '"/>
                                            <input type="hidden" value="' . $newVersionData['new_version'] . '" name="latest_version_' . $system_name . '"/>
                                            <span class="btn btn-success" data-original-text="' . lang('update') . '" name="' . $system_name . '" onclick="update_module(this)" title="' . lang('update') . '">' . lang('update') . '</span>
                                            </div>';
                                    echo '<div id="update_messages_' . $system_name . '"></div>';
                                }
                                ?>
                            </td>
                            <td>
                                <p>
                                    <?php echo isset($module['headers']['description']) ? $module['headers']['description'] : ''; ?>
                                </p>
                                <?php
                                $module_description_info = [];

                                if (isset($module['headers']['author'])) {
                                    $author = $module['headers']['author'];
                                    if (isset($module['headers']['author_uri'])) {
                                        $author = '<a target="_blank" href="' . $module['headers']['author_uri'] . '">' . $author . '</a>';
                                    }
                                    array_unshift($module_description_info, lang('module_by', $author));
                                }

                                array_unshift($module_description_info, lang('module_version', $module['headers']['version']));
                                echo implode('&nbsp;|&nbsp;', $module_description_info); ?>
                            </td>
                        </tr>
                    <?php
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function show_update_option(event) {
        // var module = $(event).attr('name');
        var id = $(event).data('show');
        $('.' + id).toggleClass('hidden');
    }

    function update_module(event) {
        var module = $(event).attr('name');
        var key = $('input[name="purchase_key_' + module + '"]');
        var author = $('input[name="buyer_' + module + '"]');
        var version = $('input[name="latest_version_' + module + '"]');

        if (key.hasClass('parsley-error')) {
            key.removeClass('parsley-error');
        }
        if (author.hasClass('parsley-error')) {
            author.removeClass('parsley-error');
        }
        var purchase_key = key.val();
        var buyer = author.val();
        var latest_version = version.val();
        var update_errors;
        if (purchase_key != '' && buyer != '') { // validate            
            $(event).html('Please wait...');
            $(event).addClass('disabled');
            $.post('<?= base_url() ?>admin/auto_update', {
                purchase_key: purchase_key,
                module: module,
                buyer: buyer,
                latest_version: latest_version,
                auto_update: true
            }).done(function(res) {
                if (res) {
                    var result = JSON.parse(res);
                    console.log('sibe:' + result);
                    $('#update_messages_' + module + '').html('<div class="alert alert-danger mt-lg"></div>');
                    $('#update_messages_' + module + ' .alert').append('<p>' + result.message + '</p>');
                    $(event).removeClass('disabled');
                    $(event).html($(event).data('original-text'));
                } else {
                    $.post('<?= base_url('admin/my_module/upgrade_database/') ?>' + module, {
                        auto_update: true,
                        latest_version: latest_version,
                    }).done(function(result) {
                        console.log('dddd:' + result);

                        $('#update_messages_' + module + '').html('<div class="alert alert-success mt-lg"></div>');
                        $('#update_messages_' + module + ' .alert').append('<p>' + result + '</p>');
                        $(event).removeClass('disabled');
                        $(event).html($(event).data('original-text'));
                        setTimeout(function() {
                            window.location.reload();
                        }, 5000);
                    }).fail(function(result) {
                        $('#update_messages_' + module + '').html('<div class="alert alert-danger mt-lg"></div>');
                        $('#update_messages_' + module + ' .alert').append('<p>' + result + '</p>');
                        $(event).removeClass('disabled');
                        $(event).html($(event).data('original-text'));
                    });
                }
            }).fail(function(response) {
                $('#update_messages_' + module + '').html('<div class="alert alert-danger mt-lg"></div>');
                $('#update_messages_' + module + ' .alert').append('<p> sss' + response.responseText + '</p>');
                $(event).removeClass('disabled');
                $(event).html($(event).data('original-text'));
            });

        } else if (purchase_key != '' && buyer == '') {
            if (key.hasClass('parsley-error')) {
                key.removeClass('parsley-error');
            }
            author.addClass('parsley-error');
        } else if (buyer != '' && purchase_key == '') {
            key.addClass('parsley-error');
            if (author.hasClass('parsley-error')) {
                author.removeClass('parsley-error');
            }
        } else {
            key.addClass('parsley-error');
            author.addClass('parsley-error');
        }
    }
</script>