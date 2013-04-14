<?php
App::uses('FormAuthenticate', 'Controller/Component/Auth');
App::uses('Sanitize', 'Utility');

class ExtendedFormAuthenticate extends FormAuthenticate{
	// See: Cake/Controller/Component/Auth/FormAuthenticate.php (extends BaseAuthenticate)
	// (And Cake/Controller/Component/Auth/BaseAuthenticate.php)

	// TODO そのうちオーバライドするかも
	/*
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		$userModel = $this->settings['userModel'];
		list(, $model) = pluginSplit($userModel);

		$fields = $this->settings['fields'];
		if (!$this->_checkFields($request, $model, $fields)) {
			return false;
		}
		return $this->_findUser(
			$request->data[$model][$fields['username']],
			$request->data[$model][$fields['password']]
		);
	}
	protected function _checkFields(CakeRequest $request, $model, $fields) {
		if (empty($request->data[$model])) {
			return false;
		}
		if (
			empty($request->data[$model][$fields['username']]) ||
			empty($request->data[$model][$fields['password']])
		) {
			return false;
		}
		return true;
	}
	*/

	// From BaseAuthenticate
	protected function _findUser($conditions, $password = null) {
		$userModel = $this->settings['userModel'];
		list(, $model) = pluginSplit($userModel);
		$fields = $this->settings['fields'];

		// Reject bad request. conditions variable must be string (name).
		if (is_array($conditions)) {
			return false; // don't support array request, return false
		}
		$username = $conditions;

		// username and password required
		if (!$username || !$password) {
			return false;
		}

		// Username and Password conditions. Use BINARY compare for password.
		$conditions = array(
			$model . '.' . $fields['username'] => $username,
			'BINARY Data.' . $fields['password'] . ' = "'.Sanitize::clean($this->_password($password), 'default').'"' // Use UserData tables for password
		);

		// Merge scope settings if available
		if (!empty($this->settings['scope'])) {
			$conditions = array_merge($conditions, $this->settings['scope']);
		}

		// searching
		$result = ClassRegistry::init($userModel)->find('first', array(
			'conditions' => $conditions,
			'recursive' => $this->settings['recursive'],
			'contain' => $this->settings['contain'],
		));

		// Record not found, auth failed
		if (empty($result) || empty($result[$model])) {
			return false;
		}

		// Remove UserData->password field
		if (isset($result['Data']) && isset($result['Data'][$fields['password']])){
			unset($result['Data'][$fields['password']]);
		}

		// Pull user class then return it
		$user = $result[$model];
		unset($result[$model]);
		return array_merge($user, $result);
	}
}
?>