<?php

namespace app\Core;

abstract class Model
{
    //Required rules for validation
    public const RULE_REQUIRED = 'requires';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_EMAIL = 'email';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    //Array to storage erros from validation
    public array $errors = [];

    //Messages for rules
    public function ruleMassegase(): array
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_UNIQUE => 'This field must be unique',
            self::RULE_EMAIL => 'This rule must be email',
            self::RULE_MIN => 'Length of this field must be lower that {min}',
            self::RULE_MAX =>'Length of this field must be greater that {max}',
            self::RULE_MATCH => 'This field must be the same as the {match}'
        ];
    }

    //Inserting new data in model attributes and checking for this attributes.
    public function loadData($data)
    {
        foreach($data as $key => $value){
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    //Defines rules for every attributes. Result will look like arr['attributeName',['Rule-1', 'Rule-2,'...'Rule-n']]
    abstract public function rules(): array;

    //Checking getting rules form fules() and applyin int to every attribute
    public function validation()
    {
        $arrFromRules = $this->rules();
        foreach($arrFromRules as $attribute => $rules) {
            $attr = $this->{$attribute}; //Model attribute
            foreach($rules as $rule) {
                $ruleName = $rule; //Going deep to the array of Rules
                if(!is_string($ruleName)) {
                    $ruleName = $rule[0]; //This means, that rule may contain arguments like: ['RULE_MAX', 255]. But, when you have just rule, you use it?
                }
            }
            //Cheking for rules matching
            if($ruleName === self::RULE_REQUIRED && !$attr) {//If attrubute will empty, or rule name mathched with rules -> will push error 
                //write error;
            }
            if($ruleName === self::RULE_EMAIL && !filter_var($attr, FILTER_VALIDATE_EMAIL)) {//If email attribute doesnt be email, or rule name mathched with rules -> will push error 
                //write error;
            }
            if($ruleName === self::RULE_MIN && (strlen($attr) < $rule['min'])) {//If attribute size will be smaller than the rule defined min value, or rule name mathched with rules -> will push error 
                //write error;
            }
            if($ruleName === self::RULE_MAX && (strlen($attr > $rule['max']))) {//If attribute size will be bigger than the rule defined min value, or rule name mathched with rules -> will push error 
                //write error;
            }
            if($ruleName === self::RULE_MATCH && $attr !=$this->{$rule['match']}) {//If attrubute doesnt exist in model, or rule name mathched with rules -> will push error 
                //write error;
            } 
            if($ruleName === self::RULE_UNIQUE) {//If attrubute doesnt matched with setted match value, or rule name mathched with rules -> will push error 
                
                
                //write error;
            }
        }

        //Return true if arrays with errors will be empty
        
        return empty($this->errors);
    }

    public function addError($attribute, $message)
    {
        $this->errors[$attribute][] = $message;
    }

    //Replaced {{error}} are from views
    public function addErrorForRule(string $attribute, string $rule, array $params = [])
    {
        $message = $this->ruleMassegase()[$rule] ?? 'Message for this rule not created';

        foreach($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }

       $this->addError($attribute, $message);
    }

    //Checking for errors in the attribute
    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    //Getting first error in the attribute
    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }

    //Array of labels
    public function labels():array
    {
        return [];
    }

    //Recieving label for attribute
    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? '';
    }



}

?>