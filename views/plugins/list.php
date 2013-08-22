<? if(count($msg) > 0): ?>
<div class="row">
	<div class="col-md-6 col-md-push-3">
		<div class="alert alert-dismissable <?=$msg['class'];?>">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong><?=$msg['title'];?>:</strong> <?=$msg['content'];?>
		</div>
	</div>
</div>
<? endif; ?>
<div class="row">
	<div class="col-md-8 col-md-push-2">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Plugins</h3>
			</div>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<td>Name</td>
						<td>Author</td>
						<td></td>
						<td>Installed</td>
						<td>Active</td>
					</tr>
				</thead>
				<tbody>
					<? foreach($list as $plugin):?>
						<tr>
							<td><?=$plugin['info']['name'];?></td>
							<td><a href="<?=$plugin['info']['author_URL'];?>" target="_blank"><?=$plugin['info']['author'];?></a></td>
							<td><a href="#" class="tt" data-toggle="tooltip" title="<?=$plugin['info']['description'];?>"><i class="icon-info"></i></a></td>
							<td class="text-center">
								<? if($plugin['installed'] == true): ?>
									<i class="icon-thumbs-up"> </i>
								<? else: ?>
									<a href="<?=Route::url('plugins.install', array('plugin' => $plugin['name']));?>"><i class="icon-thumbs-down"></i></a>
								<? endif; ?>
							</td>
							<td class="text-center">
								<? if($plugin['active'] == true): ?>
								<a href="<?=Route::url('plugins.deactivate', array('plugin' => $plugin['name']));?>"><i class="icon-ok"> </i></a>
								<? elseif($plugin['active'] == false && $plugin['installed'] == true): ?>
									<a href="<?=Route::url('plugins.activate', array('plugin' => $plugin['name']));?>"><i class="icon-remove"></i></a>
								<? else: ?>
									<i class="icon-remove"></i>
								<? endif; ?>
							</td>
						</tr>
					<? endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>