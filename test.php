
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title> Correction de l'exercice 10 </title>
  </head>
  <body>
<h1> Contact </h1>
<p> Veuillez saisir vos infos... </p>

<?php
//  premiere modification !

//Tableau qui contiendra les valeurs saisies par l'utilisateur
$valeursSaisies = array(
  'pseudo' => '',
  'tel' => '',
  'mail' => '',
  'message' => '',
  'rep' => ''
);


//Fonction déterminant les valeurs saisies par l'utilisateur
//Met à jour le tableau $val
//Retourne true si toutes les valeurs existent, false sinon
function determineValeursSaisies(&$val)
{
  $correct = true;
  foreach($val as $c => &$v)
    if( !isset($_POST[$c]) or trim($_POST[$c])=='')
      $correct = false;
    else
      $v = $_POST[$c];  
      
  return $correct;
}


//Fonction testant les expressions régulières
function testExpressionsRegulieres($val)
{
  //Teste si le pseudo est constitué de 4 à 8 lettres
  if (!preg_match('#^[a-zA-Z]{4,8}$#',$val['pseudo']) )
  {
      echo '<p> Problème dans le pseudo </p>';
      return false;
  }
  //Teste si le numéro de téléphone est constitué de 10 chiffres, 
  //le premier étant 0 et le suivant un nombre entre 1 et 6
  if (!preg_match('#^0[1-6]([ -][0-9]{2}){4}$#',$val['tel']) )
  {
      echo '<p> Problème dans le numéro de téléphone </p>';
      return false;
  }
  //Test si le mail est constitué d'au moins 3 lettres (ou underscore), de l'arobase, d'au moins 4 lettres (ou arobase)
  //puis .fr, .com ou .org
  if (!preg_match('#^[a-zA-Z_]{3,}@[a-zA-Z_]{4,10}\.(fr|com|org)$#',$val['mail']) )
  {
      echo '<p> Problème dans le mail </p>';
      return false;
  }
  //Vérifie que la réponse saisie est Un chien(s), un chien(s), Des chien(s), des chien(s), chien(s)
  if (!preg_match('#^ *([Uu]n|[Dd]es)? *[Cc]hiens? *$#',$val['rep']) )
  {
      echo '<p> Problème dans la réponse </p>';
      return false;
  }
  return true;        
}

$envoi=false;

//On teste si l'utilisateur a saisi toutes les informations
if(determineValeursSaisies($valeursSaisies) )
{
  //On teste si les saisies satisfont les expressions régulières
  if(testExpressionsRegulieres($valeursSaisies))
  {
        //On crée une chaîne contenant les différentes informations
        $mes = 'Pseudo : ' . $valeursSaisies['pseudo'] . "\nTel : " . $valeursSaisies['tel'] . "\nE-mail :" . $valeursSaisies['mail']. "\nMessage :" . $valeursSaisies['message'];
      
        //On envoie un mail grâce à la méthode mail. 
        //ATTENTION : souvent (comme dans le cas de l'iut), les serveurs sont configurés pour ne pas envoyer
        //de mail avec la fonction mail pour des raisons de sécurité.
        $test  = mail('adresseAdminisateur@mail', '[Retour formulaire] Contact',$mes);
        if($test)
            echo '<p> Un e-mail a été envoyé à l\'administrateur du site </p>';
        else
            echo '<p> Les informations saisies sont correctes. Par contre, comme les mails sont désactivés (configuration du serveur), le mail contenant le message : '. $mes  . ' n\'a pas pu être envoyé. </p>';
        $envoi=true;
  }
  else
    echo '<p> probleme dans la saisie </p>';
}
 

//Si les informations saisies ne sont pas correctes, on réaffiche le formulaire
if(!$envoi)
{    
?>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<p> Pseudo : <input type="text" name="pseudo" value="<?php echo $valeursSaisies['pseudo'];?>"/> </p>
<p> Téléphone : <input type="text" name="tel" value="<?php echo $valeursSaisies['tel'];?>"/> </p>
<p> E-mail : <input type="text" name="mail" value="<?php echo $valeursSaisies['mail'];?>"/> </p> 
<p> Message : <textarea rows="5" cols="50" name="message"><?php echo $valeursSaisies['message'];?>
</textarea> </p>
<p>Question: Quel animal aboie? <input type="text" name="rep"
value="<?php echo $valeursSaisies['rep'];?>"/> 
<input type="submit" value="Envoyer un message" /></p>  
</form>
<?php
}
?> 
  </body>
</html>
