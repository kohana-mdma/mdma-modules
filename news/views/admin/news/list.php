<h1>Новости</h1>
<form>
    <table id="table1" class="gtable sortable">
        <thead>
            <tr>
                <th>Заголовок</th>
				<th>Дата создания</th>
				<th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item->title ?></td>
				<td><?= $item->created_on ?></td>
				<td>
                    <?php echo HTML::anchor('admin/news/edit/'.$item->id,'<img src="/images/icons/edit.png" alt="Редактировать" />', array('title'=>'Редактировать')); ?>
                    <?php echo HTML::anchor('admin/news/delete/'.$item->id, '<img src="/images/icons/cross.png" alt="Удалить" />', array('title'=>'Удалить')); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</form>
<div><?php echo HTML::anchor('admin/news/add/', 'Добавить новость', array('class'=>'icon-plus')); ?></div>