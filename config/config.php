<?php

/**
 * zz_bebreadcrumb Extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2009-2014, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-zz_bebreadcrumb
 */

if (TL_MODE == 'BE') {
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('BackendBreadcrumb', 'generate');
    $GLOBALS['TL_JAVASCRIPT']['backendbreadcrumb'] = 'system/modules/zz_bebreadcrumb/assets/backendbreadcrumb.js';
}
