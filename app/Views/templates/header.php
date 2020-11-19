<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>

    <title>login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/custom.css">


</head>

<body>
    <?php $uri = service('uri') ?>
    <?php $this->config = config('Auth');
    $redirect = $this->config->assignRedirect; ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Login System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <?php if (session()->get('isLoggedIn')) : ?>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item <?= ($uri->getSegment(1) == 'dashboard' ? 'active' : null) ?>">
                            <a class="nav-link" href="<?php echo $redirect[session()->get('role')] ?>">Dashboard</a>
                        </li>
                        <li class="nav-item <?= ($uri->getSegment(1) == 'profile' ? 'active' : null) ?>">
                            <a class="nav-link" href="<?php echo $redirect[session()->get('role')] ?>/profile">Profile </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav my-2 my-lg-0">
                        <?php if ($this->config->lockScreen) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/lockscreen">Lock </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/logout">Logout </a>
                        </li>
                    </ul>
                </div>

            <?php else : ?>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item <?= ($uri->getSegment(1) == '' ? 'active' : null) ?>">
                            <a class="nav-link" href="/login">Login</a>
                        </li>
                        <li class="nav-item <?= ($uri->getSegment(1) == 'register' ? 'active' : null) ?>">
                            <a class="nav-link" href="/register">Register </a>
                        </li>
                    </ul>
                </div>

            <?php endif; ?>
        </div>
    </nav>