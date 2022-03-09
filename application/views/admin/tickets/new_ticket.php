<form method="post" data-parsley-validate="" novalidate="" id="new_form"
      action="<?= base_url() ?>admin/tickets/create_tickets/<?php
      if (!empty($tickets_info)) {
          echo $tickets_info->tickets_id;
      }
      ?>" enctype="multipart/form-data" class="form-horizontal">
    <div class="form-group">
        <label class="col-lg-3 control-label"><?= lang('ticket_code') ?> <span
                class="text-danger">*</span></label>
        <div class="col-lg-5">
            <input type="text" class="form-control" style="width:260px" value="<?php
            $this->load->helper('string');
            if (!empty($tickets_info)) {
                echo $tickets_info->ticket_code;
            } else {
                echo strtoupper(random_string('alnum', 7));
            }
            ?>" name="ticket_code">
        </div>
    </div>
    <?php $projects = $this->uri->segment(4);
    if ($projects != 'project_tickets') {
        ?>
        <input type="hidden" value="<?php echo $this->uri->segment(3) ?>"
               class="form-control"
               name="status">
    <?php } ?>

    <div class="form-group">
        <label class="col-lg-3 control-label"><?= lang('subject') ?> <span
                class="text-danger">*</span></label>
        <div class="col-lg-5">
            <input type="text" value="<?php
            if (!empty($tickets_info)) {
                echo $tickets_info->subject;
            }
            ?>" class="form-control" placeholder="Sample Ticket Subject" name="subject"
                   required>
        </div>
    </div>
    <?php if ($this->session->userdata('user_type') == '1') {
        $type = $this->uri->segment(5);
        if (!empty($type) && !is_numeric($type)) {
            $ex = explode('_', $type);
            if ($ex[0] == 'c') {
                $primary_contact = $ex[1];
            }
        }
        ?>
        <div class="form-group">
            <label class="col-lg-3 control-label"><?= lang('reporter') ?> <span
                    class="text-danger">*</span>
            </label>
            <div class="col-lg-5">
                <div class=" ">
                    <select class="form-control select_box" style="width:100%"
                            name="reporter" required>
                        <option value=""><?= lang('none') ?></option>
                        <?php
                        $users = $this->db->get('tbl_users')->result();
                        if (!empty($users)) {
                            foreach ($users as $v_user):
                                $users_info = $this->db->where(array("user_id" => $v_user->user_id))->get('tbl_account_details')->row();
                                if (!empty($users_info)) {
                                    if ($v_user->role_id == 1) {
                                        $role = lang('admin');
                                    } elseif ($v_user->role_id == 2) {
                                        $role = lang('client');
                                    } else {
                                        $role = lang('staff');
                                    }
                                    ?>
                                    <option value="<?= $users_info->user_id ?>" <?php
                                    if (!empty($tickets_info) && $tickets_info->reporter == $users_info->user_id) {
                                        echo 'selected';
                                    } else if (!empty($primary_contact) && $primary_contact == $users_info->user_id) {
                                        echo 'selected';
                                    }
                                    ?>><?= $users_info->fullname . ' (' . $role . ')'; ?></option>
                                    <?php
                                }
                            endforeach;
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="form-group">
        <label class="col-lg-3 control-label"><?= lang('project') ?></label>
        <div class="col-lg-5">
            <div class=" ">
                <select class="form-control select_box" style="width:100%"
                        name="project_id">
                    <option><?= lang('none') ?></option>
                    <?php
                    $project = $this->db->get('tbl_project')->result();
                    $project_id = $this->uri->segment(6);
                    if (!empty($project)) {
                        foreach ($project as $v_project):
                            ?>
                            <option value="<?= $v_project->project_id ?>" <?php
                            if (!empty($tickets_info) && $tickets_info->project_id == $v_project->project_id) {
                                echo 'selected';
                            } else if ($projects == 'project_tickets') {
                                if (!empty($project_id) && $project_id == $v_project->project_id) {
                                    echo 'selected';
                                }
                            }
                            ?>><?= $v_project->project_name; ?></option>
                        <?php
                        endforeach;
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-lg-3 control-label"><?= lang('priority') ?> <span
                class="text-danger">*</span>
        </label>
        <div class="col-lg-5">
            <div class=" ">
                <select name="priority" class="form-control">
                    <?php
                    $priorities = $this->db->get('tbl_priority')->result();
                    if (!empty($priorities)) {
                        foreach ($priorities as $v_priorities):
                            ?>
                            <option value="<?= $v_priorities->priority ?>" <?php
                            if (!empty($tickets_info) && $tickets_info->priority == $v_priorities->priority || config_item('default_priority') == $v_priorities->priority) {
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
        <label class="col-lg-3 control-label"><?= lang('department') ?> <span
                class="text-danger">*</span>
        </label>
        <div class="col-lg-5">
            <div class="input-group">
                <select name="departments_id" class="form-control select_box"
                        style="width: 100%" required>
                    <?php
                    $all_departments = $this->db->get('tbl_departments')->result();
                    if (!empty($all_departments)) {
                        foreach ($all_departments as $v_dept):
                            ?>
                            <option value="<?= $v_dept->departments_id ?>" <?php
                            if (!empty($tickets_info) && $tickets_info->departments_id == $v_dept->departments_id) {
                                echo 'selected';
                            } else if (empty($tickets_info) && config_item('default_department') == $v_dept->departments_id) {
                                echo 'selected';
                            }
                            ?>><?= $v_dept->deptname ?></option>
                        <?php
                        endforeach;
                    }
                    $acreated = can_action('70', 'created');
                    ?>
                </select>
                <?php if (!empty($acreated)) { ?>
                    <div class="input-group-addon"
                         title="<?= lang('new') . ' ' . lang('department') ?>"
                         data-toggle="tooltip" data-placement="top">
                        <a data-toggle="modal" data-target="#myModal"
                           href="<?= base_url() ?>admin/departments/edit_departments/inline/true"><i
                                class="fa fa-plus"></i></a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="field-1"
               class="col-sm-3 control-label"><?= lang('tags') ?></label>
        <div class="col-sm-5">
            <input type="text" name="tags" data-role="tagsinput"
                   class="form-control"
                   value="<?php
                   if (!empty($tickets_info->tags)) {
                       echo $tickets_info->tags;
                   }
                   ?>">
        </div>
    </div>

    <?php
    if (!empty($tickets_info)) {
        $tickets_id = $tickets_info->tickets_id;
    } else {
        $tickets_id = null;
    }
    ?>
    <?= custom_form_Fields(7, $tickets_id); ?>


    <div class="form-group" style="margin-bottom: 0px">
        <label for="field-1"
               class="col-sm-3 control-label"><?= lang('attachment') ?></label>

        <div class="col-sm-5">
            <div id="comments_file-dropzone" class="dropzone mb15">

            </div>
            <div id="comments_file-dropzone-scrollbar">
                <div id="comments_file-previews">
                    <div id="file-upload-row" class="mt pull-left">

                        <div class="preview box-content pr-lg" style="width:100px;">
                                                    <span data-dz-remove class="pull-right" style="cursor: pointer">
                                    <i class="fa fa-times"></i>
                                </span>
                            <img data-dz-thumbnail class="upload-thumbnail-sm"/>
                            <input class="file-count-field" type="hidden" name="files[]"
                                   value=""/>
                            <div
                                class="mb progress progress-striped upload-progress-sm active mt-sm"
                                role="progressbar" aria-valuemin="0" aria-valuemax="100"
                                aria-valuenow="0">
                                <div class="progress-bar progress-bar-success"
                                     style="width:0%;"
                                     data-dz-uploadprogress></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (!empty($tickets_info->upload_file)) {
                $uploaded_file = json_decode($tickets_info->upload_file);
            }
            if (!empty($uploaded_file)) {
                foreach ($uploaded_file as $v_files_image) { ?>
                    <div class="pull-left mt pr-lg mb" style="width:100px;">
                                                        <span data-dz-remove class="pull-right existing_image"
                                                              style="cursor: pointer"><i
                                                                class="fa fa-times"></i></span>
                        <?php if ($v_files_image->is_image == 1) { ?>
                            <img data-dz-thumbnail
                                 src="<?php echo base_url() . $v_files_image->path ?>"
                                 class="upload-thumbnail-sm"/>
                        <?php } else { ?>
                            <span data-toggle="tooltip" data-placement="top"
                                  title="<?= $v_files_image->fileName ?>"
                                  class="mailbox-attachment-icon"><i
                                    class="fa fa-file-text-o"></i></span>
                        <?php } ?>

                        <input type="hidden" name="path[]"
                               value="<?php echo $v_files_image->path ?>">
                        <input type="hidden" name="fileName[]"
                               value="<?php echo $v_files_image->fileName ?>">
                        <input type="hidden" name="fullPath[]"
                               value="<?php echo $v_files_image->fullPath ?>">
                        <input type="hidden" name="size[]"
                               value="<?php echo $v_files_image->size ?>">
                        <input type="hidden" name="is_image[]"
                               value="<?php echo $v_files_image->is_image ?>">
                    </div>
                <?php }; ?>
            <?php }; ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $(".existing_image").on("click", function(){
                        $(this).parent().remove();
                    });

                    fileSerial = 0;
                    // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
                    var previewNode = document.querySelector("#file-upload-row");
                    previewNode.id = "";
                    var previewTemplate = previewNode.parentNode.innerHTML;
                    previewNode.parentNode.removeChild(previewNode);
                    Dropzone.autoDiscover = false;
                    var projectFilesDropzone = new Dropzone("#comments_file-dropzone", {
                        url: "<?= base_url()?>admin/global_controller/upload_file",
                        thumbnailWidth: 80,
                        thumbnailHeight: 80,
                        parallelUploads: 20,
                        previewTemplate: previewTemplate,
                        dictDefaultMessage: '<?php echo lang("file_upload_instruction"); ?>',
                        autoQueue: true,
                        previewsContainer: "#comments_file-previews",
                        clickable: true,
                        accept: function (file, done) {
                            if (file.name.length > 200) {
                                done("Filename is too long.");
                                $(file.previewTemplate).find(".description-field").remove();
                            }
                            //validate the file
                            $.ajax({
                                url: "<?= base_url()?>admin/global_controller/validate_project_file",
                                data: {file_name: file.name, file_size: file.size},
                                cache: false,
                                type: 'POST',
                                dataType: "json",
                                success: function (response) {
                                    if (response.success) {
                                        fileSerial++;
                                        $(file.previewTemplate).find(".description-field").attr("name", "comment_" + fileSerial);
                                        $(file.previewTemplate).append("<input type='hidden' name='file_name_" + fileSerial + "' value='" + file.name + "' />\n\
                                                                        <input type='hidden' name='file_size_" + fileSerial + "' value='" + file.size + "' />");
                                        $(file.previewTemplate).find(".file-count-field").val(fileSerial);
                                        done();
                                    } else {
                                        $(file.previewTemplate).find("input").remove();
                                        done(response.message);
                                    }
                                }
                            });
                        },
                        processing: function () {
                            $("#file-save-button").prop("disabled", true);
                        },
                        queuecomplete: function () {
                            $("#file-save-button").prop("disabled", false);
                        },
                        fallback: function () {
                            //add custom fallback;
                            $("body").addClass("dropzone-disabled");
                            $('.modal-dialog').find('[type="submit"]').removeAttr('disabled');

                            $("#comments_file-dropzone").hide();

                            $("#file-modal-footer").prepend("<button id='add-more-file-button' type='button' class='btn  btn-default pull-left'><i class='fa fa-plus-circle'></i> " + "<?php echo lang("add_more"); ?>" + "</button>");

                            $("#file-modal-footer").on("click", "#add-more-file-button", function () {
                                var newFileRow = "<div class='file-row pb pt10 b-b mb10'>"
                                    + "<div class='pb clearfix '><button type='button' class='btn btn-xs btn-danger pull-left mr remove-file'><i class='fa fa-times'></i></button> <input class='pull-left' type='file' name='manualFiles[]' /></div>"
                                    + "<div class='mb5 pb5'><input class='form-control description-field'  name='comment[]'  type='text' style='cursor: auto;' placeholder='<?php echo lang("comment") ?>' /></div>"
                                    + "</div>";
                                $("#comments_file-previews").prepend(newFileRow);
                            });
                            $("#add-more-file-button").trigger("click");
                            $("#comments_file-previews").on("click", ".remove-file", function () {
                                $(this).closest(".file-row").remove();
                            });
                        },
                        success: function (file) {
                            setTimeout(function () {
                                $(file.previewElement).find(".progress-striped").removeClass("progress-striped").addClass("progress-bar-success");
                            }, 1000);
                        }
                    });

                })
            </script>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 control-label"><?= lang('ticket_message') ?> </label>
        <div class="col-lg-7">
                        <textarea name="body" class="form-control textarea_" placeholder="<?= lang('message') ?>"><?php
                            if (!empty($tickets_info)) {
                                echo $tickets_info->body;
                            } else {
                                echo set_value('body');
                            }
                            ?></textarea>

        </div>
    </div>
    <?php
    $permissionL = null;
    if (!empty($tickets_info->permission)) {
        $permissionL = $tickets_info->permission;
    }
    ?>
    <?= get_permission(3, 9, $permission_user, $permissionL, ''); ?>

    <div class="btn-bottom-toolbar text-right">
        <?php
        if (!empty($tickets_info)) { ?>
            <button type="submit" id="file-save-button"
                    class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
            <button type="button" onclick="goBack()"
                    class="btn btn-sm btn-danger"><?= lang('cancel') ?></button>
        <?php } else {
            ?>
            <button type="submit" id="file-save-button"
                    class="btn btn-sm btn-primary"><?= lang('create_ticket') ?></button>
        <?php }
        ?>
    </div>
</form>
