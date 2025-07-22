<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Solicitation extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        if ($this->hasTable('solicitation')) {
            return;
        }

        $this->table('solicitation', ['id' => false, 'primary_key' => 'solicitation_id'])
            ->addColumn('solicitation_id', 'integer', ['identity' => true])
            ->addColumn('user_id', 'integer')
            ->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('description', 'text')
            ->addColumn('solicitation_date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('type', 'enum', ['values' => ['monitoria', 'produto']])
            ->addColumn('expiry_date', 'date', ['null' => true])
            ->addForeignKey('user_id', 'user', 'user_id')
            ->create();
    }
}
