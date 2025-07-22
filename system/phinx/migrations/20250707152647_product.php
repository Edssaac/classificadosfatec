<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Product extends AbstractMigration
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
        if ($this->hasTable('product')) {
            return;
        }

        $this->table('product', ['id' => false, 'primary_key' => 'product_id'])
            ->addColumn('product_id', 'integer', ['identity' => true])
            ->addColumn('ad_id', 'integer')
            ->addColumn('photo_name', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('photo_token', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('condition', 'enum', ['values' => ['novo', 'seminovo', 'usado']])
            ->addColumn('operation', 'enum', ['values' => ['venda', 'troca', 'ambos']])
            ->addColumn('quantity', 'integer', ['default' => 0])
            ->addForeignKey('ad_id', 'ad', 'ad_id')
            ->create();
    }
}
