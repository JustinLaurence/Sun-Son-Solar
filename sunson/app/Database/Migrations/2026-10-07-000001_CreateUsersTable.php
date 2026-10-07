<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'first_name'   => ['type' => 'VARCHAR', 'constraint' => 50],
            'last_name'    => ['type' => 'VARCHAR', 'constraint' => 50],
            'middle_name'  => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'birthdate'    => ['type' => 'DATE'],
            'gender'       => ['type' => 'VARCHAR', 'constraint' => 20],
            'email'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone_number' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'address'      => ['type' => 'TEXT', 'null' => true],
            'department'   => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'role'         => ['type' => 'ENUM', 'constraint' => ['Admin', 'Employee', 'Customer'], 'default' => 'Customer'],
            'username'     => ['type' => 'VARCHAR', 'constraint' => 50],
            'password'     => ['type' => 'VARCHAR', 'constraint' => 255],
            'created_at'   => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
