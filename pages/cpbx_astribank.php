<?php

foreach(glob(dirname(__FILE__)."/../includes/*.php") as $filename) {
	require_once($filename);
}

class cpbx_astribank extends page {

	public function __construct() {}
	private function ports() {
		$ports = [];
		return [
			[],
			['astribank' => 'XR0000'],
			['fxo' => 8, 'astribank' => 'XR0019', 'msrp' => '$265' ],
			['fxo' => 16, 'astribank' => 'XR0020', 'msrp' => '$730' ],
			['fxo' => 24, 'astribank' => 'XR0021', 'msrp' => '$1195' ],
			['fxo' => 32, 'astribank' => 'XR0022', 'msrp' => '$1660' ]
		];
	}

	protected function body() {
		$tpl = new template('cpbx_astribank');
		$tpl->ports = $this->ports();
		$tpl->render();
	}
}


?>
