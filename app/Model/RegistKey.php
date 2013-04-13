<?php
App::uses('AppModel', 'Model');

class RegistKey extends AppModel {
	public $name = 'RegistKey';
	public $useTable = 'regist_keys';
	public $primaryKey = 'player_id';

	public $hasOne = array(
			'User' => array(
					'className' => 'User',
					'foreignKey' => 'player_id'
				),
		);
}
?>