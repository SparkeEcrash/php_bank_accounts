<?php

	require("BankAccount.php");
	
	class ISA extends BankAccount {
		public $TimePeriod = 28;
		public $AdditionalServices;

		// Constructor
		public function __construct($time, $service, $apr, $sc, $fn, $ln, $bal=0, $lock=false){
			$this->TimePeriod = $time;
			$this->AdditionalServices = $service;

			//properties that were inherited can be constructed right here
			parent::__construct($apr, $sc, $fn, $ln, $bal=0, $lock =false);
			//this is the same as super($apr, $sc, $fn, $ln, $bal=0, $lock =false) in javascript
		}
		
		//Methods
		public function WithDraw($amount) {
			$transDate = new DateTime();
			$lastTransaction = null;
			$length = count($this->Audit);
			for( $i = $length; $i > 0; $i-- ){
				$element = $this->Audit[$i-1];
				if($element[0] === "WITHDRAW ACCEPTED") {
					$days = new DateTime( $element[3]); 
					$lastTransaction = $days->diff($transDate)->format("%a");
					//format("%a") returns an integer with the difference in the number of days
					break;
				}
			}
			if( $lastTransaction === null && $this->Locked === false || $this->Locked === false && $lastTransaction > $this->TimePeriod){
				$this->Balance -= $amount;
				$this->Audit[] = ["WITHDRAW ACCEPTED", $amount, $this->Balance, $transDate->format('c')];
			} else {
				if($this->Locked === false) {
					$this->Balance -= $amount;
					$this->Audit[] = ["WITHDRAW ACCEPTED WITH PENALTY", $amount, $this->Balance, $transDate->format('c')];
					$this->Penalty();
				} else {
					$this->Audit[] = ["WITHDRAW DENIED", $amount, $this->Balance, $transDate->format('c')];
				}
				// echo parent::INFO;
				// echo parent::$stat;
				// echo parent::stat();
				//access static properties from the parent super class
			}
		}
		private function Penalty() {
			$transDate = new DateTime();
			$this->Balance -= 10;
			$this->Audit[] = ["WITHDRAW PENALTY", 10, $this->Balance, $transDate->format('c')];
		}
	};

	trait SavingsPlus {
		private $MonthlyFee = 20;
		public $Package = "holiday insurance";

		//Method...
		public function AddedBonus() {
			echo "Hello" . $this->FirstName . " " . $this->LastName . " for $" . $this->MonthlyFee . " a month you get" . $this->Package;
		}
	}

	interface AccountPlus {
		public function AddedBonus();
	}

	interface Savers {
		public function OrderNewBook();
		public function OrderNewDepositBook();
	}

	class Savings extends BankAccount implements AccountPlus, Savers {
		use SavingsPlus;
		public $PocketBook = [];
		public $DepositBook = [];

		//Constructor
		public function __construct($fee, $package, $apr, $sc, $fn, $ln, $bal=0, $lock=false) {
			//these properties are from the SavingsPlus trait
			$this->MonthlyFee = $fee;
			$this->Package = $package;

			//properties that were inherited can be constructed right here
			parent::__construct($apr, $sc, $fn, $ln, $bal=0, $lock =false);
			//this is the same as super($apr, $sc, $fn, $ln, $bal=0, $lock =false) in javascript
		}

		//Methods
		public function OrderNewBook() {
			$orderTime = new DateTime();
			$this->PocketBook[] = "Ordered new pocket book on: " . $orderTime->format('c');
		}
		public function OrderNewDepositBook(){
			$orderTime = new DateTime();
			$this->DepositBook[] = "Ordered new deposit book on: " . $orderTime->format('c');
		}
	}

	class Debit extends BankAccount implements AccountPlus {
		use SavingsPlus;
		private $CardNumber;
		private $SecurityCode;
		private $PinNumber;

		//Constructor
		public function __construct($fee, $package, $pin, $apr, $sc, $fn, $ln, $bal=0, $lock=false) {
			//these properties are from the SavingsPlus trait
			$this->MonthlyFee = $fee;
			$this->Package = $package;
			$this->PinNumber = $pin;

			//properties that were inherited can be constructed right here
			parent::__construct($apr, $sc, $fn, $ln, $bal=0, $lock =false);
			//this is the same as super($apr, $sc, $fn, $ln, $bal=0, $lock =false) in javascript

			$this->Validate();
		}

		//Methods
		private function Validate() {
			$valDate = new DateTime();
			$this->CardNumber = rand(1000, 9999) . "-" . rand(1000, 9999) . "-" . rand(1000, 9999) . "-" . rand(1000, 9999);
			$this->SecurityCode = rand(100, 999);
			$this->Audit[] = ["VALIDATED CAR", $valDate->format('c'), $this->CardNumber, $this->SecurityCode, $this->PinNumber];
		}

		public function ChangePin($newPin){
			$pinChange = new DateTime();
			$this->PinNumber = $newPin;
			$this->Audit[] = ["PIN CHANGED", $pinChange->format('c'), $this->PinNumber];
		}
	}
?>