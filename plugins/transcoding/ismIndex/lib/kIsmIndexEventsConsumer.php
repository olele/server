<?php
class kIsmIndexEventsConsumer implements kObjectChangedEventConsumer
{	
	/* (non-PHPdoc)
	 * @see kObjectChangedEventConsumer::shouldConsumeChangedEvent()
	 */
	public function shouldConsumeChangedEvent(BaseObject $object, array $modifiedColumns)
	{
		if(
			$object instanceof flavorAsset
			&&	in_array(assetPeer::STATUS, $modifiedColumns)
			&&  ($object->getStatus() == flavorAsset::ASSET_STATUS_READY)
			&&  $object->hasTag(IsmIndexPlugin::ISM_MANIFEST_TAG)
			&&  !$object->getentry()->getStatus() == entryStatus::DELETED
			&& 	!$object->getentry()->getReplacingEntryId()
		)
			return true;
			
		return false;
	}

	/* (non-PHPdoc)
	 * @see kObjectChangedEventConsumer::objectChanged()
	 */
	public function objectChanged(BaseObject $object, array $modifiedColumns)
	{	
		// replacing the ismc file name in the ism file
		$ismFileSyncKey = $object->getSyncKey(flavorAsset::FILE_SYNC_ASSET_SUB_TYPE_ISM);
		$ismcFileSyncKey = $object->getSyncKey(flavorAsset::FILE_SYNC_ASSET_SUB_TYPE_ISMC);
			
		$ismcNewName = basename(kFileSyncUtils::getLocalFilePathForKey($ismcFileSyncKey));
		KalturaLog::debug("Editing ISM set content to [$ismcNewName]");
			
		$ismPath = kFileSyncUtils::getLocalFilePathForKey($ismFileSyncKey);
		$ismXml = new SimpleXMLElement(file_get_contents($ismPath));
		$ismXml->head->meta['content'] = $ismcNewName;
			
		$bytesWritten = file_put_contents($ismPath, $ismXml->asXML());
		if(!$bytesWritten)
			KalturaLog::err("Failed to update file [$ismPath]");
					
		return true;
	}

}