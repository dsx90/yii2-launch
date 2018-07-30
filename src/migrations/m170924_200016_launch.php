<?php

use yii\db\Migration;

class m170924_200016_launch extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%launch}}',[
            'id'            => $this->primaryKey(),
            'title'         => $this->string(70),
            'longtitle'     => $this->string(70),
            'description'   => $this->string(150),
            'keywords'      => $this->string(255),
            'menutitle'     => $this->string(20),
            'slug'          => $this->string(80)->unique(),
            'status'        => $this->smallInteger()->notNull(),
            'is_folder'     => $this->smallInteger(),
            'position'      => $this->integer(11),
            'module_id'      => $this->integer(11),
            'parent_id'     => $this->integer(11),
            'template_id'   => $this->integer(11),
            'author_id'     => $this->integer(11),
            'updater_id'    => $this->integer(11),
            'published_at'  => $this->integer(),
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer(),
        ], $tableOptions);

        //Таблица связывающих
        $this->createTable('{{%module}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'name' => $this->string(),
            'icon' => $this->string(20),
            'status' => $this->boolean(),
            'model' => $this->string(),
            'controller' => $this->string(),
            'form' => $this->string(),
        ], $tableOptions);

        //Таблица шаблонов template
        $this->createTable('{{%template}}', [
            'id'            => $this->primaryKey(),
            'title'         => $this->string()->notNull(),
            'description'   => $this->text(),
            'path'          => $this->string(),
        ] , $tableOptions);

        //Таблица просмотров документов visit
        $this->createTable('{{%visit}}', [
            'id'            => $this->primaryKey(),
            'created_at'    => $this->integer()->notNull(),
            'launch_id'     => $this->integer()->notNull(),
            'ip'            => $this->string(20)->notNull(),
            'user_agent'    => $this->text(),
            'user_id'       => $this->integer(),
        ], $tableOptions);

        //Таблица просмотров документов visit
        $this->createTable('{{%like}}', [
            'id'            => $this->primaryKey(),
            'created_at'    => $this->integer()->notNull(),
            'launch_id'     => $this->integer()->notNull(),
            'ip'            => $this->string(20)->notNull(),
            'user_agent'    => $this->text(),
            'user_id'       => $this->integer(),
        ], $tableOptions);

        //Индексы и ключи таблицы шаблонов template
        $this->createIndex(
            'idx-template-title',
            '{{%template}}',
            'title'
        );

        $this->createIndex(
            'idx-launch-parent_id',
            '{{%launch}}',
            'parent_id'
        );

        $this->createIndex(
            'idx-launch-status',
            '{{%launch}}',
            'status'
        );

        $this->createIndex(
            'idx-launch-slug',
            '{{%launch}}',
            'slug'
        );

        //Индексы и ключи таблицы таблицы просмотров документов visit
        $this->addForeignKey(
            'fk_launch_visit',
            '{{%visit}}',
            'launch_id',
            '{{%launch}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        //Индексы и ключи таблицы таблицы просмотров документов like
        $this->addForeignKey(
            'fk_launch_like',
            '{{%like}}',
            'launch_id',
            '{{%launch}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_launch_author',
            '{{%launch}}',
            'author_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_launch_updater',
            '{{%launch}}',
            'updater_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_launch_parent',
            '{{%launch}}',
            'parent_id',
            '{{%launch}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_module_launch',
            '{{%launch}}',
            'module_id',
            '{{%module}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
        $this->addForeignKey(
            'fk_template_launch',
            '{{%launch}}',
            'template_id',
            '{{%template}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_launch_author', '{{%launch}}');
        $this->dropForeignKey('fk_launch_updater', '{{%launch}}');
        $this->dropForeignKey('fk_launch_category', '{{%launch}}');
        $this->dropForeignKey('fk_template_launch', '{{%launch}}');
        $this->dropForeignKey('fk_module_launch', '{{%launch}}');
        $this->dropForeignKey('fk_launch_parent', '{{%launch}}');
        $this->dropForeignKey('fk_launch_like', '{{%launch}}');
        $this->dropForeignKey('fk_launch_visit', '{{%launch}}');

        $this->dropTable('{{%launch}}');
        $this->dropTable('{{%like}}');
        $this->dropTable('{{%visit}}');
        $this->dropTable('{{%template}}');
        $this->dropTable('{{%module}}');
    }

}
