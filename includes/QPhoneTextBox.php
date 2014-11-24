<?php

/*********************************************************

QPhoneTextBox

Contributors:	Michael Ho, Shannon Pekary, Alex Weinstein

Installation: 	From Composer, require "qcubed/plugins/QPhoneTextBox": "dev-master"

This text box validates based on the North American phone format of (xxx) xxx-xxxx, and reformats the phone if it's entered differently. 

Blank items are allowed. If the user does not enter anything, then the area code will be removed so that
it will be blank

Usage example:

$defaultAreaCode = "650";
$txtPhone = new QPhoneTextBox ($this, $defaultAreaCode);
$txtBox->Name = 'Home Phone';
$txtBox->Text = $this->objPeople->HomePhone;

**********************************************************/

// We will use these when Composer goes to PSR-4
//namespace QCubed\Plugin;
//use \QTextBox;
	
class QPhoneTextBox extends QTextBox {

	/** @var string */
	protected $strDefaultAreaCode = null;	// set this to the default area code to enter in the box when the field is entered.
	
	
	public function __construct($objParentObject, $strControlId = null) {
		parent::__construct($objParentObject, $strControlId);
		
		$this->AddPluginJavascriptFile("phonetextbox", "jquery.phonetextbox.js");
	}
	
	protected function makeJsProperty($strProp, $strKey) {
		$objValue = $this->$strProp;
		if (null === $objValue) {
			return '';
		}

		return $strKey . ': ' . JavaScriptHelper::toJsObject($objValue) . ', ';
	}

	protected function makeJqOptions() {
		$strJqOptions = '';
		$strJqOptions .= $this->makeJsProperty('DefaultAreaCode', 'defaultAreaCode');
		if ($strJqOptions) $strJqOptions = substr($strJqOptions, 0, -2);
		return $strJqOptions;
	}


	public function getJqSetupFunction() {
		return 'phoneTextBox';
	}
	
	public function getControlJavaScript() {
		return sprintf('jQuery("#%s").%s({%s})', $this->getJqControlId(), $this->getJqSetupFunction(), $this->makeJqOptions());
	}

	public function GetEndScript() {
		return $this->getControlJavaScript() . '; ' . parent::GetEndScript();
	}
	
	
	public function Validate() {
		if (parent::Validate()) {
			$this->strText = trim ($this->strText);
			if ($this->strText != "") {
				if ($this->strDefaultAreaCode) {
					$pattern = "(\(||\[)?\d{3}(\)||\])?[-\s.]+\d{3}[-\s.]+\d{4}( x\d+)?$"; // standard phone
				} else {
					$pattern = "((\(||\[)?\d{3}(\)||\])?[-\s.]+)?\d{3}[-\s.]+\d{4}( x\d+)?$"; // optional area code
				}
					
				if (! preg_match("/$pattern/", $this->strText)) {
					$this->strValidationError = QApplication::Translate("Invalid phone number");
					return false;
				}
			}
		} else
			return false;

		$this->strValidationError = "";
		return true;
	}

	public function __set($strName, $mixValue) {
		switch ($strName) {
			case "DefaultAreaCode":
				try {
					return ($this->strDefaultAreaCode = QType::Cast($mixValue, QType::String));
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}

			default:
				try {
					return parent::__set($strName, $mixValue);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}
	
	public function __get($strName) {
		switch ($strName) {
			case "DefaultAreaCode": return $this->strDefaultAreaCode;

			default:
				try {
					return parent::__get($strName);
				} catch (QCallerException $objExc) {
					$objExc->IncrementOffset();
					throw $objExc;
				}
		}
	}

	public static function GetMetaParams() {
		return array_merge(parent::GetMetaParams(), array(
			new QMetaParam (get_called_class(), 'DefaultAreaCode', '', QType::String)
		));
	}
}
?>