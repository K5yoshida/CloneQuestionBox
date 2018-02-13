<?php


use Phinx\Migration\AbstractMigration;

class UserTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('users');
        $table->setOptions(['collation'=>'utf8mb4_unicode_ci'])
            ->addColumn('twitter_id', 'string', array('limit' => 50))
            ->addColumn('access_token', 'string')
            ->addColumn('access_token_secret', 'string')
            ->addColumn('username', 'string', array('limit' => 50))
            ->addColumn('screen_name', 'string', array('limit' => 50))
            ->addColumn('user_image' , 'string')
            ->addColumn('email', 'string', array('limit' => 50, 'null' => true))
            ->addColumn('notification_flog', 'integer')
            ->addColumn('delete_flog', 'boolean')
            ->addColumn('created', 'datetime')
            ->addColumn('updated', 'datetime', array('null' => true))
            ->create();
    }
}
