<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenggunaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pengguna_id' => [
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
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'peran' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'user'],
                'null'       => true,
                'default'    => 'user',
            ],
            'kategori_id' => [
                'type'     => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null'     => false,
            ],
            'url_apk' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'api_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
                'default'    => null,
            ],
        ]);

        $this->forge->addPrimaryKey('pengguna_id');
        $this->forge->addUniqueKey(['nip', 'kategori_id'], 'nip_kategori');
        $this->forge->addUniqueKey('api_key', 'idx_api_key');
        $this->forge->addKey('kategori_id');
        $this->forge->addForeignKey('kategori_id', 'kategori', 'kategori_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengguna', true);
    }

    public function down()
    {
        $this->forge->dropTable('pengguna', true);
    }
}
