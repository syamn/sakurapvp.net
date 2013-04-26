<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
 
class ServersController extends AppController {
	var $uses = array('User', 'ServerData');

	public function index() {
		// Getting data from database.
		$servers = $this->ServerData->find('all', array(
				'conditions' => array('ServerData.hidden' => 0),
				'fields' => array('ServerData.server_id', 'ServerData.name', 'ServerData.max_players', 'ServerData.status', 'ServerData.map_id', 'ServerData.next_id'),
				'order' => array('ServerData.server_id'),
				'recursive' => -1,
			));
		$users = $this->User->find('all', array(
				'conditions' => array('User.currentServer != ' => null),
				'fields' => array('User.player_name', 'User.currentServer'),
				'recursive' => -1,
			));

		$totalUsers = count($users);
		$maxUsers = 0;
		$availables = 0;

		// Mapping users to server arrays
		foreach ($servers as &$server){
			// Server offline, skip adding this server data.
			if ((int)$server['ServerData']['status'] !== 1){
				$server['ServerData']['players'] = array();
				continue;
			}

			// Server online. Count and adding this server data.
			$online = array();
			foreach($users as $user_row => $user){
				if ($user['User']['currentServer'] === $server['ServerData']['server_id']){
					array_push($online, $user['User']['player_name']); // Add this user to this server data.
					unset($users[$user_row]); // Remove this user from $users map.
				}
			}
			
			$availables++; // increment available servers count.
			$maxUsers += (int)$server['ServerData']['max_players'];
			$server['ServerData']['players'] = $online;
		}

		$maintenance = false;
		$this->set(array(
				'maintenance' => $maintenance, // boolean
				'totalUsers' => $totalUsers, // int
				'maxUsers' => $maxUsers, // int
				'availables' => $availables, // int
				'servers' => $servers, // array
			));
		$this->set('title_for_layout', 'サーバーリスト');
	}
}