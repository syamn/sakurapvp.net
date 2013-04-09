<div class="jumbotron">
	<h1>リビジョン <small> <?=$repoName;?></small></h1>
	<br />
	<ul class="nav nav-tabs">
		<?php
		foreach ($repoList as $urlName => $data){
			$link = $this->Html->link($data[1], array('controller' => 'revisions', 'action' => $urlName));
			if ($urlName == $repo){
				echo '<li class="active">'.$link.'</li>';
			}else{
				echo '<li>'.$link.'</li>';
			}
		}
		?>
	</ul>
	<table class="table table-bordered">
		<thread>
			<tr>
				<th class="span1">#</th>
				<th class="span1">SHA</th>
				<th class="span2">日時</th>
				<th class="span2">更新者</th>
				<th>コミットメッセージ</th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($rows)): ?>
				<?php foreach($rows as $row): ?>
					<tr>
						<td><?=$firstRowNo;?></td>
						<td><?=h($row['CommitLog']['hash']);?></td>
						<td><?=date("y/m/d H:i:s", $row['CommitLog']['date']);?></td>
						<td><?=h($row['CommitLog']['author']);?></td>
						<td><?=h($row['CommitLog']['msg']);?></td>
					</tr>
				<?php $firstRowNo++; endforeach; ?>
			<?php else: ?>
				<tr><td colspan="5"><center><?=$this->Html->link('表示するデータがありません', array('controller' => 'revisions', 'action' => $repo));?></center></td></tr>
			<?php endif; ?>
		</tbody>
	</table>
	<div class="pagination pagination-centered">
		<ul>
			<?php
			for($i = $page - 5, $max = $page + 5; $i <= $max; $i++){
				if ($i <= 0) continue;
				if ($i > $lastPage) continue;

				if ($i == $page){
					echo '<li class="active"><a>'.$i.'</a></li>';
				}else{
					$link = $this->Html->link($i, array('controller' => 'revisions', 'action' => $repo, $i));
					echo '<li>'.$link.'</li>';
				}
			}
			?>
		</ul>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		<?php
			if (!(isset($username) && isLoggedIn($username))){
				print "$('.online').attr('disabled', true);\n";
			}
		?>
		$('.disable').attr('disabled', true);
	});
</script>