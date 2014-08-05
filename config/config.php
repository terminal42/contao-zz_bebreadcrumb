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
 * forked by Ingolf Steinhardt <contao@e-spin.de> 
 *
 * PHP version 5
 * @copyright  terminal42 gmbh 2009-2013
 * @author     Andreas Schempp <andreas.schempp@terminal42.ch>
 * @author     Kamil Kuzminski <kamil.kuzminski@terminal42.ch>
 * @license    LGPL
 */
 
// add css
$GLOBALS['TL_CSS'][] = 'system/modules/zz_bebreadcrumb/assets/stylesheet.css';

// add template hook
$GLOBALS['TL_HOOKS']['parseBackendTemplate'][] = array('BackendBreadcrumbTemplate', 'parseTemplate');

class BackendBreadcrumbTemplate
{
  public function parseTemplate($strContent, $strTemplate){
  	if ($strTemplate == 'be_main')
  	{
		  // append DIV at head container
		  ob_start();
			$bc = new BackendBreadcrumb();
			$out = $bc->generate();
			$bc = ob_get_clean();
			$htmlDiv = '<div id="mod_backendbreadcrumb">' . $bc . '</div>';
		  $strContent = preg_replace('#</div>(\s+)<div id="container">#i', $htmlDiv."</div>\n<div id=\"container\">", $strContent);
		}
		
		return $strContent;
		
  }
}