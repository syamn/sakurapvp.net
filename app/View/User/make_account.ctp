<div class="jumbotron">
	<h1>アカウント登録 <small> New Account</h1>
	<hr />
	<br />
	<form class="form-horizontal" method="post" action="<?=$this->Html->url();?>?key=<?=$key;?>">
		<div class="control-group">
			<label class="control-label" for="inputName">ユーザー名</label>
			<div class="controls"><input id="inputName" name="pass2" class="input-xlarge" type="text" value="<?=$name;?>" disabled /></div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputMail">メールアドレス</label>
			<div class="controls"><input id="inputMail" class="input-xlarge" type="text" value="<?=$mail;?>" disabled /></div>
		</div>
		<div id="err_pass1" class="control-group">
			<label class="control-label" for="inputPass">パスワード</label>
			<div class="controls"><input id="inputPass" name="pass1" class="input-xlarge" type="password" placeholder="Password.." />
			<span class="help-block">6文字以上、100文字以内で設定してください</span></div>
		</div>
		<div id="err_pass2" class="control-group">
			<label class="control-label" for="inputPass2">パスワード(確認)</label>
			<div class="controls"><input id="inputPass2" name="pass2" class="input-xlarge" type="password" placeholder="Confirm password.." />
			<span id="err_pass2_msg" class="help-block" style="display: none;">確認のパスワードが一致していません！</span></div>
		</div>
		<div id="err_agreed" class="control-group">
			<div class="controls">
				<label class="checkbox"><input id="agreed" type="checkbox">利用規約に同意します</label>
				<span id="err_agreed_msg" class="help-block" style="display: none;">続行するには、利用規約に同意していただく必要があります</span>
			</div>
		</div>
		<input type="hidden" name="name" value="<?=$name;?>" />
		<input type="hidden" name="regkey" value="<?=$key;?>" />
		<div class="controls"><button id="submit" type="submit" class="btn btn-large btn-primary" onClick="return validate();">アカウント登録</button></div>
	</form>
</div>
<script type="text/javascript" charset="utf-8">
	function validate(){
		var error = false;

		// pass trength
		var tmp = $("#inputPass").val().length;
		if (isNaN(tmp) || tmp < 6 || tmp > 100){
			$("#err_pass1").addClass("error");
			error = true;
		}else{
			$("#err_pass1").removeClass("error");
		}

		// compare
		var tmp = $("#inputPass2").val().length;
		var tmp2 = ($("#inputPass").val() !== $("#inputPass2").val());
		if (isNaN(tmp) || tmp < 6 || tmp > 100 || tmp2){
			$("#err_pass2").addClass("error");
			if (tmp2){
				$("#err_pass2_msg").show();
			}
			error = true;
		}else{
			$("#err_pass2").removeClass("error");
		}
		if (!tmp2){
			$("#err_pass2_msg").hide();
		}

		// checkbox
		if (!$("#agreed").attr('checked')){
			$("#err_agreed").addClass("error");
			$('#err_agreed_msg').show();
			error = true;
		}else{
			$("#err_agreed").removeClass("error");
			$("#err_agreed_msg").hide();
		}

		return (!error);
	}
</script>