<?php
/**
 * Cool Codders
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category Cool Codders Extension
 * @package  CoolCodders_Base
 * @author   Raju S <rajus@coolcodders.com>
 * @license  OSL 3.0
 * @link     http://www.coolcodders.com
 */
namespace CoolCodders\Base\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Composer\ComposerInformation;

class ExtensionList
{
    const VENDOR_NAME = 'CoolCodders';

    /**
     * @var ReadFactory
     */
    private $readFactory;

    /**
     * @var DirectoryList
     */
    private $dir;

    /**
     * @var array
     */
    private $extension;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ComposerInformation
     */
    private $composerInformation;

    /**
     * ExtensionList constructor.
     *
     * @param DirectoryList $dir
     * @param Filesystem $filesystem
     * @param ReadFactory $readFactory
     * @param ComposerInformation $composerInformation
     */
    public function __construct(
        DirectoryList $dir,
        Filesystem $filesystem,
        ReadFactory $readFactory,
        ComposerInformation $composerInformation
    ) {
        $this->dir                 = $dir;
        $this->filesystem          = $filesystem;
        $this->readFactory         = $readFactory;
        $this->composerInformation = $composerInformation;
    }

    /**
     * Retrive the installed list of extension 
     * 
     * @return array|null
     */
    public function getInstalledExtensionList()
    {
        if ($this->extension === null) {
            try {
                $this->extension = $this->retriveExtensions();
                //array_merge($this->readLocalCodePath(), $this->readVendorPath());
            } catch (\Magento\Framework\Exception\FileSystemException $e) {
                return $this->extension = [];
            }
        }

        return $this->extension;
    }

    private function retriveExtensions()
    {
        $extensions = [];

        /* retrive extension list from the app/code directory */
        $path = $this->dir->getPath(DirectoryList::APP) . DIRECTORY_SEPARATOR . 'code' .
            DIRECTORY_SEPARATOR . self::VENDOR_NAME . DIRECTORY_SEPARATOR;
        $directoryRead = $this->readFactory->create($path);

        if ($directoryRead->isDirectory($path)) {
            try {
                $directories = $directoryRead->read();
                foreach ($directories as $directory) {
                    $directoryPath = $path . $directory . DIRECTORY_SEPARATOR;
                    if ($directoryRead->isDirectory($directoryPath) &&
                        $directoryRead->isExist($directoryPath . 'composer.json')
                    ) {
                        $composerJsonData = $directoryRead->readFile($directoryPath. 'composer.json');
                        $data             = json_decode($composerJsonData, true);
                        if (isset($data['type']) && isset($data['name'])) {
                            if (!isset($result[$data['name']])) {
                                $extensions[$data['name']] = $data;
                            }
                        }
                    }
                }
            } catch (\Magento\Framework\Exception\FileSystemException $e) {
            }
        }

        /* retrive installed extension list from vendor directory */
        $data   = $this->composerInformation->getInstalledMagentoPackages();
        $vendorName = strtolower(self::VENDOR_NAME);
        $path = $this->dir->getRoot(). DIRECTORY_SEPARATOR. 'vendor'. DIRECTORY_SEPARATOR. $vendorName . DIRECTORY_SEPARATOR;
        foreach ($data as $module) {
            if (strpos($module['name'], strtolower(self::VENDOR_NAME)) === 0) {
                $extensions[$module['name']] = $this->getVendorModuleDetails($module, $path);
            }
        }
        return $extensions;
    }

    public function getVendorModuleDetails($vendorData, $path)
    {
        $directoryRead = $this->readFactory->create($path);

        if ($directoryRead->isDirectory($path)) {
            try {
                $directories = $directoryRead->read();
                foreach ($directories as $directory) {
                    $directoryPath = $path . $directory . DIRECTORY_SEPARATOR;
                    if ($directoryRead->isDirectory($directoryPath) &&
                        $directoryRead->isExist($directoryPath . 'composer.json')
                    ) {
                        $composerJsonData = $directoryRead->readFile($directoryPath. 'composer.json');
                        $data = json_decode($composerJsonData, true);
                        if (isset($data['type']) && isset($data['name']) && $data['name'] == $vendorData["name"]) {
                            $vendorData = $data;
                        }
                    }
                }
            } catch (\Magento\Framework\Exception\FileSystemException $e) {
            }
        } else {
            $vendorData["description"] = '';
        }
        return $vendorData;
    }
}