<?php

class SphinxUtils
{
	const REPLACE_CHARS  = 'zzz'; 

	public static function escapeString($str, $escapeType = SearchIndexFieldEscapeType::DEFAULT_ESCAPE, $iterations = 2)
	{
		if($escapeType == SearchIndexFieldEscapeType::DEFAULT_ESCAPE)
		{
			// NOTE: it appears that sphinx performs double decoding on SELECT values, so we encode twice.
			//		" and ! are escaped once to enable clients to use them, " = exact match, ! = AND NOT
			//	This code could have been implemented more elegantly using array_map, but this implementation is the fastest
			$from 		= array ('\\', 		'"', 		'!',		'(',		')',		'|',		'-',		'@',		'~',		'&',		'/',		'^',		'$',		'=',		'_',		'%', 		'\'',		);
	
			if ($iterations == 2)
			{
				$toDouble   = array ('\\\\', 	'\\"', 		'\\!',		'\\\\\\(',	'\\\\\\)',	'\\\\\\|',	'\\\\\\-',	'\\\\\\@',	'\\\\\\~',	'\\\\\\&',	'\\\\\\/',	'\\\\\\^',	'\\\\\\$',	'\\\\\\=',	'\\\\\\_',	'\\\\\\%',	'\\\\\\\'',	);
				return str_replace($from, $toDouble, $str);
			}
			else
			{
				$toSingle   = array ('\\\\', 	'\\"', 		'\\!',		'\\(',		'\\)',		'\\|',		'\\-',		'\\@',		'\\~',		'\\&',		'\\/',		'\\^',		'\\$',		'\\=',		'\\_',		'\\%',		'\\\'',		);
				return str_replace($from, $toSingle, $str);
			}
		}
		elseif($escapeType == SearchIndexFieldEscapeType::STRIP)
		{
			$str = trim($str);
			
			if(substr($str, -2) == '\*')
				return preg_replace('/[^\w\d]/' , self::REPLACE_CHARS , substr($str, -2)) . '\\\*';
				
			return preg_replace('/([^\w\d]|_)/' , self::REPLACE_CHARS , $str);
		}
	}
}
