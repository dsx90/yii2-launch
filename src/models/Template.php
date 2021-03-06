<?php
namespace dsx90\launcher\models;

use Yii;

/**
 * Шаблоны документов
 * Используются для применения макетов
 * отображения данных, а также закрепления
 * за документом дополнительных полей
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $path
 *
 * @property Document[] $lbDocuments
 * @property Field[] $lbFields
 */
class Template extends \yii\db\ActiveRecord
{
    public $code;

    /**
     * Наименование таблицы
     * @return string
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules()
    {
        return [
            [['title'], 'required'], // Обязательно для заполнения
            [['title'], 'unique'],   // Уникальное значение
            [['description'], 'string'],    // Текстовое поле
            [['path'], 'pathValidate', 'skipOnEmpty' => false], // Проверка на существование файла шаблона
            [['title', 'path'], 'string', 'max' => 255], // Строковое значение (максимум 255 символов)
            [['title', 'description', 'path'], 'filter', 'filter' => 'trim'],    // Обрезаем строки по краям
            [['path', 'description'], 'default', 'value' => null],  // По умолчанию = null
            ['code', 'safe']
        ];
    }

    /**
     * Наименование полей аттрибутов
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('document', 'ID'),
            'title'          => Yii::t('document', 'Наименование'),
            'description'   => Yii::t('document', 'Описание'),
            'path'          => Yii::t('document', 'Путь к файлу'),
            'code' => $this->title.'.php',
        ];
    }

    /**
     * Документы с текущим шаблоном
     * @return \yii\db\ActiveQuery
     */
    public function getLaunch()
    {
        return $this->hasMany(Launch::className(), ['template_id' => 'id']);
    }

    /**
     * Поля шаблона
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Field::className(), ['template_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->openFile();
            return true;
        }
        return false;
    }

    public function openFile(){
        $file = fopen(Yii::getAlias('@template').'/'.$this->title.'.php', "w+");
        fwrite($file, $this->code);
        fclose($file);
    }

    public function afterFind()
    {
        parent::afterFind(); // TODO: Change the autogenerated stub
        $this->loadFile();
    }

    public function loadFile(){
        $this->code = file_get_contents(Yii::getAlias('@template').'/'.$this->title.'.php');
    }

    /**
     * Проверка на существование файла
     */
    public function pathValidate()
    {
        // Определяем расширение файла
        $ext = substr($this->path, -4);
        $file = ($ext === '.php') ? $this->path : $this->path.'.php';
        if ($this->path && !file_exists(Yii::getAlias($file))) {
            // Выводим ошибку если файл не найден
            $this->addError('path', Yii::t('document', 'Файл шаблона не найден.'));
        }
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
                $type[$m->id] = $m->title;
            }
        }

        return $type;
        //$list = self::find()->select(['title', 'id'])->orderBy(['title' => SORT_ASC])->asArray()->indexBy('id')->all();
        //return $list;
    }
}
