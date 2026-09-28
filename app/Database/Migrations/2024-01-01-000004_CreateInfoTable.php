<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInfoTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'info_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'judul' => [
                'type'       => 'VARCHAR',
                'constraint' => 256,
                'null'       => false,
            ],
            'isi' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'tgl_buat' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'tgl_update' => [
                'type'    => 'DATETIME',
                'null'    => false,
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'dibuat_oleh' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => false,
            ],
            'diperbarui_oleh' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => false,
            ],
            'jumlah_tayang' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 0,
            ],
            'kategori_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'kata_kunci' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('info_id');
        $this->forge->addKey('kategori_id');
        $this->forge->addForeignKey('kategori_id', 'kategori', 'kategori_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('info', true);
    }

    public function down()
    {
        $this->forge->dropTable('info', true);
    }
}
