<div class="container">
    <div class="row">
        <div class="col-12">
            <h1> Welcome to Dashboard </h1>

            <h4>Logged in as</h4>

            <li>User ID : <?= session()->get('id') ?></li>
            <li>User First name : <?= session()->get('firstname') ?></li>
            <li>User Last name : <?= session()->get('lastname') ?></li>
            <li>User Email : <?= session()->get('email') ?></li>
        </div>
    </div>
</div>