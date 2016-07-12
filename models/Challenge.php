<?php

namespace app\models;

use yii\base\Model;

class Challenge extends Model
{
    const ADD_ANSWERS_COUNT = 3;

    protected $offset;
    protected $word;

    public $lang;

    /**
     * Declares validation rules
     *
     * @return array Array of validation rules
     */
    public function rules() {
        return [
            ['lang', 'in'  , 'range'=> Voc::$langs]
        ];
    }

    public function __construct($lang, $offset) {
        $this->offset = $offset;
        $this->setLang($lang);
    }

    public function getWord() {
        $model = Voc::find()->offset($this->offset)->one();

        if (!$model) {
            return null;
        }

        $this->word = $model;

        return $model->{$this->getChallengeLang()};
    }

    public function getAnswers() {
        if (!isset($this->word)) $this->getWord();

        $models = Voc::getRandom(self::ADD_ANSWERS_COUNT, $this->offset);
        // place where to randomly insert correct answer into
        $insert = mt_rand(0, count($models));

        $lang = $this->getAnswerLang();
        $answers = [];
        foreach ($models as $i => $model) {
            if ($i === $insert) $answers[] = $this->word->$lang;
            $answers[] = $model->$lang;
        }
        if ($insert === count($models)) $answers[] = $this->word->$lang;

        return $answers;
    }

    public function check($word, $answer) {
        $wordModel = Voc::find()->offset($this->offset)->one();
        if (!$wordModel) return false;

        $answerModel = Voc::findOne([$this->getAnswerLang() => $answer]);
        if (!$answerModel) return false;

        if ($wordModel->id != $answerModel->id) {
            Mistakes::add($wordModel->id, $answerModel->id);

            return false;
        }
        return true;
    }

    public function setLang($lang) {
        $this->lang = $lang;
    }

    public function getChallengeLang() {
        return $this->lang;
    }

    public function getAnswerLang() {
        return Voc::getOtherLang($this->lang);
    }
}
