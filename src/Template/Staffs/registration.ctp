<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/nomalize.css" rel="stylesheet">
<title>管理画面：新規登録</title>
</head>
<body>
<div>
    <h1>社員新規登録</h1>
    <p>社員情報を入力してください</p>
	<div>
		<div>
			<script>
		    function submitChkadd() {
		        /* 確認ダイアログ表示 */
		        var flag = confirm ( "登録してもよろしいですか？\n\n登録したくない場合は[キャンセル]ボタンを押して下さい");
		        /* send_flg が TRUEなら送信、FALSEなら送信しない */
		        return flag;
		    }
			</script>
	 		<p>※社員番号は自動で登録されます。</p>
	 		<?= $this->Form->create('registration', ['enctype'=>'multipart/form-data', 'type'=>'file', 'url'=>['action' => 'addStaff']]); ?>
			<?= $this->Form->file('UploadData') ?>
			<?= $this->Form->input('name',array('id'=>'name_initial','type'=>'text','label'=>'社員名：', 'required'=>'required')) ?>
			<?= $this->Form->button('登録') ?>
			<?= $this->Form->end() ?>
			</form>
		</div>
	</div>
</div>
</body>
</html>