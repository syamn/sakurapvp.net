<?php
App::uses('CakeEmail', 'Network/Email');
class ApiController extends AppController {
	var $uses = array('ServerData');
	var $args;

	public function beforeFilter(){
		parent::beforeFilter();

		$this->autoRender = false;
		$this->args = $this->params['url'];

		// Require id / key
		if (empty($this->args['sid']) || empty($this->args['skey'])){
			throw new BadRequestException('Server ID and Private Key are required.');
		}

		// Auth server
		$tmp = $this->ServerData->findByNameAndKey($this->args['sid'], $this->args['skey']);
		if (empty($tmp)){
			throw new BadRequestException('Server authentication failed.');
		}
	}

	public function new_account() {
		if (empty($this->args['name']) || empty($this->args['mail']) || empty($this->args['regkey'])){
			throw new BadRequestException('Invalid new account request.');
		}
		
		$title = "Minecraftゲームサーバー SakuraPVPへようこそ！";
		$vars = array(
				'name' => $this->args['name'],
				'key' => $this->args['regkey'],
				'url' => 'http://sakurapvp.net/user/make_account/'.$this->args['name'].'?key='.$this->args['regkey'],
			);

		$mail = new CakeEmail('default');
		$check = $mail
			->template('regist_mail', 'default')
			->viewVars($vars)
			->to($this->args['mail'])
			->subject($title)
			->send();

		if($check){
			exit("OK,");
		}else{
			exit("Error, An internal error occured.");
		}
	}
}