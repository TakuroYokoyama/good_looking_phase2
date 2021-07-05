<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/nomalize.css" rel="stylesheet">
<title>管理画面：社員詳細</title>
</head>
<body>
	<div id="detail_view">
		<div>
			<img src=/<?= $staffData->img_path ?> width='180px' height='240px'>
		</div>
		<div>
			<p>社員番号:<?= $staffData->id ?></p>
			<p>社員名:<?= $staffData->name ?></p>
			<p>得票数:<?= $staffData->vote ?></p>
		</div>
	</div>
	<div>
		<?=$this->Form->create('',['url'=>['action'=>'regist']]) ?>
		<?=$this->Form->button('編集', array('class'=>'btn btn-danger center-block')) ?>
		<?=$this->Form->end() ?>
		<?=$this->Form->create('',['url'=>['action'=>'deleteStaff']]) ?>
		<?=$this->Form->button('削除',array('class'=>'btn btn-info center-block')) ?>
		<?=$this->Form->end() ?>
	</div>
</body>
</html>