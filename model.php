<?php

/*
 * minimalist model class 
 * really just a way to access to database by the object of your choice
 *
 */
class Model
{
   protected $db;

   /**
    * 
    * @param object $db DBO object of your choice
    */
   public function __construct($db)
   {
      assert('isset($db); //* $db should be set');
      $this->db =& $db;
   }
}
?>
