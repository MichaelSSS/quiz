<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * Base vocabulary class
 */

class Voc extends ActiveRecord
{
    const MAX_ITERATIONS = 100; // when seeking random answers

    const LANG_EN = 'en';
    const LANG_RU = 'ru';

    public static $langs = [
        self::LANG_EN,
        self::LANG_RU
    ];

    public static function getRandom($n, $exclude) {
        // total number of the rows
        $count = self::find()->count();

        if ($count < $n) return [];

        // unique random offsets
        $offsets = [];
        $i = 0;
        while (count($offsets) < $n && $i < self::MAX_ITERATIONS) {
            $i += 1;
            $rand = mt_rand(0, $count - 1);
            if (in_array($rand, $offsets) || $rand == $exclude) continue;

            $offsets[] = $rand;
        }

        if (count($offsets) < $n) return [];

        $models = [];
        foreach ($offsets as $offset) {
          $models[] = self::find()->offset($offset)->one();
        }

        return $models;
    }

    public static function getOtherLang($lang) {
        return $lang == self::LANG_EN ? self::LANG_RU : self::LANG_EN;
    }
}
