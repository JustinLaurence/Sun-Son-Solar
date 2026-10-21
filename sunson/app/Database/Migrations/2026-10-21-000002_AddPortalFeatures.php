<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AddPortalFeatures extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'role' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'customer'],
        ]);
        $this->forge->addColumn('users', [
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'must_change_password' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $this->db->table('users')->where('role', 'Admin')->update(['role' => 'admin']);
        $this->db->table('users')->where('role', 'Customer')->update(['role' => 'customer']);
        $this->db->table('users')->where('role', 'Employee')->update(['role' => 'technician']);

        $this->createTable('service_bookings', [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'customer_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'service_type' => ['type' => 'VARCHAR', 'constraint' => 50],
            'scheduled_at' => ['type' => 'DATETIME'],
            'address' => ['type' => 'TEXT'],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'pending'],
            'assigned_technician_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ], [
            ['customer_id', 'users', 'id'],
            ['assigned_technician_id', 'users', 'id'],
        ]);

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'booking_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'recorded_by' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'amount' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'payment_method' => ['type' => 'VARCHAR', 'constraint' => 40],
            'receipt_number' => ['type' => 'VARCHAR', 'constraint' => 40],
            'paid_at' => ['type' => 'DATETIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('booking_id');
        $this->forge->addUniqueKey('receipt_number');
        $this->forge->addForeignKey('booking_id', 'service_bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('recorded_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments');

        $this->createTable('attendance', [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'employee_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'employee_name' => ['type' => 'VARCHAR', 'constraint' => 120],
            'checked_in_at' => ['type' => 'DATETIME'],
            'check_in_latitude' => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
            'check_in_longitude' => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
            'checked_out_at' => ['type' => 'DATETIME', 'null' => true],
            'check_out_latitude' => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
            'check_out_longitude' => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
        ], [['employee_id', 'users', 'id']]);

        $this->createTable('notifications', [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'sender_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => 150],
            'message' => ['type' => 'TEXT'],
            'type' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'announcement'],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ], [['user_id', 'users', 'id'], ['sender_id', 'users', 'id']]);

        $this->createTable('problem_reports', [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'technician_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'booking_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'category' => ['type' => 'VARCHAR', 'constraint' => 80],
            'description' => ['type' => 'TEXT'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'open'],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ], [['technician_id', 'users', 'id'], ['booking_id', 'service_bookings', 'id']]);

        $this->createTable('support_tickets', [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'customer_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'subject' => ['type' => 'VARCHAR', 'constraint' => 150],
            'message' => ['type' => 'TEXT'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'open'],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ], [['customer_id', 'users', 'id']]);

        $this->forge->addField([
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'service_notifications' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'promotional_notifications' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_preferences');

        $this->createTable('audit_logs', [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'actor_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'action' => ['type' => 'VARCHAR', 'constraint' => 100],
            'details' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ], [['actor_id', 'users', 'id']]);

        $this->createTable('quote_requests', [
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 120],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone_number' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'details' => ['type' => 'TEXT'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'new'],
            'created_at' => ['type' => 'TIMESTAMP', 'default' => new RawSql('CURRENT_TIMESTAMP')],
        ], []);

        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'service_type' => ['type' => 'VARCHAR', 'constraint' => 50],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'is_available' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('service_type');
        $this->forge->createTable('service_settings');
        $this->db->table('service_settings')->insertBatch([
            ['service_type' => 'Consultation', 'price' => null, 'is_available' => 1],
            ['service_type' => 'Installation', 'price' => null, 'is_available' => 1],
            ['service_type' => 'Maintenance', 'price' => null, 'is_available' => 1],
        ]);
    }

    public function down()
    {
        foreach (['user_preferences', 'service_settings', 'quote_requests', 'audit_logs', 'support_tickets', 'problem_reports', 'notifications', 'attendance', 'payments', 'service_bookings'] as $table) {
            $this->forge->dropTable($table, true);
        }

        $this->db->table('users')->where('role', 'admin')->update(['role' => 'Admin']);
        $this->db->table('users')->where('role', 'customer')->update(['role' => 'Customer']);
        $this->db->table('users')->whereIn('role', ['hr', 'dispatcher', 'technician'])->update(['role' => 'Employee']);
        $this->forge->modifyColumn('users', [
            'role' => ['type' => 'ENUM', 'constraint' => ['Admin', 'Employee', 'Customer'], 'default' => 'Customer'],
        ]);
        $this->forge->dropColumn('users', ['is_active', 'must_change_password']);
    }

    private function createTable(string $table, array $fields, array $foreignKeys): void
    {
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);

        foreach ($foreignKeys as [$column, $referencedTable, $referencedColumn]) {
            $this->forge->addForeignKey($column, $referencedTable, $referencedColumn, 'CASCADE', 'CASCADE');
        }

        $this->forge->createTable($table);
    }
}
