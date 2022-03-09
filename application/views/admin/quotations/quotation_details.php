<div class="row" id="printableArea">
    <div class="col-sm-12">
        <div class="row show_print">
            <div class="col-md-1 pull-left">
                <img style="width: 50px;height: 50px;margin-bottom: 5px;" src="<?= base_url() . config_item('company_logo') ?>">
            </div>
            <div class="col-md-10 pull-left ml0 pl0">
                <h4 class="m0 p0"><?= config_item('company_name') ?></h4>
                <?= lang('address') . ': ' . config_item('company_address') ?>
                <?= lang('city') . ': ' . config_item('company_city') ?>,
                <?= lang('country') . ': ' . config_item('company_country') . '-' . config_item('company_zip_code') ?>
                ,<?= lang('phone') ?> : <?= config_item('company_phone') ?>
            </div>
        </div>
        <div class="row hidden">
            <div class="col-md-2 text-center visible-md visible-lg">
                <img style="width: 100%" src="<?= base_url() . config_item('company_logo') ?>">
            </div>
            <div class="col-md-10">
                <h4 class="ml-sm"><?= config_item('company_legal_name') ?></h4>
                <address></address><?= config_item('company_address') ?>
                <br><?= config_item('company_city') ?>
                , <?= config_item('company_zip_code') ?>
                <br><?= config_item('company_country') ?>
                <br /><?= lang('phone') ?> : <?= config_item('company_phone') ?>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="panel panel-custom">
            <div class="panel-heading"><i class="fa fa-list"></i>
                <?= lang('quotation_details') ?>
            </div>
            <div class="panel-body">
                <ul class="list-group no-radius">
                    <?php
                    $client_info = $this->quotations_model->check_by(array('client_id' => $quotations_info->client_id), 'tbl_client');
                    $user_info = $this->quotations_model->check_by(array('user_id' => $quotations_info->user_id), 'tbl_users');
                    $reviewer_info = $this->quotations_model->check_by(array('user_id' => $quotations_info->reviewer_id), 'tbl_users');
                    if (!empty($user_info)) {
                        if ($user_info->role_id == 1) {
                            $user = lang('admin');
                            $label = 'danger';
                        } elseif ($user_info->role_id == 2) {
                            $user = lang('client');
                            $label = 'success';
                        } else {
                            $user = lang('staff');
                            $label = 'primary';
                        }
                    } else {
                        $user = null;
                        $label = null;
                    }
                    if (!empty($reviewer_info)) {
                        if ($reviewer_info->role_id == 1) {
                            $r_user = lang('admin');
                            $r_label = 'danger';
                        } elseif ($reviewer_info->role_id == 2) {
                            $r_user = lang('client');
                            $r_label = 'success';
                        } else {
                            $r_user = lang('staff');
                            $r_label = 'primary';
                        }
                    } else {
                        $user = null;
                        $r_label = null;
                    }
                    $currency = $this->quotations_model->client_currency_symbol($quotations_info->client_id);
                    if (empty($currency)) {
                        $currency = $this->quotations_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');
                    }
                    ?>
                    <li class="list-group-item"><span class="pull-right"><?= $quotations_info->name ?></span><?= lang('client') ?></li>
                    <li class="list-group-item"><span class="pull-right"><?= $quotations_info->email ?></span><?= lang('email') ?></li>
                    <li class="list-group-item"><span class="pull-right"><?= $quotations_info->mobile; ?></span><?= lang('mobile') ?></li>
                    <li class="list-group-item"><span class="pull-right"><?= strftime(config_item('date_format'), strtotime($quotations_info->quotations_date)) ?></span><?= lang('date') ?></li>
                    <li class="list-group-item"><span class="pull-right"><?php
                                                                            if ($quotations_info->quotations_status == 'completed') {
                                                                                echo '<span class="label label-success">' . lang('completed') . '</span>';
                                                                            } else {
                                                                                echo '<span class="label label-danger">' . lang('pending') . '</span>';
                                                                            };
                                                                            ?></span><?= lang('status') ?></li>
                    <?php if (!empty($user_info)) : ?>
                        <li class="list-group-item"><span class="pull-right"><?= (!empty($user_info->username) ? $user_info->username : '-') . ' <small class="label label-' . $label . '">' . $user . '</small>'; ?></span><?= lang('generated_by') ?>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($reviewer_info)) : ?>
                        <li class="list-group-item"><span class="pull-right"><?= $reviewer_info->username . ' <small class="label label-' . $r_label . '">' . $r_user . '</small>'; ?></span><?= lang('reviewer') ?>
                        </li>
                        <li class="list-group-item"><span class="pull-right"><?= strftime(config_item('date_format'), strtotime($quotations_info->reviewed_date)) ?></span><?= lang('reviewed_date') ?>
                        </li>
                    <?php endif; ?>
                    <li class="list-group-item">
                        <span class="pull-right">
                            <?php
                            if ($quotations_info->quotations_amount) {
                                echo display_money($quotations_info->quotations_amount, $currency->symbol);
                            }
                            ?>
                        </span>
                        <?= lang('amount') ?>
                    </li>
                    <blockquote style="font-size: 12px;word-wrap: break-word " class="mt-lg"><?php if (!empty($quotations_info->notes)) echo $quotations_info->notes; ?></blockquote>
                    <br />
                    <div class="text-center">
                        <a data-toggle='modal' data-target='#myModal' href="<?= base_url() ?>admin/quotations/set_price/<?= $quotations_info->quotations_id ?>" class="btn btn-primary"><?= lang('price_quotation') ?></a>
                    </div>

                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="panel panel-custom">
            <div class="panel-heading  "><i class="fa fa-user"></i> <?= lang('quotaion_form_response') ?>
                <div class="pull-right hidden-print mr-lg">
                    <?php if ($quotations_info->quotations_status == 'completed') { ?>
                        <?php
                        if ($quotations_info->is_convert != 'Yes') {
                        ?>
                            <div class="btn-group pull-left mr-sm">
                                <button class="btn btn-xs btn-purple dropdown-toggle" data-toggle="dropdown">
                                    <?= lang('convert') . ' ' . lang('TO') ?>
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu animated zoomIn">
                                    <li>
                                        <a data-toggle="modal" data-target="#myModal_large" href="<?= base_url() ?>admin/quotations/convert_to/invoice/<?= $quotations_info->quotations_id ?>" title="<?= lang('invoice') ?>"><?= lang('invoice') ?></a>
                                    </li>
                                    <li>
                                        <a data-toggle="modal" data-target="#myModal_large" href="<?= base_url() ?>admin/quotations/convert_to/estimate/<?= $quotations_info->quotations_id ?>"><?= lang('estimate') ?></a>
                                    </li>
                                </ul>
                            </div>
                        <?php } else {
                            if ($quotations_info->convert_module == 'invoice') {
                                $convert_info = $this->quotations_model->check_by(array('invoices_id' => $quotations_info->convert_module_id), 'tbl_invoices');
                                $c_url = base_url() . 'admin/invoice/manage_invoice/invoice_details/' . $quotations_info->convert_module_id;
                            } else {
                                $convert_info = $this->quotations_model->check_by(array('estimates_id' => $quotations_info->convert_module_id), 'tbl_estimates');
                                $c_url = base_url() . 'admin/estimates/index/estimates_details/' . $quotations_info->convert_module_id;
                            } ?>
                        <?php } ?>
                    <?php } ?>
                    <div class="pull-left mr-sm">
                        <?php if (!empty($c_url)) { ?>
                            <a class="btn btn-purple btn-xs" href="<?= $c_url ?>"><i class="fa fa-hand-o-right"></i> <?= $convert_info->reference_no ?></a>
                        <?php } ?>
                    </div>
                    <div class="pull-left mr-sm">
                        <button class="btn btn-xs pull-left btn-danger btn-print" type="button" data-toggle="tooltip" title="Print" onclick="printDiv('printableArea')"><i class="fa fa-print"></i></button>
                    </div>
                    <div class="pull-left">
                        <a href="<?= base_url() ?>admin/quotations/quotations_details_pdf/<?= $quotations_info->quotations_id ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="PDF"><i class="fa fa-file-pdf-o"></i>
                        </a>
                    </div>

                </div>
            </div>
            <div class="panel-body">
                <?php if (!empty($quotation_details)) : foreach ($quotation_details as $v_q_details) : ?>
                        <label class="control-label"> <strong><?= $v_q_details->quotation_form_data ?></strong></label>
                        <?php
                        if (@unserialize($v_q_details->quotation_data)) {
                            $multiple_data = unserialize($v_q_details->quotation_data);
                            foreach ($multiple_data as $key => $value) {
                        ?>
                                <p style="word-wrap: break-word"><span><?= $key + 1 . '.' . $value ?></span></p>
                            <?php
                            }
                        } else {
                            ?>
                            <p style="word-wrap: break-word"><span><?= $v_q_details->quotation_data ?></span></p>
                        <?php } ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function printDiv(printableArea) {
        var printContents = document.getElementById(printableArea).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>