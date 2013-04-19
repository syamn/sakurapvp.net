<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email');
 
class UserController extends AppController {
	var $uses = array('User', 'UserData', 'RegistKey', 'LoginAttempt');
	var $components = array('Cookie');

	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny('home', 'logout');
	}

	public function index(){
		$this->autoRender = false;
		$this->redirect(array('controller' => 'user', 'action' => 'home'));
	}

	public function home(){
		$this->set('title_for_layout', 'ユーザーホーム');
	}
}