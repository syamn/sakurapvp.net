<?php
App::uses('AppModel', 'Model');

class RegistKey extends AppModel {
	public $name = 'RegistKey';
	public $useTable = 'regist_keys';
	public $primaryKey = 'player_id';
	public $validate = array(
		'player_id' => array(
				'内部エラーです (PID 0)' => array('rule' => array('comparison', '>', 0)),
			),
		'email' => array(
					'有効なメールアドレスを入力してください' => array('rule' => 'email'),
					'100文字以下のメールアドレスしか設定できません' => array('rule' => array('maxLength', 100)),
				),
	);

	public $hasOne = array(
			'User' => array(
					'className' => 'User',
					'foreignKey' => 'player_id'
				),
		);
}
?>