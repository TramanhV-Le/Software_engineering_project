<?php
include ("Algorithm.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);
  $servername = 'cancercant-1.cpbyr2656phb.us-west-2.rds.amazonaws.com';
  $username = 'root';
  $password = 'cancercant';
  $database = 'cancercant';
  $port = 2801;


// Create connection
$link = mysqli_connect($servername,
$username, $password, $database, $port);
// Check connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}
else {
  echo "Connection successfully";
}

// Select all rows from the databse
  $query = "SELECT * FROM user_features";
  //execute query
  $result = $link->query($query);
  $last="SELECT * From user_features Order by user_contact_data_id DESC Limit 1";
  $last_result = $link->query($last);
    $last_id="SELECT user_contact_data_id From user_features Order by user_contact_data_id DESC Limit 1";
    $last_id_result = $link->query($last);

    $p1 = new Algorithm();
    $compare1=new Algorithm();
    $compare2=new Algorithm();
    $compare3 = new Algorithm();
    $compare1->setPoints(0);
    $compare2->setPoints(0);
    $compare3->setPoints(0);
    $arrays = array($compare1,$compare2,$compare3);

  //check the query, if error then print
  if (!mysqli_query($link,$query)) {
      echo("Error description: " . mysqli_error($link));
      exit();
    }
  //otherwise, if it was a success, then check to see how many rows
  elseif ($last_result->num_rows > 0) {
    // output data of each row
    while($row = $last_result->fetch_assoc()) {
      handle_row($row);
        $p1 -> setNewPerson($row["age"],$row["cancer_category"],$row["gender"],$row["religion"],"TL",$row["treatment_stage"],"PT",$row["role_to_cancer"]);
        print("\n");
        print($p1->getNewAge());
        print("\n");
    }
    if($result->num_rows > 0)
        while($row = $result->fetch_assoc()){
            list_compare($row);
            $p1 -> setPerson($row["age"],$row["cancer_category"],$row["gender"],$row["religion"],"TL",$row["treatment_stage"],"PT",$row["role_to_cancer"]);
            $p1->runAlgorithm();
            if($compare1->getpoints() < $p1 ->getpoints()){
                $compare3->setPerson($compare2->getAge(),$compare2->getCancerType(),$compare2->getGender(),$compare2->getReligion(),$compare2->getTreatementLoctation(),$compare2->getphaseTreatment_1(),$compare2->getphaseTreatment_2(),$compare2->getRole());
                $compare3->setPoints($compare2->getpoints());
                $compare2->setPerson($compare1->getAge(),$compare1->getCancerType(),$compare1->getGender(),$compare1->getReligion(),$compare1->getTreatementLoctation(),$compare1->getphaseTreatment_1(),$compare1->getphaseTreatment_2(),$compare1->getRole());
                $compare2->setPoints($compare1->getpoints());
                $compare1->setPerson($p1->getAge(),$p1->getCancerType(),$p1->getGender(),$p1->getReligion(),$p1->getTreatementLoctation(),$p1->getphaseTreatment_1(),$p1->getphaseTreatment_2(),$p1->getRole());
                $compare1->setPoints($p1->getpoints());
            }
            elseif($compare2->getpoints() < $p1->getpoints()){
                $compare3->setPerson($compare2->getAge(),$compare2->getCancerType(),$compare2->getGender(),$compare2->getReligion(),$compare2->getTreatementLoctation(),$compare2->getphaseTreatment_1(),$compare2->getphaseTreatment_2(),$compare2->getRole());
                $compare3->setPoints($compare2->getpoints());
                $compare2->setPerson($p1->getAge(),$p1->getCancerType(),$p1->getGender(),$p1->getReligion(),$p1->getTreatementLoctation(),$p1->getphaseTreatment_1(),$p1->getphaseTreatment_2(),$p1->getRole());
                $compare2->setPoints($p1->getpoints());
            }
            elseif($compare3 < $p1->getpoints()){
                $compare3->setPerson($p1->getAge(),$p1->getCancerType(),$p1->getGender(),$p1->getReligion(),$p1->getTreatementLoctation(),$p1->getphaseTreatment_1(),$p1->getphaseTreatment_2(),$p1->getRole());
                $compare3->setPoints($p1->getpoints());
            }
        }
}
//if no rows then outout so
else {
    echo "0 results";
}

//Helper function to output the data that was pulled from the db
//row is essentially a hashmap or dictionary pertaining to each
//row of the db. Access columns by name in the db
  function handle_row($row ) {
    echo "id: " . $row["user_contact_data_id"].
     " - age: " . $row["age"].
     " - cancer_category: " . $row["cancer_category"].
     " - religion: " . $row["religion"].
     " - treatment stage: " . $row["treatment_stage"].
     " - role to cancer: " . $row["role_to_cancer"].
     " - gender: " . $row["gender"].
     "<br>";

  }

  function list_compare($row){
      echo "id: " . $row["user_contact_data_id"].
          " - age: " . $row["age"].
          " - cancer_category: " . $row["cancer_category"].
          " - religion: " . $row["religion"].
          " - treatment stage: " . $row["treatment_stage"].
          " - role to cancer: " . $row["role_to_cancer"].
          " - gender: " . $row["gender"].
          "<br>";

  }
    print("\n");
    print($compare1->getpoints());
    print("\n");
    print($compare2->getpoints());
    print("\n");
    print($compare3->getpoints());
    print("\n");

  $link->close();




 ?>