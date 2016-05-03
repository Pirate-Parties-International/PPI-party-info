<?php

namespace Pirates\PapiInfo;

use Seld\JsonLint\JsonParser;
use Seld\JsonLint\Parsing\Exception;

/**
* Verification code used by 
*/
class Verify
{
	public function verifySyntax($string) {
		$parser = new JsonParser();

		try {
        	$return = $parser->parse($string);
        } catch (\Seld\JsonLint\ParsingException $e) {
            return $e->getMessage();
        }
        return true;
	}

	public function verifyCountry($json) {
		if (empty($json['countryCode'])) {
			return self::requiredMsg('countryCode');
		}

		if (empty($json['country'])) {
			return self::requiredMsg('country');
		}

		return true;
	}

	public function verifyPartyName($json) {
		if (empty($json['partyName'])) {
			return self::requiredMsg('partyName');
		}

		if (empty($json['partyName']['en'])) {
			return "Field 'partyName' requires an english entry.";
		}

		foreach ($json['partyName'] as $key => $value) {
			if (preg_match('/.+&&.+/', $value) === 1) {
				return "Multiple official names in 'partyName' should have seperate entries and not be concated with '&&'.";
	        }
		}

		return true;
	}

	public function verifyType($json) {
		if (empty($json['type'])) {
			return self::requiredMsg('type');
		}

        switch ($json['type']) {
            case 'national':
                break;
            case 'regional':
            case 'local':
            case 'youth':
                if (empty($json['parentorganisation'])) {
                    return "For this type of organisation field 'parentorganisation' is required";
                }
                break;
            default:
                return "Field 'type' has an invalid value. Must be one of: national, regional, local, youth.";
        }
	
		return true;            
	}

	public function verifyPartyCode($json) {
		if (empty($json['partyCode'])) {
			return self::requiredMsg('partyCode');
		}

		if ($json['type'] == 'national') {
            if (preg_match('/PP[A-Z]{2,3}/', $json['partyCode']) !== 1) {
                return "Field 'partyCode' is incorectly formated.";
            }
        } else if ($json['type'] == 'youth') {
            if (preg_match('/YP[A-Z]{2,3}/', $json['partyCode']) !== 1) {
                return "Field 'partyCode' is incorectly formated";
            }
        } else {
            if (preg_match('/PP[A-Z]{2,3}-.+/', $json['partyCode']) !== 1) {
                return "Field 'partyCode' is incorectly formated";
            }
        }

        return true;
	}

	public function verifyWebsite($json) {
		if (empty($json['websites'])) {
			return self::requiredMsg('websites');
		}
		if (empty($json['websites']['official']) && $json['websites']['official'] !== null) {
			return self::requiredMsg('websites/official');
		}
		foreach ($json['websites'] as $key => $url) {
			if ($url === null) continue;
			if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
			    continue;
			} else {
			    return "Field 'websites' at key '$key' has an invalid URL.";
			}
		}
		return true;
	}

	//
	// Not required
	//

	public function verifyParentOrg($json) {
		if (!empty($json['parentorganisation'])) {
			if (preg_match('/PP[A-Z]{2,3}/', $json['parentorganisation']) !== 1) {
                return "Parent organisation should be a national party.";
            }
		}
		return true;		
	}

	public function verifyHeadquarters($json) {
		if (!empty($json['headquarters'])) {
			if (empty($json['headquarters']['coordinates'])) {
				return "Field 'headquarters' requires subfield 'coordinates'.";
			}
		}

		return true;
	}

	public function verifySocialNetworks($json) {
		if (empty($json['socialNetworks'])) {
			return true;
		}
		$s = $json['socialNetworks'];

		if (!empty($s['facebook'])) {
			if (empty($s['facebook']['username'])) {
				return "Field 'socialNetworks', subfield 'facebook' is missing a required 'username' field";
			}
		}

		if (!empty($s['twitter'])) {
			if (empty($s['twitter']['username'])) {
				return "Field 'socialNetworks', subfield 'twitter' is missing a required 'username' field";
			}
		}

		return true;
	}

	public function verifyMembership($json) {
		if (empty($json['membership'])) {
			return true;
		}

		foreach ($json['membership'] as $org => $type) {
			if (is_string($type)) {
				$type = strtolower($type);
			}
			$org = strtolower($org);
			if ($org === 'ppeu' || $org === 'ppi' || $org === 'ype') {
				if ($type !== false && $type !== 'full' && $type !== 'observer') {
					return "Field 'membership' at key '$org' has an invalid value. Only full, observer or false are supported.";
				}
			} else {
				return "Field 'membership' has an invalid key. Only ppeu, ype and ppi are supported.";
			}
		}

		return true;
	}

	public function verifyContact($json) {
		return true;
	}

	public function verifyDefunct($json) {
		if (empty($json['defunct'])) {
			return true;
		}

		if (!is_bool($json['defunct'])) {
			return "Field 'defunct' must have a boolean value.";
		}

		return true;
	}

	//
	//
	// Support
	// 
	// 

	private function requiredMsg($fieldName) {
		return sprintf("Field '%s' is required.", $fieldName);
	}


}