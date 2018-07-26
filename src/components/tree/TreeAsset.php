<?php
/**
 * @package   yii2-resources
 * @author    Yuri Shekhovtsov <shekhovtsovy@yandex.ru>
 * @copyright Copyright &copy; Yuri Shekhovtsov, lowbase.ru, 2015 - 2016
 * @version   1.0.0
 */

namespace dsx90\launcher\components\tree;

use yii\web\AssetBundle;

/**
 * Подключение CSS и JS для компонента JSTree
 * Class TreeAsset
 * @package lowbase\document
 */
class TreeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/dsx90/yii2-launch/src/components/tree/assets';

    public $css = [
        'css/themes/default/style.css',
        'css/themes/default/theme.css'
    ];

    public $js = [
        'js/jstree.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
