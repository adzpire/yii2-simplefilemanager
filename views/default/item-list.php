<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use mirage\basicfilemanager\components\FileHelper;
?>

<?php 
$session = Yii::$app->session;
$request = Yii::$app->request;
$ps = array_filter(explode('/', trim($session->get($this->context->module->id)['path'], '/')));

if($request->get('changeDir')){
	$directPath = '';
	//echo Html::a('Root', '#!', ['class' => 'direct-path', 'data-itempath'=>'/']);
	$this->params['breadcrumbs'] = [];
	foreach ($ps as $key => $p) {
		$directPath .= $p.'/';
		$this->params['breadcrumbs'][] = [
			'label'=>$p, 
			'url'=>rtrim($directPath, '/'),
			'class' => 'nav-dir',
			//'data-itempath'=>rtrim($directPath, '/')
		];
	}
	echo Breadcrumbs::widget([
		'homeLink' => [
			'label' => 'Root',
			'url' => '/',
			'class' => 'nav-dir',
			//'data-itempath'=>'/'
		],
		'links' => $this->params['breadcrumbs']
	]);
}else{
	echo Html::tag('h1', end($ps));
}

//print_r($session->get($this->context->module->id));
?>
	<div class="row">
		<div class="col-xs-8">
			<?php foreach ($files as $key => $file): ?>
			<?php 
			$file_url = FileHelper::path2url($file);
			if(empty(rtrim($session->get('path'), '/'))){
				$file_path = basename($file);
			}else{
				$file_path = rtrim($session->get('path'), '/').'/'.basename($file);
			}
			
			$bg = FileHelper::filePreview($file);
			if(is_dir($file)){
				$cssClass = 'folder';
			}else{
				$cssClass = 'file';
			}
			?>
			<a href="#!" class="item <?= $cssClass; ?>"data-toggle="tooltip" data-placement="bottom" title="<?= basename($file); ?>" data-basename="<?= basename($file); ?>">
				<div class="preview" style="background-image: url('<?= $bg; ?>');"></div>
				<div class="label"><?= basename($file); ?></div>
			</a>
			<?php endforeach; ?>
		</div>
		<div class="col-xs-4">
			<h3>Properties</h3>
			<div class="file-info"></div>
		</div>
	</div>
</div>
