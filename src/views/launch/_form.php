<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\bootstrap\ButtonDropdown;
use kartik\widgets\Select2;
use yii\widgets\Pjax;
use trntv\yii\datetime\DateTimeWidget;
use dsx90\launcher\models\Template;
use dsx90\launcher\models\Module;

/* @var $this yii\web\View */
/* @var $model common\models\Launch */
/* @var $composit \backend\controllers\LaunchController : update  */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="launch-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-12">
            <?//php $this->beginBlock('control-panel') ?>
                <div class="control-panel pull-right">
                    <?= $form->field($model, 'status')->widget(\dosamigos\switchinput\SwitchBox::className(),[
                        'clientOptions' => [
                            'size' => 'normal',
                            'onColor' => 'success',
                            'offColor' => 'danger',
                        ],
                        'inlineLabel' => false
                    ])->label(false);?>
                    <?= Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> '.Yii::t('backend', 'Save'), ['class' => 'btn btn-primary']) ?>
                    <?php if (!$model->isNewRecord) {
                        echo Html::a('<i class="glyphicon glyphicon-eye-open"></i> '.Yii::t('backend', 'View'), ['view', 'id' => $model->id], [
                                'class' => 'btn btn-success',
                            ])." ";
                        echo Html::a('<i class="glyphicon glyphicon-trash"></i> '.Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('backend', 'Are you sure you want to delete the resources?'),
                                    'method' => 'post',
                                ],
                            ])." ";
                        echo Html::a('<i class="glyphicon glyphicon-level-up"></i> '.Yii::t('backend', 'Create child'), ['create', 'parent_id' => $model->parent_id], [
                                'class' => 'btn btn-default',
                            ])." ";
                    }
                    ?>
                    <?= Html::a('<i class="fa fa-chevron-left"></i>',['update', 'id' => $model->getPrev()], ['class' => 'btn btn-default',]) ?>
                    <?= Html::a('<i class="fa fa-arrow-up"></i>',['index'], ['class' => 'btn btn-default',]) ?>
                    <?= Html::a('<i class="fa fa-chevron-right"></i>',['update', 'id' => $model->getNext()], ['class' => 'btn btn-default',]) ?>
                </div>
            <?//php $this->endBlock() ?>

        </div>
        <div id="launch-row">
            <div id="launch-left" class="col-md-9">
                <div class="border-field">

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true])/*->hint('Длинна пароля не меньше 70 символов.')*/ ?>

                    <?= $form->field($model, 'longtitle')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>

                    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

                </div>
                <div class="adver-view">
                    <i>Видимость в поисковиках</i>
                    <h2><span id="title"><?= $model->title ? $model->title : 'Титул' ?></span>: <span id="longtitle"><?= $model->longtitle ?: 'Краткое описание' ?></span></h2>
                    <h4><?= env('FRONTEND_URL')?>›<span id="url"><?= $model->slug ?: 'url ссылка' ?></span></h4>
                    <h5><span id="description"><?= $model->description ?: 'Обьявление' ?></span></h5>
                </div>
            </div>
            <div id="launch-right" class="col-md-3">
                <?= $form->field($model, 'parent_id')->dropDownList(\dsx90\launcher\models\Launch::getAll(),
                    ['prompt' => 'Нет']) ?>

                <?= $form->field($model, 'menutitle', [
                    'addon' => [
                        'append' => [
                            'content'=> Html::a(Yii::t('backend', 'Repeat the name'), '#', [
                                'class' =>['btn btn-default repeat-name']]),
                            'asButton'=>true,
                        ],
                        'groupOptions' => [
                            'id' => 'title-btn'
                        ]
                    ]
                ]); ?>

                <?= $form->field($model, 'slug', [
                    'addon' => [
                        'append' => [
                            'content' => ButtonDropdown::widget([
                                'label' => Yii::t('backend', 'From'),
                                'dropdown' => [
                                    'items' => [
                                        ['label' => Yii::t('backend', 'From the title'), 'url' => '#', 'options' => ['class'=>'translate-name']],
                                        ['label' => Yii::t('backend', 'From the menutitle'), 'url' => '#', 'options' => ['class'=>'translate-title']],
                                    ],
                                ],
                                'options' => ['class'=>'btn-default']
                            ]),
                            'asButton' => true
                        ],
                        'groupOptions' => [
                            'id' => 'alias-btn'
                        ]
                    ]
                ]); ?>

                <?= $form->field($model, 'author_id')->textInput() ?>

                <?//= $form->field($model, 'published_at')->widget(DateTimeWidget::className(), ['phpDatetimeFormat' => 'dd.MM.yyyy, HH:mm:ss']) ?>

                <?= $form->field($model, 'module_id')->dropDownList(Module::getAll(),
                    ['prompt' => 'Без типа:']) ?>

                <?= $form->field($model, 'template_id')->dropDownList(Template::getAll(),
                    ['prompt' => 'Пустой шаблон:']) ?>
            </div>
        </div>
    </div>

    <?php Pjax::begin([
        'linkSelector' => false,
        'formSelector' => false,
        'id' => 'module'
    ]) ?>

    <div id="fields" class="forms">
        <?if ($model->module) {
            echo $this->renderAjax($model->module->form, [
                'form' => $form,
                'model' => $model->models ?: (new $model->module->model)
            ]);
        }?>
    </div>

    <?php Pjax::end() ?>
    <?php ActiveForm::end()?>


</div>

<?php
$id = $model->id;
$launch_id = ($model->isNewRecord) ? 0 : $model->id;
$this->registerJs(<<<JS
    $('.repeat-name').click(function(){
        var text = $('#launch-title').val();
        $('#launch-menutitle').val(text);
    });
    $('.translate-name').click(function(){
        var text = $('#launch-title').val().toLowerCase();
        result = translit(text);
    $('#launch-slug').val(result);
    });
    $('.translate-title').click(function(){
        var text = $('#launch-menutitle').val().toLowerCase();
        result = translit(text);
    $('#launch-slug').val(result);
    });
    $('#launch-module_id').on('change', function(){
    $.pjax.reload('#module', {
        'url': window.location.href.replace(/&module=[0-9]+/g, '') + '&module=' + $(this).val(),
        'replace': false
    })
    });
JS
);
?>
