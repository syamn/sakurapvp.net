<div class="jumbotron">
	<h1>メールアドレスの確認 <small> Verify email address</small></h1>
	<hr />
	<p>あなたのメールアドレスを変更するために、次の内容をご確認いただき、確認コードを入力して更新ボタンをクリックしてください。<br />
		なお、確認コードは発行後24時間で自動的に無効となりますので、変更を希望されない場合はボタンをクリックせず、無視してください。</p>
	<p>確認コードは、既に新しいメールアドレス宛に送信されています。もし届いていない場合は、迷惑メールとしてマークされていないかご確認ください。</p>
	<hr />
	<?=$this->Form->create(false, array('class' => 'form-horizontal', 'inputDefaults' => array(
			'label' => false, 'div' => false, 'error' => array('attributes' => array('wrap' => 'p', 'class' => 'text-error'))
		)));?>
		<div class="control-group">
			<label class="control-label" for="pname">プレイヤー名</label>
			<div class="controls"><?=$this->Form->input('player_name', array('value' => $name, 'class' => 'input-xlarge', 'id' => 'pname', 'disabled' => 'true'));?></div>
		</div>
		<div class="control-group">
			<label class="control-label" for="currentEmail">現在のメールアドレス</label>
			<div class="controls"><?=$this->Form->input('currentEmail', array('value' => $currentEmail, 'class' => 'input-xlarge', 'id' => 'currentEmail', 'disabled' => 'true'));?></div>
		</div>
		<div class="control-group">
			<label class="control-label" for="email">新しいメールアドレス</label>
			<div class="controls"><?=$this->Form->input('email', array('value' => $email, 'class' => 'input-xlarge', 'id' => 'email', 'disabled' => 'true'));?></div>
		</div>
		<div id="err_key" class="control-group">
			<label class="control-label" for="key">確認コード</label>
			<div class="controls">
				<?=$this->Form->input('key', array('placeholder' => 'Verify code...', 'class' => 'input-xlarge', 'id' => 'key', 'value' => $key));?>
				<span id="err_key_msg" class="help-block" style="display: none;">確認コードが入力されていません！</span>
			</div>
		</div>
		<div class="controls"><?=$this->Form->submit('メールアドレスを更新する', array('id' => 'submit', 'class' => 'btn btn-primary', 'onClick' => 'return validate();'));?></div>
	<?=$this->Form->end();?>
</div>
<script type="text/javascript" charset="utf-8">
	function validate(){
		var error = false;

		// pass trength
		var tmp = $("#key").val().length;
		if (isNaN(tmp) || tmp < 1){
			$("#err_key").addClass("error");
			$("#err_key_msg").show();
			error = true;
		}else{
			$("#err_key").removeClass("error");
			$("#err_key_msg").hide();
		}

		// Disable submit button for prevents double send
		if (!error){
			$("#submit").addClass('disabled');
		}
		return (!error);
	}
</script>