<?php
	$headLink = array(
			array('name' =>'Home', 'link' => $this->Html->link('Home', array('controller' => 'home', 'action' => 'index'))),
			array('name' =>'Forums', 'link' => '<a>Forums</a>'),
			array('name' =>'Rankings', 'link' => '<a>Rankings</a>'),
			array('name' =>'Maps', 'link' => '<a>Maps</a>'),
			array('name' =>'Revisions', 'link' => $this->Html->link('Revisions', array('controller' => 'revisions', 'action' => 'index'))),
		);
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $title_for_layout . ' - SakuraPVP'; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php
		// Meta
		echo $this->fetch('meta');
		echo $this->Html->meta('icon'); // favicon

		// CSS
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('sakurapvp');
		echo $this->fetch('css');
		echo $this->Html->css('bootstrap-responsive.min'); // Need to load after the original css

		// Script
		echo $this->Html->script('jquery/jquery-1.9.1.min');
		echo $this->Html->script('bootstrap.min');
		echo $this->Html->script('custom.js');
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
								<?php
									foreach ($headLink as $data){
										if ($data['name'] === $this->name){
											echo '<li class="active">'.$data['link'].'</li>';
										}else{
											echo '<li>'.$data['link'].'</li>';
										}
									}
								?>
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