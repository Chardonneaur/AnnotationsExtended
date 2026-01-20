<?php

namespace Piwik\Plugins\AnnotationsExtended;

use Piwik\Menu\MenuAdmin;
use Piwik\Piwik;

class Menu extends \Piwik\Plugin\Menu
{
    public function configureAdminMenu(MenuAdmin $menu)
    {
        if (Piwik::isUserIsAnonymous()) {
            return;
        }

        $menu->addPersonalItem('Annotations', $this->urlForAction('index'), 40);
    }
}
