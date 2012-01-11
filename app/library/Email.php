<?php
class Email{

	protected $_to      = NULL;
	protected $_cc      = NULL;
	protected $_bcc     = NULL;
	protected $_from    = NULL;
	protected $_content = NULL;
	protected $_subject = NULL;

	public function setSubject($subject){
		$this->_subject = $subject;
	}
	public function setFrom($from){
		$this->_from = $from;
	}

	public function addTo($to){
		$this->_to[] = $to;
	}

	public function addCC($cc){
		if( !isset($this->_cc[$cc]))
			$this->_cc[$cc] = $cc;
	}

	public function addBCC($bcc){
		$this->_bcc [] = $bcc;
	}

	public function setContent($html){
		$this->_content = $html;
	}

	/**
	 * This is an alias
	 * @param unknown_type $html
	 */
	public function setContet($html){
		$this->setContent($html);
	}

	public function send(){
		
		$to = is_array( $this->_to ) ? implode(",", $this->_to) : $this->_to;
		$cc = is_array($this->_cc) ? implode(",", $this->_cc) : NULL;
		$bcc = is_array($this->_bcc) ? implode(",", $this->_bcc) : NULL;
		$this->_subject;
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf8' . "\r\n";

		// Additional headers
		$headers .= "From:  {$this->_from}\r\n";
		$cc  && $headers .= "Cc: {$cc}\r\n";
		$bcc && $headers .= "Bcc: {$bcc}\r\n";
		$success = FALSE;
		$db = DbAdapter::instance();
		
		$this->_content = mysql_escape_string($this->_content);
		
		$sql = "INSERT INTO queue_dq(id_user, sender, recipient, body, subject,create_time,time_to_send,headers) VALUES(1, '{$this->_from}', '{$to}', '{$this->_content}', '{$this->_subject}',NOW(),NOW(),'{$headers}')";
		
		$success = TRUE && $db->query($sql);
		return $success;
	}
}