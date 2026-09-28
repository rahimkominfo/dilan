<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJenisTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'jenis_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => 256,
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('jenis_id');
        $this->forge->createTable('jenis', true);
    }

    public function down()
    {
        $this->forge->dropTable('jenis', true);
    }
}
