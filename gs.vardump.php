<?php

/**
 * gsVarDump: a pure PHP var_dump() replacement for unlimited levels deep
 * @author Ganjar Santoso (2014)
 * @link http://www.twitter.com/ganjarsantoso
 * @link https://github.com/ganjarsantoso/gsVarDump
 * @license The BSD 2-Clause License http://opensource.org/licenses/BSD-2-Clause
 */

class gsVarDump
{
	/**
	 * @var string $dumped Temporary store dump result
	 */
	private $dumped = '';
	
	/**
	 * @const string WHITE_SPACE : set the global white spaces
	 */
	const WHITE_SPACE = '&nbsp;&nbsp;';
	/**
	 * @const string SEPARATOR : set the global separator
	 */
	const SEPARATOR = '=>';
	/**
	 * @const string LIMIT : when reaching limit level deep (if set)
	 */
	const LIMIT = '...';
	
	/**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$dump = new gsVarDump();"
     */
	public function __construct($skin=null)
	{
		// check if the skin is used
		if (!empty($skin)) {
			// set the skin
			$this->useSkin($skin);
		}
	}
	
	/**
	 * Finalize the dump output by putting gs-var-dump css-class into div
	 */
	public function vardump($vardump, $limit=0, $htmlcode=true)
	{
		$this->dumped = '';
		return "<div class=\"gs-var-dump\">".$this->_dump($vardump, $limit, $htmlcode)."</div>";
	}
		
