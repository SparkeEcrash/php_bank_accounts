<?php
	require('SubClasses.php');

	// ISA
	$Account1 = new ISA(35, "holiday package", 5.0, "20-20-20", "Lawrence","Turton");
	$Account1->Deposit(1000);
	$Account1->Lock();
	$Account1->WithDraw(200);
	$Account1->Unlock();
	$Account1->WithDraw(159);

	// Savings Account
	$Account2 = new Savings(50, 'Cartoon Insurance', 12.0, "20-50-20", "Justin", "Dike");
	$Account2->Deposit(1000);
	$Account2->Lock();
	$Account2->WithDraw(200);
	$Account2->Unlock();
	$Account2->WithDraw(159);

	// $Account3->AddedBonus();
	$Account2->OrderNewBook();
	$Account2->OrderNewDepositBook();
	
	// Debit Account
	$Account3 = new Debit(30, "Spy Insurance", 1234, 12.0, "20-50-20", "Jason", "Bourne", "Spy Insurance");
	$Account3->Deposit(15000);
	$Account3->Lock();
	$Account3->WithDraw(200);
	$Account3->Unlock();
	$Account3->WithDraw(150);

	// $Account3->AddedBonus();
	// $Account3->Validate();
	// $Account3->ChangePin(1234);

	// echo json_encode($Account1);
	// echo json_encode($Account1, JSON_PRETTY_PRINT);
	// echo "<pre>" . print_r($Account1, true) . "</pre>";
	print_r($Account1);
	print_r($Account2); 
	print_r($Account3);

	// Array
	$AccountList = [$Account1, $Account2, $Account3];
	foreach( $AccountList as $Account ){
		//ask if a class implements a certain interface to check if certain methods can be run on that class instance
		$print = $Account->FirstName;
		if($Account instanceof AccountPlus){
			//run methods that are on the interface AccountPlus
			// $Account->AddedBonus();

			$print .= " AddedBonus()";
		}
		if($Account instanceof Savers){
			$print .= " OrderNewBook() OrderNewDepositBook()";
		}
		echo $print. "<br/>";
	}

	//access static or class level properties and methods without class instantiation that do not use $this because $this refers to instantiations of the class
	// print_r(BankAccount::INFO);
	// print_r(BankAccount::$stat);
	// print_r(BankAccount::stat());

	//access constants and static methods and properties from instances of classes as well
	echo $Account::INFO;
	echo $Account::$stat;
	echo $Account::stat();
	
?>