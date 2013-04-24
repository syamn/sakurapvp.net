<?php

class PermsComponent extends Component {
	/**
	 * ユーザーが管理人かどうかを返す
	 *
	 * @param ユーザ情報オブジェクト またはnull
	 * @return 権限を持っていればtrue
	 */
	public function isAdmin($user = null) {
		$level = (int) $this->_getUserLevel($user);
		return (bool)($level >= 10);
	}

	/**
	 * ユーザーがモデレーターかどうかを返す
	 *
	 * @param ユーザ情報オブジェクト またはnull
	 * @return 権限を持っていればtrue
	 */
	public function isModerator($user = null) {
		$level = (int) $this->_getUserLevel($user);
		return (bool)($level >= 5);
	}

	/**
	 * ユーザーレベルを返す ログインしていない場合はnullを返す
	 *
	 * @param ユーザ情報オブジェクト またはnull
	 * @return ユーザーレベルの文字列 またはnull
	 */
	private function _getUserLevel($user){
		if (is_null($user)){
			return AuthComponent::user('user_level');
		}

		if (isset($user['user_level'])){
			return $user['user_level'];
		}else{
			return null;
		}
	}
}