<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/css/nomalize.css" rel="stylesheet">
<title>管理画面：編集</title>
</head>
<body>
    <?= $this->Form->create('edit', ['enctype' => 'multipart/form-data', 'type' => 'file', 'url' => ['action' => 'updateStaff']]); ?>
    <div id="editArea">
        <div>
            <img src=/<?= $staffData->img_path ?> width='180px' height='240px'>
        </div>
        <div>
            <?= $this->Form->file('UploadData') ?>
            <p>社員番号:<?= $staffData->id ?></p>
            <?= $this->Form->input("name",["type"=>"text", "label"=>"社員名：", "default" => $staffData->name]); ?>
        </div>
        <?=$this->Form->create('',['url'=>['action'=>'regist']]) ?>
        <?=$this->Form->button('登録', array('class'=>'btn btn-danger center-block')) ?>
    </div>
    <?=$this->Form->end() ?>
</body>
</html>