<div class="container">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-4 offset-md-4 mt-5 pt-3 pb-3 bg-white form-wrapper">
            <div class="container">
                <h3>Reset Password</h3>
                <hr>

                <?php $validation = \Config\Services::validation(); ?>
                <?php if (session()->get('danger')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->get('danger'); ?>
                    </div>
                <?php endif; ?>
                <?php if (session()->get('success')) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= session()->get('success'); ?>
                    </div>
                <?php endif; ?>

                <p>Enter your new password</p>
                <form class="" action="/updatepassword/<?= $id ?>" method="post">                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" value="">
                        <?php if ($validation->getError('password')) { ?>
                            <div class='alert alert-danger mt-2'>
                                <?= $error = $validation->getError('password'); ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirm" id="password_confirm" value="">
                        <?php if ($validation->getError('password_confirm')) { ?>
                            <div class='alert alert-danger mt-2'>
                                <?= $error = $validation->getError('password_confirm'); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="row">
                        <div class="col-12 col-sm-4">
                            <button type="submit" class="btn btn-primary">Reset</button>
                        </div>

                    </div>
                </form>

            </div>

        </div>
    </div>
</div>