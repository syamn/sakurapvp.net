<div class="jumbotron">
	<h1>ログイン <small> Login</h1>
	<hr />
	<?php
		$flash = $this->Session->flash('auth');
		if (!empty($flash)){
			echo '<div id="flash" class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>';
			echo $flash;
			echo '</div>';
		}
	?>
	<?=$this->Form->create('User', array('class' => 'form-horizontal', 'inputDefaults' => array('label' => false, 'div' => false)));?>
		<div class="control-group">
			<label class="control-label" for="pname">プレイヤー名</label>
			<div class="controls"><?=$this->Form->input('player_name', array('placeholder' => 'Player Name', 'class' => 'input-xlarge', 'id' => 'pname'));?></div>
		</div>
		<div class="control-group">
			<label class="control-label" for="pass">パスワード</label>
			<div class="controls"><?=$this->Form->input('password', array('placeholder' => 'Password', 'class' => 'input-xlarge', 'id' => 'pass'));?></div>
		</div>
		<div class="control-group">
			<div class="controls"><?=$this->Form->submit('ログイン', array('class' => 'btn btn-primary'));?></div>
		</div>
	<?=$this->Form->end();?>
</div>