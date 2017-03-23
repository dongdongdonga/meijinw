<?php

namespace backend\controllers;

use Yii;
use common\models\Stafftbl;

class PublicController extends \yii\web\Controller {

    public function actionLogin() {

        $this->layout = false;
        $model = new Stafftbl();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->login($post)) {
                $this->redirect(['staff/index']);
                Yii::$app->end();
            }
        }
        return $this->render('login', ['model' => $model]);
    }

}
