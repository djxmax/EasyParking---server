<?php require "login.php";
header('Cache-Control: no-cache, must-revalidate');
header('Content-Type: application/json; charset=utf-8');

function get_close_parking_list($db)
{
  
	$req = mysqli_query($db, 'SELECT * FROM parking_ferme');

	$close_parking_list = array();

	while($donnees = mysqli_fetch_assoc($req))
	{
		$array_temp=array("id"=>$donnees['id'], "latitude"=>$donnees['latitude'], "longitude"=>$donnees['longitude'], "places_total"=>$donnees['places_total'], 
		"places_restantes"=>$donnees['places_restantes'], "adresse"=>utf8_encode($donnees['adresse']), "code_postal"=>$donnees['code_postal'], "ville"=>utf8_encode($donnees['ville']));
		
		array_push($close_parking_list, $array_temp);
	}

	mysqli_free_result($req);
		
  return $close_parking_list;
}


	
$possible_url = array("get_close_parking_list");

$value = "An error has occurred";
header('Content-Type: application/json; charset=utf-8');
if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
{
  $db = mysqli_connect($host_name, $user_name, $password, $database);
  if (mysqli_connect_errno())
  {
    echo "La connexion au serveur MySQL n'a pas abouti : " . mysqli_connect_error();
  }
  
  switch ($_GET["action"])
    {
      case "get_close_parking_list":
        $value = get_close_parking_list($db);
		
        break;
    }
}

//print_r($value);
//return JSON array
exit(json_encode($value));
?>