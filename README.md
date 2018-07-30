Yii2 Launch
=====================
Yii2 Launch

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist dsx90/yii2-launch "*"
```

or add

```
"dsx90/yii2-launch": "*"
```

to the require section of your `composer.json` file.


Локальное подключение:
Добавить в composer.json
```
{
    "type": "path",
    "url": "../dsx90/yii2-launch"
}
```

Подключение/обновление зависимостей в основном проекте
Выполнить в консоли основного проекта.
```
composer require dsx90/yii2-launch:dev-master --prefer-source
```

Далее нудно выполнить миграцию
```
yii migrate --migrationPath=@dsx90/launcher/migrations --interactive=0
```