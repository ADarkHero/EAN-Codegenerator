<?php
	error_reporting(0);
	
	function zeichen($zeichen){											//Funktion, um den Code als Tabelle auszugeben
		for($i = 0; $i < strlen($zeichen); $i++){						//Mehrere Zeichen werden übermittelt -> Alle Zeichen durchgehen
			if(substr($zeichen, $i, 1) == 0){							//Zeichen auslesen -> Ist es 0?
				echo '<th style="background-color: white; width: 2px;"></th>';
			}
			else{														//Oder 1?
				echo '<th style="background-color: black; width: 2px;"></th>';
			}
		}	
	}
	
	
	function bild_zeichnen($zeichen, $position, $bild){					//Funktion, um den Code als Bild auszugeben
	$weiss = imagecolorallocate($bild, 255, 255, 255);					//Weisse Striche
	$schwarz = imagecolorallocate($bild, 0, 0, 0);						//Schwarze Striche
	
		for($i = 0; $i < strlen($zeichen); $i++){						//Mehrere Zeichen werden übermittelt -> Alle Zeichen durchgehen
			$strichpos = $position + 3;									//Striche sind 3px breit
			if(substr($zeichen, $i, 1) == 0){							//Zeichen auslesen -> Ist es 0?
				imagefilledrectangle($bild, $position, 0, $strichpos, 100, $weiss);
			}
			else{														//Oder 1?
				imagefilledrectangle($bild, $position, 0, $strichpos, 100, $schwarz);
			}
			
			$position += 3;
		}	
		return $position;
	}
	
	
	
	function trennstriche(){											//Für Trennstriche zwischen Tabellen
		echo '<table style="height: 125px; border: 0px; display: inline;"><tr>';	
		echo '<th style="background-color: black; width: 2px;"></th>';	
		echo '<th style="background-color: black; width: 2px;"></th>';	//2 Trennstriche
		echo '</tr></table>';
		echo '<table rules="none" border="0px" style="height: 100px; border: 0px; display: inline; margin-bottom: 25px;"><tr>';	
	}
	
	
	function trennstriche_zeichnen($position, $bild){					//Für Trennstriche beim Bild
		$schwarz = imagecolorallocate($bild, 0, 0, 0);					//Schwarze Striche
		$position += 3;													//Abstand nach Trennlinien
		$strichpos = $position + 3;										//Striche sind 3px breit
		
		imagefilledrectangle($bild, $position, 0, $strichpos, 125, $schwarz);
		$position += 6;
		$strichpos = $position + 3;	
		
		imagefilledrectangle($bild, $position, 0, $strichpos, 125, $schwarz);
		$position += 6;
		
		return $position;
	}


	
	

	
	
	
	
	
	$code = $_POST["code"];											//Eingegebener Code
	
	$musterA = array(0 => "0001101",1 => "0011001",2 => "0010011",3 => "0111101",4 => "0100011",5 => "0110001",6 => "0101111",7 => "0111011",8 => "0110111",9 => "0001011");
	$musterB = array(0 => "0100111",1 => "0110011",2 => "0011011",3 => "0100001",4 => "0011101",5 => "0111001",6 => "0000101",7 => "0010001",8 => "0001001",9 => "0010111");
	$musterC = array(0 => "1110010",1 => "1100110",2 => "1101100",3 => "1000010",4 => "1011100",5 => "1001110",6 => "1010000",7 => "1000100",8 => "1001000",9 => "1110100");
	
	$zeichensatz = array(0 => "AAAAAA",1 => "AABABB",2 => "AABBAB",3 => "AABBBA",4 => "ABAABB",5 => "ABBAAB",6 => "ABBBAA",7 => "ABABAB",8 => "ABABBA",9 => "ABBABA");
	
	while(strlen($code) < 12){										//Es werden 12 Zeichen benötigt
		$code = "0".$code;
	}
	
	$teilA = substr($code, 0, 1);									//Nach 1. Zeichen Teilen
	$teilB = substr($code, 1, 5);									//Zeichen 2-7
	$teilC = substr($code, 7, 5);									//Zeichen 7-12
	$komplett = substr($code, 0, 12);								//Alle Zeichen als Array
	
	for($i = 0, $ergebnis = 0; $i < 12; $i++){						//D wird generiert
		if($i%2 != 0){												//Jede 1. Stelle mal 1 - Jede 2. Stelle mal 3 -> Ergebnisse addieren
			$ergebnis += $komplett[$i] * 1;
		}
		else{
			$ergebnis += $komplett[$i] * 3;
		}
	}	
	
	$teilD = 10 - substr($ergebnis, -1);							//Prüfziffer -> 10 minus der letzten Zahl des Ergebnisses der Schleife															
																	
	$teilC = $teilC.$teilD;											//C und D werden verbunden -> selbe codierung
	
	
	$codierung = $zeichensatz[$teilA].split("");					//Codierung als Array (Jeder Buchstabe mit anderem Index)
	$b = $teilB.split("");											//Teil B als Array
	$c = $teilC.split("");											//Teil C als Array
	

	trennstriche();
	for($i = 0; $i < 5; $i++){										//EAN generieren
		if($codierung[$i] == "A"){									//Tabelle A
			zeichen($musterA[$b[$i]]);
		}
		else{														//Tabelle B
			zeichen($musterB[$b[$i]]);
		}
	}
	
	trennstriche();
	
	for($i = 0; $i < 5; $i++){
			zeichen($musterC[$c[$i]]);								//Tabelle C
	}
	
	trennstriche();
	echo "</tr></table>";

	
	
	
		//Generierung als Bild	
	$breite = 500;
	$hoehe = 125;	
		
	$bild = imagecreatetruecolor($breite, $hoehe);					//Neues Bild erzeugen (500x125)
	 
	$weiss = imagecolorallocate($bild, 255, 255, 255);				//Farben des Bildes
	imagefill($bild, 0, 0, $weiss);									//Hintergrund des Bildes
	$position = 0;
	
	$position = trennstriche_zeichnen($position, $bild);			//Trennstriche
	for($i = 0; $i < 5; $i++){										//EAN generieren
		if($codierung[$i] == "A"){									//Tabelle A
			$position = bild_zeichnen($musterA[$b[$i]], $position, $bild);
		}
		else{														//Tabelle B
			$position = bild_zeichnen($musterB[$b[$i]], $position, $bild);
		}
	}
	
	$position = trennstriche_zeichnen($position, $bild);			//Trennstriche
	
	for($i = 0; $i < 5; $i++){										//Tabelle C
			$position = bild_zeichnen($musterC[$c[$i]], $position, $bild);
	}
	
	$position = trennstriche_zeichnen($position, $bild);			//Trennstriche
	
	
	imagejpeg($bild, "test.png");
?>

<br><br>
<img src="test.png">

	
		
