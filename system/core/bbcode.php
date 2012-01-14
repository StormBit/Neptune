<?php
	/*
		Neptune Content Management System
		BBCode Engine - /system/core/bbcode.php

		Converts BBCode to HTML.
	*/

	function neptune_bbcode($text) {	
		$text = str_replace("<", "&lt;", $text);
		$text = str_replace(">", "&gt;", $text); 
			
		// Convert newlines to HTML newlines.
		$text = nl2br($text);
			
		$urlsearchstring = " a-zA-Z0-9\:\/\-\?\&\.\=\_\~\#\'";
			
		$mailsearchstring = $urlsearchstring . " a-zA-Z0-9\.@";

		$text = preg_replace("/\[url\]([$urlsearchstring]*)\[\/url\]/", '<a href="$1" target="_blank">$1</a>', $text);
		$text = preg_replace("(\[url\=([$urlsearchstring]*)\](.+?)\[/url\])", '<a href="$1" target="_blank">$2</a>', $text); 
				
		$text = preg_replace("(\[mail\]([$mailsearchstring]*)\[/mail\])", '<a href="mailto:$1">$1</a>', $text);
		$text = preg_replace("/\[mail\=([$mailsearchstring]*)\](.+?)\[\/mail\]/", '<a href="mailto:$1">$2</a>', $text);
			
		$text = preg_replace("(\[b\](.+?)\[\/b])is",'<span class="bold">$1</span>',$text);
		$text = preg_replace("(\[i\](.+?)\[\/i\])is",'<span class="italics">$1</span>',$text);
		$text = preg_replace("(\[u\](.+?)\[\/u\])is",'<span class="underline">$1</span>',$text);
		$text = preg_replace("(\[s\](.+?)\[\/s\])is",'<span class="strikethrough">$1</span>',$text);
		$text = preg_replace("(\[o\](.+?)\[\/o\])is",'<span class="overline">$1</span>',$text);
		$text = preg_replace("(\[color=(.+?)\](.+?)\[\/color\])is","<span style=\"color: $1\">$2</span>",$text);
		$text = preg_replace("(\[size=(.+?)\](.+?)\[\/size\])is","<span style=\"font-size: $1px\">$2</span>",$text);

		$text = preg_replace("/\[list\](.+?)\[\/list\]/is", '<ul class="listbullet">$1</ul>' ,$text);
		$text = preg_replace("/\[list=1\](.+?)\[\/list\]/is", '<ul class="listdecimal">$1</ul>' ,$text);
		$text = preg_replace("/\[list=i\](.+?)\[\/list\]/s", '<ul class="listlowerroman">$1</ul>' ,$text);
		$text = preg_replace("/\[list=I\](.+?)\[\/list\]/s", '<ul class="listupperroman">$1</ul>' ,$text);
		$text = preg_replace("/\[list=a\](.+?)\[\/list\]/s", '<ul class="listloweralpha">$1</ul>' ,$text);
		$text = preg_replace("/\[list=A\](.+?)\[\/list\]/s", '<ul class="listupperalpha">$1</ul>' ,$text);
		$text = str_replace("[*]", "<li>", $text);

		$text = preg_replace("(\[font=(.+?)\](.+?)\[\/font\])","<span style=\"font-family: $1;\">$2</span>",$text);

		$codelayout = '<table class="bbcode-block nostyle"><tr><td class="quotecodeheader"> Code:</td></tr><tr><td class="codebody">$1</td></tr></table>';
		$text = preg_replace("/\[code\](.+?)\[\/code\]/is","$codelayout", $text);
			
		$phplayout = '<table class="bbcode-block nostyle"><tr><td class="quotecodeheader"> Code:</td></tr><tr><td class="codebody">$1</td></tr></table>';
		$text = preg_replace("/\[php\](.+?)\[\/php\]/is",$phplayout, $text);

		$QuoteLayout = '<table class="bbcode-block nostyle"><tr><td class="quotecodeheader"> Quote:</td></tr><tr><td class="quotebody">$1</td></tr></table>';
		$QuoteLayout2 = '<table class="bbcode-block nostyle"><tr><td class="quotecodeheader">$1 wrote:</td></tr><tr><td class="quotebody">$2</td></tr></table>';
		while(preg_match("/\[quote=(.+?)\](.+?)\[\/quote\]/is", $text)) {
			$text = preg_replace("/\[quote=(.+?)\](.+?)\[\/quote\]/is","$QuoteLayout2", $text);
		}
		while(preg_match("/\[quote\](.+?)\[\/quote\]/is", $text)) {
			$text = preg_replace("/\[quote\](.+?)\[\/quote\]/is","$QuoteLayout", $text);
				}
	 
		$text = preg_replace("/\[img\](.+?)\[\/img\]/", '<img src="$1">', $text);
		$text = preg_replace("/\[img\=([0-9]*)x([0-9]*)\](.+?)\[\/img\]/", '<img src="$3" height="$2" width="$1">', $text);
			 
		return $text;
	}
?>