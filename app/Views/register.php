<div class="contaoner">
    <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2 col-md-4 offset-md-4 mt-5 pt-3 pb-3 bg-white form-wrapper">
            <div class="container">
                <h3>Register</h3>

                <form class="" action="register" method="post">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control" name="firstname" id="firstname" value="<?= set_value('firstname') ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control" name="lastname" id="lastname" value="<?= set_value('lastname') ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="text" class="form-control" name="email" id="email" value="<?= set_value('email') ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" value="">
                    </div>
                    <div class="form-group">
                        <label for="password_confirm">Password</label>
                        <input type="password" class="form-control" name="password_confirm" id="password_confirm" value="">
                    </div>
                    <?php if (isset($validation)): ?>
                        <div class="col-12">
                            <div class="alert alert-danger" role="alert">
                                <?= $validation->listErrors() ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    <div class=" row">
                        <div class="col-12 col-sm-4">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                        <div class="col-12 col-sm-8 text-right">
                            <a href="/login">Login</a>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>