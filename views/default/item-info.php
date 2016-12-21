
<div class="action">
	<input type="hidden" value="<?= $itemInfo['filepath']; ?>" class="txt-dirname"></input>
	<input type="hidden" value="<?= $itemInfo['basename']; ?>" class="txt-basename"></input>
	<?php if(!empty(Yii::$app->request->get('fieldID'))){ ?>
	<!--<button class="btn btn-primary select-item"><i class="
glyphicon glyphicon-ok"></i> Select</button> -->
	<?php } ?>

	<?php /*if(Yii::$app->request->get('selectFileOnly')) {
		echo '1';
	}else{
		echo '2';
	}*/ ?>
	<div class="btn-group btn-group-justified" role="group" aria-label="Actions">
		<a href="#!" class="btn btn-primary select-item">
			<i class="glyphicon glyphicon-ok"></i> Select
		</a>
		<?php if(!Yii::$app->request->get('selectFileOnly')) { ?>
		<a href="#!" class="btn btn-info rename-item">
			<i class="glyphicon glyphicon-pencil"></i> Rename
		</a>
		<a href="#!" class="btn btn-danger delete-file">
			<i class="glyphicon glyphicon-trash"></i> Delete
		</a>
		<?php } ?>
	</div>
</div>
<table  class="table table-striped table-bordered detail-view table-nowrap">
	<tr>
		<th width="100">basename</th>
		<td><?= $itemInfo['basename']; ?></td>
	</tr>
	<tr>
		<th>url</th>
		<td><a href="<?= $itemInfo['fileurl']; ?>" target="_blank"><?= $itemInfo['fileurl']; ?></a></td>
	</tr>
	<tr>
		<th>size</th>
		<td><?= $itemInfo['filesize']; ?></td>
	</tr>
	<tr>
		<th>accessed</th>
		<td><?= Yii::$app->formatter->asDateTime($itemInfo['fileatime']); ?></td>
	</tr>
	<tr>
		<th>modified</th>
		<td><?= Yii::$app->formatter->asDateTime($itemInfo['filemtime']); ?></td>
	</tr>
	<tr>
		<th>changed</th>
		<td><?= Yii::$app->formatter->asDateTime($itemInfo['filectime']); ?></td>
	</tr>
</table>
