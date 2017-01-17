<main class="container">

	<ul class="comment-list">
		<?php $this->load->view('templates/comment_template'); ?>
	</ul>

	<div class="typing-users">
		<img src="<?php echo base_url('assets/img/ellipsis.gif'); ?>">
		<span class="user"></span>
	</div>

	<?php
		echo form_open('comment/create', array('name' => 'comment-form', 'id' => 'comment-form'));
		echo form_textarea(array(
			'name' => 'comment-description',
			'id'   => 'comment-description'));
	    echo form_hidden('item-id', $itemId);
		echo form_submit(array(
			'name'  => 'Comment',
			'value' => 'Comment',
			'class' => 'btn btn-comment pull-right'
		)); ?>
		<div class="error comment-error"></div>
		<?php echo form_close(); ?>

</main>