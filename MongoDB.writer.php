<?php
//__     __ __  __  _   _   _  
//\ \   / /|  \/  || \ | | | | 
// \ \_/ / | \  / ||  \| |/ __)
//  \   /  | |\/| || . ` |\__ \
//   | |   | |  | || |\  |(   /
//   |_|   |_|  |_||_| \_| |_| 

$data = file_get_contents("three_secs_data.json");
$json = json_decode($data, true);
if ($json === null) {
    echo('HOP BABA SLOW DOWN');
    // Wrong Bulk İnsert 2*** YMN$$$
}
$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$writeConcern = new MongoDB\Driver\WriteConcern(0);
$time_pre = microtime(true);
echo(count($json))."<br>";

  for($x=0;$x<count($json);$x++){

    echo($time=($json[$x]["timestamp"]))."<br>";
    echo($yaw=($json[$x]["orientation"]["yaw"]))."<br>";
    echo($roll=($json[$x]["orientation"]["roll"]))."<br>";
    echo($pitch=($json[$x]["orientation"]["pitch"]))."<br>";
    echo($lat=($json[$x]["coordinates"]["lat"]))."<br>";
    echo($lng=($json[$x]["coordinates"]["lng"]))."<br>";
    
        $bulk->insert([
            'Timestamp' =>$time ,
            'Yaw'  =>$yaw,
            'Lat' =>$lat,
            'Lng' =>$lng,
            'Roll' =>$roll,
            'Pitch' =>$pitch
        ]);
}
$time_post = microtime(true);
try {
    $result = $manager->executeBulkWrite('db.collection', $bulk, $writeConcern);
} catch (MongoDB\Driver\Exception\BulkWriteException $e) {
    $result = $e->getWriteResult();

    // Check if the write concern could not be fulfilled
    if ($writeConcernError = $result->getWriteConcernError()) {
        printf("%s (%d): %s\n",
            $writeConcernError->getMessage(),
            $writeConcernError->getCode(),
            var_export($writeConcernError->getInfo(), true)
        );
    }

    // Check if any write operations did not complete at all
    foreach ($result->getWriteErrors() as $writeError) {
        printf("Operation#%d: %s (%d)\n",
            $writeError->getIndex(),
            $writeError->getMessage(),
            $writeError->getCode()
        );
    }
} catch (MongoDB\Driver\Exception\Exception $e) {
    printf("Other error: %s\n", $e->getMessage());
    exit;
}
$exec_time = $time_post - $time_pre;
printf("Inserted %d document(s)\n", $result->getInsertedCount());
printf("Updated  %d document(s)\n", $result->getModifiedCount());
printf("Upserted %d document(s)\n", $result->getUpsertedCount());
printf("Deleted  %d document(s)\n", $result->getDeletedCount());
echo($exec_time).PHP_EOL;
?>
