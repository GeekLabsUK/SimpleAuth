<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuthTokensTable extends Migration
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
                'constraint' => 11,
            ],
            'selector' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'hashedvalidator' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'expires' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('auth_tokens');
    }

    public function down()
    {
        $this->forge->dropTable('auth_tokens');
    }
}