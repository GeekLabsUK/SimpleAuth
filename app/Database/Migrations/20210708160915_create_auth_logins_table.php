<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthLoginsTable extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 50,
            ],
            'firstname' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'lastname' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'date' => [
                'type' => 'DATETIME',
            ],
            'successfull' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('auth_logins');
    }

    public function down()
    {
        $this->forge->dropTable('auth_logins');
    }
}