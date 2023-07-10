<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'firstname' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'lastname' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'reset_token' => [
                'type' => 'VARCHAR',
                'constraint' => '250',
            ],
            'reset_expire' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'activated' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'activate_token' => [
                'type' => 'VARCHAR',
                'constraint' => '250',
                'null' => true,
            ],
            'activate_expire' => [
                'type' => 'VARCHAR',
                'constraint' => '250',
                'null' => true,
            ],
            'role' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'created_at datetime NOT NULL DEFAULT current_timestamp()',
            'updated_at datetime NOT NULL DEFAULT current_timestamp()',
            // 'created_at' => [
            //     'type' => 'DATETIME',
            //     'null' => false,
            //     'default' => current_timestamp(),
            // ],
            // 'updated_at' => [
            //     'type' => 'DATETIME',
            //     'null' => false,
            //     'default' => current_timestamp(),
            // ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}