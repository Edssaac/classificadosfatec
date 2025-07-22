<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Ad extends AbstractMigration
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
        if ($this->hasTable('ad')) {
            return;
        }

        $this->table('ad', ['id' => false, 'primary_key' => 'ad_id'])
            ->addColumn('ad_id', 'integer', ['identity' => true])
            ->addColumn('user_id', 'integer')
            ->addColumn('title', 'string', ['limit' => 255])
            ->addColumn('description', 'text')
            ->addColumn('status', 'boolean', ['default' => 0])
            ->addColumn('ad_date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
            ->addColumn('discount', 'decimal', ['precision' => 10, 'scale' => 2, 'null' => true])
            ->addColumn('discount_date', 'datetime', ['null' => true])
            ->addColumn('type', 'enum', ['values' => ['monitoria', 'produto']])
            ->addColumn('expiry_date', 'date', ['null' => true])
            ->addForeignKey('user_id', 'user', 'user_id')
            ->create();
    }
}
