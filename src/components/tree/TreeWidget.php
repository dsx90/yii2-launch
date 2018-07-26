<?php
namespace dsx90\launcher\components\tree;

use yii\base\Widget;

/**
 * Отображение документов в виде дерева
 * Class TreeWidget
 * @package lowbase\document\components
 */
class TreeWidget extends Widget
{
    public $data = []; // маассив документов

    public function run()
    {
        $data = [];
        if ($this->data) {
            foreach ($this->data as $resource) {
                $data[] = [
                    'id' => $resource->id,
                    'text' => $resource->title . ' <span class="hint">(' . $resource->id . ')</span>',
                    'parent' => ($resource->parent_id) ? $resource->parent_id : '#',
                    'icon' => ($resource->is_folder) ? 'glyphicon glyphicon-folder-open' : 'glyphicon glyphicon-file'
                ];
            }
        }
        // Преобразуем в JSON
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $this->render('treeWidget', ['data' => $data]);
    }
}
