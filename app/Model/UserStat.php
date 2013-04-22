<?php
App::uses('AppModel', 'Model');
 
class UserStat extends AppModel {
	public $name = 'UserStat';
	public $useTable = 'user_stats';
	public $primaryKey = 'player_id';
	public $validate = array(
		'player_id' => array(
				'内部エラーです (PID 0)' => array('rule' => array('comparison', '>', 0)),
			),
	);
}
?>