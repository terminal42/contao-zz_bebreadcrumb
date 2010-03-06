<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
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
 * @license    LGPL
 */



class BackendBreadcrumb extends Backend
{
	/**
	 * __construct() of Class Backend is protected...
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	
	/**
	 * Generate the backend breadcrumb
	 */
	public function generate()
	{
		$this->import('Database', 'Database');
		$this->import('Input', 'Input');
		
		$do = $this->Input->get('do');
		$id = $this->Input->get('id');
		$levels = array();
		
		if (strlen($do) && strlen($id))
		{
			$parent = true;
			
			while( $parent )
			{
				$moduleGroup = $this->findBackendModuleGroup($do);
				
				if (strlen($ptable))
					$table = $ptable;
				else if (strlen($this->Input->get('table')) && strlen($this->Input->get('act')))
					$table = $this->Input->get('table');
				else
					$table = $GLOBALS['BE_MOD'][$moduleGroup][$do]['tables'][0];
					
				if (strlen($pid))
					$id = $pid;
					
				$this->loadDataContainer($table);
				$this->loadLanguageFile($table);
				$level = array
				(
					'id'			=> $id,
					'table'			=> $table,
					'moduleGroup'	=> $moduleGroup,
					'href'			=> $GLOBALS['TL_DCA'][$table]['list']['operations']['edit']['href'],
				);
				
				$ptable = $GLOBALS['TL_DCA'][$table]['config']['ptable'];
				$tables = $GLOBALS['BE_MOD'][$moduleGroup][$do]['tables'];
					
				if (!strlen($ptable))
					$parent = false;
				
				if ($GLOBALS['TL_DCA'][$table]['config']['dataContainer'] != 'Table')
				{
					if ($do == 'tasks')
					{
						$this->loadLanguageFile('tl_task');
						$level['label'] = sprintf($GLOBALS['TL_LANG']['tl_task']['edit'][1], $id);
					}
					else
					{
						$level['label'] = $id;
					}
				}
				else
				{
					if (in_array($table, $tables))
						$level['do'] = $do;
						
					$row = $this->Database->prepare("SELECT * FROM ".$table." WHERE id=".$id)
												   ->limit(1)
												   ->execute()
										   	       ->fetchAssoc();
										   	       
					$pid = $row['pid'];
					$level['row'] = $row;
					
					if ($GLOBALS['TL_DCA'][$table]['list']['label']['fields'] && count($GLOBALS['TL_DCA'][$table]['list']['label']['fields']))
					{
						$showFields = $GLOBALS['TL_DCA'][$table]['list']['label']['fields'];
		
						// Label
						foreach ($showFields as $k=>$v)
						{
							if (in_array($GLOBALS['TL_DCA'][$table]['fields'][$v]['flag'], array(5, 6, 7, 8, 9, 10)))
							{
								$labels[$k] = date($GLOBALS['TL_CONFIG']['datimFormat'], $row[$v]);
							}
							elseif ($GLOBALS['TL_DCA'][$table]['fields'][$v]['inputType'] == 'checkbox' && !$GLOBALS['TL_DCA'][$table]['fields'][$v]['eval']['multiple'])
							{
								$labels[$k] = strlen($row[$v]) ? $GLOBALS['TL_DCA'][$table]['fields'][$v]['label'][0] : '';
							}
							else
							{
								$row_v = deserialize($row[$v]);
		
								if (is_array($row_v))
								{
									$args_k = array();
		
									foreach ($row_v as $option)
									{
										$args_k[] = strlen($GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$option]) ? $GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$option] : $option;
									}
		
									$labels[$k] = implode(', ', $args_k);
								}
								elseif ($GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]])
								{
									$labels[$k] = is_array($GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]]) ? $GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]][0] : $GLOBALS['TL_DCA'][$table]['fields'][$v]['reference'][$row[$v]];
								}
								else
								{
									$labels[$k] = $row[$v];
								}
							}
						}
		
						// Shorten label it if it is too long
						$label = vsprintf((strlen($GLOBALS['TL_DCA'][$table]['list']['label']['format']) ? $GLOBALS['TL_DCA'][$table]['list']['label']['format'] : '%s'), $labels);
		
						if (strlen($GLOBALS['TL_DCA'][$table]['list']['label']['maxCharacters']) && $GLOBALS['TL_DCA'][$table]['list']['label']['maxCharacters'] < strlen($label))
						{
							$label = trim(utf8_substr($label, 0, $GLOBALS['TL_DCA'][$table]['list']['label']['maxCharacters'])).'...';
						}
		
						// Remove empty brackets (), [], {}, <> and empty tags from label
						$label = preg_replace('/\(\) ?|\[\] ?|\{\} ?|<> ?/i', '', $label);
						$label = preg_replace('/<[^>]+>\s*<\/[^>]+>/i', '', $label);
					}
					else if ($GLOBALS['TL_DCA'][$table]['list']['sorting']['mode'] == 4 && $GLOBALS['TL_DCA'][$table]['list']['sorting']['child_record_callback'])
					{
						switch( $table )
						{
							case 'tl_content':
							case 'tl_form_field':
							case 'tl_style':
								if ($this->Input->get('act') == 'edit' && $this->Input->get('id') == $id)
								{
									$label = sprintf($GLOBALS['TL_LANG']['MSC']['editRecord'], 'ID '.$id);
									break;
								}
								
							default:
								$callback_class = $GLOBALS['TL_DCA'][$table]['list']['sorting']['child_record_callback'][0];
								$callback_func = $GLOBALS['TL_DCA'][$table]['list']['sorting']['child_record_callback'][1];
								$this->import($callback_class);
								$label = $this->{$callback_class}->$callback_func($row);
							
								preg_match('((.*?)(</div>|<br[ /]*>))', $label, $match);
								$label = strip_tags($match[0]);
								break;
						}
					}
					$level['label'] = $label;
				}
				
				$levels[] = $level;
			}
			
			krsort($levels);
		}

		echo '<a href="typolight/main.php" class="navigation home" title="Landkarten" style="background-image:url(\'system/modules/zz_bebreadcrumb/html/home.gif\');" onclick="this.blur();">Home</a>';
		
		
		if (strlen($do))
		{
			$icon = $GLOBALS['BE_MOD'][$this->findBackendModuleGroup($do)][$do]['icon'];
			if (strlen($icon))
				$style = ' style="background-image: url('.$icon.')"';
			
			$href = 'typolight/main.php?do='.$do;
			echo ' &raquo; <a href="'.$href.'" class="navigation '.$do.'"'.$style.'>'.$GLOBALS['TL_LANG']['MOD'][$do][0].'</a>';
			
			if (count($levels))
			{
				foreach( $levels as $level )
				{
					$style = '';
					switch( $level['table'] )
					{
						case 'tl_page':
							$style = ' style="background:url(system/themes/default/images/'.$row['type'].'.gif) no-repeat left center; padding-left: 20px"';
							break;
							
						case 'tl_article':
							$style = ' style="background:url(system/themes/default/images/articles.gif) no-repeat left center; padding-left: 20px"';
							break;
					}
				
					// Level is part of this module
					if (strlen($level['do']))
					{
						$href = $this->addToUrl($level['href'].'&amp;id='.$level['id'], $href);
						echo ' &raquo; <a href="'.$href.'"'.$style.'>'.$level['label'].'</a>';
					}
					
					// Level is not part of this module. Do not link.
					else
					{
						echo ' &raquo; <span'.$style.'>'.$level['label'].'</span>';
					}
				}
			}
		}
	}
	
	/**
	 * Find a particluar backend module
	 */
	protected function findBackendModuleGroup($strModule)
	{
		foreach($GLOBALS['BE_MOD'] as $moduleGroup => $modules)
		{
			if (in_array($strModule, array_keys($modules)))
				return $moduleGroup;
		}
		
		return false;
	}
	
	/**
	 * Overwrite parent method to allow any URL
	 * @param string
	 * @return string
	 */
	protected function addToUrl($strRequest, $strUrl='')
	{
		$queryString = explode('?', $strUrl);
		$queryString = $queryString[1];
		
		$strRequest = preg_replace('/^&(amp;)?/i', '', $strRequest);
		$queries = preg_split('/&(amp;)?/i', $queryString);

		// Overwrite existing parameters
		foreach ($queries as $k=>$v)
		{
			$explode = explode('=', $v);

			if (preg_match('/' . preg_quote($explode[0], '/') . '=/i', $strRequest))
			{
				unset($queries[$k]);
			}
		}

		$href = '?';

		if (count($queries) > 0)
		{
			$href .= implode('&amp;', $queries) . '&amp;';
		}
		
		return $this->Environment->base . 'typolight/main.php' . $href . str_replace(' ', '%20', $strRequest);
	}

}

