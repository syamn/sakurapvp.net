<ul class="nav pull-right">
	<li class="divider-vertical"></li>
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
			<img id="header-face" src="/img/get?l=http://skin.sakurapvp.net/face/<?=AuthComponent::user('player_name');?>/20.png"/>
			ようこそ <?=AuthComponent::user('player_name');?> さん！
			<b class="caret"></b>
		</a>
		<ul class="dropdown-menu">
			<li><?=$this->Html->link('ホーム', array('controller' => 'user', 'action' => 'home'));?></li>
			<li><?=$this->Html->link('ユーザー情報の編集', array('controller' => 'user', 'action' => 'edit'));?></li>
			<b class="caret"></b>
			<li><?=$this->Html->link('ログアウト', array('controller' => 'sessions', 'action' => 'logout'));?></li>
		</ul>
	</li>
</ul>