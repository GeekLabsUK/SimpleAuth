<?php

namespace App\Database\Seeds;

use App\Models\AuthModel;
use CodeIgniter\Database\Seeder;

/**
 * Class UserSeeder
 *
 * A seeder to insert some sample users with roles into the database.
 */
class UserSeeder extends Seeder
{
    /**
     * @var AuthModel
     */
    protected $authModel;

    /**
     * Runs the seeder.
     */
    public function run()
    {
        // Initialize the model
        $this->authModel = new AuthModel();

        // Get the available roles from the config file
        $assignRoles = config('Auth')->assignRoles;

        // Define an array of sample users without roles
        $users = [
            [
                'firstname' => 'Alice',
                'lastname' => 'Smith',
                'email' => 'super-admin@example.com',
                'password' => 'secret',
                'activated' => '1',
            ],
            [
                'firstname' => 'Bob',
                'lastname' => 'Jones',
                'email' => 'tenant@example.com',
                'password' => 'secret',
                'activated' => '1',
            ],
            [
                'firstname' => 'Charlie',
                'lastname' => 'Brown',
                'email' => 'customer@example.com',
                'password' => 'secret',
                'activated' => '1',
            ],
        ];

        // Define an array of roles for each user
        $roles = [
            $assignRoles['Super Admin'],
            $assignRoles['Tenant'],
            $assignRoles['Customer'],
        ];

        // Loop through the users and roles arrays and assign the roles to the users
        $i = 0;
        foreach ($assignRoles as $assignRoleValue) {
            $users[$i]['role'] = $assignRoleValue;
            $i++;
        }

        // Insert each user using the model
        foreach ($users as $user) {
            $this->authModel->save($user);

            // Get the role key from the assign roles array using the role value
            $roleKey = array_search($user['role'], $assignRoles);

            // Display a message with the email and password of the user
            echo "User created with email: {$user['email']}, password: secret and role: {$roleKey}\n";
        }
    }
}