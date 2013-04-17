<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $title_for_layout; ?></title>
	<?php
		echo $this->Html->meta('icon'); // favicon
		// このへんで共通のcss/js読み込む
		// CSS
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('bootstrap-responsive.min');
		echo $this->Html->css('sakurapvp');
		// Script
		echo $this->Html->script('jquery/jquery-1.8.3.min');
		echo $this->Html->script('bootstrap.min');
		echo $this->Html->script('custom.js');

		// この下3つは CP1.x のscripts_for_layoutと同じ ヘルパー使ってるビューで勝手に入れられるらしい
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div class="container">
		<div class="header">
			<!-- Header Start -->
			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">
						<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
						</button>
						<a class="brand" href="/">SakuraPVP</a>
						<div class="nav-collapse collapse">
							<ul class="nav">
								<li class="active"><a href="/">About</a></li>
								<li><a href="#forum">Forum</a></li>
								<li><a href="#stats">Stats</a></li>
								<li><a href="#stats">Map</a></li>
								<li><a href="/revisions">Revisions</a></li>
							</ul>
							<?php
								echo $this->element(($loggedIn) ? 'userNav' : 'guestNav');
							?>
						</div> <!-- /.nav-collapse -->
					</div> <!-- /navbar container -->
				</div> <!-- /navbar-inner -->
			</div><!-- /navbar -->			
		</div><!-- /header -->
		<div class="content">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div><!-- /content -->
		<hr />
		<div class="footer">
			<p>&copy; SakuraPvP 2013</p>
		</div><!-- /footer -->
	</div><!-- container -->
</body>
</html>