<footer>
    <div class = "contentArea mainColumn">
        <div class = "tile">
                <p>&copy <?php echo(date("Y")) ?> Novel Healthcare Solutions, LLC </p> <br>
                <p><a href = "#" id = "privacy"> Privacy Policy </a> </p>
                <p><a href = "#" id = "termsofuse"> Terms of Use </a> </p>
                <p><a href = "#" id = "disclaimer">Disclaimer </a> </p>
            </div>    
        <div class = "tile">
            <p>SITE MAP</p> <br>
            <?php
                $navHTML = "";

                for ($i = 0; $i < sizeof($navTitles); $i++){
                    $navHTML .= ('<p><a href = "'.$navLinks[$i].'">'.$navTitles[$i].'</a></p>');
                }

                echo($navHTML);
            ?>
        </div>
        
    </div>
</footer>