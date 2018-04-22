<div class="complete" align="center">
	<p class="thanks">投票を受け付けました。<br>ご協力ありがとうございました！</p>
	<?=$this->Form->create('',['url'=>['action'=>'index']]) ?>
	<?=$this->Form->button('TOPへ',array('class'=>'btn btn-primary')) ?>
	<?=$this->Form->end() ?>	
</div>