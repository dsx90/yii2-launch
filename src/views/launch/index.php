<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dsx90\launcher\models\Launch;
/* @var $this yii\web\View */
/* @var $searchModel common\search\LaunchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Launches');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="launch-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Launch'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-condensed table-hover table-striped table-gv'
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'parent_id',
            [
                'attribute' => 'parent_id',
                'value' => 'parent.title'
            ],
            [
                'attribute' => 'title',
                'format' => 'html',
                'value' => function ($model) {
                    return  '<h5><b>'.$model->title.'</b></h5>'.
                            '<h5>'.$model->longtitle.'</h5>'.
                            '<h6>'.$model->description.'</h6>';
                },
            ],
            //'longtitle',
            //'description',
            // 'keywords',
            // 'menutitle',
            // 'slug',
            [
                'attribute' => 'author_id',
                'format' => 'html',
                'value' => function ($model) {
                    return  '<p><b>Создал:&nbsp;</b>'.$model->author->username.'</p>
                             <p><b>Опуб-л:&nbsp;</b>'.$model->author->username.'</p>';
                },
                'options' => ['width' => '200']
            ],
            [
                'attribute' => 'created_at',
                'format' => 'html',
                'value' => function ($model) {
                    return  '<p>'.Yii::$app->formatter->asDate($model->created_at).'</p>
                             <p>'.Yii::$app->formatter->asDate($model->updated_at).'</p>';
                },
                'options' => ['width' => '200']
            ],
            [
                'attribute' => 'module_id',
                'format' => 'html',
                'value' => 'module.title',
                'options' => ['width' => '200']
            ],
            [
                'attribute' => 'like',
                'format' => 'html',
                'value' => 'likes.ip',
                'options' => ['width' => '200']
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'options' => ['style' => 'width: 65px'],
                'value' => function ($model) {
                    return $model->status ? '<span class="glyphicon glyphicon-ok text-success"></span>' : '<span class="glyphicon glyphicon-remove text-danger"></span>';
                },
                'filter' => [
                    Launch::STATUS_DRAFT => Yii::t('backend', 'Not active'),
                    Launch::STATUS_ACTIVE => Yii::t('backend', 'Active'),
                ],
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
