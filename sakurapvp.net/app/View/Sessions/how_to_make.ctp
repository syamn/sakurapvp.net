<div class="jumbotron">
	<h1>新規アカウントの取得 <small> Create a new account</small></h1>
	<hr />
	<div class="row">
		<div class="span4">
			<h3>Step 1 <small>メールアドレスの入力</small></h3>
			<p>いずれかの <a>ゲームサーバ</a> にログインし、次のコマンドを入力してください。</p>
			<center><p class="text-center"><code>/register &lt;あなたのメールアドレス&gt;</code></p></center>
			<p>もし、メールアドレスを間違えて入力した場合は、3時間お待ちいただき、正しいメールアドレスを再度入力してください。</p>
			<p>しばらく待ってもメールが届かない場合は、迷惑メールフォルダに届いていないかご確認ください。</p>
		</div>
		<div class="span4">
			<h3>Step 2 <small>必須事項の入力</small></h3>
			<p>入力したメールアドレスに届いているメールをご確認いただき、記載されているURLにアクセスして、登録を続行してください。</p>
			<p>なお、パスワードは強固な暗号化技術を用いて暗号化を行い、適切なアクセス制御を行っているデータベース上に保存されます。ですが、一般的に、他のウェブサイトやサービスなどで利用されているものと同一のパスワードを使用することは、セキュリティ上推奨されません。</p>
		</div>
		<div class="span4">
			<h3>Step 3 <small>登録完了</small></h3>
			<p>おめでとうございます！これであなたのアカウントが作成されました！</p>
			<p><?=$this->Html->link('ログインページ', array('controller' => 'sessions', 'action' => 'login'));?>から、あなたのアカウントにログインできるかご確認ください。</p>
			<p>あなたのプロフィールなどの設定は、ログイン後のページより可能です。</p>
			<p>また、登録完了のメールが送信されますので、こちらもご覧ください。</p>
		</div>
		<hr />
		<div class="span12">
			<center>
				<br />
				<h4>登録コード入力</h4>
				<p>メールに記載されているURLをクリックできない場合は、次のフォームから登録をお試しください。<br />
				プレイヤー名と、メールに記載されている登録コードを入力して、Enterキーを押下してください。</p>
				<form class="form-inline">
					<input id="name" type="text" class="input-medium submit " placeholder="プレイヤー名">
					<input id="code" type="text" class="input-small submit" placeholder="登録コード">
				</form>
				<div class="alert alert-error" id="err" style="display: none;"><strong id="err_msg"></strong></div>
			</center>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.submit').keydown(function(e) {
		$("#err_pass2").addClass("error");
		if (e.which && e.which != 13){
			return;
		}

		var name = $('#name').val();
		var code = $('#code').val();
		var error = false;

		// Validate username
		if (isValidName(name) === 1){
			if(name.length <= 0){
				$('#err_msg').html('ユーザー名が入力されていません！');
			}else{
				$('#err_msg').html('不正なユーザー名です！');
			}
			$('#name').focus();
			error = true;
		}

		// Validate key
		if(code.length < 4 || code.length > 16){
			if (code.length <= 0){
				$('#err_msg').html('登録コードが入力されていません！');
			}else{
				$('#err_msg').html('不正な登録コードです！');
			}
			$('#code').focus();
			error = true;
		}

		if (error){
			$('#err').show();
		}else{
			$('#hide').hide();
			window.location = "/sessions/make_account/" + name + "?key=" + code;
		}
	});
</script>