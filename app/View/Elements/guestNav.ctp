<ul class="nav pull-right">
	<li class="divider-vertical"></li>
	<li><?=$this->Html->link('ゲストさん ようこそ！', array('controller' => 'user', 'action' => 'login'));?></li>
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><?=$this->Html->link('ログイン', array('controller' => 'user', 'action' => 'login'));?></li>
			<li><?=$this->Html->link('アカウント作成', array('controller' => 'user', 'action' => 'make_account'));?></li>
		</ul>
	</li>
</ul>