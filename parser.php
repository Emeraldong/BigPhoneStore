<?php
$val = getopt("", ["file:",  "unique-combinations:"]);

if (!isset($val["file"]) || !isset($val["unique-combinations"])) {
	echo "No filename entered.";
    return 0;
}

$file = fopen($val["file"], "r");
$firstline = rtrim(str_replace("\"", "", fgets($file)),"\r\n");
$fields = explode(",",  $firstline);
$limiter = ",";
if(count($fields ) != 7)
{
    $fields = explode("\t", $firstline);
    $limiter = "\t";
}

$phoneArray = [];
while(($data = fgets($file)) !== FALSE)
{
    $noQuotes = str_replace("\"", "", $data);
    $noNewLine = rtrim($noQuotes, "\r\n");
    $dataFields = explode($limiter, $noNewLine);
    $properString = implode(",", $dataFields);

    foreach($fields as $key => $field)
    {
        if(($key == 0 || $key == 1) && trim($dataFields[$key]) == "")
        {
            throw new Exception("Required Field is Empty!");
        }
        echo $field . ": " . $dataFields[$key] . "\n";
    }
    
    if(!isset($phoneArray[$properString]))
    {
        $phoneArray[$properString] = 1;
    }
    else
    {
        $phoneArray[$properString] += 1;
    }
}

fclose($file);

$comboFile = fopen($val["unique-combinations"], "w");
fwrite($comboFile, implode(",", $fields).",count\n");
foreach($phoneArray as $key => $phone)
{
    fwrite($comboFile, $key . "," . $phone . "\n");
}
fclose($comboFile);