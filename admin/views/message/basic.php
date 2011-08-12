<?php foreach($messages as $message): ?>
<?php if($message->text): ?>
<div class="<?php echo $message->type; ?> msg">
    <ul>
        <?php if(is_array($message->text)): ?>
        <?php foreach($message->text as $msg): ?>
            <li><?php echo $msg; ?></li>
        <?php endforeach; ?>
        <?php else: ?>
            <li><?php echo $message->text; ?></li>
        <?php endif; ?>
    </ul>
</div>
<?php endif; ?>
<?php endforeach; ?>