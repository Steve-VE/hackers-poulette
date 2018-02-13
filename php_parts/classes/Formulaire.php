<?php

class Formulaire{
    private $_elements = [];
    private $_variables = [];
    private $_schema = [];
    private $_valid;
    private $_already_posted;

    private $_min_strlen;

    public function __construct(){
        if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
            $this->_already_posted = true;
        }
        else{
            $this->_already_posted = false;
        }
        $this->_valid = $this->_already_posted;

        $this->_min_strlen["text"] = 2;
        $this->_min_strlen["textarea"] = 24;
    }


    // Add an element in this formulaire
    public function add_element($name, $desription, $type="text", $mentadory=true, $options=null){
        $strict_name = $type."-".$name;
        // Add the element with all its values
        $this->_elements[ $strict_name ] = [
            "name" => $name,
            "description" => $desription,
            "type" => $type,
            "mendatory" => $mentadory,
            "options" => $options
        ];
        
        // We look what type of filter we need to sanitize;
        $filter_sanitize;
        switch($type){
            case "email":
            $filter_sanitize = FILTER_SANITIZE_EMAIL;
            break;
            
            default:
            $filter_sanitize = FILTER_SANITIZE_STRING;
        }
        
        // After have adding the element, we check if a value already exist for it
        $this->_variables[ $strict_name ] = try_to_get($strict_name, $filter_sanitize);

        // We look if we need to mark the formulaire as not valid
        if( $this->_variables[ $strict_name ] == null 
        || $this->_variables[ $strict_name ] == false 
        || !isset($this->_variables[ $strict_name ]) 
        || (($type == "text" || $type == "textarea") && strlen($this->_variables[ $strict_name ]) <= $this->_min_strlen[$type]) ){
            $this->_valid = false;
        }
    }

    // Draw the element in HTML
    public function print_element($strict_name){
        $current_element = $this->_elements[ $strict_name ];
        $value = "";

        if($current_element != null){

            echo '<p';
            // Check if a valid value exist already
            if($this->_already_posted){
                if($this->error($strict_name)){ // If not, we class it like an error.
                    if($this->_already_posted) echo ' class="error"';
                }
                $value = $this->_variables[$strict_name];
            }
            echo '>';
            
            // If a description exist, we create a label
            if(strlen($current_element['description']) > 0){ 
                labelise( $current_element['description'], $current_element['name'], $current_element['mendatory'] );
            }
            
            if($current_element['type'] == "text" || $current_element['type'] == "email"){ // Create an <input>
                echo '<input type="'. $current_element['type'] .'" name="'. $strict_name .'" id="'. $current_element['name'] .'"';
                
                // Check if a valid value exist already
                if( $value!== "" ){
                    echo ' value="' . $value . '"';
                }
                echo '/>';
            }
            else if($current_element['type'] == "textarea"){ // Create an <area>
                echo '<textarea name="' . $strict_name . '" id="' . $current_element['name'] . '">';
                if( $value!== "" ){
                    echo $value;
                }
                echo '</textarea>';
            }
            else if($current_element['type'] == "select" && $current_element['options'] !== null){ // Instead of an input, create a <select>
                echo '<select name="' . $strict_name . '" id="' . $current_element['name'] . '">';
                foreach( $current_element['options'] as $keyOption => $valueOption ){
                    
                    if( is_numeric( $keyOption) ){
                        $actualValue = $valueOption;
                    }
                    else {
                        $actualValue = $keyOption;
                    } 
                    echo '<option value="' . $actualValue . '"';
                    if( $value!== "" && $value == $actualValue){
                        echo ' selected="selected"';
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
                    echo '<input type="radio" name="' . $strict_name . '" name="' . $current_element['name'] . '" value="' . $valueOption . '"';
                    if( $value!== "" && $value === $valueOption
                    || $i == 0 ){
                        echo ' checked="checked"';
                    }
                    echo '/> ';
                    echo ucfirst( $valueOption ) . ' ';
                    $i++;
                }
            }
            
            echo "</p>";
        }
    }

    // Return true if an input isn't correcly fill
    private function error($strict_name){
        $current_element = $this->_elements[$strict_name];

        if( isset($this->_variables[$strict_name]) 
        && $this->_variables[$strict_name] != null ){
            if( ($current_element['type'] == "text" || $current_element['type'] == "textarea")){
                if(strlen($this->_variables[ $strict_name ]) > $this->_min_strlen[$current_element['type']]){
                    return false;
                }
            }
            else{
                return false;
            }
        }

        return true;
    }

    // Return 'true' if the form is correctly fill
    public function is_valid(){
        return $this->_valid;
    }

    public function get_message($mode="mail"){
        if($mode == "mail"){
            $message = 'Hackers Poulette - Technical Support\n';
            $message .= 'From : ' . $this->_variables['text-user-firstname'] . ' ';
            $message .= $this->_variables['text-user-lastname'] . '\n';
            $message .= 'Mail : ' . $this->_variables['email-email'] . '\n';
            $message .= 'About : ' . $this->_variables['select-subject'] . '\n';
            $message .= 'Message :\n' . $this->_variables['textarea-message'];
        }
        else{
            $message = '<h1>Hackers Poulette - Technical Support</h1>';
            $message .= '<p>';
            $message .= 'From : ' . $this->_variables['text-user-firstname'] . ' ';
            $message .= $this->_variables['text-user-lastname'] . '<br/>';
            $message .= 'Mail : ' . $this->_variables['email-email'] . '<br/>';
            $message .= 'About : ' . $this->_variables['select-subject'] . '<br/>';
            $message .= 'Message :<br/>' . $this->_variables['textarea-message'];
            $message .= '</p>';
        }

        return $message;
    }

    public function get($index_value){
        return $this->_variables[$index_value];
    }
}

?>