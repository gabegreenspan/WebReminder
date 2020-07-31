<?php
function escape($string) {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');

}
    /*
        Take a user input string, apply "htmlentities" PHP- function, explicitly 
        defines other options for security which sanitizes the string.  

        Escapes the string that is passed in.
        
        The htmlentities function converts characters to HTML entities. Prevents browsers from using characters as HTML elements. 

        ENT_QUOTES basically says to encode single and double quotes.ALTER

        UTF-8 is the default ASCII compatible 8bit unicode.
    
        */