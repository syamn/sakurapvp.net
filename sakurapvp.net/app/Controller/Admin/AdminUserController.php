<?php
App::uses('AdminAppController', 'Controller');
 
class AdminUserController extends AdminAppController {
	public function beforeFilter(){
		parent::beforeFilter();
		if ($this->action == 'list'){
			$this->setAction('list_');
		}
	}

	public function index(){
		$this->redirect(array('action' => 'list'));
	}

	public function list_(){
		
	}
}