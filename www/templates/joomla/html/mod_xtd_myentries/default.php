<?php

/**
 * @package     Extly.Modules
 * @subpackage  com_xtdir - Extended Directory for SobiPro
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2016 Prieco, S.A. All rights reserved.
 * @license     http://http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

F0FDispatcher::getTmpInstance('com_xtdir', 'myentries', $config)->dispatch();
