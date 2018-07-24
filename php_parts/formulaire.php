<?php
    include('classes/Formulaire.php');
    $formulaire = new Formulaire;
    $formulaire->addElement( 'user-firstname', 'Your name' );
    $formulaire->addElement( 'user-lastname', 'Last name' );
    $formulaire->addElement( 'email', 'Email', 'email' );

    $formulaire->addElement( 'gender', null, 'radio', false, array('male', 'female') );
    $formulaire->addClasses( 'radio-gender', 'gender' );

    $formulaire->addElement( 'subject', 'Subject', 'select', false, array('hardware', 'software', 'delivery', 'other') );
    $formulaire->addElement( 'user-country', 'Country', 'select', false, country_list() );

    $formulaire->addElement( 'message', 'Message', 'textarea' );
    $formulaire->addClasses( 'textarea-message', 'large' );
?>

<section class='formulaire'>
    <?php
    if($formulaire->isValid() && $formulaire->_honeypotted == false){
        echo "Message envoyé !";
        echo $formulaire->getMessage();
        
        mail( $formulaire->get('email-email'), 'Hackers Poulette, technical support', $formulaire->getMessage(true), $formulaire->getMailHeader() );
    }
    else{?>
    <form method='post'>
        <div class='item'>
            <h2>How can we help you ?</h2>  
            <p>How can we improve your experience</p>
        </div>
        <div class='item'>
            <h2>Contact</h2>
            <p>Fields with an * are mandatory.</p>
        </div>

       <?php $formulaire->printAllElements() ?>
       
        <div class='item center'>
            <input type='tel' name='tel-user-phone' id='user-phone'/>
            <button type='submit'>Submit</button>
        </div>
    </form>
    <?php 
    }
    ?>
</section>