<?php

class DataGetter
{

    private static $_callbacks = array();
    
    static public function addCallback($getter, $id, $callback, $priority)
    {
        if (!is_string($getter)
            || (!is_string($id))
            || (!is_callable($callback))
            || (!is_int($priority))
        ) {
            throw new InvalidArgumentException(
            	'One of the arguments was invalid'
        	);
        }
        self::$_callbacks[$getter][$id] = (object) array(
        	'callback' => $callback, 'priority' => $priority
    	);
        usort(self::$_callbacks[$getter], 'DataGetter::_sortCallbacks');
        //echo 'Added callback ' . $id . ' - Priority: ' . $priority . PHP_EOL;
    }
    
    static public function getData($getter)
    {
        $arguments = func_get_args();
        array_shift($arguments);
        foreach (self::$_callbacks[$getter] as $callback) {
            $data = call_user_func_array($callback->callback, $arguments);
            /*echo 'Call to ' . $callback->callback[1] . '. Data: '
                . print_r($data, true) . PHP_EOL;*/
            if ($data !== false) {
                return $data;
            } 
        }
    }
    
    static private function _sortCallbacks($previous, $new)
    {
        if ($previous->priority == $new->priority) {
            return 0;
        }
        return ($previous->priority > $new->priority) ? -1 : 1;
    }
    
}