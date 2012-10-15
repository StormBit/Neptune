<?php
	/*
		Neptune Content Management System
		BBCode Engine - /system/core/bbcode.php

		Converts BBCode to HTML
	*/
	
	function neptune_bbcode($text, $filter_html = true) {	
	
		// HTML Whitelist
		$text = str_replace("<b>", "[b]", $text); 
		$text = str_replace("</b>", "[/b]", $text); 
		$text = str_replace("<i>", "[i]", $text); 
		$text = str_replace("</i>", "[/i]", $text); 
		$text = str_replace("<u>", "[u]", $text); 
		$text = str_replace("</u>", "[/u]", $text); 
		$text = str_replace("<o>", "&[o]", $text); 
		$text = str_replace("</o>", "[/o]", $text); 
		$text = str_replace("<s>", "[s]", $text); 
		$text = str_replace("</s>", "[/s]", $text); 
		$text = str_replace("<", "&lt;", $text);
		$text = str_replace(">", "&gt;", $text); 
		
		// Convert newlines to HTML newlines.
		$text = str_replace("\r\n","\n",$text);
		$text = str_replace("\n", "<br>\n\t\t\t\t\t", $text);

		$urlsearchstring = " a-zA-Z0-9\:\/\-\?\&\.\=\_\~\#\%\'";
			
		$mailsearchstring = $urlsearchstring . " a-zA-Z0-9\.@";

		$text = preg_replace("(\[url\=([$urlsearchstring]*)\ type=button](.+?)\[/url\])", '<a href="$1" class="btn">$2</a>', $text); 
		$text = preg_replace("(\[url\=([$urlsearchstring]*)\ type=button-primary](.+?)\[/url\])", '<a href="$1" class="btn btn-primary">$2</a>', $text); 	

		$text = preg_replace("/\[url\]([$urlsearchstring]*)\[\/url\]/", '<a href="$1">$1</a>', $text);
		$text = preg_replace("(\[url\=([$urlsearchstring]*)\](.+?)\[/url\])", '<a href="$1">$2</a>', $text); 

		$text = preg_replace("/\[urlnew\]([$urlsearchstring]*)\[\/urlnew\]/", '<a href="$1" target="_blank">$1</a>', $text);
    $text = preg_replace("(\[urlnew\=([$urlsearchstring]*)\](.+?)\[/urlnew\])", '<a href="$1" target="_blank">$2</a>', $text); 
		  
		$text = preg_replace("(\[mail\]([$mailsearchstring]*)\[/mail\])", '<a href="mailto:$1">$1</a>', $text);
		$text = preg_replace("/\[mail\=([$mailsearchstring]*)\](.+?)\[\/mail\]/", '<a href="mailto:$1">$2</a>', $text);
			
		$text = preg_replace("(\[b\](.+?)\[\/b])is", '<span class="bold">$1</span>',$text);
		$text = preg_replace("(\[i\](.+?)\[\/i\])is", '<span class="italics">$1</span>',$text);
		$text = preg_replace("(\[u\](.+?)\[\/u\])is", '<span class="underline">$1</span>',$text);
		$text = preg_replace("(\[s\](.+?)\[\/s\])is", '<span class="strikethrough">$1</span>',$text);
		$text = preg_replace("(\[o\](.+?)\[\/o\])is", '<span class="overline">$1</span>',$text);
		$text = preg_replace("(\[color=(.+?)\](.+?)\[\/color\])is", "<span style=\"color: $1\">$2</span>",$text);
		$text = preg_replace("(\[size=(.+?)\](.+?)\[\/size\])is", "<span style=\"font-size: $1px\">$2</span>",$text);

		$text = preg_replace("/\[list\](.+?)\[\/list\]/is", '<ul class="listbullet">$1</ul>' ,$text);
		$text = preg_replace("/\[list=1\](.+?)\[\/list\]/is", '<ul class="listdecimal">$1</ul>' ,$text);
		$text = preg_replace("/\[list=i\](.+?)\[\/list\]/s", '<ul class="listlowerroman">$1</ul>' ,$text);
		$text = preg_replace("/\[list=I\](.+?)\[\/list\]/s", '<ul class="listupperroman">$1</ul>' ,$text);
		$text = preg_replace("/\[list=a\](.+?)\[\/list\]/s", '<ul class="listloweralpha">$1</ul>' ,$text);
		$text = preg_replace("/\[list=A\](.+?)\[\/list\]/s", '<ul class="listupperalpha">$1</ul>' ,$text);
	
		$text = str_replace("[*]", "<li>", $text);

		$text = preg_replace("(\[font=(.+?)\](.+?)\[\/font\])", "<span style=\"font-family: $1;\">$2</span>",$text);

		$codelayout = '<div class="bbcode"><div class="codebody">$1</div></div>';
		$text = preg_replace("/\[code\](.+?)\[\/code\]/is","$codelayout", $text);
			
		$text = preg_replace("/\[php\](.+?)\[\/php\]/is",$codelayout, $text);

		$quotelayout =  '<div class="bbcode"><div class="quotecodeheader">Quote:</div><div class="quotebody">$1</div></div>';
		$quotelayout2 = '<div class="bbcode"><div class="quotecodeheader">$1 wrote:</div><div class="quotebody">$2</div></div>';
		while(preg_match("/\[quote=(.+?)\](.+?)\[\/quote\]/is", $text)) {
			$text = preg_replace("/\[quote=(.+?)\](.+?)\[\/quote\]/is","$quotelayout2", $text);
		}
		while(preg_match("/\[quote\](.+?)\[\/quote\]/is", $text)) {
			$text = preg_replace("/\[quote\](.+?)\[\/quote\]/is","$quotelayout", $text);
		}
	 
		$text = preg_replace("/\[img\](.+?)\[\/img\]/", '<img src="$1">', $text);
		$text = preg_replace("/\[img\=([0-9]*)x([0-9]*)\](.+?)\[\/img\]/", '<img src="$3" height="$2" width="$1">', $text);
			 		
		return $text;
	}
?>