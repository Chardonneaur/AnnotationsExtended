<?php

namespace Piwik\Plugins\AnnotationsExtended;

use Piwik\Piwik;

class AnnotationsExtended extends \Piwik\Plugin
{
    public function registerEvents()
    {
        return [
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
        ];
    }

    public function getStylesheetFiles(&$stylesheets)
    {
        $stylesheets[] = 'plugins/AnnotationsExtended/stylesheets/styles.less';
    }

    public static function checkAccess(): void
    {
        Piwik::checkUserIsNotAnonymous();
    }
}
