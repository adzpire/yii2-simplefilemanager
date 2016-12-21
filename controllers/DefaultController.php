<?php

namespace mirage\basicfilemanager\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\helpers\Json;

use mirage\basicfilemanager\models\FileModel;

/**
 * Default controller for the `yiipress` module
 */
class DefaultController extends Controller
{
	public function behaviors()
	{
		return [
			[
				'class' => \yii\filters\ContentNegotiator::className(),
				'only' => [
					'upload',
					'item-rename',
					'item-delete',
					'change-directory',
					'nav-directory',
					'create-directory',
				],
				'formats' => [
					'application/json' => \yii\web\Response::FORMAT_JSON,
				],
			],
		];
	}

	public function actionError(){
		$this->layout = 'main-error';
		$exception = Yii::$app->errorHandler->exception;

		if ($exception !== null) {
			$statusCode = $exception->statusCode;
			$name = $exception->getName();
			$message = $exception->getMessage();
			
			
			return $this->render('error', [
				'exception' => $exception,
				'statusCode' => $statusCode,
				'name' => $name,
				'message' => $message
			]);
		}
	}

	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionIndex()
	{
		$model = new FileModel();
		$request = Yii::$app->request;
		/*$files=\yii\helpers\FileHelper::findFiles($model->routes->uploadDir,['recursive'=>false]);
		$files_r = [];
		foreach ($files as $value) {
			$files_r[] = str_replace('\\', '/', $value);
		}*/
		//Yii::$app->session->remove('path');
		return $this->render('index', [
			'model' => $model, 
			'files'=>$model->itemList([
				'show-directory' => $request->get('changeDir'),
				'sub-directory' => $request->get('subDir'),
			]),
		]);
	}

	public function actionItemList($subDir=null)
	{
		$model = new FileModel();
		$request = Yii::$app->request;
		//print_r($model->fileList($subDir));
		//print_r($subDir);
		return $this->renderAjax('item-list', [
			'model' => $model, 
			'files'=>$model->itemList([
				'show-directory' => $request->get('changeDir'),
				'sub-directory' => $request->get('subDir'),
			]),
		]);
	}

	public function actionChangeDirectory($name=null)
	{
		$session = Yii::$app->session;
		$model = new FileModel();
		$moduleId = $this->module->id;
		$currentPath = rtrim($session->get($moduleId)['path'], '/');
		$destPath = $currentPath.'/'.$name;
		$goDir = $model->routes->uploadDir.$destPath;
		$result = [
			'process' => false,
			'message' => 'error directory '.$goDir,
		];
		if(is_dir($goDir)){
			$session->set($moduleId, ['path'=>$destPath]);
			$result['process'] = true;
			$result['message'] = 'Change directory to '.$destPath.' successfully';
		}else{
			$session->remove($moduleId);
		}
		return $result;
	}

	public function actionNavDirectory($path)
	{
		$session = Yii::$app->session;
		$model = new FileModel();
		$moduleId = $this->module->id;
		if($path === '/'){
			$path = '';
		}
		$goDir = $model->routes->uploadDir.'/'.$path;
		$result = [
			'process' => false,
			'message' => 'error directory '.$goDir,
		];

		$session->remove($moduleId);
		if(is_dir($goDir)){
			$session->set($moduleId, ['path'=>'/'.$path]);
			$result = [
				'process' => true,
				'message' => 'Change diroctory '.$path,
			];
		}

		return $result;
	}

	public function actionItemInfo($name)
	{
		$session = Yii::$app->session;
		$model = new FileModel();
		$moduleId = $this->module->id;
		//$fileFullPath = $model->routes->basePath;
		$fileFullPath = $model->routes->uploadDir;
		$filePath = rtrim($session->get($moduleId)['path'], '/').'/'.$name;
		$fileFullPath .= $filePath;
		
		$dirname = dirname($filePath);
		if($dirname === DIRECTORY_SEPARATOR){
			$dirname = '';
		}
		//print_r($session->get($moduleId)['path']);

		$itemInfo = [
			'basename' => basename($name),
			'filepath' => trim($dirname, '/'),
			'fileurl' => Yii::$app->homeUrl.$model->routes->uploadPath.$filePath,
			//'filesize' => 'N/A',
			'fileatime' => @fileatime($fileFullPath), //accessed 
			'filemtime' => @filemtime($fileFullPath), //modified 
			'filectime' => @filectime($fileFullPath), //changed 
		];
		if(is_dir($fileFullPath)){
			$itemInfo['filesize'] = 'N/A';
		}elseif(is_file($fileFullPath)){
			$itemInfo['filesize'] = $model->filesizeText($fileFullPath);
		}else{
			throw new \yii\web\HttpException(500, 'File '.$fileFullPath.' not found');
		}
		//print_r($itemInfo);
		return $this->renderAjax('item-info', ['itemInfo'=>$itemInfo]);
	}

	public function actionUpload()
	{
		$model = new FileModel();
		$result = [
			'process' => false,
			'message' => 'error upload files',
		];

		if (Yii::$app->request->isPost) {
			$session = Yii::$app->session;
			$model->files = UploadedFile::getInstances($model, 'files');
			$resizeOptions = Yii::$app->request->get('resizeOptions');
			//if ($model->upload($session->get($this->module->id)['path'])) {
			if ($model->upload($session->get($this->module->id)['path'], $resizeOptions)) {
				// file is uploaded successfully
				$result['process'] = true;
				$result['message'] = 'Your files has been uploaded';
				$result['var'] = $resizeOptions;
			}
		}

		return $result;
	}


	public function actionItemRename($oldname, $name)
	{
		$session = Yii::$app->session;
		$model = new FileModel();

		$currentPath = rtrim($model->routes->uploadDir.'/'.$session->get($this->module->id)['path'], '/');
		$fileOldFullName = $currentPath.'/'.$oldname;
		$fileFullName = $currentPath.'/'.$name;
		$result = [
			'process' => false,
			'message' => 'Rename '.$fileFullName.' error !!',
		];

		if(@rename($fileOldFullName, $fileFullName)){
			$result['process'] = true;
			$result['message'] = 'File "'.$name.'" has been renamed';
		}
		
		//print_r($result);
		return $result;
	}

	public function actionItemDelete($name)
	{
		$session = Yii::$app->session;
		$model = new FileModel();

		$fileFullPath = rtrim($model->routes->uploadDir.'/'.$session->get($this->module->id)['path'], '/');
		$fileFullPath .= '/'.$name;
		$result = [
			'process' => false,
			'message' => 'Delete '.$fileFullPath.' error !!',
		];
		if(is_dir($fileFullPath)){
			FileHelper::removeDirectory($fileFullPath);
			$result['process'] = true;
			$result['message'] = 'Directory "'.$name.'" has been deleted';
		}else if(is_file($fileFullPath)){
			if(@unlink($fileFullPath)){
				$result['process'] = true;
				$result['message'] = 'File "'.$name.'" has been deleted';
			}
		}
		
		return $result;
	}

	public function actionCreateDirectory($name){
		$session = Yii::$app->session;
		$model = new FileModel();

		$currentPath = rtrim($model->routes->uploadDir.'/'.$session->get($this->module->id)['path'], '/');
		$createPath = $currentPath.'/'.$name;
		$result = [
			'process' => false,
			'message' => 'Create direct "'.$name.'" error !!',
		];

		if(FileHelper::createDirectory($createPath)){
			$result['process'] = true;
			$result['message'] = 'Directory "'.$name.'" has been created';
		}

		return $result;
	}
}
