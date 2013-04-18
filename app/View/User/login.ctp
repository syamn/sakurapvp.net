<div class="jumbotron">
	<h1>ログイン <small> Login</small></h1>
	<hr />
	<div class="row">
		<div class="span6">
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
					<div class="controls"><label class="checkbox"><?=$this->Form->input('remember', array('type' => 'checkbox'));?>
						次回から自動でログイン <abbr title="ログイン情報をクッキーに保持させます。ご利用のコンピューターが共用のものである場合、チェックを入れないでください。" class="initialism"> [?]</abbr></label></div>
				</div>
				<div class="control-group">
					<div class="controls"><?=$this->Form->submit('ログイン', array('class' => 'btn btn-primary'));?></div>
				</div>
			<?=$this->Form->end();?>
		</div>
		<div class="span3 offset1">
			<ul class="nav nav-tabs nav-stacked">
				<li><?=$this->Html->link('アカウントの作成', array('controller' => 'user', 'action' => 'make_account'));?></li>
			</ul>
		</div>
	</div>
</div>