<?php
/**
 * @package plugins.huluDistribution
 * @subpackage api.objects
 */
class KalturaHuluDistributionProfile extends KalturaConfigurableDistributionProfile
{
	/**
	 * @var string
	 */
	public $sftpHost;
	
	/**
	 * 
	 * @var string
	 */
	public $sftpLogin;
	
	/**
	 * @var string
	 */
	public $sftpPass;
	
	/**
	 * @var string
	 */
	public $seriesChannel;
	
	/**
	 * @var string
	 */
	public $seriesPrimaryCategory;
	
	/**
	 * @var KalturaStringArray
	 */
	public $seriesAdditionalCategories;
	
	/**
	 * @var string
	 */
	public $seasonNumber;
	
	/**
	 * @var string
	 */
	public $seasonSynopsis;
	
	/**
	 * @var string
	 */
	public $seasonTuneInInformation;
	
	/**
	 * @var string
	 */
	public $videoMediaType;
	
	/**
	 * @var bool
	 */
	public $disableEpisodeNumberCustomValidation;
	
	
	/*
	 * mapping between the field on this object (on the left) and the setter/getter on the object (on the right)  
	 */
	private static $map_between_objects = array 
	(
		'sftpHost',
		'sftpLogin',
		'sftpPass',
		'seriesChannel',
		'seriesPrimaryCategory',
		'seasonNumber',
		'seasonSynopsis',
		'seasonTuneInInformation',
		'videoMediaType',
		'disableEpisodeNumberCustomValidation'
	);
		 
	public function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	public function toObject($dbObject = null, $skip = array())
	{
		if (is_null($dbObject))
			return null;
			
		parent::toObject($dbObject, $skip);
		
		if (!is_null($this->seriesAdditionalCategories))
		{
			$seriesAdditionalCategoriesArray = array();
			foreach($this->seriesAdditionalCategories as $stringObj)
				$seriesAdditionalCategoriesArray[] = $stringObj->value;
				
			$dbObject->setSeriesAdditionalCategories($seriesAdditionalCategoriesArray);
		}
					
		return $dbObject;
	}
	
	public function fromObject ($source_object)
	{
		parent::fromObject($source_object);
		
		$this->seriesAdditionalCategories = KalturaStringArray::fromStringArray($source_object->getSeriesAdditionalCategories());
	}
}