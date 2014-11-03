<?php


/**
 * Load the persistent variable table.
 *
 * The variable table is composed of values that have been saved in the table
 * with variable_set() as well as those explicitly specified in the configuration
 * file.
 */
function variable_init($conf = array()){
  // NOTE: caching the variables improves performance by 20% when serving cached pages.
  if ($cached = cache_get('variables', 'cache')) {
    $variables = $cached->data;
  } else {
    $result = db_query('SELECT * FROM {variable}');
    while ( $variable = db_fetch_object($result) ) {
      $variables [$variable->name] = unserialize($variable->value);
    }
    cache_set('variables', $variables);
  }
  
  foreach ( $conf as $name => $value ) {
    $variables [$name] = $value;
  }
  
  return $variables;
}


/**
 * Returns a persistent variable.
 *
 * Case-sensitivity of the variable_* functions depends on the database
 * collation used. To avoid problems, always use lower case for persistent
 * variable names.
 *
 * @param $name The
 *          name of the variable to return.
 * @param $default The
 *          default value to use if this variable has never been set.
 * @return The value of the variable.
 *        
 * @see variable_del(), variable_set()
 */
function variable_get($name, $default){
  global $conf;
  
  return isset($conf [$name]) ? $conf [$name] : $default;
}


/**
 * Sets a persistent variable.
 *
 * Case-sensitivity of the variable_* functions depends on the database
 * collation used. To avoid problems, always use lower case for persistent
 * variable names.
 *
 * @param $name The
 *          name of the variable to set.
 * @param $value The
 *          value to set. This can be any PHP data type; these functions take care
 *          of serialization as necessary.
 *          
 * @see variable_del(), variable_get()
 */
function variable_set($name, $value){
  global $conf;
  
  $serialized_value = serialize($value);
  db_query("UPDATE {variable} SET value = '%s' WHERE name = '%s'", $serialized_value, $name);
  if (! db_affected_rows()) {
    @db_query("INSERT INTO {variable} (name, value) VALUES ('%s', '%s')", $name, $serialized_value);
  }
  
  cache_clear_all('variables', 'cache');
  
  $conf [$name] = $value;
}


/**
 * Unsets a persistent variable.
 *
 * Case-sensitivity of the variable_* functions depends on the database
 * collation used. To avoid problems, always use lower case for persistent
 * variable names.
 *
 * @param $name The
 *          name of the variable to undefine.
 *          
 * @see variable_get(), variable_set()
 */
function variable_del($name){
  global $conf;
  
  db_query("DELETE FROM {variable} WHERE name = '%s'", $name);
  cache_clear_all('variables', 'cache');
  
  unset($conf[$name]);
}






