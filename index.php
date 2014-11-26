<?php
/**
 * @param	string	$word	The string to be analysed
 * @return	string	$cgn_val	Represents Kölner Phonetik value
 * @access	public
 * @author	Mats Bähr
 * www.matsbaehr.de
 */

class CgnPhon
{
    static $letter_coding 	= 	['a' => '', 'e' => '', 'i' => '', 'o' => '', 'u' => '', 'j' => '', 
                            	'y' => '', 'b' => '1', 'f' => '3', 'v' => '3', 'w' => '3', 'g' => '4', 
                            	'k' => '4', 'q' => '4', 'l' => '5', 'm' => '6', 'n' => '6', 'r' => '7', 
                            	's' => '8', 'z' => '8', 't' => '2', 'p' => '1', 'x' => '48', 'd' => '2'];
    static $exc_not_before 	= 	['ph' => '3', 'dc' => '8', 'tc' => '8', 'ds' => '8', 'ts' => '8',
							 	'dz' => '8', 'tz' => '8', 'ca' => '4', 'ch' => '4', 'ck' => '4',
							  	'co' => '4', 'cq' => '4', 'cu' => '4', 'cx' => '4'];
	static $exc_not_after 	= 	['cx' => '8', 'kx' => '8', 'qx' => '8', 'sc' => '8', 'sz' => '8'];
	static $exc_anlaut 		= 	['cl' => '4', 'cr' => '4'];
    
    function getValue ($word) {
    
        $word = strtolower($word);
		$word = str_replace(['ä', 'ö', 'ü', 'ß'], ['a', 'o', 'u', 'ss'], $word);
        $cgn_val = '';
		
        for ($i=0;$i<strlen($word);$i++) {
			$exc_flag = 0;
			// First letter exceptions
			if ($word[$i] == 0 && isset(self::$exc_anlaut[$word[0] . $word[1]])) {
				$cgn_val .= self::$exc_anlaut[$word[0] . $word[1]];
			} else {
				// Exception for letter h
				if ($word[$i] == 'h') {
					$cgn_val .= '';
				} elseif (@isset(self::$exc_not_before[$word[$i] . $word[$i+1]])) {
					if ($word[$i] == 'c' && !in_array($word[$i-1], ['s', 'z'])) {
						$cgn_val .= '4';
					} else {
						// Not before ...
						$cgn_val .= self::$exc_not_before[$word[$i] . $word[$i+1]];
					}
					$exc_flag = true;
				} elseif (@isset(self::$exc_not_after[$word[$i-1] . $word[$i]])) {
					// Not after ...
					$cgn_val .= self::$exc_not_after[$word[$i-1] . $word[$i]];
					$exc_flag = true;
				} else {
					if ($exc_flag == false)	$cgn_val .= self::$letter_coding[$word[$i]];
				}
			}
        }
		
		// Delete double values
		for ($i=0;$i<strlen($cgn_val);$i++) {
			if ($cgn_val[$i] == @$cgn_val[$i+1])
				$cgn_val[$i] = '';
		}
		
		return $cgn_val;
    }
}
