<?php
if(!defined("DSAIYIN_SET")){exit("<h1>Access Denied</h1>");}
class session_default extends session
{
	public $path_dir = '';
	public function __construct($config)
	{
		parent::__construct($config);
		$this->config($config);
		if($this->path_dir){
			$this->save_path($this->path_dir);
		}
		$this->start();
	}

	public function config($config)
	{
		parent::config($config);
		$this->path_dir = $config['path'] ? $config['path'] : '';
	}
}