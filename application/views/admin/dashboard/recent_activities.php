
<div class="panel panel-custom menu" style="height: 437px;overflow-y: scroll;">
    <header class="panel-heading">
    <h3 class="panel-title"><?= lang('recent_activities') ?></h3>
</header>
<div class="panel-body">
    <section class="comment-list block">
        <section>
            <?php
            if (!empty($activities)) {

                foreach ($activities as $v_activities) {
                    ?>
                    <article id="comment-id-1" class="comment-item" style="font-size: 11px;">
                        <div class="pull-left recect_task  ">
                            <a class="pull-left recect_task  ">
                                <img style="width: 30px;margin-left: 18px;
    height: 29px;
    border: 1px solid #aaa;"
                                     src="<?= base_url() . $v_activities->avatar ?>"
                                     class="img-circle">
                            </a>
                        </div>
                        <section class="comment-body m-b-lg">
                            <header class=" ">
                                <strong>
                                    <?= $v_activities->fullname ?></strong>
                                <span data-toggle="tooltip" data-placement="top"
                                      title="<?= display_datetime($v_activities->activity_date) ?>"
                                      class="text-muted text-xs"> <?php
                                    echo time_ago($v_activities->activity_date);
                                    ?>
    </span>
                            </header>
                            <div>
                                <?= lang($v_activities->activity) ?>
                                <strong> <?= $v_activities->value1 . ' ' . $v_activities->value2 ?></strong>
                            </div>
                            <hr/>
                        </section>
                    </article>


                    <?php
                }
            }
            ?>
        </section>
</div>
</div>
