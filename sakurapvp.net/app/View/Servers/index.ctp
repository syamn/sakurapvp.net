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
						<div class="thumbnail server-thumbnail">
							<?php if((int)$server['ServerData']['status'] === 1): ?>
								<span class="label label-info">オンライン</span>
							<?php else: ?>
								<span class="label label-important">オフライン</span>
							<?php endif;?>							
							<!--<img data-src="#" alt="" /><center><code>画像準備中…</code></center>-->
							<center>
								<h4><?=$server['ServerData']['name'];?>.sakurapvp.net<small><br />
								<?=count($server['ServerData']['players']);?>/<?=$server['ServerData']['max_players'];?> players online</small></h4>
							</center>
							<ul>
								<?php foreach($server['ServerData']['players'] as $name): ?>
									<a rel="tooltip" title="<?=$name;?>">
										<img class="avatar-face" src="/img/get?l=http://skin.sakurapvp.net/face/<?=$name;?>/40.png" />
									</a>
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
					<div class="thumbnail other-server">
						<!--<img data-src="#" alt="" /><center><code>画像準備中…</code></center>-->
						<center><h4><s>auth.sakurapvp.net</s></h4></center>
						<p>SakuraPVP ユーザー登録用サーバーです。<br />
							他のサーバーにログインできなくなった際のユーザー登録にご利用ください。</p>
					</div>
				</li>
				<li class="span6">
					<div class="thumbnail other-server">
						<!--<img data-src="#" alt="" /><center><code>画像準備中…</code></center>-->
						<center><h4>build.sakurapvp.net</h4></center>
						<p>SakuraPVP マップ製作用サーバーです。<br />
							新しいPVPマップの製作に使われます。</p>
						<div class="pull-right">
							<?=$this->Html->link('<span class="label label-success">&gt;&gt; マップの製作について</span>',
								array('controller' => 'help', 'action' => 'map_making'),
								array('escape' => false));
							?>							
						</div>
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
							<a rel="tooltip" title="<?=$name;?>">
								<img class="avatar-face" src="/img/get?l=http://skin.sakurapvp.net/face/<?=$name;?>/40.png" />
							</a>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('a[rel=tooltip]').tooltip({'placement': 'top'});

	$(document).ready(function() {
		equalHeight($(".other-server"));
	});

	$(window).load(function() {
		equalHeight($(".server-thumbnail"));
	});

	function equalHeight(group) {
		tallest = 0;
		group.each(function() {
			thisHeight = $(this).height();
			if(thisHeight > tallest) {
				tallest = thisHeight;
			}
		});
		group.each(function() { $(this).height(tallest); });
	}
</script>