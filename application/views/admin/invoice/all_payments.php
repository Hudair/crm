

<div id="payments_state_report_div">
<?php //$this->load->view("admin/invoice/payments_state_report"); ?>
</div>

<?php
$edited = can_action('13', 'edited');
$deleted = can_action('13', 'deleted');
?>

<section class="panel panel-custom ">
    <header class="panel-heading"><?= lang('all_payments') ?>
        <div class="pull-right">
            <a data-toggle="modal" data-target="#myModal"
               href="<?= base_url() ?>admin/invoice/zipped/payment"
               class="btn btn-success btn-xs ml-lg"><?= lang('zip_payment') ?></a>
            <a data-toggle="modal" data-target="#myModal"
               href="<?= base_url() ?>admin/invoice/make_payment"
               class="btn btn-danger btn-xs ml-lg"><?= lang('make_payment') ?></a>
            <?php $is_department_head = is_department_head();
            if ($this->session->userdata('user_type') == 1 || !empty($is_department_head)) { ?>
                <div class="ml-lg btn-group pull-right btn-with-tooltip-group _filter_data filtered"
                     data-toggle="tooltip"
                     data-title="<?php echo lang('filter_by'); ?>">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-filter" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu group animated zoomIn"
                        style="width:300px;">
                        <li class="filter_by all_filter"><a href="#"><?php echo lang('all'); ?></a></li>
                        <li class="divider"></li>
                        <li class="filter_by" id="today"><a href="#"><?php echo lang('today'); ?></a></li>
                        <li class="filter_by" id="this_months"><a href="#"><?php echo lang('this_months'); ?></a></li>
                        <li class="filter_by" id="last_month"><a href="#"><?php echo lang('last_month'); ?></a></li>
                        <li class="dropdown-submenu pull-left  " id="from_account">
                            <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('invoices'); ?></a>
                            <ul class="dropdown-menu dropdown-menu-left from_account"
                                style="">
                                <?php
                                $invoice_info = $this->invoice_model->get_permission('tbl_invoices');
                                if (!empty($invoice_info)) {
                                    foreach ($invoice_info as $v_invoice) {
                                        ?>
                                        <li class="filter_by" id="<?= $v_invoice->invoices_id ?>"
                                            search-type="by_invoice">
                                            <a href="#"><?php echo $v_invoice->reference_no; ?></a>
                                        </li>
                                    <?php }
                                }
                                ?>
                            </ul>
                        </li>
                        <div class="clearfix"></div>
                        <li class="dropdown-submenu pull-left  " id="from_reporter">
                            <a href="#"
                               tabindex="-1"><?php echo lang('by') . ' ' . lang('account'); ?></a>
                            <ul class="dropdown-menu dropdown-menu-left from_reporter"
                                style="">
                                <?php
                                $all_account = $this->invoice_model->get_permission('tbl_accounts');
                                if (!empty($all_account)) {
                                    foreach ($all_account as $v_account) {
                                        ?>
                                        <li class="filter_by" id="<?= $v_account->account_id ?>"
                                            search-type="by_account">
                                            <a href="#"><?php echo $v_account->account_name; ?></a>
                                        </li>
                                    <?php }
                                }
                                ?>
                            </ul>
                        </li>
                        <div class="clearfix"></div>
                        <li class="dropdown-submenu pull-left " id="to_account">
                            <a href="#" tabindex="-1"><?php echo lang('by') . ' ' . lang('client'); ?></a>
                            <ul class="dropdown-menu dropdown-menu-left to_account"
                                style="">
                                <?php
                                $all_client = $this->db->get('tbl_client')->result();
                                if (count($all_client) > 0) { ?>
                                    <?php foreach ($all_client as $v_client) {
                                        ?>
                                        <li class="filter_by" id="<?= $v_client->client_id ?>"
                                            search-type="by_client">
                                            <a href="#"><?php echo $v_client->name; ?></a>
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
        </div>

    </header>

    <div class="panel-body">
        <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th><?= lang('payment_date') ?></th>
                <th><?= lang('invoice_date') ?></th>
                <th><?= lang('invoice') ?></th>
                <th><?= lang('client') ?></th>
                <th><?= lang('amount') ?></th>
                <th><?= lang('payment_method') ?></th>
                <?php if (!empty($edited) || !empty($deleted)) { ?>
                    <th class="hidden-print"><?= lang('action') ?></th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <script type="text/javascript">
                $(document).ready(function () {
                    list = base_url + "admin/invoice/paymentList";
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
//                        alert(base_url + "admin/invoice/paymentList/" + filter_by + search_type);
                        table_url(base_url + "admin/invoice/paymentList/" + filter_by + search_type);
                    });
                    <?php }?>
                });
            </script>
            </tbody>
        </table>
    </div>
</section>

<script>
    $(document).ready(function () {  ins_data(base_url+'admin/invoice/payments_state_report')   });
</script>