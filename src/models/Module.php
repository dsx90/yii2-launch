<?php
namespace dsx90\launcher\models;


use dsx90\launcher\behaviors\CacheFlush;
use dsx90\launcher\behaviors\SortableModel;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%tie_type}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $model
 * @property string $controller
 * @property string $form
 */
class Module extends ActiveRecord
{
    const STATUS_OFF= 0;
    const STATUS_ON = 1;

    const CACHE_KEY = 'launch_modules';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%module}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'model', 'controller'], 'required'],
            ['title', 'unique'],
            [['name', 'title', 'icon'], 'trim'],
            ['icon', 'string'],
            ['status', 'in', 'range' => [0,1]],
            [['title', 'model', 'controller', 'form'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'title'         => 'Title',
            'model'         => 'Model',
            'controller'    => 'Controller',
            'form'          => 'Form',
        ];
    }

    /**
     * Список шаблонов массивом
     * @return array
     */
    public static function getAll()
    {
        $type = [];
        $model = self::find()->orderBy(['title' => SORT_ASC])->all();
        if ($model) {
            foreach ($model as $m) {
                $type[$m->id] = $m->name;
            }
        }

        return $type;
    }
}
