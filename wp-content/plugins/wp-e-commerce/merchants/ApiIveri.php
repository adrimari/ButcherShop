<?php

Class ApiIveri
{
	private $url = "https://gateway.iveri.co.za/iVeriGateway/Gateway.aspx";
	
	private $CertificateID = "";
	private $ApplicationID = "";
	private $Mode="Test";
	private $MerchantID="001122";
	private $Currency="ZAR";
	
	private $DebugMode = false;
	
	function ProcessDebit($transactionData=null)
	{
		//************* Validation
		
		if (!isset($transactionData["MerchantReference"]))
			$transactionData["MerchantReference"]=null;
		
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
				
		//Fix the StartDate format
		if (isset($transactionData["StartDate"])) //Month, Year
		{
			$StartDate=explode("/",$transactionData["StartDate"]);
			
			if (sizeof($StartDate)==2)
				$transactionData["StartDate"] = date("mY",strtotime($StartDate[1]."/".$StartDate[0]."/01"));
		}
			
		if (isset($transactionData["PurchaseDate"]))
		{		
			$PurchaseDate=explode("/",$transactionData["PurchaseDate"]);
			
			if (sizeof($PurchaseDate)==3)  //Day, Month, Year
				$transactionData["PurchaseDate"] = date("mD",strtotime($ExpiryDate[2]."/".$ExpiryDate[1]."/01"));
			elseif (sizeof($PurchaseDate)==2)  //Month, Year
				$transactionData["PurchaseDate"] = date("mD",strtotime($ExpiryDate[1]."/".$ExpiryDate[0]."/01"));
				
		}
		
		//Check the BudgetPeriod
		if (isset($transactionData["BudgetPeriod"]))
		{
			$BudgetPeriods=array(0,3,6,9,12,18,24,36);
			
			if (!in_array($transactionData["BudgetPeriod"],$BudgetPeriods))
				return array('Errors' => array('External' => 'Invalid BudgetPeriod ('.$transactionData["BudgetPeriod"].'); valid options include: '.implode(", ",$BudgetPeriods)));
		}
				
		//Check CreditCardNumber length is 16
		if ((strlen($transactionData["CreditCardNumber"])<12 || strlen($transactionData["CreditCardNumber"])>18) || !is_numeric($transactionData["CreditCardNumber"]))
				return array('Errors' => array('External' => 'Invalid card number'));
				
		//Check ExpiryDate length is 6
		if (!is_numeric($transactionData["ExpiryDate"]) || (strlen($transactionData["ExpiryDate"])!=6))
				return array('Errors' => array('External' => 'Invalid expiry date'));
			
		//Check MerchantReference exists
		if (empty($transactionData["MerchantReference"]))
				return array('Errors' => array('External' => 'Missing MerchantReference'));
			
		if (!isset($transactionData["Amount"]))
			$transactionData["Amount"]="1.00";
			
		//Format the amount and take out the decimal
		$transactionData["Amount"]=number_format($transactionData["Amount"],2,"","");
		
		//****************

		$XMLdata='
		<V_XML Version="2.0" Direction="Request" CertificateID="'.$this->CertificateID.'">
			<Transaction ApplicationID="'.$this->ApplicationID.'" Mode="'.$this->Mode.'" Command="Debit">
				<MerchantId>'.$this->MerchantID.'</MerchantId>
				<MerchantReference>'.$transactionData["MerchantReference"].'</MerchantReference>
				<PAN>'.$transactionData["CreditCardNumber"].'</PAN>
				<ExpiryDate>'.$transactionData["ExpiryDate"].'</ExpiryDate>
				<Amount>'.$transactionData["Amount"].'</Amount>
				<Currency>'.$this->Currency.'</Currency>
				';
				
		if ($this->Is3dSecure()) {
			if (strlen($transactionData["Cavv"]) && strlen($transactionData["Xid"]))
				$XMLdata.='
					<ElectronicCommerceIndicator>ThreeDSecure</ElectronicCommerceIndicator>
					<CardHolderAuthenticationData>'.$transactionData["Cavv"].'</CardHolderAuthenticationData>
					<CardHolderAuthenticationID>'.$transactionData["Xid"].'</CardHolderAuthenticationID>
				';
		}
			
		if (isset($transactionData["CardSecurityCode"]))
			if (is_numeric($transactionData["CardSecurityCode"]))
				$XMLdata.='
						<CardSecurityCode>'.$transactionData["CardSecurityCode"].'</CardSecurityCode>
						';
						
		if (isset($transactionData["StartDate"]))
			if (is_numeric($transactionData["StartDate"]))
				$XMLdata.='
						<StartDate>'.$transactionData["StartDate"].'</StartDate>
						';

		if (isset($transactionData["PurchaseDate"]))
			if (is_numeric($transactionData["PurchaseDate"]))
				$XMLdata.='
						<PurchaseDate>'.$transactionData["PurchaseDate"].'</PurchaseDate>
						';

		if (isset($transactionData["PurchaseTime"]))
			if (is_numeric($transactionData["PurchaseTime"]))
				$XMLdata.='
						<PurchaseTime>'.$transactionData["PurchaseTime"].'</PurchaseTime>
						';
						
		if (isset($transactionData["BudgetPeriod"]))
			if (is_numeric($transactionData["BudgetPeriod"]))
				$XMLdata.='
						<BudgetPeriod>'.$transactionData["BudgetPeriod"].'</BudgetPeriod>
						';
						
		//Insert the CardDetails array
		if (isset($transactionData["CardDetails"]))
		{
			if (isset($transactionData["CardDetails"]["Association"]))
				$XMLdata.='
						<Association>'.$transactionData["CardDetails"]["Association"].'</Association>
						';
			if (isset($transactionData["CardDetails"]["CardType"]))
				$XMLdata.='
						<CardType>'.$transactionData["CardDetails"]["CardType"].'</CardType>
						';
			if (isset($transactionData["CardDetails"]["Jurisdiction"]))
				$XMLdata.='
						<Jurisdiction>'.$transactionData["CardDetails"]["Jurisdiction"].'</Jurisdiction>
						';
		}
						
		$XMLdata.='
			</Transaction>
		</V_XML>
		';

		//echo $XMLdata;exit;
		
		$params = array(
			"timeout"=>30
		);
		
		if ($this->DebugMode)
			echo htmlentities($XMLdata);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "");
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $XMLdata);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 4s
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
		//set_curl_options ($ch, $payment_parameters);
									   
		$data = curl_exec($ch);
		if (curl_error($ch)) {
			$error_message = curl_errno($ch) . " " . curl_error($ch);
		}
		curl_close($ch);
		
		$result = (array)simplexml_load_string($data);
		
		if ($this->DebugMode) 
			var_dump($result);
		
		unset($result["@attributes"]);
		
		if (empty($data))
			return array('Errors' => array('External' => 'Iveri could not be contacted. Please try back later.'));
		
		if (isset($result["Result"]))
			$result = (array)$result["Result"];
		else
			$result = (array)$result["Transaction"];
			
		$attributes = $result["@attributes"];
		$transactionResult = $result;
		$transactionResult["Result"]=(array)$transactionResult["Result"];

		if (!$attributes["Description"])
			$InternalError=0;
		else
		{
			$InternalError=$attributes["Description"];
			unset($attributes["Description"]);
		}
		
		unset($transactionResult["@attributes"]);
		unset($transactionResult["Result"]);
		
		$transactionMeta = (array)$result["Result"];
		unset($result);
		
		$attributes = $transactionMeta["@attributes"];
		
		if (!$attributes["Description"])
			$ExternalError=0;
		else
		{
			$ExternalError=$attributes["Description"];
			unset($transactionMeta["Description"]);
		}
		
		if ($ExternalError || $InternalError)
		{
			$Errors = array(
							 'Internal' => $InternalError,
							 'External' => $ExternalError
							);
		}
		else
			$Errors = 0;
		
		return array(
					 'Response' => $data,
					 'Meta' => $transactionMeta,
					 'Errors' => $Errors,
					 'Transaction' => $transactionResult
					 );
	}
	
	function Setup($Credentials)
	{
		if (isset($Credentials["CertificateID"]))
			$this->CertificateID=$Credentials["CertificateID"];
			
		if (isset($Credentials["ApplicationID"]))
			$this->ApplicationID=$Credentials["ApplicationID"];
			
		if (isset($Credentials["MerchantID"]))
			$this->MerchantID=$Credentials["MerchantID"];
	}
	
	function Currency($code)
	{
		$this->Currency=$code;
	}
	
	function Mode($mode)
	{
		if (!stristr($mode,"live"))
			$this->Mode="Test";
		else
			$this->Mode="Live";
	}
	
	function set3dSecure($bool) {
		$this->ThreeDSecure=(bool)$bool;
	}
	
	function Is3dSecure() {
		return $this->ThreeDSecure;
	}
	
	function Debug() {
		$this->DebugMode=true;
	}
}
	
?>