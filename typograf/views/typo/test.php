<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo Kohana::$charset ?>">
        <title>Типограф - Тесты</title>
    </head>
    <body>
        <h1>Типограф - <?php echo $function;?></h1>

		<?php foreach($test as $data): ?>
			<h2>Файл: <?php echo $data['file'];?></h2><br>
			<?php foreach($data['message'] as $message): ?>
				<br><br><span style="color: #600; font-weight: bold">Ошибка в строке: <?php echo $message['line'];?></span>
	            IN:  <?php echo $message['in'];?><br>
				OUT: <?php echo $message['out'];?><br>
				CFG: <?php echo $message['cfg'];?><br>
			<?php endforeach; ?>
			<br><br><b>Ошибок / Тестов:</b> <?php echo $data['errors'];?> / <?php echo $data['tests'];?>
		<?php endforeach; ?>

		<br><hr><h2>Итого:</h2><b>Ошибок / Тестов:</b> <?php echo $all_errors; ?> / <?php echo $all_tests; ?>  </body>
    </body>
</html>