<?php
	require('../../../framework/qcubed.inc.php');

	class SampleForm extends QForm {
		protected $txtWorkPhone;
		protected $txtHomePhone;

		protected function Form_Create() {
			$defaultAreaCode = "650";
			$this->txtWorkPhone = new QPhoneTextBox($this);
			$this->txtWorkPhone->DefaultAreaCode = '650';
			$this->txtHomePhone = new QPhoneTextBox($this);
			$this->txtHomePhone->DefaultAreaCode = '650';
		}
	}

	SampleForm::Run('SampleForm');
?>