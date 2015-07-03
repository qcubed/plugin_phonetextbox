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

	protected function makeJqOptions() {
		$jqOptions = null;
		if (!is_null($val = $this->DefaultAreaCode)) {$jqOptions['defaultAreaCode'] = $val;}
		return $jqOptions;
	}

	public function GetEndScript() {
		$strRet = '';
		$strId = $this->getJqControlId();
		$jqOptions = $this->makeJqOptions();
		$strFunc = $this->getJqSetupFunction();

		$strParams = '';
		if (!empty($jqOptions)) {
			$strParams = JavaScriptHelper::toJsObject($jqOptions);
		}
		$strRet .= "\$j('#{$strId}').{$strFunc}({$strParams});"  . _nl();

		return $strRet . parent::GetEndScript();
	}


	public function getJqSetupFunction() {
		return 'phoneTextBox';
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
					$this->ValidationError = QApplication::Translate("Invalid phone number");
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


			case "Text":
			case "Value":
				parent::__set($strName, $mixValue);
				// Reformat after a change. Can't detect this kind of change just in JavaScript.
				QApplication::ExecuteControlCommand($this->getJqControlId(), $this->getJqSetupFunction(), 'checkChanged', QJsPriority::Low);
				break;


			default:
				try {
					parent::__set($strName, $mixValue);
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


	/**
	 * Required to allow this to be part of the code generation process. Must match with superclass.
	 * @return QTextBox_CodeGenerator
	 */
	public static function GetCodeGenerator () {
		return new QTextBox_CodeGenerator(get_class());
	}

	/**
	 * @return array|QModelConnectorParam[]
	 */
	public static function GetModelConnectorParams() {
		return array_merge(parent::GetModelConnectorParams(), array(
			new QModelConnectorParam (get_called_class(), 'DefaultAreaCode', '', QType::String)
		));
	}
}
?>