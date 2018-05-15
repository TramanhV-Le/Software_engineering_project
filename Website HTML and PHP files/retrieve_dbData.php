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
  echo"\n"
;
}
$age = $cancer_type = $religious = $phase_treatment=$gender=$role ="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    #<------------------------user_contact_data---------------->
   echo $age =$_POST["age"];
  echo  $cancer_type = $_POST["cancer-category"];
   echo $phase_treatment= $_POST["treatment_phase"];
    echo $religious=$_POST["religion"];
    echo $role=$_POST["your_role"];
   echo $gender=$_POST["gender"];

}
echo("\n");
echo($age);
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$sql = "INSERT INTO user_features (age,cancer_category,treatment_stage,religion,role_to_cancer,gender)
    VALUES ('$age','$cancer_type','$phase_treatment','$religious','$role','$gender')";

if ($link->query($sql) === TRUE) {
    echo "New record created successfully";

} else {
    echo "Error: " . $sql . "<br>" . $link->error;
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
        $p1 -> setNewPerson($row["user_contact_data_id"],$row["age"],$row["cancer_category"],$row["gender"],$row["religion"],"TL",$row["treatment_stage"],"PT",$row["role_to_cancer"]);
        print("\n");
        print($p1->getNewAge());
        print("\n");
    }
    if($result->num_rows > 0)
        while($row_new = $result->fetch_assoc()) {
            handle_row($row_new);
            $p1->setPerson($row_new["user_contact_data_id"], $row_new["age"], $row_new["cancer_category"], $row_new["gender"], $row_new["religion"], "TL", $row_new["treatment_stage"], "PT", $row_new["role_to_cancer"]);
            $p1->setPoints(0);
            $p1->runAlgorithm();
           if ($p1->getId() != $p1->getNewId()) {
                if ($compare1->getpoints() < $p1->getpoints()) {
                    $compare3->setPerson($compare2->getId(), $compare2->getAge(), $compare2->getCancerType(), $compare2->getGender(), $compare2->getReligion(), $compare2->getTreatementLoctation(), $compare2->getphaseTreatment_1(), $compare2->getphaseTreatment_2(), $compare2->getRole());
                    $compare3->setPoints($compare2->getpoints());
                    $compare2->setPerson($compare1->getId(), $compare1->getAge(), $compare1->getCancerType(), $compare1->getGender(), $compare1->getReligion(), $compare1->getTreatementLoctation(), $compare1->getphaseTreatment_1(), $compare1->getphaseTreatment_2(), $compare1->getRole());
                    $compare2->setPoints($compare1->getpoints());
                    $compare1->setPerson($p1->getId(), $p1->getAge(), $p1->getCancerType(), $p1->getGender(), $p1->getReligion(), $p1->getTreatementLoctation(), $p1->getphaseTreatment_1(), $p1->getphaseTreatment_2(), $p1->getRole());
                    $compare1->setPoints($p1->getpoints());
                } elseif ($compare2->getpoints() < $p1->getpoints()) {
                    $compare3->setPerson($compare2->getId(), $compare2->getAge(), $compare2->getCancerType(), $compare2->getGender(), $compare2->getReligion(), $compare2->getTreatementLoctation(), $compare2->getphaseTreatment_1(), $compare2->getphaseTreatment_2(), $compare2->getRole());
                    $compare3->setPoints($compare2->getpoints());
                    $compare2->setPerson($p1->getId(), $p1->getAge(), $p1->getCancerType(), $p1->getGender(), $p1->getReligion(), $p1->getTreatementLoctation(),
                                            $p1->getphaseTreatment_1(), $p1->getphaseTreatment_2(), $p1->getRole());
                    $compare2->setPoints($p1->getpoints());
                } elseif ($compare3 ->getpoints() < $p1->getpoints()) {
                    $compare3->setPerson($p1->getId(), $p1->getAge(), $p1->getCancerType(), $p1->getGender(), $p1->getReligion(), $p1->getTreatementLoctation(), $p1->getphaseTreatment_1(), $p1->getphaseTreatment_2(), $p1->getRole());
                    $compare3->setPoints($p1->getpoints());
                }
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
    print("\n");
    $p1->printNewperson();
    $compare1->printPerson();
    $compare2->printPerson();
    $compare3->printPerson();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\STMP;

require 'libs/phpmailer/src/Exception.php';
require 'libs/phpmailer/src/PHPMailer.php';
require 'libs/phpmailer/src/SMTP.php';


$variables = array();
$variables = get_patient_info_for_html($variables, $compare1, $compare2, $compare3);


$template = file_get_contents("contents.html");

foreach($variables as $key => $value)
{
    echo "KEY : " . $value;
    $template = str_replace('{{ '.$key.' }}', $value, $template);
}

send_email_with_info($template);



function get_patient_info_for_html($variables, $compare1, $compare2, $compare3)
{
    $variables['location1'] = $compare1->getTreatementLoctation();
    $variables['cancertype1'] = $compare1->getCancerType();
    $variables['age1'] = $compare1->getAge();
    $variables['religion1'] = $compare1->getReligion();
    $variables['role1'] = $compare1->getRole();
    $variables['cancertype2'] = $compare2->getCancerType();
    $variables['location2'] = $compare2->getTreatementLoctation();
    $variables['age2'] = $compare2->getAge();
    $variables['religion2'] = $compare2->getReligion();
    $variables['role2'] = $compare2->getRole();
    $variables['cancertype3'] = $compare3->getCancerType();
    $variables['age3'] = $compare3->getAge();
    $variables['religion3'] = $compare3->getReligion();
    $variables['role3'] = $compare3->getRole();
    $variables['location3'] = $compare3->getTreatementLoctation();

    return $variables;
}


function send_email_with_info($template)
{
    $mail = new PHPMailer(); // create a new object
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = "9621312@gmail.com";
    $mail->Password = "2BlueTankJumped!";
    $mail->SetFrom("9621312@gmail.com");
    $mail->Subject = "Test";
    $mail->Body = "hello";
    $mail->AddAddress("shadow4040@live.com");


    $mail->Body = $template;

    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message has been sent";
    }

}



$link->close();




 ?>
