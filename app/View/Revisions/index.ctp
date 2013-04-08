<div class="jumbotron">
	<h1>リビジョン</h1>
	<hr />

	<table class="table table-bordered">
		<thread>
			<tr>
				<th class="span1">#</th>
				<th class="span2">SHA</th>
				<th class="span2">日時</th>
				<th class="span2">更新者</th>
				<th>コミットメッセージ</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($rows as $row): ?>
				<tr>
					<td><?=$firstRowNo;?></td>
					<td><?=h($row['CommitLog']['hash']);?></td>
					<td><?=date("y/m/d H:i:s", $row['CommitLog']['date']);?></td>
					<td><?=h($row['CommitLog']['author']);?></td>
					<td><?=h($row['CommitLog']['msg']);?></td>
				</tr>
			<?php $firstRowNo++; endforeach; ?>
		</tbody>
	</table>
</div>
