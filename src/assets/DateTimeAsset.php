<?php
/**
 * PHP version 7.3
 *
 * @package   Infrastructure\Widgets
 * @author    Oleksandr Barabolia <alexandrbarabolya@gmail.com>
 * @copyright 2016-2019 Oleksandr Barabolia. All Rights Reserved.
 */

namespace obarabolia\yii2\airdatepicker\assets;

use Yii;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
class DateTimeAsset extends AssetBundle
{

    /**
     * @var string
     */
    public $sourcePath = '@bower/air-datepicker/dist';

    /**
     * @var array
     */
    public $css = ['css/datepicker.min.css'];

    /**
     * @var array
     */
    public $js = ['js/datepicker.min.js'];

    /**
     * @var array
     */
    public $depends = [JqueryAsset::class];


    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();

        if (Yii::$app->language !== 'ru-RU') {
            $this->js[] = 'js/i18n/datepicker.' . stristr(Yii::$app->language, '-', true) . '.js';
        }
    }
}
