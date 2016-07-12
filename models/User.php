<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use auth\models\AccessToken;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const MAX_ERRORS = 3;

    const SESSION_PREFIX = 'user_';

    public $lang;

    /**
     * Declares validation rules
     *
     * @return array Array of validation rules
     */
    public function rules() {
        return [
            ['name', 'required'],
            ['lang', 'in'  , 'range'=> ['en', 'ru']]
        ];
    }

    public static function tableName() {
        return 'users';
    }

    public function getId() {
        return $this->name;
    }

    public static function findIdentity($id) {
        $user = new User;
        $user->name = $id;

        return $user;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->score = $this->getState('score');
            $this->max_score = Voc::find()->count();

            return true;
        } else {
            return false;
        }
    }

    public function start($lang) {
      $this->setState('offset', null);
      $this->setState('errors', 0);
      $this->setState('score', 0);

      $this->offsetNext();

      return $this->getChallenge($lang);
    }

    public function offsetNext() {
        $offset = $this->getState('offset');
        if ($offset === null) {
            $offset = 0;
        } else {
            $offset += 1;
        }


        $count = Voc::find()->count();

        if ($offset >= $count) return false;

        $this->setState('offset', $offset);

        return true;
    }

    public function getChallenge($lang) {
        $this->setState('lang', $lang);

        $challenge = new Challenge($lang, $this->getState('offset'));

        return $challenge;
    }

    public function incErrors() {
        $errors = $this->getState('errors');
        if (!$errors === null) {
            $errors = 0;
        }

        $errors += 1;

        $this->setState('errors', $errors);

        return $errors;
    }

    public function incScore() {
        $score = $this->getState('score');
        if (!$score === null) {
            $score = 0;
        }

        $score += 1;

        $this->setState('score', $score);

        return $score;
    }

    public function setState($key, $value) {
        Yii::$app->session->set(self::SESSION_PREFIX . $key, $value);
    }
    public function hasState($key) {
        return Yii::$app->session->has(self::SESSION_PREFIX . $key);
    }
    public function getState($key) {
        return Yii::$app->session->get(self::SESSION_PREFIX . $key);
    }

   /**
     * Required to implemnt UserIdentity interface
     *
     * @return null
     */
    public static function findIdentityByAccessToken($token, $type = null) {
    }

    /**
     * Required to implemnt UserIdentity interface
     *
     * @return null
     */
    public function getAuthKey() {
    }

    /**
     * Required to implemnt UserIdentity interface
     *
     * @return false
     */
    public function validateAuthKey($authKey) {
    }
}
