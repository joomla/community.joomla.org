<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 * @copyright   Copyright (C) 2005 - 2026 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;

$config = Factory::getConfig();

// Note. It is important to remove spaces between elements.
?>

<ul class="nav nav-pills">
<?php
foreach ($list as $i => &$item) :
    $class = 'item-' . $item->id;

    if ($item->id == $active_id) {
        $class .= ' current';
    }

    if (\in_array($item->id, $path)) {
        $class .= ' active';
    } elseif ($item->type == 'alias') {
        $aliasToId = $item->getParams()->get('aliasoptions');
        if (\count($path) > 0 && $aliasToId == $path[\count($path)-1]) {
            $class .= ' active';
        } elseif (\in_array($aliasToId, $path)) {
            $class .= ' alias-parent-active';
        }
    }

    if ($item->deeper) {
        $class .= ' dropdown';
    }

    if ($item->parent) {
        $class .= ' parent';
    }

    $class .= ' divider-vertical';

    if (!empty($class)) {
        $class = ' class="' . trim($class) . '"';
    }

    echo '<li' . $class . '>';

    // Render the menu item.
    switch ($item->type) :
        case 'separator':
        case 'url':
        case 'component':
        case 'heading':
            require ModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
            break;

        default:
            require ModuleHelper::getLayoutPath('mod_menu', 'default_url');
            break;
    endswitch;

    if ($item->deeper) {
        // The next item is deeper.
        echo '<ul class="dropdown-menu">';
    } elseif ($item->shallower) {
        // The next item is shallower.
        echo '</li>';
        echo str_repeat('</ul></li>', $item->level_diff);
    } else {
        // The next item is on the same level.
        echo '</li>';
    }
endforeach;
?>
</ul>
