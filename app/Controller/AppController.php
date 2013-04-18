<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $uses = array('UserData');
	public $components = array(
		'DebugKit.Toolbar',
		'Session',
		'Security',
		'Auth' => array(
				'authenticate' => array(
					'ExtendedForm' => array( // Class ExtendedFormAuthenticate exntends FormAuthenticate
						'fields' => array('username' => 'player_name', 'password' => 'password'),
						'userModel' => 'User',
						'recursive' => 0,
					),
				),
				'loginRedirect' => array('controller' => 'user', 'action' => 'index'),
				'logoutRedirect' => array('controller' => 'user', 'action' => 'login'),
				'loginAction' => array('controller' => 'user', 'action' => 'login'),
				'authError' => 'このアクションを行うためにはログインが必要です',
			)
		);

	public function beforeFilter(){
		// First, require SSL connection if this isn't on the test environment 
		if (env('SERVER_ADDR') !== env('REMOTE_ADDR')){
			$this->Security->blackHoleCallback = '_forceSSL';
			$this->Security->requireSecure();
		}

		// Call parent filter
		parent::beforeFilter();
		$this->Auth->allow();

		// Set login status for view
		$this->set('loggedIn', $this->Auth->loggedIn());

		// Update user activity if logged in
		if ($this->Auth->loggedIn()){
			$this->UserData->save(array('UserData' => array(
				'player_id' => $this->Auth->user('player_id'), 
				'lastView' => time(), 
				'lastViewPage' => $this->name . '/' . $this->action
			)));
		}
	}

	public function _forceSSL() {
		$this->redirect('https://' . env('SERVER_NAME') . $this->here);
	}
}
