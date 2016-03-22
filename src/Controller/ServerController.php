<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Server Controller
 *
 * @property \App\Model\Table\ServerTable $Server
 */
class ServerController extends AppController
{

	public function initialize()
    {
        parent::initialize();
		
		//$this->layout = 'ajax';
		$this->viewBuilder()->layout('ajax');

        $this->loadModel('GameBoards');
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        die('Nothing for you here!');
    }

    /**
     * View method
     *
     * @param string|null $id Server id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function register()
    {
		if($this->request->is('get') && $this->request->query('sessionid'))
		{			
			$this->pruneBoard(); // Make stagnant board vacant and active
			$board = $this->checkBoardAndUser($this->request->query('sessionid'));
			echo json_encode($board);
			exit();
		}  
    }
	
	public function sendmove($boardid,$player)
	{		
		if($this->request->is('get') && ($col=$this->request->query('col'))!=='')
		{
			$nextplayer = ($player=='2'?'1':'2');
			$boards = $this->GameBoards->query();
			$boards->update()
					->set(["player{$player}_move='$col'","player{$nextplayer}_move=''",'turn'=>$nextplayer,'lastping=current_timestamp'])
					->where(['id'=>$boardid])
					->execute();
					
			echo 'received';
			exit();
		}
	}
	
	public function newgame($boardid,$reset=false)
	{		
		if($this->request->is('get'))
		{
			$this->reset($boardid,$reset);
					
			echo 'received';
			exit();
		}
	}
	
	private function reset($boardid,$reset=false)
	{
		$boards = $this->GameBoards->query();
		$boards->update()
				->set(['newgame'=>($reset?'0':'1'),'player1_move'=>'','player2_move'=>'','lastping=current_timestamp','turn'=>'1'])
				->where(['id'=>$boardid])
				->execute();
	}
	
	public function ping()
	{
		if($this->request->is('get') && $sessionid=$this->request->query('sessionid'))
		{
			$theboard = $this->GameBoards->find()->where(["player1='{$sessionid}' or player2='{$sessionid}'"]);
			$theboard = $theboard->first();
			// update board to mark active
			$boards = $this->GameBoards->query();
			$boards->update()
					->set(['lastping=current_timestamp'])
					->where(['id'=>$theboard->id])
					->execute();
			
			$board = $this->checkBoardAndUser($this->request->query('sessionid'));
			
			$this->pruneBoard(); // Make stagnant board vacant and active
			
			echo json_encode($board);
		}
		exit();
	}
	
	// Unassign stagnant users from board
	private function pruneBoard()
	{
		$boards = $this->GameBoards->query();
		$boards->update()
				->set(['player1'=>'','player2'=>'','player1_move'=>'','player2_move'=>'','lastping=current_timestamp','turn'=>'1','newgame'=>'1'])
				->where(["(current_timestamp - lastping) > (interval '60 seconds')"])
				->execute();
		
		$boards = $this->GameBoards->query();
		$boards->delete()
				->where(["(current_timestamp - lastping) > (interval '600 seconds')","(player1 is NULL AND player2 is NULL) or (player1='' AND player2='')"])
				->execute();
	}
	
	// registers and retrieves board and user info
	private function checkBoardAndUser($sessionid) 
	{
		// look for active board user is registered
		$boards = $this->GameBoards->find()->where(["(current_timestamp - lastping) < (interval '60 seconds')","player1='{$sessionid}' or player2='{$sessionid}'"]);
		if($board = $boards->first())
		{
			if($board->player1==$sessionid)
			{
				$assignment = 1;
			}
			elseif($board->player2==$sessionid)
			{
				$assignment = 2;
			}
		}
		else // no active board user is in
		{			
			// look for active board with vacancy
			$boards = $this->GameBoards->find()->where(["(current_timestamp - lastping) < (interval '60 seconds')", "((player1 is NULL AND player2 not like '{$sessionid}') OR (player2 is NULL AND player1 not like '{$sessionid}'))"]);

			$assignment = 0;
			if($board = $boards->first()) // found board with vacancy
			{
				if($board->player1=='')
				{
					$assignment = 1;
				}
				elseif($board->player2=='')
				{
					$assignment = 2;
				}
				// register to board
				$board = $this->GameBoards->patchEntity($board,['player'.$assignment=>$sessionid,'lastping=NOW()']);
				$board = $this->GameBoards->save($board);
			}
			else // all boards are full
			{
				
				$assignment = 1;
				// make a new one
				$board = $this->GameBoards->newEntity();
				$board = $this->GameBoards->patchEntity($board,['player1'=>$sessionid]);
				$board = $this->GameBoards->save($board);
				
			}
		}
		
		// check oponent status
		$oponent_assignment = 0;
		$user_move = '';
		$oponent_move = '';
		if($assignment==2)//user is 2
		{
			$user_move = $board->player2_move;
			if($board->player1!='') // oponent is 1
			{
				$oponent_assignment = 1;
				$oponent_move = $board->player1_move;
			}
			else
				$oponent_assignment = 0;
		}
		else // user is 1
		{
			$user_move = $board->player1_move;
			if($board->player2!='') // oponent is 2
			{
				$oponent_assignment = 2;
				$oponent_move = $board->player2_move;
			}
			else
				$oponent_assignment = 0;
		}
		
		//return ['boardid'=>$board->id,'assignment'=>$assignment];
		return ['boardid'=>$board->id,'player1_move'=>$board->player1_move,'player2_move'=>$board->player2_move,'turn'=>$board->turn,'newgame'=>$board->newgame,'user'=>['assignment'=>$assignment,'move'=>$user_move],'oponent'=>['assignment'=>$oponent_assignment,'move'=>$oponent_move]];
	}

}
