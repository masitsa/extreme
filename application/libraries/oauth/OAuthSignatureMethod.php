<?php
// vim: foldmethod=marker

if(!class_exists("OAuthSignatureMethod") ) {
	class OAuthSignatureMethod {
	  public function check_signature(&$request, $consumer, $token, $signature) {
		$built = $this->build_signature($request, $consumer, $token);
		return $built == $signature;
	  }
	}
}
?>