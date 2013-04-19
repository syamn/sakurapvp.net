<div class="jumbotron">
	<h1>ログイン <small> Login</small></h1>
	<hr />
	<div class="row">
		<?php
			$flash = $this->Session->flash('auth');
			if (!empty($flash)){
				echo '<div id="flash" class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>';
				echo $flash;
				echo '</div>';
			}
		?>
		<div class="span6">
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
						次回から自動でログイン <abbr title="ログイン情報をクッキーに保持させます。ご利用のコンピューターが共用のものである場合、チェックを入れないでください。" class="initialism"><i class="icon-question-sign"></i></abbr>
					</label></div>
				</div>
				<?php if($remain > 0): ?>
					<div class="control-group">
						<div class="controls"><?=$this->Form->submit('ログイン', array('class' => 'btn btn-primary'));?></div>
					</div>
					<div class="alert alert-info controls">
						あと <strong><?=$remain;?>回</strong> ログイン試行できます <button type="button" class="close" data-dismiss="alert">&times;</button>
					</div>
				<?php else: ?>
					<div class="alert alert-error controls">
						<i class="icon-remove"></i> <strong><u>ログインできません！</u></strong><br />
						あなたのIPはブロックされています。<br /><small>数時間経過しても解除されない場合は、サポートまでご連絡ください。</small>
					</div>
				<?php endif; ?>
			<?=$this->Form->end();?>
			
		</div>
		<div class="span3 offset1">
			<ul class="nav nav-tabs nav-stacked">
				<li><?=$this->Html->link('アカウントの作成', array('controller' => 'sessions', 'action' => 'make_account'));?></li>
				<li><?=$this->Html->link('パスワードを忘れた', array('controller' => 'sessions', 'action' => 'forgot_password'));?></li>
			</ul>
		</div>
	</div>
</div>