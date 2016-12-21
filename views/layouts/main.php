<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\ArrayHelper;
use mirage\basicfilemanager\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style type="text/css">
        .basicfilemanager .item{
            width:100px;
            height: 100px;
            padding: 5px;
            margin: 5px;
            float: left;
            position: relative;
        }
        .basicfilemanager .item:hover{
            background-color: #e5f3ff;
        }
        .basicfilemanager .item .preview{
            width: 100%;
            height: 80%;
            background-repeat: no-repeat;
            background-size: contain;
            background-position: 50% 50%;
        }
        .basicfilemanager .item .label{
            color:#333;
            width: 100%;
            display: block;
            overflow:hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
        table.table-nowrap td, table.table-nowrap th {
            max-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        body.loading .basicfilemanager .waiting{
            display: flex;
        }
        body .basicfilemanager .waiting{
            position: fixed;
            display: none;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            z-index: 1000;
            background-color: rgba(0,0,0,0.5);
        }

        .uploadfiles{
            display: none !important;
        }
        #gallery .thumbnail{
            width:150px;
            height: 150px;
            float:left;
            margin:2px;
        }
        #gallery .thumbnail img{
            width:150px;
            height: 150px;
        }
        .glyphicon-refresh-animate {
            -animation: spin .7s infinite linear;
            -webkit-animation: spin2 .7s infinite linear;
        }

        @-webkit-keyframes spin2 {
            from { -webkit-transform: rotate(0deg);}
            to { -webkit-transform: rotate(360deg);}
        }

        @keyframes spin {
            from { transform: scale(1) rotate(0deg);}
            to { transform: scale(1) rotate(360deg);}
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">

    <div class="container-fluid">
    <?php
    /*foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        //echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
        echo \yii\bootstrap\Alert::widget([
            'body'=>ArrayHelper::getValue(Yii::$app->session->getFlash($key), 'body'),
            'options'=>ArrayHelper::getValue(Yii::$app->session->getFlash($key), 'options'),
        ]);
    }*/
    ?>
    <?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
        <?= \yii\bootstrap\Alert::widget([
            'body'=>ArrayHelper::getValue($message, 'body'),
            'options'=>ArrayHelper::getValue($message, 'options'),
        ])?>
    <?php endforeach; ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<?php
$this->registerJs("
$('[data-toggle=\"tooltip\"]').tooltip();
");
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
