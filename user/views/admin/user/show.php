<?php foreach($user->as_array() as $key=>$value): ?>
	<?php if(in_array($key, array('password')))	continue; ?>
		<?php echo Arr::get($user->labels(), $key, $key); ?>:
	<?php if(in_array($key, array('last_login'))): ?>
		<?php echo date(Date::$timestamp_format, $value); ?>
	<?php else: ?>
		<?php echo $value; ?>
	<?php endif; ?>
	 <br />
<?php endforeach; ?>