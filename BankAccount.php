<?php

	abstract class BankAccount{
		const INFO = "Constant in BankAccount class <br/><br/>";
		//this constant is compiled to memory and will never change
		static public $stat = "static property string <br/><br/>";
		//a change in this static property will affect all instances of the classes that extend from this parent class

		protected $Balance = 0;
		public $APR;
		public $SortCode;
		public $FirstName;
		public $LastName;
		public $Audit = [];
		protected $Locked;

		//Set Static Method
		static public function stat() {
			//static functions cannot use $this but can use self:: to refer to the abstract class itself
			echo "This is the method static string ****" . self::INFO . self::$stat;
		}

		//Constructor
		public function __construct( $apr, $sc, $fn, $ln, $bal=0, $lock=false ) {
			//put $bal and $lock last because they are optional and have default values
			//this construct function will not get invoked because this class is abstract and meant to be inherited not instantiated
			$this->Balance = $bal;
			$this->APR = $apr;
			$this->SortCode = $sc;
			$this->FirstName = $fn;
			$this->LastName = $ln;
			$this->Locked = $lock;
		}

		//Methods
		public function WithDraw($amount) {
			$transDate = new DateTime();
			if( $this->Locked === false) {
				$this->Balance -= $amount;
				$this->Audit[] = ["WITHDRAW ACCEPTED", $amount, $this->Balance, $transDate->format('c')];
			} else {
				$this->Audit[] = ["WITHDRAW DENIED", $amount, $this->Balance, $transDate->format('c')];
			}
		}
		public function Deposit($amount) {
			$transDate = new DateTime();
			if( $this->Locked === false) {
				$this->Balance += $amount;
				$this->Audit[] = ["DEPOSIT ACCEPTED", $amount, $this->Balance, $transDate->format('c')];
			} else {
				$this->Audit[] = ["DEPOSIT DENIED", $amount, $this->Balance, $transDate->format('c')];
			}
		}
		public function Lock() {
			$this->Locked = true;
			$lockedDate = new DateTime();
			$this->Audit[] = ["Account Locked", $lockedDate->format('c')];
		}
		public function Unlock() {
			$this->Locked = false;
			$unlockedDate = new DateTime();
			$this->Audit[] = ["Account Unlocked", $unlockedDate->format('c')];
		}
	}

?>