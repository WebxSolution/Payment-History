<?php
/*------------------------------------------------------------------------
# Copyright (C) 2020 WebxSolution Ltd. All Rights Reserved.
# @license - GPLv2.0
# Author: WebxSolution Ltd
# Websites:  http://www.webxsolution.com
# Terms of Use: An extension that is derived from the JoomlaCK editor will only be allowed under the following conditions: http://joomlackeditor.com/terms-of-use
# ------------------------------------------------------------------------*/ 

defined('_JEXEC') or die('Restricted access');

class pkg_paymenthistoryInstallerScript
{
	
	private $system_plugins = array
	(
		'paymenthistory'
	);
		
	private $task_plugins = array
	(
		'paymenthistoryclean'
	);
	
		
	public function postflight($parent)
	{		
		//Publish system plugins
		
		$query = $db->getQuery(true);
		$query->update('#__extensions')
			->set('enabled = 1')
			->where('folder = '.$db->quote('system'))
			->where('element IN ('. $db->quote(implode($db->quote(','),$this->system_plugins),false).')');

		$db->setQuery($query);

		if(!$db->execute())
			$app->enqueueMessage( 'Failed to publish system plugins for Payment History package' );
		
		//Publish task plugins
		
		$query = $db->getQuery(true);
		$query->update('#__extensions')
			->set('enabled = 1')
			->where('folder = '.$db->quote('task'))
			->where('element IN ('. $db->quote(implode($db->quote(','),$this->task_plugins),false).')');

		$db->setQuery($query);

		if(!$db->execute())
			$app->enqueueMessage( 'Failed to publish task plugins for Payment History package' );
		
		
	}
}
	
	