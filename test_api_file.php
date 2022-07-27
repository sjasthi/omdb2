<?php
include 'api_methods.php';

echo "<br>The length of a english string.<br>";
echo api_getLength('frenchtoast', 'English')."<br>";

echo "<br>The length of a telugu string.<br>";
echo api_getLength('సావిత్రి', 'Telugu')."<br>";

echo "<br>The length of a english string, no spaces.<br>";
echo api_getLengthNoSpaces('What about bob', 'English')."<br>";

echo "<br><br>Parsing to Logical Characters: <br>";
print_r(api_parseToLogicalCharacters('frenchtoast','English'))."<br>";

echo "<br><br>Parsing to Logical Characters, and getting the first letter in english: <br>";
echo api_parseToLogicalCharacters('frenchtoast','English')[0]."<br>";

echo "<br><br>Parsing to Logical Characters: <br>";
print_r(api_parseToLogicalCharacters('సావిత్రి','Telugu'))."<br>";

echo "<br><br>Parsing to Logical Characters, and getting the first letter in telugu: <br>";
echo api_parseToLogicalCharacters('సావిత్రి', 'Telugu')[0]."<br>";

echo "<br>Index of the first instance of a character in a given English string.<br>";
echo api_indexOf('frenchtoast','English', 't')."<br>";

echo "<br>Index of the first instance of a character in a given Telugu string.<br>";
echo api_indexOf('సావిత్రి','Telugu', 'వి')."<br>";

echo "<br>Does a given string contain a given english letter, 1 for yes, 0 for no?<br>";
echo api_containsChar('frenchtoast','English', '')."<br>";

echo "<br>Does a given string contain a given telugu letter, 1 for yes, 0 for no?<br>";
echo api_containsChar('సావిత్రి','English', 'వి')."<br>";


?>
