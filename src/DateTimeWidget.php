<?php
/**
 * PHP version 7.3
 *
 * @author    Oleksandr Barabolia <alexandrbarabolya@gmail.com>
 * @copyright 2016-2019 Oleksandr Barabolia. All Rights Reserved.
 */

namespace obarabolia\yii2\airdatepicker;

use obarabolia\yii2\airdatepicker\assets\DateTimeAsset;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

/**
 * Class DateTimeWidget
 *
 * @see Js lib - http://t1m0n.name/air-datepicker/docs/index-ru.html
 */
class DateTimeWidget extends InputWidget
{

    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * @var array the event handlers.
     */
    public $clientEvents = [];

    /**
     * @var array
     */
    public $containerOptions = [];

    /**
     * @var boolean
     */
    public $showTime = false;

    /**
     * @var string
     */
    public $timeSeparator = ' - ';

    /**
     * @var string
     */
    public $phpDateFormat = 'dd.MM.yyyy';

    /**
     * @var string
     */
    public $phpTimeFormat = 'HH:mm';

    /**
     * @var string
     */
    public $airDateFormat = 'dd.mm.yyyy';

    /**
     * @var string
     */
    public $airTimeFormat = 'hh:ii';

    /**
     * @var array
     */
    public $phpAirMapping = [];

    /**
     * @var boolean
     */
    public $submitOnChange = false;


    /**
     * @return void
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $value = (false === empty($this->hasModel()))
            ? Html::getAttributeValue($this->model, $this->attribute)
            : $this->value;

        if (true === empty($this->airDateFormat) || true === empty($this->phpDateFormat)) {
            throw new InvalidConfigException('Please set airDateFormat and phpDateFormat.');
        }

        if (true === $this->showTime) {
            $this->clientOptions = ArrayHelper::merge(
                $this->clientOptions,
                [
                    'timepicker' => true,
                    'timeFormat' => $this->airTimeFormat,
                ]
            );
        }

        $this->clientOptions = ArrayHelper::merge(
            $this->clientOptions,
            ['language' => stristr(Yii::$app->language, '-', true)]
        );

        // Init default clientOptions.
        $this->clientOptions = ArrayHelper::merge(
            ['dateFormat' => $this->airDateFormat],
            $this->clientOptions
        );

        // Init default options.
        $this->options = ArrayHelper::merge(
            ['class' => 'form-control'],
            $this->options
        );

        if (null !== $value && trim($value) !== '' && false === array_key_exists('value', $this->options)) {
            if (true === $this->showTime) {
                $this->options['value'] = Yii::$app->formatter->asDatetime(
                    $value,
                    $this->phpDateFormat . $this->timeSeparator . $this->phpTimeFormat
                );
            } else {
                $this->options['value'] = Yii::$app->formatter->asDatetime(
                    $value,
                    $this->phpDateFormat
                );
            }
        }

        if (false === isset($this->containerOptions['id'])) {
            $this->containerOptions['id'] = $this->getId();
        }

        $this->registerJs();
    }

    /**
     * @return void
     */
    protected function registerJs()
    {
        DateTimeAsset::register($this->getView());
        if (true === $this->submitOnChange) {
            $this->clientOptions = ArrayHelper::merge(
                $this->clientOptions,
                [
                    'onSelect' => new JsExpression(
                        "function (date) {
                            if ($('.grid-view').length > 0) {
                                $('#{$this->containerOptions['id']}').closest('.grid-view').yiiGridView('applyFilter');
                            }
                        }"
                    ),
                ],
                $this->clientEvents,
            );
        }

        $clientOptions = Json::encode($this->clientOptions);

        $this->getView()
            ->registerJs("$('#{$this->containerOptions['id']} input').datepicker({$clientOptions})");
    }

    /**
     * @return string
     */
    public function run()
    {
        $content = [];

        Html::addCssStyle($this->containerOptions, 'position: relative');

        $content[] = Html::beginTag('div', $this->containerOptions);

        $content[] = $this->renderInput();

        $content[] = Html::endTag('div');

        return implode("\n", $content);
    }

    /**
     * @return string
     */
    protected function renderInput()
    {
        if (true === $this->hasModel()) {
            $content = Html::activeTextInput($this->model, $this->attribute, $this->options);
        } else {
            $content = Html::textInput($this->name, $this->value, $this->options);
        }

        return $content;
    }
}
