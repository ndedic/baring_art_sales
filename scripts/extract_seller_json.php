<?php

$filePath = "../Graves_Art_Sales.json";

go($filePath);

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }

    return (substr($haystack, -$length) === $needle);
}

function extractSeller($str) {

    $parts = explode(" ", $str);        
    $result = '';

    foreach ($parts as $idx => $part) {               
        if (endsWith($part, ".")) {
            $p = rtrim($part, ".");
            if (is_numeric($p)) {
                break;
            }
        }
        
        $sep = $idx == 0 ? "" : " ";        
        $result .= $sep . $part;                       
    }

    return $result == $str ? "" : $result;
}

function generateNewFile($oldFile) {        
    
    if ($oldFile !== false) {
        $items = json_decode($oldFile, true);
        $result = [];
        foreach ($items as $item) {   
            if (isset($item['seller/artwork'])) {
                $item['seller'] = extractSeller($item['seller/artwork']);        
                $result[] = $item;
            }
        }   
    }
    
    return json_encode($result);
}

function saveNewFile($file) {
    $fileName = "extracted-sellers-" . uniqid() . ".json";
    file_put_contents($fileName, $file);
}

function go($filePath) {
    
    $file = file_get_contents($filePath);    
    $newFile = generateNewFile($file);   
    saveNewFile($newFile);

}