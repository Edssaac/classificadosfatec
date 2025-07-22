<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class User extends AbstractMigration
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
        if ($this->hasTable('user')) {
            return;
        }

        $this->table('user', ['id' => false, 'primary_key' => 'user_id'])
            ->addColumn('user_id', 'integer', ['identity' => true])
            ->addColumn('admin', 'boolean', ['default' => 0])
            ->addColumn('name', 'string', ['limit' => 255])
            ->addColumn('birth_date', 'date')
            ->addColumn('phone', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('institution', 'integer')
            ->addColumn('email', 'string', ['limit' => 100])
            ->addIndex(['email'], ['unique' => true])
            ->addColumn('password', 'string', ['limit' => 60])
            ->addColumn('token', 'string', ['limit' => 40, 'null' => true])
            ->addColumn('active', 'boolean', ['default' => 0])
            ->addColumn('last_access', 'datetime', ['null' => true])
            ->create();
    }
}
