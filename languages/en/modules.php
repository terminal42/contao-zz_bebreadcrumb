<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 * PHP version 5
 * @copyright  terminal42 gmbh 2009-2013
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @author     Kamil Kuźmiński <kamil.kuzminski@terminal42.ch>
 * @license    LGPL
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

