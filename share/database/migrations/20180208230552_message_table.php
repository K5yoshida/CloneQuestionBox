<?php


use Phinx\Migration\AbstractMigration;

class MessageTable extends AbstractMigration
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
        $table = $this->table('messages');
        $table->setOptions(['collation'=>'utf8mb4_unicode_ci'])
            ->addColumn('user_id', 'integer')
            ->addColumn('image_path', 'string')
            ->addColumn('hash', 'string')
            ->addColumn('message_text', 'text')
            ->addColumn('answer_text', 'text', array('null' => true))
            ->addColumn('send_flog', 'integer')
            ->addColumn('created', 'datetime')
            ->addColumn('updated', 'datetime', array('null' => true))
            ->create();
    }
}