	/**
	 * Process the dump variable to match php var_dump()
	 */
	private function _dump($vardump, $limit=0, $htmlcode=true, $level=1, $onlevel=1)
	{
		// get the temporary dump result
		$result = $this->dumped;
		// get data type and make it to UPPERCASE
		$datatype = strtoupper(gettype($vardump));
		
		switch($datatype) {
			// in case data type is BOOLEAN
			case 'BOOLEAN'		: 
				// set bool status
				if ($vardump) $booltext = 'true'; else $booltext = 'false';
				$result = 
					$this->_useHtmlCode(gettype($vardump), "type", $htmlcode, "&nbsp;") . 
					$this->_useHtmlCode($booltext, "value boolean", $htmlcode, "<br>");
				break;
				
			// in case data type is INTEGER
			case 'INTEGER'		: 
				$result = 
					$this->_useHtmlCode("int", "type", $htmlcode, "&nbsp;") . 
					$this->_useHtmlCode($vardump, "value integer", $htmlcode, "<br>");
				break;
				
			// in case data type is DOUBLE or FLOAT
			case 'DOUBLE'		:
				// because the output value of gettype(float) is always double then set it to float when it's float
				// in case in the future float and double are different in php
				if (is_float($vardump)) $dtype = 'float'; else $dtype = gettype($vardump);
				$result = 
					$this->_useHtmlCode(gettype($vardump), "type", $htmlcode, "&nbsp;") . 
					$this->_useHtmlCode($vardump, "value double", $htmlcode, "<br>");
				break;
				
			// in case data type is STRING
			case 'STRING'		:
				$result = 
					$this->_useHtmlCode(gettype($vardump), "type", $htmlcode, "&nbsp;") . 
					$this->_useHtmlCode($vardump, "value string", $htmlcode, "&nbsp;") .
					$this->_useHtmlCode("(length=".mb_strlen($vardump).")", "size", $htmlcode, "<br>");
				break;
				
			// in case data type is ARRAY
			case 'ARRAY'		:
				// get array size
				$size = sizeof($vardump);
				$result = 
					$this->_useHtmlCode(gettype($vardump), "array", $htmlcode, "&nbsp;") . 
					$this->_useHtmlCode("(size={$size})", "size", $htmlcode, "<br>");
				
				// loop all the array datas
				foreach ($vardump as $key => $val) {
					// in case the array key is integer, omit the quote
					if (is_int($key)) $keyx = $key; else $keyx = "'{$key}'";
					// create white space to make nice look
					if ($htmlcode) $result .= str_repeat(self::WHITE_SPACE, $level);
					// if limit exceeded
					if ($limit>0) {
						if ($onlevel>$limit) {
							// write limit symbol
							if ($htmlcode) $result .= self::LIMIT.'<br>';
							break;
						}
					}
					
					// set the result
					$result .= 
						$this->_useHtmlCode(gettype($keyx), "value arraykey", $htmlcode, "&nbsp;") . 
						$this->_useHtmlCode(self::SEPARATOR, "separator", $htmlcode);
						
					// create white space to make nice look
					if (strtoupper(gettype($val))=='ARRAY' || strtoupper(gettype($val))=='OBJECT') {
						if ($htmlcode) {
							if ($htmlcode) $result .= '<br>';
							if ($htmlcode) $result .= str_repeat(self::WHITE_SPACE, ++$level);
						}
						// loop the function for multilevel array
						$result .= $this->_dump($val, $limit, $htmlcode, ++$level, ++$onlevel);
						$level--;
					} else {
						if ($htmlcode) $result .= '&nbsp;';
						// loop the function for multilevel array
						$result .= $this->_dump($val, $limit, $htmlcode, ++$level, ++$onlevel);
					}
					$onlevel--;
					$level--;
				}
				/*
				foreach ($vardump as $key => $val) {
					if (is_int($key)) $keyx = $key; else $keyx = "'{$key}'";
					if ($htmlcode) $result .= str_repeat(WHITE_SPACE, $level);
					$result .= 
						$this->_useHtmlCode(gettype($keyx), "value arraykey", $htmlcode, "&nbsp;") . 
						$this->_useHtmlCode(SEPARATOR, "separator", $htmlcode, "&nbsp;");
					$result .= $this->_dump($val, $limit, $htmlcode, ++$level, ++$onlevel);
					$level--;
				}
				*/
				break;
				
			// in case data type is OBJECT
			case 'OBJECT'		:
				// get size of object
				$sizeobj = count((array)$vardump)-1;
				$result = 
					$this->_useHtmlCode(gettype($vardump), "object", $htmlcode) . "(" .
					$this->_useHtmlCode(get_class($vardump), "objname", $htmlcode) . ")[" .
					$this->_useHtmlCode($sizeobj, "size class", $htmlcode) . "]";
				if ($htmlcode) $result .= "<br>";
				
				// get class name
				$classname = get_class($vardump);
				// loop all the object datas
				foreach ((array)$vardump as $obj => $val) {
					// get visibility of object variable
					if ($classname!=$obj) {
						if ($obj[1]=='*') {
							$objname = substr($obj,2);
							$visibility = 'protected';
						} elseif (strpos($obj, $classname)!==false) {
							$objname = substr($obj, mb_strlen($classname)+1);
							$visibility = 'private';
						} else {
							$objname = $obj;
							$visibility = 'public';
						}
					}
					
					// in case object name is integer, omit the quotes
					if (!is_int($objname)) $objname = "'{$objname}'";
					// create white space to make nice look
					if ($htmlcode) $result .= str_repeat(self::WHITE_SPACE, $level);
					// if limit exceeded
					if ($limit>0) {
						if ($onlevel>$limit) {
							if ($htmlcode) $result .= self::LIMIT.'<br>';
							break;
						}
					}
					
					// set the result
					$result .= 
						$this->_useHtmlCode($visibility, "visibility", $htmlcode, "&nbsp;") . 
						$this->_useHtmlCode($objname, "value arraykey", $htmlcode, "&nbsp;") . 
						$this->_useHtmlCode(self::SEPARATOR, "separator", $htmlcode);
						
					// create white space to make nice look
					if (strtoupper(gettype($val))=='ARRAY' || strtoupper(gettype($val))=='OBJECT') {
						if ($htmlcode) $result .= '<br>';
						if ($htmlcode) $result .= str_repeat(self::WHITE_SPACE, ++$level);
						// loop the function for multilevel object
						$result .= $this->_dump($val, $limit, $htmlcode, ++$level, ++$onlevel);
						$level--;
					} else {
						if ($htmlcode) $result .= '&nbsp;';
						// loop the function for multilevel object
						$result .= $this->_dump($val, $limit, $htmlcode, ++$level, ++$onlevel);
					}
					$onlevel--;
					$level--;
				}
				break;
				
			// in case data is NULL
			case 'NULL'			:
				$result = 
					$this->_useHtmlCode(strtolower(gettype($vardump)), "null", $htmlcode, "<br>");
				break;
				
			// in case data is RESOURCES
			case 'RESOURCE'		:
				$result = 
					$this->_useHtmlCode(gettype($vardump), "type", $htmlcode, "&nbsp;");
					$this->_useHtmlCode($vardump, "value resource", $htmlcode, "<br>");
				break;
				
			// in case of unknown data type
			case 'UNKNOWN TYPE'	:
				$result = "<span class=\"type\">".gettype($vardump)."</span>&nbsp;<span class=\"value unknown\">{$vardump}</span><br>";
					$this->_useHtmlCode(gettype($vardump), "type", $htmlcode, "&nbsp;");
					$this->_useHtmlCode($vardump, "value unknown", $htmlcode, "<br>");
				break;
		}
		// move result to temporary
		$this->dumped = $result;
		// return result
		return $result;
	}
	
	/**
	 * Generate html-styled in every output in span
	 */
	private function _useHtmlCode($display_item, $css_class='', $use_html=true, $html_add='')
	{
		if ($use_html) {
			if (!empty($css_class)) $css_class = "class=\"{$css_class}\"";
			$result = "<span ".$css_class.">".$display_item."</span>".$html_add;
		} else {
			$result = $display_item;
		}
		return $result;
	}
	
	/**
	 * Generate css-themes
	 */
	public function useSkin($skinPath)
	{
		echo '<link rel="stylesheet" type="text/css" href="'.$skinPath.'">';
		/*
		$handle = fopen($skinPath, 'r');
		$read = fread($handle, filesize($skinPath));
		fclose($handle);
		echo '<style type="text/css">'.$read.'</style>';
		*/
	}
}

// Direct function to gsVarDump
function gs_vardump($vardump, $limit=0, $use_htmlcode=true, $skin=false)
{
	$dump = new gsVarDump($skin);
	echo $dump->vardump($vardump, $limit, $use_htmlcode);
}