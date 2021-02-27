<div class="container">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-4 offset-md-4 mt-5 pt-3 pb-3 bg-white form-wrapper">
            <div class="container">
                <h3>Screen Locked</h3>
                <hr>
                <h5>Hi <?= session()->get('firstname') ?></h5>
                <?php $validation = \Config\Services::validation(); ?>
                <?php if (session()->get('success')) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= session()->get('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if (session()->get('danger')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->get('danger'); ?>
                        <?php if (session()->get('resetlink')) {
                            echo session()->get('resetlink');
                        } ?>
                    </div>
                <?php endif; ?>

                <form class="" action="/login" method="post">

                    <input type="hidden" class="form-control" name="email" id="email" value="<?= session()->get('email') ?>">
                    <input type="hidden" class="form-control" name="rememberme" id="rememberme" value="<?= session()->get('rememberme') ?>">

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" value="">
                        <?php if ($validation->getError('password')) { ?>
                            <div class='alert alert-danger mt-2'>
                                <?= $error = $validation->getError('password'); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>