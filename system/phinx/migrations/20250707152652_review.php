<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Review extends AbstractMigration
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
        if ($this->hasTable('review')) {
            return;
        }

        $this->table('review', ['id' => false, 'primary_key' => 'review_id'])
            ->addColumn('review_id', 'integer', ['identity' => true])
            ->addColumn('ad_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->addColumn('comment', 'text', ['null' => true])
            ->addColumn('rating', 'integer', ['limit' => Phinx\Db\Adapter\MysqlAdapter::INT_TINY])
            ->addColumn('review_date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('ad_id', 'ad', 'ad_id')
            ->addForeignKey('user_id', 'user', 'user_id')
            ->create();
    }
}
