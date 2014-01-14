<?php
class kIsmIndexEventsConsumer implements kBatchJobStatusEventConsumer
{	

	/* (non-PHPdoc)
	 * @see kBatchJobStatusEventConsumer::shouldConsumeJobStatusEvent()
	 */
	public function shouldConsumeJobStatusEvent(BatchJob $dbBatchJob)
	{
		if(	$dbBatchJob->getStatus() == BatchJob::BATCHJOB_STATUS_FINISHED
			&& $dbBatchJob->getJobType() == BatchJobType::CONVERT
			&& $dbBatchJob->getJobSubType() == IsmIndexPlugin::getApiValue(IsmIndexConversionEngineType::ISM_MANIFEST)
		)
			return true;
		else	
			return false;
	}

	/* (non-PHPdoc)
	 * @see kBatchJobStatusEventConsumer::updatedJob()
	 */
	public function updatedJob(BatchJob $dbBatchJob)
	{ 
		// replacing the ismc file name in the ism file
		$ismcOldName = null;

		$fileSyncDescriptors = $dbBatchJob->getData()->getExtraDestFileSyncs();		
		foreach ($fileSyncDescriptors as $fileSyncDescriptor) 
		{
			if($fileSyncDescriptor->getFileSyncObjectSubType() == flavorAsset::FILE_SYNC_ASSET_SUB_TYPE_ISMC)
			{
				$ismcOldName = basename($fileSyncDescriptor->getFileSyncLocalPath());
				break;
			}
		}		
		$flavorAsset = assetPeer::retrieveById($dbBatchJob->getFlavorAssetId());
		if($flavorAsset && $ismcOldName)
		{		
			$ismFileSyncKey = $flavorAsset->getSyncKey(flavorAsset::FILE_SYNC_ASSET_SUB_TYPE_ISM);
			$ismcFileSyncKey = $flavorAsset->getSyncKey(flavorAsset::FILE_SYNC_ASSET_SUB_TYPE_ISMC);
			
			$ismcNewName = basename(kFileSyncUtils::getLocalFilePathForKey($ismcFileSyncKey));
			KalturaLog::debug("Editing ISM [$ismcOldName] to [$ismcNewName]");
			
			$ismPath = kFileSyncUtils::getLocalFilePathForKey($ismFileSyncKey);
			$ismContent = file_get_contents($ismPath);
			$ismContent = str_replace("content=\"$ismcOldName\"", "content=\"$ismcNewName\"", $ismContent);
	
			$bytesWritten = file_put_contents($ismPath, $ismContent);
			if(!$bytesWritten)
				KalturaLog::err("Failed to update file [$ismPath]");
		}
		else
		{
			KalturaLog::err("Failed to replace ismc old path in ism manifest");
		}
			
		return true;
	}

}