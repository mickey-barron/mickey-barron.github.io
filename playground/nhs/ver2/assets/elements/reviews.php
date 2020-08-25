<?php 
$reviews = ['So incredibly helpful - your insight and ability to problem solve is impeccable!', 'Thanks to you, we are able to provide effective and valuable care to our patients.', 'I am absolutely amazed with the quality of your work - you picked up on so many important things; thank you!', 'Your expertise was critical to developing our program.', 'Thank you so much, NHS; You helped use plan an incredible space to help our patients.', 'It was great to work with you; the report you created for us is so valuable to our hospital.', 'Our protocols have improved monumentally thanks to you.'];
$reviewers = ['Roger S, Physician Executive', 'Michelle W, Program Manager', 'Kerri H, Director', 'Sam T, Physician Executive', 'Ari D, President', 'Amber H, Virological Lab Manager', 'Myka N, Physician Assistant Representative'];
$institutions = ['Massachusetts General Hospital', 'Johns Hopkins', 'Harvard Medical School Teaching Hospital', 'Dana Farber Cancer Institute', 'Boston Children&rsquo;s Hospital', 'Boston Medical Center', 'Brigham & Women&rsquo;s Hospital'];
$images = ['assets/images/MassGenHosp.jpg','assets/images/JohnsHopkins.jpg','assets/images/HarvardMed.jpg', 'assets/images/DanaFarber.jpg', 'assets/images/BostonChildrensHospital.jpg', 'assets/images/BostonMedicalCenter.jpg', 'assets/images/BrighamAndWomens.jpg'];
$html = "";

for ($i = 0; $i < count($reviews); $i++){
    $html .=  "<div class = 'slide' style = 'background:url(".$images[$i].");'>
                    <div class = 'slideContent'>
                        <h4>".$institutions[$i]."</h4>
                        <span><p>".$reviews[$i]."</p>
                        <p>- ".$reviewers[$i]."</p></span>
                    </div>
                </div>";
}

echo($html);

?>