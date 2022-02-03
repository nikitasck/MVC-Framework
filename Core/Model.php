<?php

namespace app\Core;

abstract class Model
{
    //Rules for validation
    public const RULE_REQUIRED = 'requires';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_EMAIL = 'email';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';
    public const RULE_IMAGE_FILE = 'image_file';
    public const RULE_FILE_SIZE = 'image_size';
    public const RULE_FILE_TYPE = 'image_file_type';

    //Contains validation errors.
    public array $errors = [];

    //Messages for rules
    protected array $ruleMessages  = [
        self::RULE_REQUIRED => 'This field is required',
        self::RULE_UNIQUE => 'This field must be unique',
        self::RULE_EMAIL => 'This field must be email',
        self::RULE_MIN => 'Length of this field must be lower that {min}',
        self::RULE_MAX =>'Length of this field must be greater that {max}',
        self::RULE_MATCH => 'This field must be the same as the {match}',
        self::RULE_IMAGE_FILE => 'Please, uploade image file', 
        self::RULE_FILE_SIZE => 'Sorry, your file is too large. Max size 7mb.',
        self::RULE_FILE_TYPE => 'Please, use correct image format: jpg,jpeg,png.'  
    ];

    public function getRuleMessages(): array
    {
        return $this->ruleMessages;
    }

    //Inserting new data in matching model attributes.
    public function loadData($data)
    {
        foreach($data as $key => $value){
            if(property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    //Labels for model attribute.
    public function labels():array
    {
        return [];
    }

    //Defines rules for every attributes. Result will look like arr['attributeName' => ['Rule-1', 'Rule-2,'...'Rule-n']]
    abstract public function rules(): array;

    //Validate model attribute. If rule doesn't pass rule, insert error message in error arrays.
    public function validation()
    {
        foreach($this->rules() as $attribute => $rules) {
            $attr = $this->{$attribute}; //Model attribute
            foreach($rules as $rule) {
                $ruleName = $rule; //Get rule.
                if(!is_string($ruleName)) {//if rule contains values.
                    $ruleName = $rule[0]; 
                }
            
                //Checking for compliance with the rule.
                if($ruleName === self::RULE_REQUIRED && !$attr) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if($ruleName === self::RULE_EMAIL && !filter_var($attr, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if($ruleName === self::RULE_MIN && strlen($attr) < $rule['min']) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if($ruleName === self::RULE_MAX && strlen($attr) > $rule['max']) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if($ruleName === self::RULE_MATCH && $attr != $this->{$rule['match']}) {
                    $rule['match'] = $this->getLabel($rule['match']);
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                } 
                if($ruleName === self::RULE_UNIQUE) {
                    $className = $rule['class'];
                    $uniqueAttribute = $rule['attribute'] ?? $attribute;
                    $tableName = $className::tableName();
                    
                    $statement = Application::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttribute = :attr");
                    $statement->bindValue(":attr", $attr);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if($record) {
                        $this->addErrorForRule($attribute, SELF::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
                    }
                }
                if($ruleName === self::RULE_IMAGE_FILE){
                    if(!is_file($_FILES[$attribute]["tmp_name"])){
                        $this->addError($attribute, 'Please, uploade file.');
                    }
                }
                if($ruleName === self::RULE_FILE_SIZE && ($_FILES[$attribute]["size"] > $rule['size'])){
                        $this->addError($attribute, 'Sorry, your file is too large. Max size 7mb.');
                    }
                if($ruleName === self::RULE_FILE_TYPE){
                    //File extension;
                    $file = $_FILES[$attribute]['name'];
                    $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                    if(!in_array($fileExtension, $rule['type'])){
                        $this->addError($attribute, 'Please, use correct image format: jpg,jpeg,png.');
                    }
                }
            }
        }
        return empty($this->errors);
    }

    //Adding errors to the error array.
    public function addError($attribute, $message)
    {
        $this->errors[$attribute][] = $message;
    }

    //Replaced {{error}} from views
    public function addErrorForRule($attribute, $rule, $params = [])
    {
        $message = $this->getRuleMessages()[$rule] ?? 'Message for this rule not created';

        foreach($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }

       $this->addError($attribute, $message);
    }

    //Checking for errors in the attribute.
    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    //Getting first error in the attribute
    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }

    //Recieving label for attribute
    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? '';
    }


}

?>