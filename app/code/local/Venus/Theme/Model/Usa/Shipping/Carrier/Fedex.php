<?php
class Venus_Theme_Model_Usa_Shipping_Carrier_Fedex extends Mage_Usa_Model_Shipping_Carrier_Fedex {
	protected function _createSoapClient($wsdl, $trace = false) {
		$client = new SoapClient($wsdl, array('trace' => $trace));
		$client->__setLocation(
			$this->getConfigFlag('sandbox_mode')
				? 'https://wsbeta.fedex.com:443/web-services'
				: 'https://ws.fedex.com:443/web-services'
		);

		return $client;
	}
}
