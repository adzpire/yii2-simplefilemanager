<?php

namespace mirage\basicfilemanager;

use Yii;
/**
 * yiipress module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'mirage\basicfilemanager\controllers';
    
    public $routes = [];
    
    public $thumbs = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->layoutPath = '@mirage/basicfilemanager/views/layouts';
        $this->layout = 'main';

        Yii::$app->errorHandler->errorAction = '/'.$this->id.'/default/error';
    }
}
