<?php

namespace dsx90\launcher;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface{
    //Метод, который вызывается автоматически при каждом запросе
    public function bootstrap($app)
    {
        //Правила маршрутизации
        $app->getUrlManager()->addRules([
            'launch'        => 'launcher/launch/index',
            'template'      => 'launcher/template/index',
            'module'        => 'launcher/module/index',

        ], false);
        /*
         * Регистрация модуля в приложении
         * (вместо указания в файле frontend/config/main.php
         */
        $app->setModule('launcher', 'dsx90\launcher\Module');
    }
}