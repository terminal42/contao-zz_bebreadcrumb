<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2008
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @package    BackendBreadcrumb
 * @license    LGPL
 */


/**
 * Module folder description
 */
$GLOBALS['TL_LANG']['MOD']['zz_bebreadcrumb'] = array('Backend Breadcrumb', 'Provides the backend breadcrumb.');


/**
 * Load backend breadcrumb
 */
if (TL_MODE == 'BE')
{
	$GLOBALS['TL_JAVASCRIPT']['backendbreadcrumb'] = 'system/modules/zz_bebreadcrumb/html/backendbreadcrumb.js';
	
	ob_start();
	$bc = new BackendBreadcrumb();
	$bc->generate();
	$bc = ob_get_clean();

	$GLOBALS['TL_LANG']['MSC']['backendModules'] .= '</h1><div id="mod_backendbreadcrumb" style="text-align: left; display: none">' . $bc . '</div><h1 style="display:none">&nbsp;';
	
}