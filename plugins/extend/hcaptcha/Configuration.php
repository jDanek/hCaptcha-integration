<?php

namespace SunlightExtend\Hcaptcha;

use Sunlight\Plugin\Action\ConfigAction;
use Sunlight\Util\Form;

class Configuration extends ConfigAction
{
    protected function getFields(): array
    {
        return [
            'site_key' => [
                'label' => _lang('hcaptcha.site_key'),
                'input' => $this->createInput('text', 'site_key', ['class' => 'inputbig']),
                'type' => 'text'
            ],
            'secret_key' => [
                'label' => _lang('hcaptcha.secret_key'),
                'input' => $this->createInput('text', 'secret_key', ['class' => 'inputbig', 'placeholder' => '0x.....']),
                'type' => 'text'
            ],
            'dark_mode' => [
                'label' => _lang('hcaptcha.dark_mode'),
                'input' => $this->createInput('checkbox', 'dark_mode'),
                'type' => 'checkbox'
            ]
        ];
    }

    private function createInput(string $type, string $name, $attributes = null): string
    {
        $result = "";
        $attr = [];

        if (is_array($attributes)) {
            foreach ($attributes as $k => $v) {
                if (is_int($k)) {
                    $attr[] = $v . '=' . $v;
                } else {
                    $attr[] = $k . '=' . $v;
                }
            }
        }

        if ($type === 'checkbox') {
            $result = '<input type="checkbox" name="config[' . $name . ']" value="1"' . implode(' ', $attr) . Form::activateCheckbox($this->plugin->getConfig()->offsetGet($name)) . '>';
        } else {
            $result = '<input type="' . $type . '" name="config[' . $name . ']" value="' . $this->plugin->getConfig()->offsetGet($name) . '"' . implode(' ', $attr) . '>';
        }

        return $result;
    }
}