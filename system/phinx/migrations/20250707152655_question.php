<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Question extends AbstractMigration
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
        if ($this->hasTable('question')) {
            return;
        }

        $this->table('question', ['id' => false, 'primary_key' => 'question_id'])
            ->addColumn('question_id', 'integer', ['identity' => true])
            ->addColumn('ad_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->addColumn('question', 'text')
            ->addColumn('question_date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('answer', 'text', ['null' => true])
            ->addColumn('answer_date', 'datetime', ['null' => true])
            ->addForeignKey('ad_id', 'ad', 'ad_id')
            ->addForeignKey('user_id', 'user', 'user_id')
            ->create();
    }
}
