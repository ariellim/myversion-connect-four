<?php
use Migrations\AbstractMigration;

class AlterGameBoardsSetDefaults extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('game_boards');
		$table->removeColumn('turn');
        $table->addColumn('turn', 'integer', [
            'default' => '1',
            'limit' => 1,
            'null' => true,
        ]);
		$table->removeColumn('newgame');
        $table->addColumn('newgame', 'integer', [
            'default' => '1',
            'limit' => 1,
            'null' => true,
        ]);
        $table->update();
    }
}
