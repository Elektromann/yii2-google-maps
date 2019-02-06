Google Maps Yii2
================
Use many google maps in one page

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist elektromann/yii2-google-maps "*"
```

or add

```
"elektromann/yii2-google-maps": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \elektromann\googlemaps\Map::widget(); ?>```