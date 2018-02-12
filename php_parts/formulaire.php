<?php
    $debug = true;
    // Getting form's variables
    $form_variables = [
        "text-user-firstname" => "",
        "text-user-lastname" => "",
        "email-email" => "",
        "select-user-country" => "",
        "radio-gender" => "", 
        "select-subject" => "",
        "textarea-message" => ""
    ];
            
    if($debug){

        foreach($form_variables as $key => $value){
            $form_variables[$key] = try_to_get($key);
        }
        var_dump($form_variables);
        
        $validate = validate($form_variables);
        echo 'FORMULAIRE VALIDE ? >> ' . ($validate?' true' : ' false');
    }
?>

<section class="formulaire">
    <form method="post" action="">
        <div class="item">
            <h2>How can we help you ?</h2>  
            <p>How can we improve your experience</p>
        </div>
        <div class="item">
            <h2>Contact</h2>
            <p>Fields with an * are mandatory.</p>
        </div>
        <div class="item">
            <?php create_input( "user-firstname", "Your name" ); ?>
        </div>
        <div class="item">
            <?php create_input( "user-lastname", "Last name" ); ?>
        </div>
        <div class="item">
            <?php create_input( "email", "Email", "email"  ); ?>
        </div>
        <div class="item gender">
            <?php create_input( "gender", null, "radio", false, array("male", "female") ); ?>
        </div>
        <div class="item">
        <?php create_input( "subject", "Subject", "select", false, array("hardware", "software", "delivery", "other") ); ?>
        </div>
        <div class="item">
            <?php create_input( "user-country", "Country", "select", true, country_list() ); ?>
        </div>
        <div class="item large">
            <?php create_input( "message", "Message", "textarea" ); ?>
        </div>
        <div class="item center">
            <button type="submit">Submit</button>
        </div>
    </form>
</section>