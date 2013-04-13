<?php
App::uses('AppModel', 'Model');
 
class User extends AppModel {
	public $name = 'User';
	public $useTable = 'user_base';
	public $primaryKey = 'player_id';

	public $hasOne = array(
			'Stat' => array(
					'className' => 'UserStat',
					'foreignKey' => 'player_id'
				),
			'Data' => array(
					'className' => 'UserData',
					'foreignKey' => 'player_id'
				)
		);

	/* ユーザーIDからユーザ名のみを返す 存在しない場合はｎｕｌｌを返す */
	public function getUsername($id){
		$record = $this->find('first', array(
			'conditions' => array('User.player_id' => $id),
			'recursive' => -1,
			'fields' => array('User.player_name')
			));
		return (empty($record)) ? null : $record['User']['player_name'];
	}

	/* ユーザー名からユーザＩＤのみを返す 存在しない場合はnullを返す */
	public function getUserID($name){
		$record = $this->find('first', array(
			'conditions' => array('User.player_name' => $name),
			'recursive' => -1,
			'fields' => array('User.player_id')
			));
		return (empty($record)) ? null : $record['User']['player_id'];
	}
}
?>