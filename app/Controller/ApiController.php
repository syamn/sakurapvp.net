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

		$name = $this->args['name'];
		$key = $this->args['regkey'];

		$title = "SakuraPVPへようこそ！";
		$body = $name." さん SakuraPVPへようこそ！\n\n";
		$body .= "これは、サーバー内から登録用のコマンドを入力した際に送信される自動送信メールです。\n";
		$body .= "あなたのアカウントはまだ作成されていません。次のリンクをクリックして、ユーザー登録へお進みください：\n";
		$body .= "http://sakurapvp.net/user/make_account/".$name."?key=".$key."\n";
		$body .= "あなたの登録キー: ".$key."\n\n";
		$body .= "(このメッセージは送信専用のメールアドレスより送信しているため、返信しないでください。)\n";
		$body .= "---------------------------------------------------\n";
		$body .= "SakuraPVP Staff\n  http://sakurapvp.net";


		$mail = new CakeEmail();
		$check = $mail
			->config(array('log' => 'emails'))
			->from(array('noreply@sakurapvp.net' => 'SakuraPVP'))
			->to($this->args['mail'])
			->subject($title)
			->send($body);

		if($check){
			exit("OK,");
		}else{
			exit("Error, An internal error occured.");
		}
	}
}