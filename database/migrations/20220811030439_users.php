<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Users extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users');
        $table->addColumn('name', 'string', [
                'comment'  => "用户名称",
            ])
            ->addColumn('password', 'string', [
                'comment' => "登录密码",
            ])
            ->addColumn('last_login_ip', 'string', [
                'comment' => "上次登录ip",
                'null' => true
            ])
            ->addColumn('last_login_at', 'string', [
                'comment' => "上次登录时间",
                'null' => true
            ])
            ->addIndex('name', ['unique' => true])
            ->addTimestamps()
            ->save();
    }

    public function down()
    {
        $table = $this->table('users');
        $table->drop()->save();
    }
}
