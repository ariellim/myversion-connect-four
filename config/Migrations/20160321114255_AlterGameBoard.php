<?php
use Migrations\AbstractMigration;

class AlterGameBoard extends AbstractMigration
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
		$table->removeColumn('player1');
        $table->addColumn('player1', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
		$table->removeColumn('player2');
        $table->addColumn('player2', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
		$table->removeColumn('player1_move');
        $table->addColumn('player1_move', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
		$table->removeColumn('player2_move');
        $table->addColumn('player2_move', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => true,
        ]);
        $table->update();
    }
}
