<div class="jumbotron">
	<h1>登録情報編集 <small> ユーザー基本情報</small></h1>
	<br />
	<ul class="nav nav-tabs">
		<li class="active disabled"><a>基本情報</a></li>
		<li><?=$this->Html->link('プロフィール', array('controller' => 'user', 'action' => 'edit', 'profile'));?></li>
	</ul>
	<?=$this->Form->create('UserData', array('class' => 'form-horizontal', 'inputDefaults' => array(
			'label' => false, 'div' => false, 'error' => array('attributes' => array('wrap' => 'p', 'class' => 'text-error'))
		)));?>
		<div class="controls"><h5>変更する情報のみ入力してください</h5></div>
		<div class="control-group">
			<label class="control-label" for="pname">プレイヤー名</label>
			<div class="controls"><?=$this->Form->input('player_name', array('value' => $user['player_name'], 'class' => 'input-xlarge', 'id' => 'pname', 'disabled' => 'true'));?>
			 <span class="help-inline">変更できません</span></div>
		</div>
		<div class="control-group">
			<label class="control-label" for="email">メールアドレス</label>
			<div class="controls"><?=$this->Form->input('email', array('placeholder' => 'Email', 'value' => $user['Data']['email'], 'class' => 'input-xlarge', 'id' => 'email', 
				'disabled' => (!empty($pendingEmail))?'true':'' ));?></div>
			<?php
				if (!empty($pendingEmail)){
					$text = '確認コードを入力する (確認メール送信済み)';
					echo '<div class="controls"><label>'.$pendingEmail.' への変更をリクエスト中<br />'.$this->Html->link($text, array('controller' => 'user', 'action' => 'confirm_email')).'</label></div>';
				}
			?>
		</div>
		<div id="err_pass1" class="control-group">
			<label class="control-label" for="pass1">新しいパスワード</label>
			<div class="controls">
				<?=$this->Form->input('pass1', array('placeholder' => 'Password', 'class' => 'input-xlarge', 'id' => 'pass1', 'type' => 'password'));?>
				 <span class="help-inline">6 ～ 100文字以内で設定してください</span>
			</div>
		</div>
		<div id="err_pass2" class="control-group">
			<label class="control-label" for="pass2">新しいパスワード(確認)</label>
			<div class="controls">
				<?=$this->Form->input('pass2', array('placeholder' => 'Confirm password', 'class' => 'input-xlarge', 'id' => 'pass2', 'type' => 'password'));?>
				<span id="err_pass2_msg" class="help-block" style="display: none;">確認のパスワードが一致していません！</span>
			</div>
		</div>
		<hr />
		<div class="controls"><label>設定を変更するには、現在のパスワードを入力する必要があります</label></div>
		<div id="err_currentPass" class="control-group">
			<label class="control-label" for="currentPass">現在のパスワード</label>
			<div class="controls"><?=$this->Form->input('currentPass', array('placeholder' => 'Current password', 'class' => 'input-xlarge', 'id' => 'currentPass', 'type' => 'password'));?>
			<span id="err_currentPass_msg" class="help-block" style="display: none;">現在のパスワードを入力してください！</span>
			</div>
		</div>
		<div class="controls"><?=$this->Form->submit('登録情報を更新する', array('id' => 'submit', 'class' => 'btn btn-primary', 'onClick' => 'return validate();'));?></div>
	<?=$this->Form->end();?>
</div>
<script type="text/javascript" charset="utf-8">
	function validate(){
		var error = false;

		// pass trength
		var tmp = $("#currentPass").val().length;
		if (isNaN(tmp) || tmp < 6 || tmp > 100){
			$("#err_currentPass").addClass("error");
			$("#err_currentPass_msg").show();
			error = true;
		}else{
			$("#err_currentPass").removeClass("error");
			$("#err_currentPass_msg").hide();
		}

		// compare
		var tmp = $("#pass1").val().length;
		var tmp2 = ($("#pass1").val() !== $("#pass2").val());
		if ((isNaN(tmp) == false && tmp > 0) && (tmp < 6 || tmp > 100 || tmp2)){			
			$("#err_pass1").addClass("error");
			$("#err_pass2").addClass("error");
			if (tmp2){
				$("#err_pass2_msg").show();
			}
			error = true;
		}else{
			$("#err_pass1").removeClass("error");
			$("#err_pass2").removeClass("error");
		}
		if (!tmp2){
			$("#err_pass2_msg").hide();
		}

		// Disable submit button for prevents double send
		if (!error){
			$("#submit").addClass('disabled');
		}
		return (!error);
	}
</script>