<?php

namespace dsda\catcher;

class catcher {

	function __construct($config){
		error_reporting(0);
		set_error_handler(array($this,'errorHandler'));
		register_shutdown_function(array($this,'fatalErrorShutdownHandler'));
		$this->config = $config;
	}

	function errorHandler($code, $message, $file, $line) {
		$this->debug(array('Error'=>$code,'Message'=>$message,'In file'=>$file,'On line'=>$line));
		exit();
	}

	function fatalErrorShutdownHandler() {
		$last_error = error_get_last();
		if ($last_error['type'] === E_ERROR) {
			$this->errorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
		}
	}

	// Example: throw new Exception('Division by zero');
	function debug($smth,$vardump=false,$backtrace=true){
		echo "<pre>";
		echo "================== DEBUG MESSAGE ==================\n";
		
		if (!$vardump) {
			print_r($smth);
		} else {
			var_dump($smth);
		}
	
		if ($backtrace==true) {
			echo "\n\n";
			echo "================== BACKTRACE ==================\n";
			print_r(debug_backtrace());
		}
		echo "</pre>";
	}

}
?>
