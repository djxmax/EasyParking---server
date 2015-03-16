<?php require "login.php";
// This is the API, 2 possibilities: show the app list or show a specific app by id.
// This would normally be pulled from a database but for demo purposes, I will be hardcoding the return values.
	

function get_user_by_id($id,$db)
{
	$user_list = array();

	$req_pre = mysqli_prepare($db, 'SELECT id,email,mdp FROM utilisateurs WHERE id = ?');
	mysqli_stmt_bind_param($req_pre, "i", $id);
	mysqli_stmt_execute($req_pre);
	mysqli_stmt_bind_result($req_pre, $donnees['id'], $donnees['email'], $donnees['mdp']);
	while(mysqli_stmt_fetch($req_pre))
	{
		$array_temp=array("id"=>$donnees['id'], "email"=>$donnees['email'], "mdp"=>$donnees['mdp']);
		array_push($user_list, $array_temp);
	}
	
  return $user_list;
}

function get_user_by_email($email,$db)
{
	$user_list = array();

	$req_pre = mysqli_prepare($db, 'SELECT id,email,mdp FROM utilisateurs WHERE email = ?');
	mysqli_stmt_bind_param($req_pre, "s", $email);
	mysqli_stmt_execute($req_pre);
	mysqli_stmt_bind_result($req_pre, $donnees['id'], $donnees['email'], $donnees['mdp']);
	while(mysqli_stmt_fetch($req_pre))
	{
		$array_temp=array("id"=>$donnees['id'], "email"=>$donnees['email'], "mdp"=>$donnees['mdp']);
		array_push($user_list, $array_temp);
	}
	
  return $user_list;
}

function get_user_list($db)
{
  //normally this info would be pulled from a database.
  //build JSON array
  
  
	
	
	$req = mysqli_query($db, 'SELECT * FROM utilisateurs');

	$user_list = array();

	while($donnees = mysqli_fetch_assoc($req))
	{
		$array_temp=array("id"=>$donnees['id'], "email"=>$donnees['email'], "mdp"=>$donnees['mdp']);
		array_push($user_list, $array_temp);
	}
	mysqli_free_result($req);

  return $user_list;
}


	
$possible_url = array("get_user_list", "get_user");

$value = "An error has occurred";

if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
{
  $db = mysqli_connect($host_name, $user_name, $password, $database);
  if (mysqli_connect_errno())
  {
    echo "La connexion au serveur MySQL n'a pas abouti : " . mysqli_connect_error();
  }
  
  switch ($_GET["action"])
    {
      case "get_user_list":
        $value = get_user_list($db);
        break;
      case "get_user":
        if (isset($_GET["id"]))
          $value = get_user_by_id($_GET["id"],$db);
		if (isset($_GET["email"]))
          $value = get_user_by_email($_GET["email"],$db);
        else
          $value = "Missing argument";
        break;
    }
}

//return JSON array
exit(json_encode($value));
?>