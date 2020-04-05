<?php
/*---------------------------------------------------------------*/
 Création d'images miniatures
/*---------------------------------------------------------------*/

  /* *************************************************************
  - vignette($img_file, $img_max_width, $img_max_height)
  - Création d'une vignette à partir d'une image ($img_file)
  - Les extensions prises en compte sont jpg et png
  (le gif simple est remplacé par le png)
  - Le gif n'est jamais redimensionné (gif animé)

  * $img_file : chemin vers le fichier image à redimensionner
  * $img_max_width : largeur maximum que doit faire la miniature
  * $img_max_height : Hauteur maximum que doit faire l'image
  ******************************************************************** */

  define("REW", "200"); // nombre de pixel dans le nom de l'image
  define("DOSS_TEMP", "tmp");  // le répertoire qui va etre créé

  function vignette($img_file, $img_max_width, $img_max_height) {
  
  $file = realpath($img_file); // Chemin canonique absolu de l'image
  $dir = dirname($img_file).'/'; // Chemin du dossier contenant l'image
  $img_infos = getimagesize($file); // Récupération des infos de l'image
  $img_width = $img_infos[0]; // Largeur de l'image
  $img_height = $img_infos[1]; // Hauteur de l'image
  $img_type = $img_infos[2]; // Type de l'image
  
  // Détermination des dimensions de l'image
  if ($img_max_width > $img_width) {
    $img_max_width = $img_width; // Largeur de la vignette
  }
  
  if ($img_max_height > $img_height) {
    $img_max_height = $img_height; // Hauteur de la vignette
  }
  // Facteur largeur par hauteur des dimensions max de la vignette
  $img_thumb_fact_width_height = $img_max_width / $img_max_height;
  // Facteur largeur par hauteur de l'original
  $img_fact_width_height = $img_width / $img_height;
  
  // Détermination des dimensions de la vignette
  if ($img_thumb_fact_width_height < $img_fact_width_height) {
    $img_thumb_width  = $img_max_width; // Largeur de la vignette
    $img_thumb_height = $img_thumb_width / $img_fact_width_height; // Hauteur
  } else {
    $img_thumb_height = $img_max_height;  // Hauteur de la vignette
    $img_thumb_width  = $img_thumb_height * $img_fact_width_height; // Largeur
  }
  
  // Vérification de la présence de la vignette
  $img_file_temp = 'temp/'.$img_file; // Adresse de l'image temporaire de base
  // Découpe de la taille de la vignette
  $exp_img_thumb_width = explode(',', $img_thumb_width);
  //Adresse de la vignette
$img_thumb_name=preg_replace('/(.+).(.+)/U',
                             '$1'.REW.$exp_img_thumb_width[0].'px.$2',
                             $img_file_temp);
  if (is_file($img_thumb_name) ) {
    return $img_thumb_name;
  }    
  
  // Création du dossier de l'image
  $exp_dir = explode('/', DOSS_TEMP.$dir); // Découpe du chemin
  $nb_exp_dir = count($exp_dir) -1;
  
  
  for  ($a = 0, $dir = NULL; $a < $nb_exp_dir; $a ++) {
    $dir .= $exp_dir[$a].'/'; // Chemin du cache
    
    // Détermination du chemin
    if (!is_dir($dir)) {
      mkdir($dir, 0755, true); // Création du dossier
    }
  }
  
  // Sélection des variables selon l'extension de l'image
  switch ($img_type) {
    case 2:
      // Création d'une nouvelle image jpeg à partir du fichier
      $img = imagecreatefromjpeg($file);
      $img_ext = '.jpg'; // Extension de l'image
      break;
    case 3:
      // Création d'une nouvelle image png à partir du fichier
      $img = imagecreatefrompng($file);
      $img_ext = '.png';  // Extension de l'image
  }
  // Création de la vignette
  $img_thumb = imagecreatetruecolor($img_thumb_width, $img_thumb_height);
   // Insertion de l'image de base redimensionnée
  imagecopyresampled($img_thumb, $img, 0, 0, 0, 0, $img_thumb_width,
                                                 $img_thumb_height,
                                                 $img_width,
                                                 $img_height);
$file_name = basename($img_file, $img_ext); // Nom du fichier sans son extension
// Chemin complet du fichier de la vignette
  $img_thumb_name = $dir.$file_name.REW.$exp_img_thumb_width[0].'px'.$img_ext;
  
  // Sélection de la vignette créée
  switch($img_type){
    case 2:
    // Enregistrement d'une image jpeg avec une compression de 75 par défaut
      imagejpeg($img_thumb, $img_thumb_name);
      break;
    case 3:
      imagepng($img_thumb, $img_thumb_name); // Enregistrement d'une image png
  }
  
  return $img_thumb_name; // Chemin de la vignette
  }
?>
<?php

    vignette('image.png', 100, 100);
?>
