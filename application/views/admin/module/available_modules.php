<style type="text/css">
    .fs-16 {
        font-size: 2rem !important;
    }

    .fw-600 {
        font-weight: 600 !important;
    }

    .text-reset {
        color: inherit !important;
    }

    .rating i.hover,
    .rating i.active,
    .text-rating {
        color: #ffa707;
    }

    .rating i {
        color: #c3c3c5;
        font-size: 1rem;
        letter-spacing: -1px;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
    }

    .text-truncate-3 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    .pt-4,
    .py-4 {
        padding-top: 1.5rem !important;
    }

    .card {
        -webkit-box-shadow: 0 0 13px 0 rgb(82 63 105 / 5%);
        box-shadow: 0 0 13px 0 rgb(82 63 105 / 5%);
        background-color: #fff;
        margin-bottom: 20px;
        border-color: #ebedf2;
    }

    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: .25rem;
    }

    .card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        min-height: 1px;
        padding: 1.25rem;
    }

    .card .card-footer {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
        border-top: 1px solid #ebedf2;
        background-color: transparent;
        padding: 12px 25px;
    }

    .card-footer:last-child {
        border-radius: 0 0 calc(.25rem - 1px) calc(.25rem - 1px);
    }

    .fs-22 {
        font-size: 1.975rem !important;
    }

    .fw-600 {
        font-weight: 600 !important;
    }

    .img-fluid {
        max-width: 100%;
        height: auto;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 ">
        <?php
        if (!empty($available_modules)) {
            $available_modules = json_decode($available_modules);
            if (!empty($available_modules)) {
                foreach ($available_modules as  $modules) {
                    if (!empty($modules)) { ?>
                        <div class="col-lg-4 col-md-6 ">
                            <div class="card addon-card">
                                <div class="card-body">
                                    <a href="<?= $modules->url ?>" target="_blank"><img class="img-fluid" src="<?= $modules->preview_image ?>"></a>
                                    <div class="pt-4">
                                        <a class="fs-16 fw-600 text-reset" href="<?= $modules->url ?>" target="_blank"><?= $modules->name ?></a>
                                        <div class="rating mb-2">
                                            <?php
                                            for ($i = 1; $i <= round($modules->rating); $i++) { ?>
                                                <i class="fa fa-star active"></i>
                                            <?php }
                                            ?>
                                        </div>
                                        <div class="mar-no text-truncate-3">
                                            <p class="mar-no text-truncate-3"><?= short_text(strip_tags($modules->description), 250, 0) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-danger fs-22 fw-600">$<?= $modules->price ?></div>
                                    <div class=""><a href="<?= $modules->url ?>" target="_blank" class="btn btn-sm btn-secondary">Preview</a> <a href="<?= $modules->purchase_link ?>" target="_blank" class="btn btn-sm btn-primary">Purchase</a></div>
                                </div>
                            </div>
                        </div>
        <?php }
                }
            }
        }
        ?>
    </div>
</div>