<?php foreach ($comments as $comment): ?>
    <li id="comment_<?php echo $comment['commentid']; ?>">
        <div class="name"><?php echo $comment['username']; ?></div>
        <div class="date"><?php echo standard_date('DATE_RFC822', strtotime($comment['date'])); ?></div>
        <div class="text"><?php echo highlight_phrase($comment['description'], "[removed]", '<span class="removed-text">', '</span>'); ?></div>
    </li>
<?php endforeach; ?>