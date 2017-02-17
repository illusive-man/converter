<?php
	
	function ConvertToText (float $numberToConvert): string
	{
		$Units =    ["ноль ","один ","два ","три ","четыре ","пять ","шесть ","семь ","восемь ","девять ","десять ",
					"одиннадцать ","двенадцать ","тринадцать ","четырнадцать ","пятнадцать ","шестандцать ",
					"семнадцать ","восемнадцать ","девятнадцать ","двадцать "];
		
		$Tens =     ["empty","десять ","двадцать ","тридцать ","сорок ","пятьдесят ",
					"шестьдесят ","семьдесят ","восемьдесят ","девяносто "];
		
		$Hundreds = ["empty","сто ","двести ","триста ","четыреста ","пятьсот ",
					"шестьсот ","семьсот ","восемьсот ","девятьсот "];
		
		$Registry = array(  array(0,"копейка ","копейки ","копеек "), //future functionality
							array(0,"рубль ","рубля ","рублей "),
							array(0,"тысяча ","тысячи ","тысяч "),
							array(0,"миллион ","миллиона ","миллионов "),
							array(0,"миллиард ","миллиарда ","миллиардов "),
							array(0,"триллион ","триллиона ","триллионов "));
		
		If ($numberToConvert < 0 || $numberToConvert > 99999999999999) {
			
			return $error = "Error: Input number should be between 1 and 99'999'999'999'999";
		}
		
		//Future functionality - working with SIGNED numbers
//		$negative = false;
//		if ($numberToConvert < 0) {
//			$negative = true;
//			$numberToConvert = abs($numberToConvert);
//		}
		
		
		$Chunks = array(); // TODO: Convert all chunks to string type from int.
		$Chunks[] = 0;
		$reversedValue = strrev(strval($numberToConvert));
		$reversedSize = strlen($reversedValue);
		$fullResult = null;
		
		For ($i = 0; $i <= $reversedSize; $i += 3) {
			
			$Chunks[] = strrev(substr($reversedValue, $i, 3));
		}
		
		$numGroups = count($Chunks) - 1;
		
		For ($i = $numGroups; $i >= 1; $i--) { // Main big cycle
			
			If ($i == 2) {
				$Units[1] = "одна ";
				$Units[2] = "две ";
			} else {
				$Units[1] = "один ";
				$Units[2] = "два ";
			}
			
			$resArray = array();
			$preResult = null;
			$numLen = strlen($Chunks[$i]);
			
			$centis = null;
			If ($numLen == 3) {
				$centis = intval(substr($Chunks[$i], 0, 1));
			}
			
			if ($centis != 0) {
				$preResult .= $Hundreds[$centis];
			}
			
			$decimals = intval(substr($Chunks[$i], -2));
			
			if ($decimals > 0 && $decimals < 21) {
				$preResult .= $Units[$decimals];
				
			} else if ($decimals != 0) {
				$preResult .= $Tens[intval(substr($Chunks[$i], -2, 1))];
				
				if (intval(substr($decimals, -1)) != 0) {
					$preResult .= $Units[intval(substr($Chunks[$i], -1))];
				}
			}
			if ($Chunks[$i] != '000') {
				$preResult .= getGroupname($Registry, $i, $Chunks[$i]);
			}
			
			$resArray[$i] = $preResult;
			$fullResult .= $resArray[$i];
		}
		
		return $fullResult;
	}

	function getGroupname (array $group, int $gnum, string $number): string
	{
		
		$subResult = null;
		$number = intval($number);
		$ns = strlen($number);
		$nx = substr($number, -2);
		
		if ($ns > 1 && $nx == 11 || $nx == 12 || $nx == 13 || $nx == 14 ) {
			$subResult = $group[$gnum][3];
			
		} else {
			
			switch (substr($number, -1)) {
				
				case 1:
					$subResult = $group[$gnum][1];
					break;
				case 2;
				case 3;
				case 4:
					$subResult = $group[$gnum][2];
					break;
				default:
					$subResult = $group[$gnum][3];
					break;
			}
		}
		
		return $subResult;
	}
	
	$num = 123412;
	$result = ConvertToText($num); // TODO: Resolve 000 at the end of a number issue
	echo $result . "<br />";
	echo $num;
