<?php
error_reporting(-1);
$title = "Gaming Evolution Generator";
$games = json_decode(file_get_contents("games.json"));
$img_sections = array(
  "fg"=>"First Game",
  "cdg"=>"Childhood \nDefining Game",
  "adg"=>"Adolescence \nDefining Game",
  "cfg"=>"Current Favorite \nGame"
);
?><html>
  <head>
    <title><?php echo $title; ?></title>
  </head>
  <body>
    <h1><?php echo $title; ?></h1>
<?php
if($_GET){
  echo "<h2>Output</h2>";
  $hash = md5($_GET['y'].$_GET['fg'].$_GET['cdg'].$_GET['adg'].$_GET['cfg']);
  $img = imagecreatefrompng("assets/bg.png");
  $top = imagecreatefrompng("assets/top.png");
  $bottom = imagecreatefrompng("assets/bottom.png");
  $pos = 0;
  
  $black = imagecolorallocate($img, 0, 0, 0);
  $white = imagecolorallocate($img, 255, 255, 255);
  $grey = imagecolorallocate($img, 127, 127, 127);
  $font_size = 36;
  $offset = 240-36;
  $string = "Born\n".$_GET['y'];
  imagettftext($img, $font_size, 0, $font_size, $font_size+$offset, $black, "./assets/font.ttf",$string);
  imagettftext($img, $font_size, 0, $font_size+2, $font_size+$offset+2, $white, "./assets/font.ttf",$string);
  
  foreach($img_sections as $img_section_key => $img_section){
    $pos++;
    $t_img = imagecreatefrompng ("covers/".$_GET[$img_section_key].".png");
    imagecopy($img,$bottom,(256+40)*$pos-20,0,0,0,296,480);
    imagecopy($img,$t_img,(256+40)*$pos,80,0,0,256,320);
    imagecopy($img,$top,(256+40)*$pos-20,0,0,0,296,480);
    $font_size = 12;
    $offset = 80+$font_size+4+320;
    $string = $img_section;
    imagettftext($img, $font_size, 0, $pos*(256+40)-1, $font_size+$offset-1, $black, "./assets/font.ttf",$string);
    imagettftext($img, $font_size, 0, $pos*(256+40)+1, $font_size+$offset-1, $black, "./assets/font.ttf",$string);
    imagettftext($img, $font_size, 0, $pos*(256+40)-1, $font_size+$offset+1, $black, "./assets/font.ttf",$string);
    imagettftext($img, $font_size, 0, $pos*(256+40)+1, $font_size+$offset+1, $black, "./assets/font.ttf",$string);
    imagettftext($img, $font_size, 0, $pos*(256+40), $font_size+$offset, $white, "./assets/font.ttf",$string);
  }
  imagepng($img, "cache/$hash.png");
  echo "<img src=\"cache/$hash.png\" />";
}
?>
    <h2>Generator</h2>
    <form action="" method="get" />
      <div class="title">Year of Birth:</div>
      <div class="section">
        <select name="y">
<?php
  for($i=date("Y")-67;$i<=date("Y")-6;$i++){
    if(isset($_GET['y']) and $i == (int)$_GET['y']){
      $selected = " selected=\"selected\"";
    } else {
      $selected = "";
    }
    echo "          <option value=\"$i\"$selected>$i</option>\n";
  }
?>
        </select>
      </div>
<?php foreach($img_sections as $img_section_key => $img_section){ ?>
      <div class="section">
        <div class="title"><?php echo $img_section; ?>:</div>
        <select name="<?php echo $img_section_key; ?>">
<?php 
$options = "";
foreach($games as $game){
  if(isset($_GET[$img_section_key]) and $game->img == $_GET[$img_section_key]){
    $selected = " selected=\"selected\"";
  } else {
    $selected = "";
  }
  $options .= "          <option value=\"".$game->img."\"$selected>".$game->title."</option>\n";
}
echo $options;
?>
        </select>
      </div>
<?php } ?>
      <div>
        <input type="submit" value="Generate!" />
      </div>
    </form>
  </body>
</html>
