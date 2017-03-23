<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "audit_typetbl".
 *
 * @property string $id
 * @property string $audit_type
 */
class AuditTypetbl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'audit_typetbl';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['audit_type'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'audit_type' => 'Audit Type',
        ];
    }
}
