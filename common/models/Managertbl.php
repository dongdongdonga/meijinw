<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "managertbl".
 *
 * @property integer $id
 * @property string $name
 * @property integer $department
 * @property string $position
 * @property string $mobile
 * @property string $QQ
 * @property string $email
 * @property string $emergency_contact_no
 * @property string $emergency_contact
 * @property string $id_no
 * @property string $native_place
 * @property integer $education
 * @property string $addr
 * @property string $Ukey_info
 * @property string $login_pwd
 * @property integer $gmt_create
 * @property integer $gmt_modified
 * @property string $bank
 * @property string $acct_id
 * @property string $hiredate
 * @property string $expiration
 * @property string $medical_examination
 * @property string $other
 * @property string $photo
 * @property string $pic_path
 * @property integer $state
 * @property integer $examination
 * @property integer $is_del
 */
class Managertbl extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'managertbl';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'department', 'position', 'mobile', 'QQ', 'email', 'emergency_contact_no', 'emergency_contact', 'id_no', 'native_place', 'education', 'addr', 'Ukey_info', 'login_pwd', 'bank', 'acct_id', 'hiredate', 'expiration', 'state', 'examination', 'adopt'], 'required'],
            [['department', 'education', 'gmt_create', 'gmt_modified', 'state', 'examination', 'adopt', 'is_del'], 'integer'],
            [['hiredate', 'expiration'], 'safe'],
            [['name'], 'string', 'max' => 30],
            [['position', 'QQ', 'emergency_contact', 'Ukey_info', 'login_pwd'], 'string', 'max' => 32],
            [['mobile', 'emergency_contact_no'], 'string', 'max' => 11],
            [['email', 'native_place', 'addr', 'bank', 'medical_examination', 'other', 'photo', 'pic_path'], 'string', 'max' => 128],
            [['id_no', 'acct_id'], 'string', 'max' => 18],
            //新增验证规则
            ////手机号,紧急联系人电话,银行卡号
            [['mobile', 'emergency_contact_no', 'acct_id'], 'integer'],
            /////确定位数
            [['mobile', 'emergency_contact_no'], 'string', 'max' => 11, 'min' => 11],
            ////QQ
            [['QQ'], 'integer', 'min' => 18],
            ////邮箱
            [['email'], 'email'],
            ////身份证号
            [['id_no', 'acct_id'], 'string', 'max' => 18, 'min' => 18],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'department' => '部门',
            'position' => '职位',
            'mobile' => '手机号',
            'QQ' => 'QQ',
            'email' => '邮箱',
            'emergency_contact_no' => '紧急联系人电话',
            'emergency_contact' => '紧急联系人',
            'id_no' => '身份证号',
            'native_place' => '籍贯',
            'education' => '学历',
            'addr' => '住址',
            'Ukey_info' => 'U盾',
            'login_pwd' => '员工密码',
            'gmt_create' => '创建时间',
            'gmt_modified' => '修改时间',
            'bank' => '开户行',
            'acct_id' => '员工银行卡号',
            'hiredate' => '入职时间',
            'expiration' => '合约到期',
            'medical_examination' => '体检报告',
            'other' => '其他信息',
            'photo' => '照片',
            'pic_path' => '图片存放路径',
            'state' => '状态',
            'examination' => '审核',
            'adopt' => '通过状态',
            'is_del' => '删除',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->gmt_create = time();
                $this->gmt_modified = time();
            } else {
                $this->gmt_modified = time();
            }
            return true;
        } else {
            return false;
        }
    }

    //表连接查询
    //部门
    public function getDepartmenttypetbl() {
        return $this->hasOne(DepartmentTypetbl::className(), ['id' => 'department']);
    }

    //职位
    public function getPositiontypetbl() {
        return $this->hasOne(PositionTypetbl::className(), ['id' => 'position']);
    }

    //学历
    public function getEducationtypetbl() {
        return $this->hasOne(EducationTypetbl::className(), ['id' => 'education']);
    }

    //状态
    public function getStatetypetbl() {
        return $this->hasOne(StateTypetbl::className(), ['id' => 'state']);
    }

    //审核
    public function getExaminationtypetbl() {
        return $this->hasOne(ExaminationTypetbl::className(), ['id' => 'examination']);
    }

    //通过状态
    public function getAdopttypetbl() {
        return $this->hasOne(AdoptTypetbl::className(), ['id' => 'adopt']);
    }

    //设置审核状态(自定义修改)[1为未审核,2为已审核,3为审核失败]
    public function approve() {
        $this->examination = 2;
        return ($this->save() ? true : false);
    }

    //查看有多少条未审核的数据
    public static function getPengdingCommentCount() {
        return Managertbl::find()->where(['examination' => 1])->count();
    }

    public function approve1() {
        $this->adopt = 2;
        return ($this->save() ? true : false);
    }

}
