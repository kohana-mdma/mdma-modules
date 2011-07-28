<h1><?= $_name;?></h1>
<form>
    <table id="table1" class="gtable sortable">
        <thead>
            <tr>
                <th>Название</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>

            <tr>
				<td><?= $item->title ?></td>
                <td>
                    <?php echo HTML::anchor('admin/'.$_name.'/edit/'.$item->id,'<img src="/images/icons/edit.png" alt="Редактировать" />', array('title'=>'Редактировать')); ?>
                    <?php echo HTML::anchor('admin/'.$_name.'/delete/'.$item->id, '<img src="/images/icons/cross.png" alt="Удалить" />', array('title'=>'Удалить')); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="tablefooter clearfix">
        <!--  <div class="actions">
            <select>
                <option>Действие:</option>
                <option value="edit">Редактировать</option>
                <option value="delete">Удалить</option>
            </select>
            <button class="button small">Применить</button>
        </div>-->
    </div>
</form>
<div><?php echo HTML::anchor('admin/'.$_name.'/add/', 'Добавить новый элемент', array('class'=>'icon-plus')); ?></div>