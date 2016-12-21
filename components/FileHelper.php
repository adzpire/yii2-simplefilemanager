<?php
namespace mirage\basicfilemanager\components;

use Yii;
use yii\web\UploadedFile;

use mirage\basicfilemanager\models\FileModel;
/**
* 
*/
class FileHelper extends \yii\helpers\FileHelper
{
	const FILE_TYPE_DIR = 'dir';
	const FILE_TYPE_FILE = 'file';
	const FILE_TYPE_IMAGE = 'image';

	public static $bfmAsset = null;


	public static function path2url($fullName='')
	{
		$model = new FileModel();
		$basePath = realpath($model->routes->basePath);
		$fullName = realpath($fullName);
		$r = $model->routes->baseUrl;

		$r = str_replace($basePath, '', $fullName);
		$fileUrl = str_replace('\\', '/', $r);

		return $fileUrl;
	}

	public static function isImage($file)
	{
		$result = false;
		if(!is_dir($file) && getimagesize($file)){
			$result = true;
		}

		return $result;
	}

	public static function getFileType($file)
	{
		if(is_dir($file)){
			return self::FILE_TYPE_DIR;
		}else{
			if (@getimagesize($file)) {
				return self::FILE_TYPE_IMAGE;
			}else{
				return self::FILE_TYPE_FILE;
			}
		}
	}

	public static function filePreview($file)
	{
		self::registerAsset();
		$ft = self::getFileType($file);
		if($ft === 'dir'){
			return self::$bfmAsset->baseUrl.'/images/extensions/folder.png';
		}elseif($ft === 'image'){
			return self::path2url($file);
		}else{
			$extension = pathinfo($file, PATHINFO_EXTENSION);
			$extionStore = self::extensionStore();
			if(array_key_exists($extension, $extionStore)){
				return self::$bfmAsset->baseUrl.'/images/extensions/'.$extionStore[$extension];
			}else{
				return self::$bfmAsset->baseUrl.'/images/extensions/unknow.jpg';
			}
		}
	}






	protected static function extensionStore()
	{
		self::registerAsset();
		$files=self::findFiles(self::$bfmAsset->basePath.'/images/extensions',['recursive'=>false, 'only'=>['*.jpg']]);
		$extensions = [];
		foreach ($files as $key => $file) {
			$pi = pathinfo($file);
			$extensions[$pi['filename']] = $pi['basename'];
		}
		
		return $extensions;
	}

	private static function registerAsset()
	{
		if(self::$bfmAsset === null){
			$view = Yii::$app->controller->getView();
			self::$bfmAsset = \mirage\basicfilemanager\assets\BfmAsset::register($view);
		}
	}
}