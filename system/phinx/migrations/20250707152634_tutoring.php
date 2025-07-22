<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Tutoring extends AbstractMigration
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
        if ($this->hasTable('tutoring')) {
            return;
        }

        $this->table('tutoring', ['id' => false, 'primary_key' => 'tutoring_id'])
            ->addColumn('tutoring_id', 'integer', ['identity' => true])
            ->addColumn('ad_id', 'integer')
            ->addColumn('subject', 'string', ['limit' => 255])
            ->addColumn('schedules', 'json')
            ->addForeignKey('ad_id', 'ad', 'ad_id')
            ->create();
    }
}
