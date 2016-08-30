<?php
$digitArray = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
$file = 'game1data.txt';
if(!empty($_POST['email']) && !empty($_POST['answer'])&&filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $handle = fopen($file, "r");
    //print 'test';
    $outData = "";
    $isEmail = 0;
    $isNewData = 0;
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $pos = strpos($line, ':');
            if($pos != false){
                $email = substr($line,0,$pos);
                //print $email . $_POST['email'] . "\n";
                if($email == $_POST['email']){
                    $isBad=0;
                    $isEmail=1;
                    $line = substr($line,$pos+1);
                    $numDigits = strpos($line, ':');
                    $solution = substr($line,0,$numDigits);
                    $line = substr($line,$numDigits+1);
                    $pos = strpos($line, ':');
                    $newLine = strpos($line, "\n");
                    $numTries = substr($line,0,$pos);
                    $line = substr($line,$pos+1,$newLine-$pos-1);
                    if(strlen($_POST['answer'])!=$numDigits){
                        print "bad answer\n";
                        break;
                    }
                    if($_POST['answer']==$solution){
                        $numDigits = rand(6,8);
                        $isNewData = 1;
                        shuffle($digitArray);
                        $digitString=$digitArray[0];
                        for($i = 1; $i < $numDigits; $i++){
                            $digitString *= 10;
                            $digitString += $digitArray[$i];
                        }
                        $outData .= $email . ":" . $digitString . ":0:" . $line . ($numTries+1) . ",\n";
                        $file2 = 'game1scoreboard.txt';
                        ini_set('date.timezone', 'America/Los_Angeles');
                        //$lineadd = date('H:i:s', time()) . " " . ($numTries+1) . " " . $email . " " . $numDigits . "\n";
                        $lineadd = "GameOver" . " " . ($numTries+1) . " " . $email . " " . $numDigits . "\n";
                        file_put_contents($file2, $lineadd, FILE_APPEND | LOCK_EX);
                        print "Success\n" . $numDigits . "\nSuccess attempts:" . $line . ($numTries+1) . ",\n";

                        continue;
                    }
                    $countA=0;
                    $countB=0;
                    $digitValues = "0123456789";
                    foreach (count_chars($_POST['answer'], 1) as $i => $val) {
                        if(is_numeric(chr($i))==0||$val!=1){
                            $isBad=1;
                            // print $val;
                            break;
                        }
                        else if(strpos(" " . $solution,chr($i))==False){

                        }
                        else if(strpos($solution,chr($i))==strpos($_POST['answer'],chr($i))){
                            $countA++;
                        }
                        else{
                            $countB++;
                        }
                    }
                    if($isBad==1){
                        print "bad answer\n";
                        break;
                    }
                    else{
                        $isNewData=1;
                        $outData .= $email . ":" . $solution . ":" . ($numTries+1) . ":" . $line . "\n";
                        print "(" . $countA . "," . $countB . ")\n";
                        continue;
                    }
                }
                else{
                    $outData.= $line;
                }
            }
        }
        fclose($handle);
        if($isNewData){
            file_put_contents($file, $outData,LOCK_EX);
        }
        if($isEmail==0){
            print "Email not found. Send a POST with only an email(no answer) first!\n";
        }
    } else {
        print 'server failed!';
    }
} elseif (!empty($_POST['email'])&&filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    $handle = fopen($file, "r");
    //print 'test';
    $isEmail = 0;
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $pos = strpos($line, ':');
            if($pos != false){
                $email = substr($line,0,$pos);
                //print $email . $_POST['email'] . "\n";
                if($email == $_POST['email']){
                    $isEmail=1;
                    $line = substr($line,$pos+1);
                    $numDigits = strpos($line, ':');
                    $line = substr($line,$numDigits+1);
                    $pos = strpos($line, ':');
                    $numTries = substr($line,0,$pos);
                    $line = substr($line,$pos+1);
                    print $numDigits . "\n";
                    break;
                }
            }
        }
        fclose($handle);
        if($isEmail==0){
            $numDigits = rand(6,8);
            
            shuffle($digitArray);
            $digitString=$digitArray[0];
            for($i = 1; $i < $numDigits; $i++){
                $digitString *= 10;
                $digitString += $digitArray[$i];
                //print $digitArray[$i];
            }

            // Open the file to get existing content
            $current = file_get_contents($file);
            // Append a new person to the file
            $current .= $_POST['email'] . ":" . $digitString . ":0:\n";
            // Write the contents back to the file
            file_put_contents($file, $current,LOCK_EX);
            print $numDigits . "\n";
        }
    } else {
        print 'server failed!';
    }
} else{
    print 'Use POST with either only email or email and answer';
}
?>