<?php
/*
 * Permet de créer une miniature carrée d'une image de forme quelconque.     
 * $src     désigne le nom de l'image source                
 * $dest     désigne le nom de l'image d'origine                
 * $largeur     désigne la largeur du carré de la miniature            
 * $pos     peut prendre les valeurs suivante :                 
 *             "left", "right" (si la photo est horizontale)
 *             "bottom","top" (si la photo est verticale) 
 *            ou n'importe quelle autre valeur pour le milieu.
*/  
    function images_resize_carre($src, $dest, $largeur, $pos){
      list($srcX, $srcY, $type, $attr) = getimagesize($src);
      $imgSrc=imagecreatefromjpeg($src); 
      if (empty($imgSrc)){ 
          return false; 
      }
      if($srcX>= $srcY){
          $dim=$srcY;
          $horizontale=true;
      }
      elseif($srcX<= $srcY){
          $dim=$srcX; 
          $verticale=true;
      }
      else{
          $dim=$srcX;
      }   
      //on determine le point de depart x,y
      if($horizontale)
      {
       switch($pos){
        case "left":
          $point_x_ref="0";
          $point_y_ref="0";
        break;
        case "right":
          $point_x_ref=($srcX)-($dim);
          $point_y_ref="0";
        break;
        default: 
          $point_x_ref=($srcX/2)-($dim/2);
          $point_y_ref="0";
        break;
        }
      }
      elseif($verticale)
      {
       switch($pos){
        case "top":
          $point_x_ref="0";
          $point_y_ref="0";
        break;
        case "bottom":
          $point_x_ref="0";
          $point_y_ref=($srcY)-($dim);
        break;
        default: 
          $point_x_ref="0";
          $point_y_ref=($srcY/2)-($dim/2); 
        break;
       }
      }
      $imDest= imagecreatetruecolor($largeur, $largeur);
              
      imagecopyresampled($imDest,
                         $imgSrc,
                         0,
                         0,
                         $point_x_ref,
                         $point_y_ref,
                         $largeur,
                         $largeur,
                         $dim,
                         $dim);
      imagedestroy($imgSrc); 
      imagejpeg($imDest, $dest, 100); 
      imagedestroy($imDest); 
      return true;
    }
?>
<?php

     images_resize_carre("exemple.jpg","new_exemple.jpg",100,"left");
?>


