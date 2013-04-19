<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
 
class RevisionsController extends AppController { 
	var $uses = array('CommitLog');

	var $repoList = array(
			'sakurapvp' => array(9029307, 'SakuraPVP'),
			'mapmaker' => array(9174613, 'MapMaker'),
			'web' => array(9194389, 'ウェブサイト')
		);

	public function beforeFilter(){
		// Call parent filter
		parent::beforeFilter();

		// Don't check post data only github-hooks. (But SSL connection still required.)
		if ($this->action === 'github_hooks'){
			$this->Security->unlockedActions = array('github_hooks');
		}
	}

	public function index($repo = 'sakurapvp', $page = 1) {
		// Validate
		$repo = trim(strtolower($repo));
		$repoData = $this->repoList[$repo];
		if (empty($repoData)){
			throw new NotFoundException('指定されたリポジトリが見つかりません');
		}

		$page = ((int)$page > 0) ? (int)$page : 1;

		$rowsForPage = 30;

		$rows = $this->CommitLog->find('all', array(
			'conditions' => array('CommitLog.repo_id' => $repoData[0]),
			'order' => 'CommitLog.id DESC',
			'limit' => $rowsForPage,
			'page' => $page
			));

		// get max pages
		$count = $this->CommitLog->find('count', array('conditions' => array('CommitLog.repo_id' => $repoData[0])));
		$lastPage = (int)(($count - 1) / $rowsForPage) + 1;

		// Ser variables for View
		$this->set('firstRowNo', $page * $rowsForPage - 29);
		$this->set('rows', $rows);

		$this->set('repoName', $repoData[1]);
		$this->set('repoList', $this->repoList);
		$this->set('repo', $repo);
		$this->set('page', $page);
		$this->Set('lastPage', $lastPage);

		$this->set('title_for_layout', 'リビジョン');
	}

	public function sakurapvp($page = 1){
		$this->setAction('index', 'sakurapvp', $page);
	}
	public function mapmaker($page = 1){
		$this->setAction('index', 'mapmaker', $page);
	}
	public function web($page = 1){
		$this->setAction('index', 'web', $page);
	}

	public function github_hooks() {
		$this->autoRender = false;

		// Load Libraries
		App::import('Vendor', 'OAuth'.DS.'OAuthClient');
		App::import('Vendor', 'OAuth'.DS.'Config');

		// Github settings
		$ALLOW_REPO_OWNER = array('syamn', 'SakuraServer', 'SakuraPVP');
		$GITHUB_IPS = array('207.97.227.253', '50.57.128.197', '108.171.174.178', '50.57.231.61', '204.232.175.64', '204.232.175.75', '192.30.252.0');
		// End of github settings

		// begen new logfile
		$this->_log("==BEGEN REQUEST==", true);

		// check from github IP
		if (!in_array($_SERVER['REMOTE_ADDR'], $GITHUB_IPS)){
			$this->_log("Error: Invalid IP ".$_SERVER['REMOTE_ADDR']);
			header("HTTP/1.1 404 Not Found");
			exit;
		}

		// check req data
		if (!isset($_POST['payload'])){
			$this->_log("Error: Request error (POST['payload'])");
			exit;
		}

		$payload_raw = $_POST['payload'];
		try{
			$payload = json_decode($payload_raw); // json_decode(stripslashes($payload_raw));
		}catch(Exception $ex){
			$this->_log("Error: Occred exception while json_decode!");
			$this->_log(print_r($ex, TRUE));
			exit;
		}

		if (!is_object($payload) || !is_array($payload->commits)){
			$this->_log("Error: Empty or no commits payload!");
			exit;
		}

		$this->_log("Payload Contents: ");
		$this->_log(print_r($payload, TRUE));

		// get detail
		/* Pusher */
		$pusher_name = $payload->pusher->name;
		$pusher_email = $payload->pusher->email;

		/* Repository */
		$repo_name = $payload->repository->name;
		$repo_url = $payload->repository->url;
		$repo_owner = $payload->repository->owner->name;

		/* Etc */
		$ref = $payload->ref; // check pushed to master? if(=== 'refs/heads/master'){}
		$compare_url = $payload->compare;

		// Check repo owner
		if (!in_array($repo_owner, $ALLOW_REPO_OWNER)){
			$this->_log("Error: This repo owner '".$repo_owner."' not contains to allows list");
			exit;
		}

		$now = time();
		$repoId = $payload->repository->id;

		$this->_log("Starting foreach loop");
		foreach($payload->commits as $commit) {
			/* Commits */
			$commit_url = $commit->url;
			$commit_msg = $commit->message;
			$committer_name = $commit->author->name;
			$committer_username = $commit->author->username;
			$committer_email = $commit->author->email;
			$hash = $commit->id;
			$shortHash = mb_substr($hash, 0, 6);

			$this->_log("Updating database for commit sha ".$shortHash);

			// Update Database
			$newRaw = array('CommitLog' => array(
				'repo_id' => $repoId,
				'hash' => $shortHash,
				'author' => $committer_username,
				'msg' => $commit_msg,
				'date' => $now
			));
			$this->CommitLog->create();
			$this->CommitLog->save($newRaw);
		}

		// Update twitter status
		$this->_log("Building update message");

		$tweet = null;
		foreach ($this->repoList as $urlName => $data){
			if ($data[0] == (int)$repoId){
				$tweet = $committer_username.'が'.$data[1].'を更新しました: http://sakurapvp.net/revisions/'.$urlName.' (HEAD: '.$shortHash.')';
			}
		}
		if (!empty($tweet)){
			if (mb_strlen($tweet) > 140){
				$tweet = mb_substr($tweet, 0, 138)."..";
			}
			$this->_log("Tweeting..: ".$tweet);
			$twitter = new OAuthClient(OAUTH_CONFIG::CONSUMER_KEY, OAUTH_CONFIG::CONSUMER_SECRET);
			$twitter->post(OAUTH_CONFIG::ACCESS_TOKEN, OAUTH_CONFIG::ACCESS_TOKEN_SECRET, 
				'https://api.twitter.com/1/statuses/update.json', array('status' => $tweet));
		}else{
			$this->_log("Tweet message is empty! Skipped tweeting!");
		}

		// end of logfile
		$this->_log("==END==");
	}

	private function _log($line, $newFile = false){
		if ($newFile){
			file_put_contents('log/last_github_hook.log', $line."\n");
		}else{
			file_put_contents('log/last_github_hook.log', $line."\n", FILE_APPEND);
		}
	}
}