<?php $this->Html->css('headers', null, array('inline' => false)); ?>
<?php $this->Html->script('jquery/jquery.pjax.js', array('inline' => false)); ?>
<div class="container-fluid">
	<h1>マップ製作について <small> How to make a new Map?</small></h1>
	<hr />
	<ul id="tab-list" class="nav nav-tabs">
		<li class="offset1 active"><a href="#begin" data-toggle="tab" step="0">Step0: 製作方法の決定</a></li>
		<li class=""><a href="#server" data-toggle="tab" step="1">Step1: build.sakurapvp.net</a></li>
		<li class="disabled"><a>Step2: 設定ファイルの作成</a></li>
		<li class="disabled"><a>Step3: にゃあ</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="begin">
			<p>このページでは、新しいマップの製作方法から、実際にそのマップで遊べるようになるまでの流れを説明します。<br />
				新しいマップを自分で作ってみたい、自分の作ったマップで遊んでみたい！と思ったときに、このページを読み進めてください。</p>

			<h3>マップ製作の準備を整える</h3>
			<p>まず、マップを作るための方法を決めてください。<br />
				SakuraPVPでは、マップを製作するためのサーバーを提供していますが、サーバーダウンやラグなどが心配であればシングルで作ることもできますし、友達とサーバーを建てて製作をしても構いません。</p>
			<p>SakuraPVPで提供している、マップ製作用のサーバーを使う場合は <a id="toggleStep1">Step1: build.sakurapvp.net</a> をご覧ください。<br />
				また、他の方法で製作する場合はStep1のタブは読まずに飛ばしてください。</p>
			<h3>シングルで作る</h3>
			<p>にゅーん<p>
			<h3>サーバーを建てて作る</h3>
			<p>ごにょにょ</p>
		</div>
		<div class="tab-pane" id="server">
			<h4>新規マップの製作申請をする</h4>
			<p>まず、マップ製作用のフォーラムで、新たに製作するマップに関する情報をスタッフに申請してください。</p>
				<ul>
					<li>製作するマップの名前 (後から変更することもできます)</li>
					<li>別の既存のマップからコピーする場合、コピーするマップ名</li>
					<li>複数人で製作する場合、協力者のプレイヤー名</li>
				</ul>
			<p>これらを申請した後、スタッフが確認次第、申請したマップ名と同じ名前のワールドが製作用サーバーに生成されます。</p>

			<h4>実際にマップを作る</h4>
			<p>あなたのマップのためのワールドが作られたら、そのワールドへ移動してみましょう。<br />
				コマンド <code>/world tp (ワールド名)</code> を使って、ワールド移動ができます。</p>
			<p>スタッフが適切に権限の設定を完了させていれば、あなたは自分のワールドでブロックの設置や破壊、プラグインのコマンドが使えるようになっています。<br />
				もし、権限が無いと表示される場合はスタッフの設定不足によるものですので、ワールドの作成を担当したスタッフまでご連絡ください。</p>
			<p>既存のマップをコピーして新たなマップを製作する場合を除いて、作成されたワールドはブロックが一切生成されていない、何もない世界です。<br />
				(ただしチャンクファイルは生成されてしまうので、不必要に遠くまで移動しないでください。)</p>
			<p>おめでとうございます！これで新たなマップを作り始めるための準備が整いました。あなたの感性で、素敵なマップを作ってください！</p>

			<h5>製作のためのTips</h5>
			<p>まず、ブロックを設置したり、WorldEditなどで領域選択を容易にするために、足場となるブロックを出現させましょう。<br />
				ワールドの中心(座標XZ 0,0)付近で、 <code>//cyl 1 1</code> コマンドを入力してください。足場が作られます。</p>
			<p>あなたが製作しているマップが存在するワールド内では、サーバーにインストールされている地形編集のためのプラグイン(WorldEditやVoxelSniperなど)を自由に使うことができます。<br /></p>
				これらのプラグインの使い方については説明しませんので、それぞれのプラグインのページなどを読んでからご利用ください。</p>
			<ul>
				<li>WorldEdit</li>
				<ul>
					<li>Usage: <?=$this->Html->link('wiki.sk89q.com/wiki/WorldEdit', 'http://wiki.sk89q.com/wiki/WorldEdit#Usage');?></li>
					<li>CheatSeet (pdf): <?=$this->Html->link('cloud.github.com/.../worldedit_ref_rev6.pdf', 'http://cloud.github.com/downloads/sk89q/worldedit/worldedit_ref_rev6.pdf');?></li>
				</ul>
				<li>VoxelSniper</li>
				<ul>
					<li>Usage: <?=$this->Html->link('www.voxelwiki.com/minecraft/VoxelSniper', 'http://www.voxelwiki.com/minecraft/VoxelSniper#VoxelSniper_Commands');?></li>
				</ul>
				<li>そのほかの便利なリンク</li>
				<ul>
					<li>Minecraft ID List: <?=$this->Html->link('www.minecraftinfo.com/IDList.htm', 'http://www.minecraftinfo.com/IDList.htm');?></li>
				</ul>
			</ul>

			<h5>サーバーの仕様と注意事項</h5>
			<ul>
				<li>一定間隔でバックアップを行っていますが、基本的なログは取得していないため、荒らし行為があった場合などの巻き戻しはできません。<br />
					なお、マップ制作申請者と、その申請者が申告した協力者以外はそのワールドで地形編集などを行うことはできませんので、ご安心ください。</li>
				<li>このサーバーで入力したコマンドは、サーバー全体に表示されます。これは、強力なツールの悪用防止と、大規模な地形編集によるラグなどを予測できるようにするためです。</li>
				<li>権限設定によって、地形編集ツールなどは自分が製作を行っているマップ上でしか動作しないようになっていますが、これらのプラグインの仕様で、一部他人のマップが存在するワールドに影響のあるコマンドも存在します。
					もし見つけたとしても、それらのコマンドは絶対に試さないでください。意図的に実行した場合は接続禁止措置を行います。</li>
				<li>WorldEditを使用した一度のブロック変更可能数はデフォルトで50,000、上限は10,000です。</li>
				<li>WorldEditのスクリプト機能や、他の地形編集プラグインを使用したい場合は、スタッフまでご相談ください。</li>
			</ul>

			<h5>使うことができるコマンド</h5>
			<p>快適にマップ製作を行えるように、次のコマンドをサーバー内で使うことができます。<br />
				一部のコマンドは、ワールド保護のため、自分が製作しているマップのワールド内でのみ利用できます。<br />
				プラグイン・コマンドの使い方については、<?=$this->Html->link('検索', 'http://google.co.jp/', array('target' => '_blank'));?>するか、手探りで覚えてください。</p>
			<ul>
				<li>WorldEditで地形編集に関係のあるすべてのコマンド</li>
				<li>VoxelSniperで地形編集に関係のあるコマンド(LiteSniper権限)</li>
				<li>QuickSignプラグインのすべてのコマンド (看板の一括編集など)</li>
				<li>TimTheEnchanterプラグインのすべてのコマンド (特殊エンチャントアイテムなど)</li>
				<li>PistonJumpプラグインのすべてのコマンドと権限</li>
				<li>Essentialsプラグインの次のコマンド</li>
				<br />
				<ul class="span4">
					<li><b><u>Userグループから使用可能</u></b></li>
					<li>/sethome [home-name]</li>
					<li>/delhome [home-name]</li>
					<li>/home [player:][home-name]</li>
					<li>/tp [name]</li>
					<li>/tppos [x] [y] [z]</li>
					<li>/back</li>
					<li>/jump</li>					
					<li>/top</li>
					<li>/exp set [amount]</li>
					<li>/feed</li>
					<li>/heal</li>
					<li>/fly</li>
					<li>/speed</li>
					<li>/gamemode [survival|creative|adventure]</li>
					<li>/item [item|id] [amount [itemmeta]]</li>
					<li>/ci, /clearinventory</li>
					<li>/m [name] [message]</li>
					<li>/ping</li>
					<li>/getpos [name]</li>
					<br />
				</ul>				
				<ul class="span4">
					<li><b><u>Creatorグループから使用可能</u></b></li>
					<li>/weather</li>
					<li>/spawner [mob]</li>
					<li>/bigtree</li>
					<li>/break</li>					
					<li>/fireball</li>
					<li>/lightning</li>					
				</ul>
			</ul>
		</div>
		<div class="tab-pane" id="config">
			<p>内容がありません</p>
		</div>
		<div class="tab-pane" id="meow">
			<p>meow</p>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#tab-list a').click(function (e) {
		e.preventDefault();
		var step = $(this).attr('step');
		if (isset(step)){
			history.pushState(null, null, "<?=$this->Html->url(array('controller' => $this->params['controller'], 'action' => $this->action));?>/" + step);
		}
	});

	$("#toggleStep1").click(function () {
		$('#tab-list a:eq(1)').tab('show');
	});

	$(document).ready(function() {
		$('#tab-list a:eq(<?=h($tabId);?>)').tab('show');
	});
</script>