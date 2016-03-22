<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Home Controller
 *
 * @property \App\Model\Table\HomeTable $Home
 */
class HomeController extends AppController
{
	
	public function initialize()
    {
        parent::initialize();
		
		$this->layout = 'default';

        $this->loadModel('GameBoards');
    }
	
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
      
    }
}
