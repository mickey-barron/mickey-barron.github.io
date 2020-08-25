<?php
    $contactRequest = json_decode($_POST['data']);
    $associateEmail = "mickeyb135@gmail.com";
    $errors = array();
    $reqs = ["name", "title", "zip", "email", "subject", "message"];
    $fields = ["name", "company", "title", "position", "zip", "email", "phone", "subject", "message"];
    $response = NULL;

    function genSummaryHTML($dataPackage, $fields){
        $html = "<table>";
        foreach($fields as $field){
            $fieldValue = "";   
            if ($dataPackage->$field == ""){
                $fieldValue = "Not provided";
            } else{
                $fieldValue = $dataPackage->$field;
            }
            $html .= "<tr>
                <td>".$field."</td> <td>".$fieldValue."</td>
            </tr>";
        }
        $html .= "</table>";

        return($html);

    }

    function genEmailHTML($dataPackage, $fields, $introduction, $closer){
        $html = "<html>
                        <head>
                            <title>Contact from Novel Healthcare Solutions, LLC</title>
                        </head>
                        <body>
                            <div class = 'header'>
                                <h2> Novel Healthcare Solutions, LLC. </h2>
                            </div>
                            <div class = 'salutation'>".$introduction."</div>
                            <div class = 'summary'>".genSummaryHTML($dataPackage, $fields)."</div>
                            <div class = 'footer'>".$closer."</div>
                        </body>
                </html>";
    }

    $expectedAgreement = "I agree to the Terms & Conditions, Privacy Policy, and Disclaimer of Novel Healthcare Solutions, LLC.";

    if($contactRequest->agreement != $expectedAgreement){
        array_push($errors, "Our server did not receive the expected agreement statement to our Terms & Conditions, Privacy Policy, and Disclaimer. It should have been automatically generated when checking the agreement box and its presence should have been automatically validated on your client prior to making a request. Please ensure the statement has not been altered and you have enabled JavaScript on your machine and try again.");
    }

    if(!filter_var($contactRequest->email, FILTER_VALIDATE_EMAIL)){
        array_push($errors, "Our server received an invalid email address. It should have been automatically validated on your client prior to making a request. Please ensure the email is properly formatted and you have enabled JavaScript on your machine and try again.");
    }

    $incompletes = 0;

    foreach($reqs as $req){
        if ($contactRequest->$req == ""){
            $incompletes = $incompletes + 1;
        }
    }

    if($incompletes > 0){
        array_push($errors, "Our server received incomplete required information. It should have been automatically validated on your client prior to making a request. Please ensure all required information is completed and that JavaScript is enabled on your machine and try again.");
    }

    if(sizeof($errors) > 0){
        $response = array(
            "result" => "rejected",
            "errors" => $errors,
        );
        $response = json_encode($response);
        
    } else{
        $associateHeaders = "From: contact@novelhealthcare.services\r\n";
        $associateHeaders .= "Reply-To: ". strip_tags($contactRequest->email) . "\r\n";
        $associateHeaders .= "MIME-Version: 1.0\r\n";
        $associateHeaders .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        $associateSubject = "NHS Inquiry From ".$contactRequest->name." | ".$contactRequest->subject;

        if(mail($associateEmail, $associateSubject, genEmailHTML($contactRequest, $fields, "Greetings, a new inquiry was submitted to the NHS website. Their information is summarized in the table below:", "Please be advised by replying to this email, you will be replying to the inquirer. <br> <br> Thank you kindly, <br> NHS Web Services"), $associateHeaders )){

            $response = array(
                "result" => "accepted",
                "response" => "Your request has been sent to our associates. Thank you.",
            );
            $response = json_encode($response);

        }

        

    }

    echo($response);


?>