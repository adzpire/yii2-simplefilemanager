<?php

namespace mirage\basicfilemanager\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\InputWidget;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Json;


class BfmTextInput extends InputWidget
{
    const RETURN_TYPE_URL = 'url';

    const RETURN_TYPE_ABSOLUTE = 'absolute';

    const RETURN_TYPE_HEHIND = 'behind';

    const RETURN_TYPE_BASENAME = 'basename';

    public $options = ['class' => 'form-control'];

    public $modalOptions = [];

    public $browserUrl = '/basicfilemanager';

    public $fieldID = null;

    public $returnType = null;

    public $resize = false;

    public $header = 'File browser';

    public $toggleButton = [
        'label' => 'Browse',
    ];

    private $src = 'data:image/png;base64,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';

    protected $widget = [];

    public $uploadDir = '';

    public $uploadURL = '';

    public function init()
    {
        parent::init();

        $this->widget = [
            'id' => Html::getInputId($this->model, $this->attribute),
            'name' => Html::getInputName($this->model, $this->attribute)
        ];

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        if (!isset($this->options['subDir'])) {
            $this->options['subDir'] = null;
        }

        //adzpire edit
        if (!isset($this->options['selectFileOnly'])) {
            $this->options['selectFileOnly'] = false;
        }
        //adzpire edit
        if (!isset($this->options['hidePreview'])) {
            $this->options['hidePreview'] = false;
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

        if (!isset($this->options['resizeOptions'])) {
            $this->options['resizeOptions'] = null;
        }
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->fieldID = $this->widget['id'];
        $modal = $this->genModal();

        $this->options = array_merge(['class' => 'form-control'], $this->options);

        if ($this->hasModel()) {
            $attribute = $this->attribute;
            if(!empty($this->model->$attribute)){
                $this->src = $this->model->$attribute;
            }
        }
        //adzpire edit
        echo ($this->options['hidePreview']) ? false : Html::tag('div', Html::img($this->src, ['id'=>$this->widget['id'].'-preview', 'class'=>'img-responsive']), ['class'=>'well well-sm text-center', 'style'=>'margin:5px;']);
        echo '<div class="input-group">';
        if ($this->hasModel()) {
            echo Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textInput($this->name, $this->value, $this->options);
        }
        echo '<span class="input-group-btn"><button type="button" class="btn btn-default btn-flat" id="'.$this->widget['id'].'-btn-clear"><i class="glyphicon glyphicon-remove"></i></button></span>';
        echo '<span class="input-group-btn"><button type="button" class="btn btn-default btn-flat" data-toggle="modal" data-target="#'.$modal->id.'"><i class="glyphicon glyphicon-folder-open"></i></button></span>';
        echo '</div>';

        $this->registerJs();
    }

    private function genModal()
    {
        $view = $this->getView();
        $forceOptions = [
            /*'toggleButton' => [
                'label' => '<i class="glyphicon glyphicon-folder-open"></i>',
                'class' => 'btn btn-default btn-flat',
            ],*/
            'header' => 'Browse file',
            'size'=>Modal::SIZE_LARGE
        ];
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
            'selectFileOnly' => $this->options['selectFileOnly'],
            'changeDir' => $this->options['changeDir'],
            'upload' => $this->options['upload'],
            'resizeOptions' => $this->options['resizeOptions'],
        ]);
        echo '<iframe src="'.$url.'" frameborder="0" style="height:80vh;width:100%"></iframe>';
        Modal::end();

        $view->registerJs(
/*$('#$modal->id').on('hidden.bs.modal', function () {
    alert('$modal->id');
})*/
"
function ".$modal->id."_after_selected_function(){
    $('#$this->fieldID').trigger('change');
}
", $view::POS_HEAD);

        return (object)[
            'id' => $modal->id,
        ];


        /*$modal = Modal::begin([
            'header' => '<h2>Hello world</h2>',
            'toggleButton' => ['label' => 'click me'],
        ]);
        echo 'sdfskdkfjskld';
        Modal::end();*/
    }

    protected function registerJs()
    {
        $view = $this->getView();
        $widgetId = $this->widget['id'];
        $btnBrowse = $this->widget['id'].'-btn-browse';
        $btnClear = $this->widget['id'].'-btn-clear';
        $js = [];
        $view->registerJs(implode("\n", $js), $view::POS_HEAD);

        $jsBtn = [];
        $jsBtn[] = <<<JSBTN

$('#$widgetId').on("propertychange change keyup paste input", function(){
    $('#$widgetId-preview').addClass('img-responsive');
    if($(this).val() == ''){
        $('#$widgetId-preview').attr('src', '$this->src');
    }else{
        $('#$widgetId-preview').attr('src', $(this).val());
    }
});

$("#$btnClear").click(function(e){
    e.preventDefault()
    $("#$widgetId").val('');
    $('#$widgetId').change();
});
JSBTN;
        $view->registerJs(implode("\n", $jsBtn));
    }
}
