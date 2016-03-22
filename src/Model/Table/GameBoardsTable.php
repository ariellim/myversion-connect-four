<?php
namespace App\Model\Table;

use App\Model\Entity\Player;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Players Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Sessions
 */
class GameBoardsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('game_boards');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

		$validator
            ->allowEmpty('player1');
		
		$validator
            ->allowEmpty('player2');
		
		$validator
            ->allowEmpty('player1_move');
		
		$validator
            ->allowEmpty('player2_move');
		
		$validator
            ->allowEmpty('lastping');
		
		$validator
            ->allowEmpty('turn');
		
		$validator
            ->allowEmpty('newgame');
		
        return $validator;
    }
}
