<?php

use yii\db\Migration;

class m160408_145921_create_initial_tables extends Migration
{
    public function up()
    {
        $this->createTable('voc', [
            'id' => $this->primaryKey(),
            'en' => $this->string(255)->notNull(),
            'ru' => $this->string(255)->notNull(),
            'INDEX `en` (`en`)',
            'INDEX `ru` (`ru`)',
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $data = json_decode('{"apple": "яблоко",
          "pear": "персик",
          "orange": "апельсин",
          "grape": "виноград",
          "lemon": "лимон",
          "pineapple": "ананас",
          "watermelon": "арбуз",
          "coconut": "кокос",
          "banana": "банан",
          "pomelo": "помело",
          "strawberry": "клубника",
          "raspberry": "малина",
          "melon": "дыня",
          "apricot": "абрикос",
          "mango": "манго",
          "pear": "слива",
          "pomegranate": "гранат",
          "cherry": "вишня"}');

        $vals = '';
        foreach ($data as $en => $ru) {
            $vals .= "('$en','$ru'),";
        }

        $this->execute('INSERT INTO `voc`(`en`,`ru`) VALUES ' . substr($vals,0,-1));

        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'name' => $this->string(32)->notNull(),
            'score' => $this->smallInteger()->notNull()->defaultValue(0),
            'max_score' => $this->smallInteger()->notNull()->defaultValue(0),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('mistakes', [
            'challenge_id' => $this->integer()->notNull(),
            'answer_id' => $this->integer()->notNull(),
            'count' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'PRIMARY KEY (`challenge_id`, `answer_id`)',
            'INDEX `challenge_id` (`challenge_id`)',
            'FOREIGN KEY (`challenge_id`) REFERENCES voc(`id`) ON DELETE CASCADE',
            'FOREIGN KEY (`answer_id`) REFERENCES voc(`id`) ON DELETE CASCADE',
        ]);
    }

    public function down()
    {
        $this->dropTable('mistakes');
        $this->dropTable('users');
        $this->dropTable('voc');
    }
}
