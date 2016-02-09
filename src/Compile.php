<?php

namespace PPI\PapiInfo;

/**
* Used to compile data and pass it into other applications
*/
class Compile
{
	protected $rootPath;

	protected $dataFiles;

	protected $logoFiles;

	protected $flagFiles;

	function __construct() {
		 $this->rootPath = __DIR__ . '/../';

		 $this->dataFiles = scandir($this->rootPath . '/data/');

		 $this->logoFiles = scandir($this->rootPath . '/logo/');

		 $this->flagFiles = scandir($this->rootPath . '/country-flag/');
	}

	/**
	 * Returns all party data (json parsed into PHP objects&arrays)
	 * @return array
	 */
	public function getAllData() {
		$output = [];

		foreach ($this->dataFiles as $filename) {
			if (preg_match('/.+\.json$/i', $filename) === 1) {
				$ppdata = file_get_contents(sprintf('%s/data/%s', $this->rootPath, $filename));
				$ppdata = json_decode($ppdata);

				$output[$ppdata->partyCode] = $ppdata;
			}
		}

		return $output;
		
	}

	/**
	 * Returns compiled list of all National Flag images
	 * @return array 
	 */
	public function getFlagFiles() {
		$output = [];
		foreach ($this->flagFiles as $filename) {
			if (preg_match('/(.+)\.(png|jpg)$/i', $filename, $matches) === 1) {
				$output[strtoupper($matches[1])] = sprintf('%s/country-flag/%s', $this->rootPath, $filename);
			}
		}

		return $output;
	}

	/**
	 * Returns compiled list of all party logo images
	 * @return array
	 */
	public function getLogoFiles() {
		$output = [];
		foreach ($this->logoFiles as $filename) {
			if (preg_match('/(.+)\.(png|jpg)$/i', $filename, $matches) === 1) {
				$output[strtoupper($matches[1])] = sprintf('%s/logo/%s', $this->rootPath, $filename);
			}
		}

		return $output;
	}
}