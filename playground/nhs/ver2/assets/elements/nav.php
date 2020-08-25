<?php

$navTitles = ['Home', 'About', 'Services', 'Testimonials', 'Contact'];
$navLinks = ['#home', "#about", '#services', '#testimonials', '#contact'];
$navHTML = '<div class = "fixedContainer"><nav class = "accent bottom"><div class = "logo"><p><span>NOVEL HEALTHCARE</span> <br>SOLUTIONS</p> <img class = "logo" alt = "Novel Healthcare Solutions Logo" src = "assets/images/logo.svg"/></div> <div class = "divider"></div> <i id = "mobileMenuIcon" class="material-icons">menu</i>  <div class = "links"><ul>';

for ($i = 0; $i < sizeof($navTitles); $i++){
    $navHTML .= ('<li><a href = "'.$navLinks[$i].'">'.$navTitles[$i].'</a></li>');
}

$navHTML .= '</ul>  </div></nav></div>';

echo($navHTML);
?>