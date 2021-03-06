<?php

namespace dsx90\launcher\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * Просмотры статьи
 *
 * @property integer $id
 * @property string $created_at
 * @property integer $launch_id
 * @property string $ip
 * @property string $user_agent
 * @property integer $user_id
 *
 * @property LbLaunchnt $launch
 */
class Visit extends \yii\db\ActiveRecord
{
    public $count;  // Количество просмотров

    /**
     * Автозаполнение даты просмотра
     * документа
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
            ]];
    }

    /**
     * Наименование таблицы
     * @return string
     */
    public static function tableName()
    {
        return 'visit';
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            [['launch_id', 'ip'], 'required'],    // Обязательные поля для заполнения
            [['launch_id', 'user_id', 'count', 'created_at'], 'integer'],   // Целочисленные значения
            [['user_agent'], 'string'], // Текстовое значение
            [['ip'], 'string', 'max' => 20],    // Строка (максимум 20 символов)
            [['launch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Launch::className(), 'targetAttribute' => ['launch_id' => 'id']],
        ];
    }

    /**
     * Наименование полей аттрибутов
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('backend', 'ID'),
            'created_at'    => Yii::t('backend', 'Создано'),
            'launch_id'     => Yii::t('backend', 'Документ'),
            'ip'            => Yii::t('backend', 'IP'),
            'user_agent'    => Yii::t('backend', 'Данные браузера'),
            'user_id'       => Yii::t('backend', 'Прользователь'),
        ];
    }

    /**
     * Документ
     * @return \yii\db\ActiveQuery
     */
    public function getLaunch()
    {
        return $this->hasOne(Launch::className(), ['id' => 'launch_id']);
    }

    /**
     * Фиксирум просмотр документа
     * Не более 1 раза с одного IP в день
     * @param $launch_id - ID документа
     * @return bool
     */
    public static function check($launch_id)
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        // Проверяем наличие просмотров за сегодня с этого IP
        $model = Visit::find()->where('launch_id=:launch_id && ip=:ip && created_at>=:created_at', [
            ':launch_id' => $launch_id,
            ':ip' => $ip,
            ':created_at' => date('Y-m-d'). ' 00:00:00',
        ])->count();
        // Сохраняем запись
        if (!$model) {
            $visit = new Visit();
            $visit->launch_id = $launch_id;
            $visit->ip = $ip;
            $visit->user_id = (Yii::$app->user->isGuest) ? null : Yii::$app->user->id;
            $visit->user_agent = $_SERVER['HTTP_USER_AGENT'];
            $visit->save();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Получить просмотры документа/ов
     * при shedule = flase - общее количество за все время
     * при shedule = true - количество просмотров, сгруппированные по дням
     *
     * @param null $launch_ids - ID документа (-ов)
     * @param bool $shedule - включить расписание просмотров?
     * @return array|\yii\db\ActiveRecord[] - возвращает только дату, id документа, кол-во просмотров
     */
    public static function getAll($launch_ids = null, $shedule = false)
    {
        $table = self::tableName();
        $group_by = ($shedule) ? 'DATE(created_at)' : 'launch_id';
        if ($launch_ids) {
            $ids = (is_array($launch_ids)) ? implode(',', $launch_ids) : $launch_ids;
            $sql = 'SELECT date(created_at) as created_at , launch_id, count(launch_id) as count FROM ' . $table . ' where launch_id IN ('.$ids.') GROUP BY ' . $group_by;
        } else {
            $sql = 'SELECT date(created_at) as created_at , launch_id, count(launch_id) as count FROM ' . $table . ' GROUP BY ' . $group_by;
        }
        $model = Visit::findBySql($sql)->all();

        return $model;
    }
}
