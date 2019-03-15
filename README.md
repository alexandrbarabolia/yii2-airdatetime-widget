# Yii2 Date/Time Picker Widget
Date/Time Picker widget without bootstrap for Yii2 framework
Based on [Air Datepicker](https://github.com/t1m0n/air-datepicker)



Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require obarabolia/yii2-airdatetime-widget
```

or add

```
"obarabolia/yii2-datetime-widget": "*"
```

to the require section of your `composer.json` file.


## Usage

Once the extension is installed, simply use it in your code by  :

```php
<?php echo $form->field($model, 'attribute')->widget(
        obarabolia\yii2\airdatepicker\DateTimeWidget::class,
        [ ... options ... ]
    ); 
?>
```
