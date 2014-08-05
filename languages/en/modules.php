<?php

/**
 * zz_bebreadcrumb Extension for Contao Open Source CMS
 *
 * @copyright  Copyright (c) 2009-2014, terminal42 gmbh
 * @author     terminal42 gmbh <info@terminal42.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       http://github.com/terminal42/contao-zz_bebreadcrumb
 */


/**
 * Extension name
 */
$GLOBALS['TL_LANG']['MOD']['zz_bebreadcrumb'] = array('Backend Breadcrumb');


/**
 * Load backend breadcrumb
 */
if (TL_MODE == 'BE')
{
	$GLOBALS['TL_JAVASCRIPT']['backendbreadcrumb'] = 'system/modules/zz_bebreadcrumb/assets/backendbreadcrumb.js';

	ob_start();
	$bc = new BackendBreadcrumb();
	$bc->generate();
	$bc = ob_get_clean();

	$GLOBALS['TL_LANG']['MSC']['backendModules'] .= '</h1><div id="mod_backendbreadcrumb" style="text-align: left; display: none">' . $bc . '</div><h1 style="display:none">&nbsp;';
	
}

