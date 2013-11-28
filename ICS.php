<?php

use Illuminate\Filesystem\Filesystem;

class ICS
{
	/* * 
	 * Variables have the following structure:
	 * _        Scope (private and protected variables with underscore)
	 * (d) date/(s) string/(i) integer/(b) bool/(o) object  Initials for the type
	 * name     Name
	 */

	/**
	 * @var $_d_end
	 */
	private $_d_end;

	/**
	 * @var $_d_start
	 */
	private $_d_start;

	/**
	 * @var $_s_address
	 */
	private $_s_address;

	/**
	 * @var $_s_description
	 */
	private $_s_description;

	/**
	 * @var $_s_uri
	 */
	private $_s_uri;

	/**
	 * @var $_s_summary
	 */
	private $_s_summary;

	/**
	 * @var $_s_output
	 */
	private $_s_output;

	/**
	 * @var $_o_file
	 */
	private $_o_file;

	/**
	 * @var $_s_file_path
	 */
	private $_s_file_path;

	/**
	 * @var $_s_prodid
	 */
	private $_s_prodid;

	/**
	 * Create a new Invite instance.
	 *
	 * @param  string $prodid      Identifier (ex.: //Company//Product//Language)
	 * @param  array  $attributes  Predefined attributes
	 * @param  bool   $generate    Generate markup
	 * @return void
	 */
	public function __construct($prodid, array $attributes = array())
	{
		if ( ! is_string($prodid) || $prodid === '')
		{
			throw new Exception('PRODID is required');
		}

		$this->_o_file = new Filesystem();

		$this->_s_prodid = $prodid;

		foreach ($attributes as $key => $value)
		{
			$this->$key = $value;
		}
	}

	/**
	 * Get properties
	 *
	 * @param string $name startDate|endDate|address|summary|uri|description|path
	 * @param mixed  $value Variable assignment
	 * @return ICS
	 * @throws Exception If $name is not recognized
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'startDate':
				$this->_d_start = $value;
				break;

			case 'endDate':
				$this->_d_end = $value;
				break;

			case 'address':
				$this->_s_address = $value;
				break;

			case 'summary':
				$this->_s_summary = $value;
				break;

			case 'uri':
				$this->_s_uri = $value;
				break;

			case 'description':
				$this->_s_description = $value;
				break;

			case 'path':
				$this->_s_file_path = $value;
				break;
		}

		return $this;
	}

	/**
	 * Get properties
	 *
	 * @param string $name startDate|endDate|address|summary|uri|description|path
	 * @return mixed
	 * @throws Exception If $name is not recognized
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'startDate':
				return $this->_d_start;
				break;

			case 'endDate':
				return $this->_d_end;
				break;

			case 'address':
				return $this->_s_address;
				break;

			case 'summary':
				return $this->_s_summary;
				break;

			case 'uri':
				return $this->_s_uri;
				break;

			case 'description':
				return $this->_s_description;
				break;

			case 'path':
				return $this->_s_file_path;
				break;
		}
	}

	/**
	 * Return the string file output
	 * 
	 * @return string
	 */
	public function get()
	{
		($this->_s_output) ? $this->_s_output : $this->_generate();

		return $this->_s_output;
	}

	/**
	 * Save the output to a file
	 *
	 * @param  string    $path Path to file or directory
	 * @param  string    $name File name
	 * @return mixed
	 * @throws Exception If path param is not a valid directory
	 */
	public function save($path = '', $name = '')
	{
		$this->path = ($path !== '') ? $path : $this->path;

		$this->path .= ($name !== '') ? $name : $this->_createFileName();

		return $this->_o_file->put($this->path, $this->get());
	}

	/**
	 * Delete the current generated file
	 *
	 * @return bool
	 */
	public function delete()
	{
		return $this->_o_file->delete($this->path);
	}

	/**
	 * Create a default file name
	 *
	 * @return string
	 */
	private function _createFileName()
	{
		return date('Ymd', time()).'.ics';
	}

	/**
	 * Generate ICS markup
	 *
	 * @return void
	 */
	private function _generate()
	{
		$this->_s_output = "BEGIN:VCALENDAR\n".
			   "VERSION:2.0\n".
				 "PRODID:-".$this->_s_prodid."\n".
				 "METHOD:REQUEST\n".
				 "BEGIN:VEVENT\n".
				 "DTSTART:".$this->_dateToCal($this->startDate)."\n".
				 "DTEND:".$this->_dateToCal($this->endDate)."\n".
				 "SUMMARY:New ".$this->_escapeString($this->summary)."\n".
				 "LOCATION:".$this->_escapeString($this->address)."\n".
				 "DESCRIPTION:".$this->_escapeString($this->description)."\n".
				 "URL;VALUE=URI:".$this->_escapeString($this->uri)."\n".
				 "UID:".uniqid()."\n".
				 "SEQUENCE:0\n".
				 "DTSTAMP:".$this->_dateToCal(time())."\n".
				 "END:VEVENT\n".
				 "END:VCALENDAR\n";
	}

	/**
	 * Generate the specific date markup for a ics file
	 * 
	 * @param  integer $timestamp Timestamp to be transformed
	 * @return string
	 */
	private function _dateToCal($timestamp)
	{
		return date('Ymd\THis\Z', ($timestamp) ? $timestamp : time());
	}

	/**
	 * Escape characters
	 * 
	 * @param  string $string String to be escaped
	 * @return string
	 */
	private function _escapeString($string)
	{
		return preg_replace('/([\,;])/','\\\$1', ($string) ? $string : '');
	}

}