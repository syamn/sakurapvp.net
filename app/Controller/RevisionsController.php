<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
 
class RevisionsController extends AppController { 
	var $uses = array('CommitLog');

	public function index() {
		//$this->layout = "Sample"; // Use default layout
		//$this->set("msg", "This is home controller!");

		$repoID = 9029307; // SakuraPVP
		$rows = $this->CommitLog->find('all', array(
			'conditions' => array('CommitLog.repo_id' => $repoID),
			'order' => 'CommitLog.id DESC',
			'limit' => '30',
			'page' => 1
			));

		$this->set('firstRowNo', 1);
		$this->set('rows', $rows);
	}

	public function github_hooks() {
		$this->autoRender = false;

		// Load Libraries
		App::import('Vendor', 'OAuth'.DS.'OAuthClient');
		App::import('Vendor', 'OAuth'.DS.'Config');

		// Github settings
		$ALLOW_REPO_OWNER = array('syamn', 'SakuraServer', 'SakuraPVP');
		$GITHUB_IPS = array('207.97.227.253', '50.57.128.197', '108.171.174.178', '204.232.175.75');
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
    	$tweet = "Updated by ".$committer_username.". http://sakurapvp.net/revisions (HEAD: ".$shortHash.")";
	    if (mb_strlen($tweet) > 140){
	        $tweet = mb_substr($tweet, 0, 138)."..";
	    }
	    $this->_log("Tweeting..: ".$tweet);

    	$twitter = new OAuthClient(OAUTH_CONFIG::CONSUMER_KEY, OAUTH_CONFIG::CONSUMER_SECRET);
		$twitter->post(OAUTH_CONFIG::ACCESS_TOKEN, OAUTH_CONFIG::ACCESS_TOKEN_SECRET, 
			'https://api.twitter.com/1/statuses/update.json', array('status' => $tweet));

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