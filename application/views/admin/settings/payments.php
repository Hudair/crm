<?php echo message_box('success') ?>
<div class="row">
    <!-- Start Form -->
    <div class="col-lg-12">

        <div class="panel panel-custom">
            <header class="panel-heading  "><?= lang('payment_settings');
                                            if (!empty($payment)) {
                                                echo   ' ' . lang('for') .  ' ' . $payment;
                                            }
                                            ?></header>
            <div class="panel-body">
                <input type="hidden" name="settings" value="<?= $load_setting ?>">
                <?php if (!empty($payment)) : ?>

                    <form role="form" id="form" action="<?php echo base_url(); ?>admin/settings/save_payments/<?= $payment ?>" method="post" class="form-horizontal  ">
                        <?php $payment_info = get_row('tbl_online_payment', array('gateway_name' => $payment));
                        for ($key = 1; $key <= 5; $key++) {
                            $field = 'field_' . $key;
                            echo get_form_field($payment_info->$field);
                        }
                        $status = $this->config->item(slug_it(strtolower($payment)) . '_status');
                        ?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><?= lang('status') ?></label>
                            <div class="col-lg-6">
                                <select name="<?= slug_it(strtolower($payment)) . '_status' ?>" class="form-control">
                                    <option <?= ($status == 'active' ? 'selected' : '') ?> value="active"><?= lang('active') ?></option>
                                    <option <?= ($status == 'deactive' ? 'selected' : '') ?> value="deactive"><?= lang('deactive') ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label"></label>
                            <div class="col-lg-1">
                                <button type="submit" class="btn btn-sm btn-primary"><?= lang('save_changes') ?></button>
                            </div>
                        </div>
                    </form>
                    <hr>
                <?php endif ?>
                <section class="panel panel-custom">
                    <div class="table-responsive">
                        <table class="table table-striped DataTables " id="Transation_DataTables">
                            <thead>
                                <tr>
                                    <th><?= lang('icon') ?></th>
                                    <th><?= lang('gateway_name') ?></th>
                                    <th><?= lang('status') ?></th>
                                    <th><?= lang('action') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $payment_method = $this->db->get('tbl_online_payment')->result();
                                foreach ($payment_method as $v_payments) { ?>
                                    <tr>
                                        <td><img style="width: 80px;height: 50px" src="<?= base_url($v_payments->icon) ?>">
                                        </td>
                                        <td><?= $v_payments->gateway_name; ?></td>
                                        <td><?php
                                            $status = $this->config->item(slug_it(strtolower($v_payments->gateway_name)) . '_status');
                                            if ($status == 'active') {
                                            ?>
                                                <span class="label label-success"><?= lang($status) ?></span>
                                            <?php } else { ?>
                                                <span class="label label-danger"><?= lang($status) ?></span>
                                            <?php }
                                            ?>
                                        </td>
                                        <td><a data-toggle="tooltip" title="<?= lang('edit') ?>" class="btn btn-xs btn-primary" href="<?= base_url() ?>admin/settings/payments/<?= $v_payments->gateway_name ?>"><i class="fa fa-edit"></i></a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- End details -->
                </section>
            </div>
        </div>
    </div>

    <!-- End Form -->