<?php
namespace dsx90\launcher\assets;

use yii\web\AssetBundle;

/**
 * Подключение CSS и JS для компонента JSTree
 * Class TreeAsset
 * @package lowbase\document
 */
class AssetsBundle extends AssetBundle
{
    public $sourcePath = '@vendor/dsx90/yii2-launch/assets';

    public $css = [
        'css/style.css',
    ];

    public $js = [
        'js/translate.js',
    ];

    public $depends = [

    ];
}