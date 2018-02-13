<?php
    include("classes/Formulaire.php");
    $formulaire = new Formulaire;
    $formulaire -> add_element( "user-firstname", "Your name" );
    $formulaire -> add_element( "user-lastname", "Last name" );
    $formulaire -> add_element( "email", "Email", "email" );

    $formulaire -> add_element( "gender", null, "radio", false, array("male", "female") );
    $formulaire -> add_classes( "radio-gender", "gender" );

    $formulaire -> add_element( "subject", "Subject", "select", false, array("hardware", "software", "delivery", "other") );
    $formulaire -> add_element( "user-country", "Country", "select", true, country_list() );

    $formulaire -> add_element( "message", "Message", "textarea" );
    $formulaire -> add_classes( "textarea-message", "large" );
?>

<section class="formulaire">
    <?php
    if($formulaire->is_valid()){
        echo 'Message envoyé !';
        echo $formulaire->get_message();
        mail( $formulaire->get('email-email'), 'Hackers Poulette, technical support', $formulaire->get_message(true) );
    }
    else{?>
    <form method="post" action="">
        <div class="item">
            <h2>How can we help you ?</h2>  
            <p>How can we improve your experience</p>
        </div>
        <div class="item">
            <h2>Contact</h2>
            <p>Fields with an * are mandatory.</p>
        </div>

       <?php $formulaire-> print_all_elements() ?>
       
        <div class="item center">
            <button type="submit">Submit</button>
        </div>
    </form>
    <?php 
    }
    ?>
</section>