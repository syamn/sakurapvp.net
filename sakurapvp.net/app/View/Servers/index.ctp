<div class="container-fluid">
	<h1>サーバーリスト <small> SakuraPVP Servers</small></h1>
	<hr />
	<?php if($maintenance): ?>
		<div class="alert alert-info">
			<i class="icon-exclamation-sign"></i> <b>現在、サーバーのメンテナンスを行っています！</b> 接続した場合でも、正常なゲームプレイができない可能性があります。ご注意ください。
		</div>
	<?php endif; ?>
	<div class="row-fluid">
		<div id="servers" class="span8">
			<h3>SakuraPVP ゲームサーバー <small> Total <?=$availables;?> Servers Available!</small></h3>
			<ul class="thumbnails">
				<?php foreach($servers as $server): ?>
					<li class="span6">
						<div class="thumbnail">
							<?php if((int)$server['ServerData']['status'] === 1): ?>
								<span class="label label-info">Status: Online</span>
							<?php else: ?>
								<span class="label label-important">Status: Offline</span>
							<?php endif;?>
							
							<img data-src="#" alt="" /><center><code>画像準備中…</code></center>
							<center>
								<h4><?=$server['ServerData']['name'];?>.sakurapvp.net<small><br />
								<?=count($server['ServerData']['players']);?>/<?=$server['ServerData']['max_players'];?> players online</small></h4>
							</center>
							<ul>
								<?php foreach($server['ServerData']['players'] as $name): ?>
									<li><?=$name;?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<hr />
			<h3>その他のサーバー <small> Other servers</small></h3>
			<ul class="thumbnails">
				<li class="span6">
					<div class="thumbnail">
						<!--<img data-src="#" alt="" /><center><code>画像準備中…</code></center>-->
						<center><h4>auth.sakurapvp.net</h4></center>
						<p>SakuraPVP ユーザー登録用サーバーです。<br />
							他のサーバーにログインできなくなった際のユーザー登録にご利用ください。</p>
					</div>
				</li>
				<li class="span6">
					<div class="thumbnail">
						<!--<img data-src="#" alt="" /><center><code>画像準備中…</code></center>-->
						<center><h4>build.sakurapvp.net</h4></center>
						<p>SakuraPVP マップ製作用サーバーです。<br />
							新しいPVPマップの製作に使われます。</p>
					</div>
				</li>
			</ul>
		</div>
		<div id="servers" class="span4">
			<h3>オンラインプレイヤー<small class="pull-right">Total <?=$totalUsers;?>/<?=$maxUsers;?> players online now!</small></h3>
			<br />
			<div class="well">
				<ul>
					<?php foreach($servers as $server): ?>
						<?php foreach($server['ServerData']['players'] as $name): ?>
							<li><?=$name;?></li>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>