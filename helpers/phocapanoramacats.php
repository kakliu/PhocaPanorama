<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
class PhocaPanoramaCatsHelper
{
	public static function getActions($t, $id = 0) {
		$user		= JFactory::getUser();
		$result		= new JObject;
		
		if (empty($id)) {
			$assetName = $t['o'];
		} else {
			$assetName = $t['o'].'.'.$t['tasks'].'.'.(int) $id;
		}
		$actions 	= array('core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete');

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}
		return $result;
	}
}
?>