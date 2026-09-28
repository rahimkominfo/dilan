<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOperatorTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'operator_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nip' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => false,
            ],
            'info_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'tgl_tulis' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'jenis_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('operator_id');
        $this->forge->addKey('nip');
        $this->forge->addKey('info_id');
        $this->forge->addKey('jenis_id');
        $this->forge->addForeignKey('nip', 'pengguna', 'nip', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('info_id', 'info', 'info_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('jenis_id', 'jenis', 'jenis_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('operator', true);
    }

    public function down()
    {
        $this->forge->dropTable('operator', true);
    }
}
