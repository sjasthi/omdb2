<?php

	// For finding the length of string
	function api_getLength($string,$language){

		$jsonLength = "http://indic-wp.thisisjava.com/api/getLength.php?string=".urlencode($string)."&language=$language";
	    $jsonfile= file_get_contents($jsonLength);
	    $decoder = json_decode(strstr($jsonfile, '{'));
	    $result = intval($decoder->data);

	    return $result;
	}

	// For finding the length of string with no empty spaces counted
	function api_getLengthNoSpaces($string,$language){

		$jsonLength = "https://wpapi.telugupuzzles.com/api/getLengthNoSpaces.php?string=".urlencode($string)."&language=$language";
	    $jsonfile= file_get_contents($jsonLength);
	    $decoder = json_decode(strstr($jsonfile, '{'));
	    $result = intval($decoder->data);

	    return $result;
	}

	// Creates an array of the characters of a given string
	function api_parseToLogicalCharacters($string,$language){

		$jsonLength = "https://wpapi.telugupuzzles.com/api/parseToLogicalCharacters.php?string=".urlencode($string)."&language=$language";
	    $jsonfile= file_get_contents($jsonLength);
	    $decoder = json_decode(strstr($jsonfile, '{'));
	    $result = $decoder->data;

	    return $result;
	}

	// The first instance of a given character in a string
	function api_indexOf($string,$language,$location){

		$jsonLength = "https://wpapi.telugupuzzles.com/api/indexOf.php?input1=".urlencode($string)."&input2=$language&input3=$location";
	    $jsonfile= file_get_contents($jsonLength);
	    $decoder = json_decode(strstr($jsonfile, '{'));
	    $result = intval($decoder->data);

	    return $result;
	}

	// Checks if a character is in a given string.
	function api_containsChar($string,$language,$isPresent){

		$jsonLength = "https://wpapi.telugupuzzles.com/api/containsChar.php?input1=".urlencode($string)."&input2=$language&input3=$isPresent";
	    $jsonfile= file_get_contents($jsonLength);
	    $decoder = json_decode(strstr($jsonfile, '{'));
	    $result = intval($decoder->data);

	    return $result;
	}

?>
