<?php
$my_todo_list = $this->db->where('user_id', $this->session->userdata('user_id'))->order_by('order', 'ASC')->get('tbl_todo')->result();
?>
<div class="panel panel-custom menu" style="height: 437px;overflow-y: scroll;">
    <header class="panel-heading mb0">
        <h3 class="panel-title"><?= lang('to_do') . ' ' . lang('list') ?> |
            <a class="text-sm" target="_blank"
               href="<?= base_url() ?>admin/dashboard/all_todo"><?= lang('view_all') ?></a>
            <div class="pull-right " style="padding-top: 0px;padding-bottom: 8px">
                <a href="<?= base_url() ?>admin/dashboard/new_todo"
                   class="btn btn-xs btn-success" data-toggle="modal" data-placement="top"
                   data-target="#myModal_lg"><?= lang('add_new') ?></a>
            </div>
        </h3>
    </header>
    <div class="">

        <table class="table todo-preview table-striped m-b-none text-sm items">
            <thead>
            <tr>
                <th></th>
                <th></th>
                <th><?= lang('what') . ' ' . lang('to_do') ?></th>
                <th><?= lang('status') ?></th>
                <th><?= lang('end_date') ?></th>
                <th></th>
            </tr>
            </thead>
<tbody>
<?php
if (!empty($my_todo_list)):foreach ($my_todo_list as $tkey => $my_todo):
    if ($my_todo->status != 3) {
        if ($my_todo->status == 3) {
            $todo_label = '<small style="font-size:10px;padding:2px;" class="label label-success ">' . lang('done') . '</small>';
        } elseif ($my_todo->status == 2) {
            $todo_label = '<small style="font-size:10px;padding:2px;" class="label label-danger ">' . lang('on_hold') . '</small>';
        } else {
            $todo_label = '<small style="font-size:10px;padding:2px;" class="label label-warning">' . lang('in_progress') . '</small>';
        }
        if (!empty($my_todo->due_date)) {
            $due_date = $my_todo->due_date;
        } else {
            $due_date = date('D-M-Y');
        }
        ?>
        <tr class="sortable item" data-item-id="<?= $my_todo->todo_id ?>">
            <td class="item_no dragger pl-lg pr-lg"><?= $tkey + 1 ?></td>
            <td>
                <div class="complete-todo checkbox c-checkbox ">
                    <label>
                        <input type="checkbox" data-id="<?= $my_todo->todo_id ?>"
                               style="position: absolute;" <?php
                        if ($my_todo->status == 3) {
                            echo 'checked';
                        }
                        ?>>
                        <span class="fa fa-check"></span>
                    </label>
                </div>
            </td>
            <td>
                <a <?php
                if ($my_todo->status == 3) {
                    echo 'style="text-decoration: line-through;"';
                }
                ?> class="text-info" data-toggle="modal" data-target="#myModal_lg"
                   href="<?= base_url() ?>admin/dashboard/new_todo/<?= $my_todo->todo_id ?>">
                    <?php echo $my_todo->title; ?></a>
                <?php if (!empty($my_todo->assigned) && $my_todo->assigned != 0) {
                    //$a_userinfo = $this->db->where('user_id', $my_todo->assigned)->get('tbl_account_details')->row();

                    ?>
                    <small class="block" data-toggle="tooltip"
                           data-placement="top"><?= lang('assign_by') ?><a
                            class="text-danger"
                            href="<?= base_url() ?>admin/user/user_details/<?= $my_todo->assigned ?>"> <?= $a_userinfo->fullname ?></a>
                    </small>
                <?php } ?>
            </td>
            <td>
                <?= $todo_label ?>
                <div class="btn-group">
                    <button style="font-size:10px;padding:0px;margin-top: -1px"
                            class="btn btn-xs btn-success dropdown-toggle"
                            data-toggle="dropdown">
                        <?= lang('change_status') ?>
                        <span class="caret"></span></button>
                    <ul class="dropdown-menu animated zoomIn">
                        <li>
                            <a href="<?= base_url() ?>admin/dashboard/change_todo_status/<?= $my_todo->todo_id . '/1' ?>"><?= lang('in_progress') ?></a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>admin/dashboard/change_todo_status/<?= $my_todo->todo_id . '/2' ?>"><?= lang('on_hold') ?></a>
                        </li>
                        <li>
                            <a href="<?= base_url() ?>admin/dashboard/change_todo_status/<?= $my_todo->todo_id . '/3' ?>"><?= lang('done') ?></a>
                        </li>
                    </ul>
                </div>
            </td>
            <td>
                <strong data-toggle="tooltip" data-placement="top"
                        title="<?= strftime(config_item('date_format'), strtotime($due_date)) ?>"><?= date("l", strtotime($due_date)) ?>

                    <span class="block"><?= daysleft($due_date) ?></span>

                </strong>
            </td>
            <td><?= btn_edit_modal('admin/dashboard/new_todo/' . $my_todo->todo_id) ?>
                <?= btn_delete('admin/dashboard/delete_todo/' . $my_todo->todo_id) ?></td>

        </tr>
        <?php
    }
endforeach; ?>
<?php endif; ?>
</tbody>
        </table>
    </div><!-- ./box-body -->

</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.complete-todo input[type="checkbox"]').change(function () {
            var todo_id = $(this).data().id;
            var todo_complete = $(this).is(":checked");

            var formData = {
                'todo_id': todo_id,
                'status': '3'
            };
            $.ajax({
                type: 'POST',
                url: '<?= base_url()?>admin/dashboard/completed_todo/' + todo_id,
                data: formData,
                dataType: 'json',
                encode: true,
                success: function (res) {
                    if (res) {
                        toastr[res.status](res.message);
                    } else {
                        alert('There was a problem with AJAX');
                    }
                }
            })

        });

    })
    ;
</script>