<?php
/**
 * WebpackAssets plugin for Craft CMS 3.x
 *
 * Webpack assets for craft cms
 *
 * @link      https://www.lahautesociete.com
 * @copyright Copyright (c) 2018 La Haute Société
 */

namespace lhs\webpackassets;

use lhs\webpackassets\services\JsonReader as JsonReaderService;
use lhs\webpackassets\variables\WebpackAssetsVariable;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

/**
 * Class WebpackAssets
 *
 * @author    La Haute Société
 * @package   WebpackAssets
 * @since     2.0.0
 *
 * @property  JsonReaderService $jsonReader
 */
class WebpackAssets extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var WebpackAssets
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('webpackAssets', WebpackAssetsVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'webpack-assets',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

}
