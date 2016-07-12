<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Challenge;

class QuizController extends BaseController
{
    public function actionStart() {
        Yii::$app->user->logout();

        $user = User::findIdentity(Yii::$app->request->post('name'));
        if (!$user) {
            $this->renderJson(['error' => 'User not found']);
        }

        Yii::$app->user->login($user);

        $challenge = $user->start('en');

        $this->renderChallenge($challenge);
    }

    public function actionSubmit() {
        if (Yii::$app->user->isGuest) {
            $this->renderJson(['error' => 'User not found']);
        }

        $word = Yii::$app->request->post('challenge');
        $answer = Yii::$app->request->post('answer');

        $user = Yii::$app->user->getIdentity();
        $lang = $user->getState('lang');

        $challenge = $user->getChallenge($lang);

        if (!$challenge->check($word, $answer)) {  // incorect answer
            $errors = $user->incErrors();

            if ($errors >= User::MAX_ERRORS) {  // errors limit
                $user->save();

                $this->renderJson(['status' => 'error', 'finished' => true, 'score' => $user->score]);

            }

            $this->renderJson(['status' => 'error', 'errors' => $errors]);
        }

        // correct answer
        $user->incScore();

        if (!$user->offsetNext()) {  // end of the quiz
            $user->save();

            $this->renderJson([
              'status' => 'success',
              'finished' => true,
              'score' => $user->score
            ]);
        }

        // next challenge
        $challenge = $user->getChallenge(['en', 'ru'][mt_rand(0,1)]);
        $this->renderChallenge($challenge);
    }

    protected function renderChallenge($challenge) {
        $word = $challenge->getWord();
        $answers = $challenge->getAnswers();

        $this->renderJson([
          'status' => 'success',
          'challenge' => $word,
          'answers' => $answers,
        ]);
    }
}
