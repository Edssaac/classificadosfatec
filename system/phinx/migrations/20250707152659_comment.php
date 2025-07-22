<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Comment extends AbstractMigration
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
        if ($this->hasTable('comment')) {
            return;
        }

        $this->table('comment', ['id' => false, 'primary_key' => 'comment_id'])
            ->addColumn('comment_id', 'integer', ['identity' => true])
            ->addColumn('solicitation_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->addColumn('comment', 'text')
            ->addColumn('comment_date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('solicitation_id', 'solicitation', 'solicitation_id')
            ->addForeignKey('user_id', 'user', 'user_id')
            ->create();
    }
}
