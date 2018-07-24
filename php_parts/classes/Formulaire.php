<?php

class Formulaire{
    private $_elements = [];
    private $_variables = [];
    private $_schema = [];
    private $_valid;
    private $_already_posted;

    private $_min_strlen;
    public $_honeypotted = false;

    public function __construct(){
        if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            $this->_already_posted = true;
        }
        else{
            $this->_already_posted = false;
        }
        $this->_valid = $this->_already_posted;

        $this->_min_strlen['text'] = 2;
        $this->_min_strlen['textarea'] = 24;

        if(isset($_POST['tel-user-phone'])){
            if($_POST['tel-user-phone'] =! ''){
                $_honeypotted = true;
            }
        }
    }


    // Add an element in this formulaire
    public function add_element($name, $desription, $type='text', $mentadory=true, $options=null){
        // SETTING OF THE ELEMENT
        $strict_name = $type.'-'.$name;
        // Add the element with all its values
        $this->_elements[ $strict_name ] = [
            'name' => $name,
            'description' => $desription,
            'type' => $type,
            'mendatory' => $mentadory,
            'options' => $options,
            'classes' => 'item'
        ];
        

        // GETTING OF ITS VALUE (if possible)
        // We look what type of filter we need to sanitize;
        $filter_sanitize;
        switch($type){
            case 'email':
            $filter_sanitize = FILTER_SANITIZE_EMAIL;
            break;
            
            default:
            $filter_sanitize = FILTER_SANITIZE_STRING;
        }
        // After have adding the element, we check if a value already exist for it
        $this->_variables[ $strict_name ] = try_to_get($strict_name, $filter_sanitize);

        // CHECK THE VALIDITY OF THE FORM
        // We look if we need to mark the formulaire as not valid
        if( $this->error( $strict_name )){
            $this->_valid = false;
        }
        // if( $this->_variables[ $strict_name ] == null 
        // || $this->_variables[ $strict_name ] == false 
        // || !isset($this->_variables[ $strict_name ]) 
        // || (($type == 'text' || $type == 'textarea') && strlen($this->_variables[ $strict_name ]) <= $this->_min_strlen[$type]) ){
        //     $this->_valid = false;
        // }
    }

    // Add some CSS class in an element
    public function add_classes($strict_name, $classes){
        $this->_elements[$strict_name]['classes'] = $this->_elements[$strict_name]['classes'] . ' ' . $classes;
    }
    

    // Draw all elements in the formulaire
    public function print_all_elements(){
        foreach( $this->_elements as $strict_name => $element ){
            $this->print_element($strict_name);
        }
    }

    // Draw the element in HTML
    public function print_element($strict_name){
        $current_element = $this->_elements[ $strict_name ];
        $value = '';

        if($current_element != null){

            echo '<div class=\'' . $current_element['classes'];
            // Check if a valid value exist already
            if($this->_already_posted){
                if($this->error($strict_name)){ // If not, we class it like an error.
                    echo ' error';
                }
                $value = $this->_variables[$strict_name];
            }
            echo '\'>';
            echo '<p>';
            
            // If a description exist, we create a label
            if(strlen($current_element['description']) > 0){ 
                // $this->labelise( $current_element['description'], $current_element['name'], $current_element['mendatory'] );
                $this->labelise( $current_element );
            }
            
            if($current_element['type'] == 'text' || $current_element['type'] == 'email'){ // Create an <input>
                echo '<input type=\''. $current_element['type'] .'\' name=\''. $strict_name .'\' id=\''. $current_element['name'] .'\'';
                
                // Check if a valid value exist already
                if( $value!== '' ){
                    echo ' value=\'' . $value . '\'';
                }

                // Check if required
                if($current_element['mendatory']){
                    echo ' required=\'required\'';
                }
                echo '/>';
            }
            else if($current_element['type'] == 'textarea'){ // Create an <area>
                echo '<textarea name=\'' . $strict_name . '\' id=\'' . $current_element['name'] . '\'';
                // Check if required
                if($current_element['mendatory']){
                    echo ' required=\'required\'';
                }
                echo '>';
                if( $value!== '' ){
                    echo $value;
                }
                echo '</textarea>';
            }
            else if($current_element['type'] == 'select' && $current_element['options'] !== null){ // Instead of an input, create a <select>
                echo '<select name=\'' . $strict_name . '\' id=\'' . $current_element['name'] . '\'';
                // Check if required
                if($current_element['mendatory']){
                    echo ' required=\'required\'';
                }
                echo '>';
                foreach( $current_element['options'] as $keyOption => $valueOption ){
                    
                    if( is_numeric( $keyOption) ){
                        $actualValue = $valueOption;
                    }
                    else {
                        $actualValue = $keyOption;
                    } 
                    echo '<option value=\'' . $actualValue . '\'';
                    if( $value!== '' && $value == $actualValue){
                        echo ' selected=\'selected\'';
                    }
                    echo '>';
                    echo ucfirst( $valueOption );
                    echo '</option>';
                }
                echo '</select>';
            }
            else if($current_element['type'] == 'radio' && $current_element['options'] !== null){ // Instead of an input, create some <radio>
                $i = 0;

                foreach( $current_element['options'] as $valueOption ){
                    echo '<input type=\'radio\' name=\'' . $strict_name . '\' value=\'' . $valueOption . '\'';
                    if( $value!== '' && $value === $valueOption
                    || $i == 0 ){
                        echo ' checked=\'checked\'';
                        // Check if required
                        if($current_element['mendatory']){
                            echo ' required=\'required\'';
                        }
                    }
                    echo '/> ';
                    echo ucfirst( $valueOption ) . ' ';
                    $i++;
                }
            }
            
            echo '</p>';
            echo '</div>';
        }
    }

    // Create a <label>
    function labelise( $current_element ){
        global $mark;
        $strict_name = $current_element['type'] . '-' . $current_element['name'];
        $error = $this->error($strict_name);

        echo '<label for=\'' . $current_element['name'] . '\'';
        if($error){
            echo ' class=\'error\'';
        }
        echo '>';

        echo $current_element['description'];
        
        if($current_element['mendatory']){ // Write the mendatory's mark
            echo $mark;
        }

        if($error){ // Write a message error for the user know what to do
            echo '<span class=\'error_message\'>';
            echo $this->error_message( $strict_name );
            echo '</span>';
        }

        echo '</label>';
    }

    // Return true if an input isn't correcly fill
    private function error($strict_name){
        // If the form wasn't be submit at least one time, we return no error
        if( !$this->_already_posted ){
            return false;
        }

        // We get the element...
        $current_element = null;
        if( isset($this->_elements[ $strict_name ]) ){
            $current_element = $this->_elements[$strict_name];
        }
        else{
            return true;
        }
        $current_variable = null;
        // ...then we get its value
        if( isset($this->_variables[ $strict_name ]) ) {
            $current_variable = $this->_variables[ $strict_name ];
        }

        // Error if it have no value
        if( $current_variable == null || $current_variable == false ){
            return true;
        }

        // If the element's type is a text or textarea...
        if( ($current_element['type'] == 'text' || $current_element['type'] == 'textarea')){
            // ... and don't have the minimum string lenght
            if(strlen($this->_variables[ $strict_name ]) < $this->_min_strlen[$current_element['type']]){
                return true;
            }
            if( $current_element['type'] == 'text' && !ctype_alpha( str_replace(' ', '', $current_variable) ) ){ // ... and contains some none letter characters
                return true;
            }
        }
        else if( $current_element['type'] == 'email' ){ // If the elemet's type is an email input...
            // ... but its value is not a valid email adress
            if( !filter_var($current_variable, FILTER_VALIDATE_EMAIL) ){
                return true;
            }
        }

        // No error detected
        return false;
    }

    // Return a message associate with the error type
    private function error_message($strict_name){
        // We get the element...
        $current_element = null;
        if( isset($this->_elements[ $strict_name ]) ){
            $current_element = $this->_elements[$strict_name];
        }
        else{
            return true;
        }
        $current_variable = null;
        // ...then we get its value
        if( isset($this->_variables[ $strict_name ]) ) {
            $current_variable = $this->_variables[ $strict_name ];
        }

        // Error if it have no value
        if( $current_variable == null || $current_variable == false ){
            return 'You must complete this field';
        }

        // If the element's type is a text or textarea...
        if( ($current_element['type'] == 'text' || $current_element['type'] == 'textarea')){
            // ... and don't have the minimum string lenght
            if(strlen($this->_variables[ $strict_name ]) < $this->_min_strlen[$current_element['type']]){
                return $current_element['description'] . ' is too short';
            }
            if( $current_element['type'] == 'text' && !ctype_alpha( str_replace(' ', '', $current_variable) ) ){ // ... and contains some none letter characters
                return $current_element['description'] . ' can contain only alphabetical characters';
            }
        }
        else if( $current_element['type'] == 'email' ){ // If the elemet's type is an email input...
            // ... but its value is not a valid email adress
            if( !filter_var($current_variable, FILTER_VALIDATE_EMAIL) ){
                return 'This email adress isn\'t valid';
            }
        }

        // No error detected
        return 'Something wrong here';
    }
    
    // Return 'true' if the form is correctly fill
    public function is_valid(){
        return $this->_valid;
    }

    // Write and return a message from form's data
    public function get_message($complete=false){
        $message = '';

        if($complete){
            $message .= '<html><body>';
        }

        $message .= '<h2>Hackers Poulette - Technical Support</h3>';
        $message .= '<h3>Message :</h3>';
        $message .= '<p>';
        $message .= nl2br($this->_variables['textarea-message']);
        $message .= '</p>';

        if($complete){
            $message .= '</body></html>';
        }

        return $message;
    }

    public function get_mail_header(){
        $email_adress = $this->_variables['email-email'];

        // $mail_header = 'From: ' . strip_tags( $email_adress ) . '\r\n';
        // $mail_header .= 'Reply-To: '. strip_tags( $email_adress ) . '\r\n';
        $mail_header = 'From: hackers-poulette@becode.org\r\n';
        $mail_header .= 'Reply-To: hackers-poulette@becode.org\r\n';
        // $mail_header .= 'CC: susan@example.com\r\n';
        $mail_header .= 'MIME-Version: 1.0\r\n';
        $mail_header .= 'Content-Type: text/html; charset=UTF-8\r\n';

        return $mail_header;
    }

    // Return a value at the index
    public function get($index_value){
        return $this->_variables[$index_value];
    }
}

?>