<?php

namespace dsda\catcher;

class catcher {

	private $config = null;
	
	function __construct($config=null){
		error_reporting(-1);
		set_error_handler(array($this,'errorHandler'));
		register_shutdown_function(array($this,'fatalErrorShutdownHandler'));
		$this->config = $config;
	}

	function errorHandler($code, $message, $file, $line) {
		$this->debug(array('Error'=>$code,'Message'=>$message,'In file'=>$file,'On line'=>$line));
		// exit(); // no need exit it's can be halted on @$_GET['qwe']...
	}

	function fatalErrorShutdownHandler() {
		$last_error = error_get_last();
		if ($last_error['type'] === E_ERROR) {
			$this->errorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
		}
	}

	function prepare_output($smth,$vardump=false,$backtrace=false){
		ob_start();
		echo "================== DEBUG MESSAGE ==================".PHP_EOL;
		echo date("Y-m-d H:i:s", time()).PHP_EOL;
		if (!$vardump) {
			print_r($smth);
		} else {
			var_dump($smth);
		}
	
		if ($backtrace==true) {
			echo PHP_EOL.PHP_EOL;
			echo "================== BACKTRACE ==================".PHP_EOL;
			print_r(debug_backtrace());
		}
		$log = ob_get_contents();
		ob_end_clean();
		return $log;
	}
	
	function log($smth,$vardump=false,$backtrace=false){
		if (($this->config!==null && $this->config->get('debug')==true) || $backtrace==true) {
			$backtrace=true;
		}
		$log = $this->prepare_output($smth,$vardump,$backtrace);
		if (php_sapi_name()!=='cli') {
			echo '<pre>'.$log.'</pre>';
		} else {
			echo $log;
		}
		
	}
	
	// Example: throw new Exception('Division by zero');
	function debug($smth,$vardump=false,$backtrace=false){
		if (($this->config!==null && $this->config->get('debug')==true) || $backtrace==true) {
			$backtrace=true;
		}
		$log = $this->prepare_output($smth,$vardump,$backtrace);
		if (!file_exists($this->config->get('app_data').'catcher')) mkdir($this->config->get('app_data').'/catcher');
		@file_put_contents($this->config->get('app_data').'catcher/catcher.txt', $log, FILE_APPEND);
	}

}
?>