            <? if($history->as_array()): ?>
			<div class="box info">
                <h2>Версии</h2>
                <section class="block-version">
                    <ul>
						<? foreach($history->as_array() as $row): ?>
                        <li <?= ($row['version']==$version)?'class="current" ':'';?>/><?= HTML::anchor('admin/'.$_name.'/edit/'.$item->id.($item->version!=$row['version']?'/version/'.$row['version']:''),date('Y-m-d H:i', strtotime($row['validation_time_modified'])).($item->version==$row['version']?' <span>(текущая версия)</span>':'')); ?></li>
						<? endforeach; ?>
                    </ul>
                    <a href="#" class="block-version-more" id="block-1">Ранние версии</a>
                </section>
            </div>
			<? endif; ?>