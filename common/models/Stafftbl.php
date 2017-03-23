<?php

namespace common\models;

use Yii;

class Stafftbl extends \yii\db\ActiveRecord {

    public $rememberMe = true;
    public $repwd;

    public static function tableName() {
        return 'stafftbl';
    }

    public function rules() {
        return [
            ['mobile', 'required', 'message' => '手机号不能为空',
                'on' => ['login', 'seekpwd', 'changepwd', 'create']],
            ['mobile', 'unique', 'message' => '手机号已存在', 'on' => 'create'],
            ['login_pwd', 'required', 'message' => '管理员密码不能为空', 'on' => ['login', 'changepwd', 'login_pwd', 'create', 'changeemail']],
            ['rememberMe', 'boolean', 'on' => 'login'],
            ['login_pwd', 'validatePwd', 'on' => ['login', 'changeemail']],
            ['email', 'required', 'message' => '邮箱不能为空', 'on' => ['seekpwd', 'create', 'changeemail']],
            ['email', 'email', 'message' => '邮箱格式不正确', 'on' => ['seekpwd', 'create', 'changeemail']],
            ['email', 'unique', 'message' => '邮箱已存在', 'on' => ['create', 'changeemail']],
            ['email', 'validateEmail', 'on' => 'seekpwd'],
            //声明repwd的验证方法：和login_pwd对比
            ['repwd', 'required', 'message' => '新密码不能为空', 'on' => ['changepwd', 'create']],
            ['repwd', 'compare', 'compareAttribute' => 'login_pwd', 'message' => '两次密码输入不一致', 'on' => ['changepwd', 'create']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'position' => '职位',
            'mobile' => '手机号',
            'QQ' => 'QQ',
            'email' => 'Email',
            'emergency_contact_no' => '紧急联系人电话',
            'emergency_contact' => '紧急联系人',
            'id_no' => '身份证号',
            'addr' => '住址',
            'login_pwd' => '员工密码',
            'gmt_create' => '创建时间',
            'gmt_modified' => '修改时间',
            'is_del' => 'Is Del',
            'bank' => '开户行',
            'acct_id' => '银行卡号',
            'hiredate' => '入职时间',
            'expiration' => '合约到期',
            'medical_examination' => '体检报告',
            'other' => '其他信息',
            'photo' => '照片',
            'pic_path' => '图片存放路径',
            'name' => '姓名',
            'department' => '部门',
            'education' => '学历',
            'native_place' => '籍贯',
            'state' => '在职状态',
            'audit' => '审核状态',
            'repwd'=>'确认密码'
        ];
    }

    public function validatePwd() {
        if (!$this->hasErrors()) {
            $data = self::find()->where('mobile= :mobile and login_pwd = :pwd', [
                        ":mobile" => $this->mobile, ":pwd" => md5($this->login_pwd)
                    ])->one();
            if (is_null($data)) {
                $this->addError("mobile", "手机或者密码错误");
            }
        }
    }

    public function validateEmail() {
        if (!$this->hasErrors()) {
            $data = self::find()->where('mobile = :mobile and email = :email', [
                        ":mobile" => $this->mobile, ":email" => $this->email])
                    ->one();
            if (is_null($data)) {
                $this->addError("email", "帐号邮箱不匹配");
            }
        }
    }

    public function login($data) {
        $this->scenario = "login";
        if ($this->load($data) && $this->validate()) {
            $session = Yii::$app->session;
            $lifetime = $this->rememberMe ? 24 * 3600 : 0;
            session_set_cookie_params($lifetime);
            $session['admin'] = ['mobile' => $this->mobile,
                'isLogin' => 1,];
            $this->save(['login_time' => time(), 'login_ip' => ip2long(Yii::$app->request->userIP)], 'mobile= :mobile', [':mobile' => $this->mobile]);
            return (bool) $session['admin']['isLogin'];
        }
        return false;
    }

    //自定义token生成方法
    public function createToken($mobile, $time) {
        return md5(md5($mobile) . base64_decode(Yii::$app->request->userIP) . md5($time));
    }

    public function changePwd($data) {
        $this->scenario = 'changepwd';
        if ($this->load($data) && $this->validate()) {
            return (bool) $this->save(['mobile' => md5($this->mobile)], 'mobile = :mobile', [':mobile' => $this->mobile]);
        }
        return false;
    }

    //添加员工
    public function create($data) {
        $this->scenario = 'create';
        //save 自动判断添加或修改，包含validate方法
        if ($this->load($data) && $this->validate()) {
            $this->login_pwd = md5($this->login_pwd);
            //save（）不需要再做验证，给一个false
            if ($this->save(false)) {
                return true;
            }
            return false;
        }
        return false;
    }

}
