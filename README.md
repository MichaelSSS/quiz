English-Russian quiz
=======================================================
Application for testing language knowledge

MAIN FOLDERS
-------------------

      config/             contains application configurations
      controllers/        contains Web controller classes
      models/             contains model classes
      web/js              contains JavaScript files
      web/views           contains HTML files


REQUIREMENTS
------------

* PHP >= 5.4.0.
* MySQL 5

INSTALLATION
------------
* Clone repository
```
https://github.com/MichaelSSS/quiz.git
```

* Run the following command in the project directory
```
php composer.phar install
```
OR
copy `vendor` directory from [Yii 2 Basic Project Template](http://www.yiiframework.com/download/) in the project directory.

* Configure DB conection in config/db.php

* Run the following command in the project directory to apply migrations
```
php yii migrate
```

* Run the following command in the project directory to start PHP built-in web server
```
php -S localhost:8000 -t web
```

* Navigate to http://localhost:8000 in your browser
