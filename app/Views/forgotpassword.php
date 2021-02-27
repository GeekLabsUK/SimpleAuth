<div class="container">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-4 offset-md-4 mt-5 pt-3 pb-3 bg-white form-wrapper">
            <div class="container">
                <h3>Reset Password</h3>
                <hr>
                <p>Enter your email address to reset your password</p>
                <?php $validation = \Config\Services::validation(); ?>
                <?php if (session()->get('success')) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= session()->get('success'); ?>
                    </div>
                <?php endif; ?>
                <?php if (session()->get('danger')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->get('danger'); ?>
                    </div>
                <?php endif; ?>
                <form class="" action="/forgotpassword" method="post">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" class="form-control" name="email" id="email" value="<?= set_value('email') ?>">
                        <?php if ($validation->getError('email')) { ?>
                            <div class='alert alert-danger mt-2'>
                                <?= $error = $validation->getError('email'); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <button type="submit" class="btn btn-primary" id="btnfetch">Reset</button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
</div>