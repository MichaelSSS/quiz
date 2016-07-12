<?php

namespace app\models;

use yii\db\ActiveRecord;

class Mistakes extends ActiveRecord
{
    /**
     * Declares validation rules
     *
     * @return array Array of validation rules
     */
    public function rules() {
      return [
          [['challenge_id', 'answer_id'], 'required']
      ];
    }

    public static function add($challenge_id, $answer_id) {
        $table = self::tableName();

        $command = self::getDb()->createCommand("INSERT INTO $table (`challenge_id`,`answer_id`,`count`)
          VALUES (:challenge_id,:answer_id,1) ON DUPLICATE KEY UPDATE `count`=`count`+1");

        $command->bindValues([
          ':challenge_id' => $challenge_id,
          ':answer_id' => $answer_id
        ]);

        return $command->execute();
    }
}
