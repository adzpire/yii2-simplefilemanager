<?php
namespace mirage\basicfilemanager\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

class ModalBrowser extends \yii\base\Widget
{
    const RETURN_TYPE_URL = 'url';

    const RETURN_TYPE_ABSOLUTE = 'absolute';

    const RETURN_TYPE_HEHIND = 'behind';

    const RETURN_TYPE_BASENAME = 'basename';

    public $options = [];

    public $modalOptions = [];

    public $browserUrl = '/basicfilemanager';

    public $fieldID = null;

    public $returnType = null;

    public $header = 'File browser';

    public $toggleButton = [
        'label' => 'Browse',
    ];
    
    public function init()
    {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        if (!isset($this->options['subDir'])) {
            $this->options['subDir'] = null;
        }

        if (!isset($this->options['createDir'])) {
            $this->options['createDir'] = true;
        }

        if (!isset($this->options['changeDir'])) {
            $this->options['changeDir'] = true;
        }

        if (!isset($this->options['upload'])) {
            $this->options['upload'] = true;
        }

        $this->registerJs();

        $this->getView()->registerCss("
.modal-body {
    min-height: 80vh;
    padding: 0;
}
        ");
    }

    public function run()
    {
        return $this->genModal();
    }

    private function genModal()
    {
        $forceOptions = ['size'=>Modal::SIZE_LARGE];
        $modal = Modal::begin(array_merge($this->modalOptions, $forceOptions));
        if($this->fieldID !== null && $this->returnType === null){
            $this->returnType = self::RETURN_TYPE_URL;
        }
        $url = Url::to([
            $this->browserUrl, 
            'fieldID'=>$this->fieldID, 
            'returnType'=>$this->returnType, 
            'modalID'=>$modal->id,
            'subDir' => $this->options['subDir'],
            'createDir' => $this->options['createDir'],
            'changeDir' => $this->options['changeDir'],
            'upload' => $this->options['upload'],
        ]);
        echo '<iframe src="'.$url.'" frameborder="0" style="height:80vh;width:100%"></iframe>';
        Modal::end();
    }

    private function registerJs()
    {
        $this->getView()->registerJs("
$('.modal').on('shown.bs.modal', function () {
    var iframe = $(this).find('iframe');
    iframe.attr('src', iframe.attr('src'));
});
        ");
    }
}
