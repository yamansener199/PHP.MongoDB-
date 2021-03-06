<?php

//__     __ __  __  _   _   _  
//\ \   / /|  \/  || \ | | | | 
// \ \_/ / | \  / ||  \| |/ __)
//  \   /  | |\/| || . ` |\__ \
//   | |   | |  | || |\  |(   /
//   |_|   |_|  |_||_| \_| |_| 

$connection = new MongoDB\Driver\Manager("mongodb://localhost:27017");
   
//$filter = ["Car_id"=>"1"];
$filter = ['Lat'=>['$gt'=> 0.0]];
//$options=['projection'=>['_Lat':0],];
/* the following condition, and others similar to it, work as well

$filter = ["age"=>["$gt"=>"18"]]; /*/
   
//$options = []; /* put desired options here, should you need any */

$query = new MongoDB\Driver\Query($filter);//$filter YMN$$$
$documents = $connection->executeQuery('db.collection'/*dbname.collection_name*/,$query);
foreach($documents as $document){
    $document = json_decode(json_encode($document),true);
    echo($lat= $document["Lat"])."<br>";
    echo($lng=$document["Lng"])."<br>";

   
}
?>