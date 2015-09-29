<?php
/**
 * Created by PhpStorm.
 * User: Łukasz Biały
 * URL: http://keios.eu
 * Date: 9/28/15
 * Time: 11:28 AM
 */

namespace Keios\DualFormWidget\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Exception;

/**
 * Class DualFormWidget
 *
 * @package Keios\DualFormWidget
 */
class DualFormWidget extends FormWidgetBase
{
    /**
     *
     */
    const LEFT = 'left';
    /**
     *
     */
    const RIGHT = 'right';

    /**
     * @var bool
     */
    private $jsonable = false;

    /**
     *
     */
    public function init()
    {
        $this->jsonable = $this->getConfig('jsonable', false);

        $this->prepareVars();
    }

    /**
     * Render the form widget
     */
    public function render()
    {
        return $this->makePartial('widget');
    }

    /**
     * Prepare widget variables
     */
    public function prepareVars()
    {
        $leftConfig = $this->getConfig('left');
        $rightConfig = $this->getConfig('right');

        $this->vars['leftLabel'] = array_pull($leftConfig, 'label', '');
        $this->vars['rightLabel'] = array_pull($rightConfig, 'label', '');

        $loadValue = $this->getLoadValue();

        $this->vars['selected'] = ($this->jsonable &&
            is_array($loadValue) &&
            isset($loadValue['side'])
        ) ? $loadValue['side'] : self::LEFT;

        $this->vars[self::LEFT] = $this->makeNestedFormWidget(self::LEFT, $leftConfig);
        $this->vars[self::RIGHT] = $this->makeNestedFormWidget(self::RIGHT, $rightConfig);
    }

    /**
     * Load widget assets
     */
    public function loadAssets()
    {
        $this->addJs('js/dualformwidget.js');
        $this->addCss('css/dualformwidget.css');
    }

    /**
     * Return save value
     *
     * @param mixed $value
     *
     * @return  array
     * @throws Exception
     */
    public function getSaveValue($value)
    {
        $side = post($this->getId('form-option'));

        switch ($side) {
            case self::LEFT:
                $value = array_get(post($this->getId(self::LEFT)), $this->formField->fieldName, null);
                $sideToStore = self::LEFT;
                break;
            case self::RIGHT:
                $value = array_get(post($this->getId(self::RIGHT)), $this->formField->fieldName, null);
                $sideToStore = self::RIGHT;
                break;
            default:
                throw new Exception();
        }

        if ($this->jsonable) {
            return ['side' => $sideToStore, 'value' => $value];
        } else {
            return $value;
        }
    }


    /**
     * @param $name
     * @param $fieldConfig
     *
     * @return \Backend\Widgets\Form
     * @throws \SystemException
     */
    private function makeNestedFormWidget($name, $fieldConfig)
    {
        $loadValue = $this->getLoadValue();

        if ($this->jsonable && is_array($loadValue)) {
            $value = $loadValue['value'];
        } else {
            $value = $loadValue;
        }

        $wrappedConfig = ['fields' => [$this->formField->fieldName => $fieldConfig]];

        $formConfig = $this->makeConfig($wrappedConfig);
        $formConfig->model = $this->model;
        $formConfig->data = [$name => $value];
        $formConfig->alias = $this->alias.'_'.'Form';
        $formConfig->arrayName = $this->getId($name);

        /**
         * @var \Backend\Widgets\Form $widget
         */
        $widget = $this->makeWidget('Backend\Widgets\Form', $formConfig);
        $widget->bindToController();

        return $widget;
    }

}