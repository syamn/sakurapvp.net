<?php
App::uses('AppModel', 'Model');
 
class UserStat extends AppModel {
	public $name = 'UserStat';
	public $useTable = 'user_stats';
	public $primaryKey = 'player_id';
}
?>