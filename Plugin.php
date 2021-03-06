<?php namespace Keios\DualFormWidget;

use Keios\DualFormWidget\FormWidgets\DualFormWidget;
use System\Classes\PluginBase;

/**
 * DualFormWidget Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'DualFormWidget',
            'description' => 'No description provided yet...',
            'author'      => 'Keios',
            'icon'        => 'icon-leaf'
        ];
    }

    public function registerFormWidgets()
    {
        return [
            DualFormWidget::class => 'dual'
        ];
    }
}
