<?php

Class Api3dSecure
{
	private $urlDev = "http://msgtest.bankserv.co.za/maps/txns.asp";
	private $urlLive = "https://msgnedcor.bankserv.co.za/maps/txns.asp";
	private $ReturnURL="http://localhost/3dsecure/?authenticate";
	
	private $ProcessorID = "1000";
	private $Password = "DeMo123";
	private $Mode="Test";
	private $MerchantID="12345678";
	private $Currency="ZAR";
	
	private $CurrencyCodes = array(
		"ZAR" => "840"
	);
	
	private $DebugMode = false;
	
	private static $HTTP_Request_Link = "HTTP/Request.php";
	
	private $Parameter = array();
	
	private static $WARNINGS = array(
		350, 1001, 1002, 1051, 1055, 1060, 1085, 1120, 1130, 1140,
		1150, 1160, 1355, 1360, 1380, 1390, 1400, 1710, 1752, 1755,
		1789, 2001, 2003, 2006, 2007, 2009, 2010, 4000, 4020, 4240,
		4243, 4245, 4268, 4310, 4375, 4400, 4770, 4780, 4790, 4800,
		4810, 4820, 4930, 4951, 4963, 4965
	);

	private static $ERRORS = array(
		6000 => "General Error Communicating with MAPS Server" ,
		6010 => "Failed to connect() to server via socket connection" ,
		6020 => "Failed Parse of Response XML Message Returned From the MPI Server - Socket Communication" ,
		6030 => "Failed Parse of Response XML Message Returned From the MPI Server - HTTP Communication" ,
		6040 => "Failed Parse of Response XML Message Returned From the MPI Server - HTTPS Communication" ,
		6050 => "Failed to initialize socket connection" ,
		6060 => "Error Communicating with MAPS Server, No Response Message Received - Socket Communication" ,
		6070 => "The URL to the MAPS Server does not use a recognized protocol (https required)" ,
		6080 => "Error Communicating with MAPS Server, Error Response - HTTP Communication" ,
		6090 => "Error Communicating with MAPS Server, Error Response - HTTPS Communication" ,
		6100 => "Unable to Verify Trusted Server" ,
		6110 => "Unable to Establish a SSL Context" ,
		6120 => "Unable to Establish a SSL Connection" ,
		6130 => "Error extract the underlying file descriptor" ,
		6140 => "Error establishing Network Connection" ,
		6150 => "Error during SSL Read of Reponse Data" ,
		6160 => "Unable to Establish a Socket Connection for SSL connectivity" ,
		6170 => "Unable to capture a Socket for SSL connectivity" ,
		9999 => "CURRENCY AMOUNT ERROR: TWO DECIMALS NEEDED"
	);
	
	private $ContinueProcessing=false;
	private $Form=null;
	private $TransactionID=0;
	private $TempKey=0;
	
	function ProcessLookup($transactionData=null)
	{
		//************* Validation
		
		if (!isset($transactionData["CreditCardNumber"]))
			$transactionData["CreditCardNumber"]=null;
			
		if (!isset($transactionData["ExpiryDate"]))
			$transactionData["ExpiryDate"]=null;
	
		//Fix the ExpiryDate format
		{
			$ExpiryDate=explode("/",$transactionData["ExpiryDate"]); //Month, Year
			
			if (sizeof($ExpiryDate)==2)
				$transactionData["ExpiryDate"] = date("mY",strtotime($ExpiryDate[1]."/".$ExpiryDate[0]."/01"));
		}
		
		$err=array();
		
		//Check OrderNumber exists
		if (!is_numeric($transactionData["OrderNumber"]) || (!strlen($transactionData["ExpiryDate"])))
				$err[]['External'] = 'Invalid order number';
				
		//Check CreditCardNumber length is 16
		if ((strlen($transactionData["CreditCardNumber"])<12 || strlen($transactionData["CreditCardNumber"])>18) || !is_numeric($transactionData["CreditCardNumber"]))
				$err[]['External'] = 'Invalid card number';
				
		//Check ExpiryDate length is 6
		if (!is_numeric($transactionData["ExpiryDate"]) || (strlen($transactionData["ExpiryDate"])!=6))
				$err[]['External'] = 'Invalid expiry date';
				
		if (sizeof($err)) {
			if ($this->DebugMode) 
				var_dump($err);
			
			return array('Errors' => $err);
		}
			
		if (!isset($transactionData["Amount"]))
			$transactionData["Amount"]="1.00";
			
		//Format the amount and take out the decimal
		$transactionData["Amount"]=number_format($transactionData["Amount"],2,"","");
		
		//****************

		$XMLdata='
		<CardinalMPI>
			<MsgType>cmpi_lookup</MsgType>
			<Version>1.7</Version>
			<ProcessorId>'.$this->ProcessorID.'</ProcessorId>
			<MerchantId>'.$this->MerchantID.'</MerchantId>
			<TransactionPwd>'.$this->Password.'</TransactionPwd>
			<TransactionType>C</TransactionType>
			<Amount>'.$transactionData["Amount"].'</Amount>
			<CurrencyCode>'.$this->CurrencyCode().'</CurrencyCode>
			<OrderNumber>'.$transactionData["OrderNumber"].'</OrderNumber>
			<CardNumber>'.$transactionData["CreditCardNumber"].'</CardNumber>
			<CardExpMonth>'.$ExpiryDate[0].'</CardExpMonth>
			<CardExpYear>'.$ExpiryDate[1].'</CardExpYear>
		</CardinalMPI>
		';

		if ($this->DebugMode)
			echo htmlentities($XMLdata);
			
		//echo $XMLdata;exit;
		
		$params = array(
			"timeout"=>30
		);
		
		$XMLdata = trim(str_replace("\t",null,$XMLdata));
		
		if ($this->isDevMode()) $param["sandbox"]=true;
		
		$cmpi_msg = "cmpi_msg=".urlencode($XMLdata);
		
		$url=$this->urlDev;
		if ($this->isLiveMode())
			$url=$this->urlLive;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $cmpi_msg);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		//set_curl_options ($ch, $payment_parameters);
									   
		$data = curl_exec($ch);
		if (curl_error($ch)) {
			$error_message = curl_errno($ch) . " " . curl_error($ch);
		}
		curl_close($ch);
		
		$result = (array)simplexml_load_string($data);
		
		if ($this->DebugMode)
			var_dump($result);
		
		//exit;
		
		if (!strlen($data))
			return array('Errors' => array('External' => 'Bankserv could not be contacted. Please try back later.'));

		$InternalError = array();
		$ExternalError = array();
		
		if ($result["ErrorNo"]) {
			$ErrorNos=explode(", ",$result["ErrorNo"]);
			foreach ($ErrorNos AS $ErrorNo) {
				if (isset(self::$ERRORS[$ErrorNo]))
					$InternalError[]=self::$ERRORS[$ErrorNo];
				elseif (in_array($ErrorNo,self::$WARNINGS)) {
					$ExternalError[]=isset($result["ErrorDesc"])?$result["ErrorDesc"]:$ErrorNo;
				}
			}
		}
		
		$SessionID=null;
		if (isset($transactionData["SessionID"])) {
			$SessionID=$transactionData["SessionID"];
		}
		
		$ACSUrl=null;
		if (isset($result["ACSUrl"])) {
			if (empty($result["ACSUrl"]))
				$InternalError[]="Sorry, we could not transfer you to 3D secure. Please try back later.";
			else {
				$ACSUrl=$result["ACSUrl"];
				$Payload=$result["Payload"];
			}
		}
	
		$Form = null;
		if (sizeof($ExternalError) || sizeof($InternalError))
		{
			$Errors = array(
							 'Internal' => implode(", ",$InternalError),
							 'External' => implode(", ",$ExternalError)
							);
		}
		else {
			$Errors = 0;
			
			if ($ACSUrl) {
			
				$action="onload=\"document.frmLaunch.submit();\"";
				$button="<input type=\"submit\" value=\"Continue..\" />";
				
				if ($this->DebugMode)
					$action=null;
				else
					$button=null;
			
				$Form = "	
<HTML>
	<BODY {$action}>
		<FORM name=\"frmLaunch\" method=\"POST\" action=\"{$ACSUrl}\">
			<input type=hidden name=\"PaReq\" value=\"{$Payload}\">
			<input type=hidden name=\"TermUrl\" value=\"{$this->ReturnURL}\">
			<input type=hidden name=\"MD\" value=\"{$SessionID}\">
			{$button}
		</FORM>
	</BODY>
</HTML>
				";
				
				$this->Form=$Form;
				$this->ContinueProcessing=true;
			}
		}
		
		if (isset($result["TransactionId"])) {
			session_start();
			$_SESSION["ThreeDSecure_TransactionID"]=$this->TransactionID=$result["TransactionId"];
		}
		
		//exit;
		
		return array(
					 'Errors' => $Errors,
					 'Form' => $Form,
					 'Result' => $transactionResult
					 );
	}
	
	function ProcessAuthentication($transactionData=null)
	{
		
		$err=array();
		
		if (!isset($transactionData["TransactionID"]))
			$transactionData["TransactionID"]=0;
			
		if (!isset($transactionData["Key"]))
			$transactionData["Key"]=0;
		
		//Check TransactionID exists
		if (!strlen($transactionData["TransactionID"]) && !$this->TransactionID)
			$err[]['External'] = 'Invalid Transaction';
		elseif ($transactionData["TransactionID"])
			$this->TransactionID=$transactionData["TransactionID"];
				
		//Check PAResPayload exists
		if (!strlen($transactionData["Key"]) && !$this->TempKey)
			$err[]['External'] = 'Invalid Session';
		elseif ($transactionData["Key"])
			$this->TempKey=$transactionData["Key"];
				
		if (sizeof($err)) {
			if ($this->DebugMode) 
				var_dump($err);
			
			return array('Errors' => $err);
		}
		
		//****************

		$XMLdata='
		<CardinalMPI>
			<MsgType>cmpi_authenticate</MsgType>
			<Version>1.7</Version>
			<ProcessorId>'.$this->ProcessorID.'</ProcessorId>
			<MerchantId>'.$this->MerchantID.'</MerchantId>
			<TransactionPwd>'.$this->Password.'</TransactionPwd>
			<TransactionType>C</TransactionType>
			<TransactionId>'.$this->TransactionID.'</TransactionId>
			<PAResPayload>'.$this->TempKey.'</PAResPayload>
		</CardinalMPI>
		';

		if ($this->DebugMode) {
			echo $XMLdata."\n\n";
			echo htmlentities($XMLdata);
		}
			
		//echo $XMLdata;exit;
		
		$params = array(
			"timeout"=>30
		);
		
		$XMLdata = trim(str_replace("\t",null,$XMLdata));
		
		if ($this->IsDevMode()) $param["sandbox"]=true;
		
		$cmpi_msg = "cmpi_msg=".urlencode($XMLdata);
		
		$url=$this->urlDev;
		if ($this->isLiveMode())
			$url=$this->urlLive;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $cmpi_msg);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		//set_curl_options ($ch, $payment_parameters);
									   
		$data = curl_exec($ch);
		if (curl_error($ch)) {
			$error_message = curl_errno($ch) . " " . curl_error($ch);
		}
		curl_close($ch);
		
		$result = (array)simplexml_load_string($data);
		
		session_start();
		
		if ($this->DebugMode) {
			echo $data."\n\n";
			var_dump($result);
		}
		
		//exit;
		
		if (empty($data))
			return array('Errors' => array('External' => 'Bankserv could not be contacted. Please try back later.'));

		$InternalError = array();
		$ExternalError = array();
		
		$ErrorDescs=array();
		
		if ($result["ErrorNo"]) {
			$ErrorNos=explode(", ",$result["ErrorNo"]);
			foreach ($ErrorNos AS $ErrorNo) {
				$ErrorDesc=isset($result["ErrorDesc"])?$result["ErrorDesc"]:null;
				if (isset(self::$ERRORS[$ErrorNo]))
					$InternalError[]=self::$ERRORS[$ErrorNo];
				elseif ((in_array($ErrorNo,self::$WARNINGS) || $ErrorNo) && !in_array($ErrorDesc,$ErrorDescs)) {
					if ($ErrorDesc) $ErrorDescs[]=$ErrorDesc;
					$ExternalError[]=$ErrorDesc?$ErrorDesc:$ErrorNo;
				}
			}
		}
		
		$SessionID=null;
		if (isset($transactionData["SessionID"])) {
			$SessionID=$transactionData["SessionID"];
		}
		
		if (isset($result["PAResStatus"])) {
			if ($result["PAResStatus"]=="N")
				$ExternalError[]="Sorry, we could not validate you on 3D secure. Please try back later.";
		}		
		
		if (isset($result["SignatureVerification"])) {
			if (!sizeof($ExternalError) && $result["SignatureVerification"]=="N")
				$ExternalError[]="Sorry, we could not validate the 3D secure signature. Please try back later.";
		}
	
		if (sizeof($ExternalError) || sizeof($InternalError))
		{
			$Errors = array(
							 'Internal' => implode(", ",$InternalError),
							 'External' => implode(", ",$ExternalError)
							);
		}
		else {
			if (isset ($result["Xid"]))
				$transactionResult["Xid"]=(string)$result["Xid"];
				
			if (isset ($result["Cavv"]))
				$transactionResult["Cavv"]=(string)$result["Cavv"];
		
			$Errors = 0;
		}
		
		return array(
					 'Errors' => $Errors,
					 'Result' => $transactionResult
					 );
	}
	
	function Setup($Credentials)
	{
		if (isset($Credentials["ProcessorID"]))
			$this->ProcessorID=$Credentials["ProcessorID"];
			
		if (isset($Credentials["MerchantID"]))
			$this->MerchantID=$Credentials["MerchantID"];
			
		if (isset($Credentials["Password"]))
			$this->Password=$Credentials["Password"];
			
		if (isset($Credentials["ReturnURL"]))
			$this->ReturnURL=$Credentials["ReturnURL"];
	}
	
	function Currency($code)
	{
		$this->Currency=$code;
	}
	
	function setDevMode() { (bool) $this->Mode="Test"; }
	
	function setLiveMode() { (bool) $this->Mode="Live"; }
	
	function isDevMode() { return (bool) $this->Mode=="Test"; }
	function isLiveMode() { return (bool) $this->Mode=="Live"; }
	
	private function CurrencyCode() {
		return $this->CurrencyCodes[$this->Currency];
	}
	
	function Debug() {
		$this->DebugMode=true;
	}
	
	function setCurrency($curr) {
		if (isset($this->CurrencyCodes[$curr])) $this->Currency = $curr;
	}
	
	function getCurrency($curr) {
		return $this->Currency;
	}
	
	function canContinueProcessLookup() {
		return ($this->ContinueProcessing);
	}
	
	function RedirectForm() {
		if ($this->canContinueProcessLookup()) {
			echo $this->Form;
			exit;
		}
	}
	
	function canAuthenticate() {
		session_start();
		$bool = (isset($_POST["PaRes"]) && (isset($_SESSION["ThreeDSecure_TransactionID"]) || $this->TransactionID || isset($_POST["TransactionID"])));

		if ($bool) {
			$this->TempKey=$_POST["PaRes"];
			if (!$this->TransactionID) {
				if (isset($_SESSION["ThreeDSecure_TransactionID"])) 
					$this->TransactionID=$_SESSION["ThreeDSecure_TransactionID"];
				elseif (isset($_POST["TransactionID"]))
					$this->TransactionID=$_POST["TransactionID"];
			}
		}
		
		unset($_SESSION["ThreeDSecure_TransactionID"]);
		
		return $bool;
	}
}
	
?>