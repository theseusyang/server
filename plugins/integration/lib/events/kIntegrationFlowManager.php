<?php
/**
 * @package plugins.integration
 * @subpackage lib.events
 */
class kIntegrationFlowManager implements kBatchJobStatusEventConsumer
{
	/* (non-PHPdoc)
	 * @see kBatchJobStatusEventConsumer::updatedJob()
	 */
	public function updatedJob(BatchJob $dbBatchJob)
	{
		kEventsManager::raiseEvent(new kIntegrationJobClosedEvent($dbBatchJob));
	}

	/* (non-PHPdoc)
	 * @see kBatchJobStatusEventConsumer::shouldConsumeJobStatusEvent()
	 */
	public function shouldConsumeJobStatusEvent(BatchJob $dbBatchJob)
	{
		if($dbBatchJob->getJobType() == IntegrationPlugin::getBatchJobTypeCoreValue(IntegrationBatchJobType::INTEGRATION) && in_array($dbBatchJob->getStatus(), BatchJobPeer::getClosedStatusList()))
		{
			return true;
		}
				
		return false;
	}
	
	public static function addintegrationJob($objectType, $objectId, kIntegrationJobData $data) 
	{
		$partnerId = kCurrentContext::getCurrentPartnerId();
		
		$batchJob = new BatchJob();
		$batchJob->setPartnerId($partnerId);
		$batchJob->setObjectType($objectType);
		$batchJob->setObjectId($objectId);
		
		if($objectType == BatchJobObjectType::ENTRY)
		{
			$batchJob->setEntryId($objectId);
		}
		elseif($objectType == BatchJobObjectType::ASSET)
		{
			$asset = assetPeer::retrieveById($objectId);
			if($asset)
				$batchJob->setEntryId($asset->getEntryId());
		}
		
		$jobType = IntegrationPlugin::getBatchJobTypeCoreValue(IntegrationBatchJobType::INTEGRATION);
		return kJobsManager::addJob($batchJob, $data, $jobType, $data->getProviderType());
	}
}