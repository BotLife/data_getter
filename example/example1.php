<?php

require_once __DIR__ . '/../src/DataGetter.php';

class Foo
{
    
    const GENDER_MALE   = 1;
    const GENDER_FEMALE = 2;
    
    public function __construct()
    {
        DataGetter::addCallback(
        	'get-gender', 'get-by-name', array($this, 'getByName'), 50
    	);
        DataGetter::addCallback(
        	'get-gender', 'get-by-user', array($this, 'getByUser'), 20
    	);
    }
    
    public function getByName($name)
    {
        $names = array(
        	'henk' => self::GENDER_MALE, 'truus' => self::GENDER_FEMALE
    	);
        if (isset($names[$name])) {
            return $names[$name];
        }
        return false;
    }
    
    public function getByUser($name)
    {
        $names = array(
        	'henk123' => self::GENDER_MALE, 'truus456' => self::GENDER_FEMALE
        );
        if (isset($names[$name])) {
            return $names[$name];
        }
        return false;
    }
    
}

new Foo;

var_dump(DataGetter::getData('get-gender', 'non-existing-name'));
var_dump(DataGetter::getData('get-gender', 'henk'));
var_dump(DataGetter::getData('get-gender', 'truus456'));

DataGetter::addCallback(
	'get-gender', 'just-return-male', 'bla', 10
);

function bla($name)
{
    return Foo::GENDER_MALE;
}

var_dump(DataGetter::getData('get-gender', 'non-existing-name'));