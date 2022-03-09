<div class="panel panel-custom menu" style="height: 437px;overflow-y: scroll;">
    <header class="panel-heading mb0">
        <h3 class="panel-title"><?= lang('announcements') ?></h3>
    </header>

    <?php
    $all_announcements = get_order_by('tbl_announcements', null, 'announcements_id', null, '10');
    if (!empty($all_announcements)):foreach ($all_announcements as $v_announcements): ?>
        <div class="notice-calendar-list panel-body">
            <div class="notice-calendar">
    <span
        class="month"><?php echo date('M', strtotime($v_announcements->created_date)) ?></span>
                <span
                    class="date"><?php echo date('d', strtotime($v_announcements->created_date)) ?></span>
            </div>

            <div class="notice-calendar-heading">
                <h5 class="notice-calendar-heading-title">
                    <a href="<?php echo base_url() ?>admin/announcements/announcements_details/<?php echo $v_announcements->announcements_id; ?>"
                       title="View" data-toggle="modal"
                       data-target="#myModal_lg"><?php echo $v_announcements->title ?></a>
                </h5>
                <div class="notice-calendar-date">
                    <?php
                    echo strip_html_tags(mb_substr($v_announcements->description, 0, 200),true) . '...';
                    ?>
                </div>
            </div>
            <div style="margin-top: 5px; padding-top: 5px; padding-bottom: 10px;">
    <span style="font-size: 10px;" class="pull-right">
    <strong>
    <a href="<?php echo base_url() ?>admin/announcements/announcements_details/<?php echo $v_announcements->announcements_id; ?>"
       title="View" data-toggle="modal"
       data-target="#myModal_lg"><?= lang('view_details') ?></a></strong>
    </span>
            </div>
        </div>
    <?php

    endforeach; ?>
    <?php endif; ?>

</div><!-- ./box-body -->
