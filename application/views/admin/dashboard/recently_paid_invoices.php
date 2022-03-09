<section class="panel panel-custom menu" style="height: 437px;overflow-y: scroll;">
    <header class="panel-heading">
        <h3 class="panel-title"><?= lang('recently_paid_invoices') ?></h3>
    </header>
    <div class="panel-body inv-slim-scroll">
        <div class="list-group bg-white">
            <?php
            /*$recently_paid = $this->db
            ->order_by('created_date', 'desc')
            ->get('tbl_payments', 5)
            ->result();*/

            if (!empty($recently_paid)) {
                foreach ($recently_paid as $key => $v_paid) {

                    ?>
                    <a href="<?= base_url() ?>admin/invoice/manage_invoice/payments_details/<?php echo $v_paid->invoices_id; ?>"
                       class="list-group-item" title="view">
                        <?= !empty($v_paid->reference_no) ? $v_paid->reference_no : $v_paid->trans_id ?>
                        -
                        <small
                            class="text-muted"><?= display_money($v_paid->amount, $v_paid->currency) ?>
                            <span
                                class="label label-<?= $v_paid->label ?> pull-right"><?= $v_paid->method_name  ?></span>
                        </small>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <div class="panel-footer">
        <small ><?= lang('total_receipts') ?>: <strong id="total_receipts">
            </strong></small>
    </div>
</section>
