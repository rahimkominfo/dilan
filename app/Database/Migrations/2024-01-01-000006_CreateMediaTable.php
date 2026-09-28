<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMediaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'media_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'file' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'tipe_media' => [
                'type'       => 'VARCHAR',
                'constraint' => 32,
                'null'       => false,
            ],
            'ukuran_media' => [
                'type'       => 'VARCHAR',
                'constraint' => 32,
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('media_id');
        $this->forge->createTable('media', true);
    }

    public function down()
    {
        $this->forge->dropTable('media', true);
    }
}
