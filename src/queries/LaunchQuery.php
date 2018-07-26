<?php

namespace dsx90\launcher\queries;


/**
 * This is the ActiveQuery class for [[dsx90\launcher\models\Launch]].
 *
 * @see dsx90\launcher\models\Launch
 */
class LaunchQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere('[[status]]=1');
    }

    /**
     * @inheritdoc
     * @return dsx90\launcher\models\Launch[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return dsx90\launcher\models\Launch|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
