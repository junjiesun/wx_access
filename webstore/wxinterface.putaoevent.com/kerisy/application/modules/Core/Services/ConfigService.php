<?php
namespace App\Services;

class ConfigService
{
	protected $config = array();
	
	protected $ext_to_content_type_array = array();
	
	public function __construct()
	{

	}

	public function get( $key )
	{
		return $this->config[ $key ];
	}
	
}

?>
