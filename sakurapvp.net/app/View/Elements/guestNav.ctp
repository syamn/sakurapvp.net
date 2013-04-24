<ul class="nav pull-right">
	<li class="divider-vertical"></li>
	<li>		
		<?=$this->Html->link('<img id="header-face" src="/img/get?l=http://skin.sakurapvp.net/face/guest/22.png"/>ゲストさん ようこそ！',
		array('controller' => 'sessions', 'action' => 'login'), array('escape' => false));?>
	</li>
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i><b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><?=$this->Html->link('ログイン', array('controller' => 'sessions', 'action' => 'login'));?></li>
			<li><?=$this->Html->link('アカウント作成', array('controller' => 'sessions', 'action' => 'make_account'));?></li>
		</ul>
	</li>
</ul>