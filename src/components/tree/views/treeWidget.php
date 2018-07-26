<?php

//use dsx90\launcher\components\tree;
use yii\helpers\Url;

\dsx90\launcher\components\tree\TreeAsset::register($this);
?>
<div class="sidebar-form">
    <div class="input-group">
        <input id="jstree_search_input" type="text" class="form-control" placeholder="Поиск по названию или ID">
        <span class="input-group-btn">
            <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                <i class="fa fa-search"></i>
            </button>
        </span>
    </div>
</div>
<div id="jstree_div">
</div>

<?=$this->registerJs("
$('#jstree_div').jstree({
    'core' : {
        'check_callback' : function(o, n, p, i, m) {
            return true;
        },
        'data' : ".$data."
    },
    'plugins' : ['contextmenu', 'search', 'dnd', 'types', 'changed']
}).bind('move_node.jstree', function(e, data){
        var new_inst = data.new_instance;
        var next = new_inst.get_next_dom(data.node, true);
        if (next){
            next = next.context.id;
        }
        var prev = new_inst.get_prev_dom(data.node, true);
        if (prev){
            prev = prev.context.id;
        }
        new_inst.set_icon(data.parent, 'glyphicon glyphicon-folder-open');
        if (!new_inst.is_parent(data.old_parent)){
            new_inst.set_icon(data.old_parent,'glyphicon glyphicon-file');
        }
        $.ajax({
            url: '".Url::to(['/launcher/launch/move'])."',
            type: 'POST',
            data: {
                'id' : data.node.id,
                'old_parent_id' : data.old_parent,
                'new_parent_id' : data.parent,
                'new_prev_id' : prev,
                'new_next_id' : next
            },
            success: function(data){
            }
        });
    });

var to = false;
$('#jstree_search_input').keyup(function () {
    if(to) { clearTimeout(to); }
    to = setTimeout(function () {
        var v = $('#jstree_search_input').val();
        $('#jstree_div').jstree(true).search(v);
    }, 250);
});
");
?>
