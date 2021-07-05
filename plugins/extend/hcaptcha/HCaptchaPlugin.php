<?php

namespace SunlightExtend\Hcaptcha;

use HCaptcha\HCaptcha;
use HCaptcha\Response;
use Sunlight\Extend;
use Sunlight\Plugin\Action\PluginAction;
use Sunlight\Plugin\ExtendPlugin;
use Sunlight\User;

/**
 * ReCaptcha plugin
 *
 * @author Jirka Daněk <jdanek.eu>
 */
class HCaptchaPlugin extends ExtendPlugin
{

    /** @var HCaptcha */
    private $hcaptcha;

    public function initialize(): void
    {
        parent::initialize();

        $pluginConfig = $this->getConfig();
        if ($pluginConfig->offsetExists('site_key') && $pluginConfig->offsetExists('secret_key')) {

            $this->hcaptcha = new HCaptcha(
                $pluginConfig->offsetGet('site_key'),
                $pluginConfig->offsetGet('secret_key')
            );

            Extend::regm([
                'tpl.head' => [$this, 'onHead'],
                'captcha.init' => [$this, 'onCaptchaInit'],
                'captcha.check' => [$this, 'onCaptchaCheck'],
            ]);
        }
    }

    /**
     * @param array $args
     */
    public function onHead(array $args): void
    {
        if (!User::isLoggedIn()) {
            $args['js_before'] .= "<script src='" . HCaptcha::URL_SCRIPT . "' async defer></script>";
        }
    }

    /**
     * @param array $args
     */
    public function onCaptchaInit(array $args): void
    {
        if (!User::isLoggedIn()) {
            $content = "<div class='h-captcha' data-sitekey='" . $this->getConfig()->offsetGet('site_key') . "'" . ($this->getConfig()->offsetGet('dark_mode') ? " data-theme='dark'" : '') . "></div>";
            $args['value'] = [
                'label' => _lang('captcha.input'),
                'content' => $content,
                'top' => true,
                'class' => ''
            ];
        }
    }

    /**
     * @param $args
     */
    public function onCaptchaCheck(array $args): void
    {
        if (!User::isLoggedIn()) {
            if (isset($_POST['h-captcha-response'])) {
                /** @var $response Response */
                $response = $this->hcaptcha->validate($_POST['h-captcha-response'] ?? null);
                $args['value'] = $response->isSuccess();
            } else {
                $args['value'] = false;
            }
        }
    }

    public function getAction(string $name): ?PluginAction
    {
        if ($name === 'config') {
            return new Configuration($this);
        }
        return parent::getAction($name);
    }

    protected function getConfigDefaults(): array
    {
        return [
            'site_key' => null,
            'secret_key' => null,
            'dark_mode' => false,
        ];
    }

}
