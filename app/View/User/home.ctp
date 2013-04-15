<div class="jumbotron">
	<h1>ホーム <small> Home</h1>
	<hr />
	<h2>You logged in as <?=h(AuthComponent::user('player_name'));?> (<?=h(AuthComponent::user('Data.email'));?>)!</h2>
	<h3><?=$this->Html->link('Logout', array('controller' => 'user', 'action' => 'logout'));?></h3>
</div>