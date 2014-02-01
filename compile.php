<?php

$dataFiles = scandir('data/');
$logoFiles = scandir('logo/');
$flagFiles = scandir('country-flag/');

$output = array(
	'data' => array(),
	'logo' => array(),
	'flag' => array()
);

foreach ($dataFiles as $filename) {
	if (preg_match('/.+\.json$/i', $filename) === 1) {
		$ppdata = file_get_contents(sprintf('%s/data/%s', __DIR__, $filename));
		$ppdata = json_decode($ppdata);

		$output['data'][$ppdata->partyCode] = $ppdata;
	}
}

foreach ($logoFiles as $filename) {
	if (preg_match('/(.+)\.(png|jpg)$/i', $filename, $matches) === 1) {
		$output['logo'][strtoupper($matches[1])] = sprintf('%s/logo/%s', __DIR__, $filename);
	}
}
foreach ($flagFiles as $filename) {
	if (preg_match('/(.+)\.(png|jpg)$/i', $filename, $matches) === 1) {
		$output['flag'][strtoupper($matches[1])] = sprintf('%s/country-flag/%s', __DIR__, $filename);
	}
}

//
// This is used to more easily parse the data when importing into the API
// 
// API import script will include this file, with the defined constant. This way
// the data is returned into a PHP variable, not requiring the creation of the file.
// 
if(defined('INCLUDE_RETURN_DATA')) {
	return $output;
} else {
    file_put_contents('compiled_data.json', json_encode($output, JSON_PRETTY_PRINT));
}

