<?php

namespace Bitrix\Landing\Assets;

use Bitrix\Main;
use Bitrix\Main\Web\WebPacker;

class WebpackBuilder extends Builder
{
	const PACKAGE_NAME_SUFFIX = '_webpack';
	const PACKAGE_CRITICAL_NAME = 'landing_grid';
	
	/**
	 * @var ResourceCollection
	 */
	protected $criticalResources;
	protected $normalizedCriticalResources = [];
	/**
	 * @var WebPacker\FileController
	 */
	protected $fileController;
	/**
	 * @var WebPacker\Package
	 */
	protected $package;
	
	public function __construct($resources)
	{
		parent::__construct($resources);
		$this->criticalResources = new ResourceCollection();
		$this->package = new WebPacker\Resource\Package();
		$this->fileController = new WebPacker\FileController();
	}
	
	public function setOutput()
	{
		if($this->resources->isEmpty())
		{
			return;
		}
		
		$this->normalizeResources();
		$this->setCriticalOutput();
		
		$this->fillPackageWithResources();
		$this->buildFileOfPackage();
		$this->setBaseOutput();
	}
	
	protected function normalizeResources()
	{
		$this->normalizeCriticalResources();
		$this->normalizeBaseResources();
	}
	
	protected function normalizeCriticalResources()
	{
		$this->criticalResources = $this->resources->getSliceByFilter(
			ResourceCollection::KEY_LOCATION, Location::LOCATION_BEFORE_ALL
		);
		$this->normalizedCriticalResources = $this->criticalResources->getNormalized();
	}
	
	protected function normalizeBaseResources()
	{
		$this->resources->remove($this->criticalResources->getPathes());
		$this->normalizedResources = $this->resources->getNormalized();
	}
	
	protected function setCriticalOutput()
	{
		$this->initResourcesAsJsExtension($this->normalizedCriticalResources, self::PACKAGE_CRITICAL_NAME);
	}
	
	protected function fillPackageWithResources()
	{
		foreach (Types::getAssetTypes() as $type)
		{
			if (array_key_exists($type, $this->normalizedResources))
			{
				foreach ($this->normalizedResources[$type] as $resource)
				{
					$this->package->addAsset(WebPacker\Resource\Asset::create($resource));
				}
			}
		}
	}
	
	protected function buildFileOfPackage()
	{
//		dbg: speed: cache
		$this->fileController->addExtension('ui.webpacker');    // need core ext always
		$this->fileController->addModule(new WebPacker\Module(self::PACKAGE_NAME, $this->package));
		$this->fileController->configureFile(
			'',
			self::MODULE_ID,
			self::FOLDER_NAME,
			self::PACKAGE_NAME . self::PACKAGE_NAME_SUFFIX . '.js'
		)->build();
	}
	
	protected function setBaseOutput()
	{
		$outString = $this->fileController->getLoader()->setSkipMoving(true)->getString();
		Main\Page\Asset::getInstance()->addString($outString);
	}
}