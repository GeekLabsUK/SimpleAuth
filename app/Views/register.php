<div class="container">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-4 offset-md-4 mt-5 pt-3 pb-3 bg-white form-wrapper">
            <div class="container">
                <h3>Register</h3>

                <?php 
                // Displays the registration form if allowed. See below for 
                // notice when allowRegister is true
                if ($this->config->allowRegister !== false) : ?>

                <?php $validation = \Config\Services::validation(); ?>
                <form class="" action="register" method="post">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control" name="firstname" id="firstname" value="<?= set_value('firstname') ?>">
                        <?php if ($validation->getError('firstname')) { ?>
                            <div class='alert alert-danger mt-2'>
                                <?= $error = $validation->getError('firstname'); ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control" name="lastname" id="lastname" value="<?= set_value('lastname') ?>">
                        <?php if ($validation->getError('lastname')) { ?>
                            <div class='alert alert-danger mt-2'>
                                <?= $error = $validation->getError('lastname'); ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" class="form-control" name="email" id="email" value="<?= set_value('email') ?>">
                        <?php if ($validation->getError('email')) { ?>
                            <div class='alert alert-danger mt-2'>
                                <?= $error = $validation->getError('email'); ?>
                            </div>
                        <?php } ?>
                    </div>
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
                    <div class=" row">
                        <div class="col-12 col-sm-4">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                        <div class="col-12 col-sm-8 text-right">
                            <a href="/login">Login</a>
                        </div>
                    </div>
                </form>
                <?php else: ?>

                <p class="alert alert-primary mt-5">
                We are sorry. We are not accepting any new registrations at the moment.
                </p>

                <?php endif; ?>
            </div>

        </div>
    </div>
</div>