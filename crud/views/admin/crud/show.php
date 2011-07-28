<h1><?php $_name; ?> №<?php echo $item->id?></h1>
<table class="table-zebra">
    <tbody>
    <?php foreach($item->as_array() as $key=>$value): ?>
        <tr>
            <th>
                <?php echo Arr::get($item->labels(), $key, $key); ?>
        	</th>
            <td>
				<?php echo $value; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>