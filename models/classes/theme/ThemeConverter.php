<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2015 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 */

namespace oat\tao\model\theme;

use oat\oatbox\Configurable;
use oat\tao\model\theme\DefaultTheme;
use oat\tao\model\theme\ConfigurablePlatformTheme;
use oat\tao\helpers\Template;

/**
 * Class ThemeConverter
 *
 * Class to convert legacy platform themes to ConfigurablePlatformTheme
 *
 * @package oat\tao\model\theme
 */
class ThemeConverter
{


    /**
     * Build an instance of ConfigurablePlatformTheme from a legacy theme
     *
     * @param object|array $theme
     * @return ConfigurablePlatformTheme
     * @throws \common_exception_MissingParameter
     */
    public static function convertFromLegacyTheme($theme)
    {
        if ($theme instanceof ConfigurablePlatformTheme) {
            return $theme;
        }

        // older themes are stored as an instance, newer ones as array
        if(is_array($theme)) {
            if(empty($theme['class'])) {
                throw new \common_exception_MissingParameter('class', __METHOD__);
            }
            $options = !empty($theme['options']) ? $theme['options'] : [];
            $theme = new $theme['class']($options);
        }

        // list of all previously used templates
        $templates = ['footer', 'header', 'header-logo', 'login-message'];

        // all keys associated with a public getter from previously used theme classes
        $getKeys = ['id', 'label', 'stylesheet', 'logoUrl', 'link', 'message', 'customTexts'];

        // collect options
        $options = [];
        if (method_exists($theme, 'getOptions')) {
            $options = $theme->getOptions();
        }

        if (method_exists($theme, 'getThemeData')) {
            $options = array_merge($options, $theme->getThemeData());
        }
        unset($options['data']);

        foreach ($getKeys as $key) {
            $method = 'get' . ucfirst($key);
            if (method_exists($theme, $method)) {
                $options[$key] = $theme->{$method}();
            }
        }
        // TAO default logo URL is different
        if($theme instanceof DefaultTheme) {
            $options['logoUrl'] = Template::img('tao-logo.png', 'tao');
        }

        if (method_exists($theme, 'getTemplate')) {
            if (empty($options['templates'])) {
                $options['templates'] = [];
            }
            foreach ($templates as $id) {
                $template = $theme->getTemplate($id);
                if(!is_null($template)) {
                    $options['templates'][$id] = $template;
                }
            }
        }

        // example: oat\taoExtension\model\theme\MyTheme
        $themeClass = get_class($theme);
        if (empty($options['extensionId'])) {
            strtok($themeClass, '\\');
            $options['extensionId'] = strtok('\\');
        }

        if (empty($options['label'])) {
            $options['label'] = basename($themeClass);
        }

        $class = get_class();
        return new $class($options);
    }
}
