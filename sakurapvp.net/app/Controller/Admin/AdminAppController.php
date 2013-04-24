<?php
App::uses('AppController', 'Controller');
class AdminAppController extends AppController {
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->deny();
	}

	public function isAuthorized($user) {
		if (!$this->Perms->isModerator($user)){
			$this->Session->setFlash('リクエストされたページにアクセスする権限がありません！', 'error');
			return false;
		}else{
			return true;
		}
	}
}