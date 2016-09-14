<?php
/*
 *
 Copyright 2016 Trigallez Arno
 Distributed under the terms of the GNU Lesser General Public License v3
 
 This file is part of the mini-mvc library.
 
 mini-mvc is free software: you can redistribute it and/or modify
 it under the terms of the GNU Lesser General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.
 
 mini-mvc is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU Lesser General Public License
 along with mini-mvc.  If not, see <http://www.gnu.org/licenses/>.
 *
 */ 
 
/**
 * DAO class
 */
class DBmySQLi
{
   private $connexion;
   private $cursor;
   private $showQuery = false;

   /**
    * Establish MySQL database connexion
    *
    * @param string $login username 
    * @param string $pass password
    * @param string $base database name
    * @param string $server MySQL server hostname
    */
   public function __construct($login, $pass, $base, $server)
   {
       assert('!empty($login); //* $login should not be empty');
       //assert('!empty($pass); //* $pass should not be empty');
       assert('!empty($base); //* $base should not be empty');
       assert('!empty($server); //* $server should not be empty');

      if(!$this->connexion = @mysqli_connect($server, $login, $pass, $base))
         throw new Exception ('DB server connexion error');

      mysqli_query("SET NAMES 'utf8'", $this->connexion);

      // if we are in auto-escape mode we normalise...
      if(get_magic_quotes_gpc()):
         $_POST = $this->normaliseHTTP($_POST);
         $_GET = $this->normaliseHTTP($_GET);
         $_REQUEST = $this->normaliseHTTP($_REQUEST);
         $_COOKIE = $this->normaliseHTTP($_COOKIE);
         $_SERVER = $this->normaliseHTTP($_SERVER);
      endif;
   }

   public function safe($tosafe)
   {
      assert('is_string($tosafe); //* $tosafe should be a string');
      return mysqli_real_escape_string($this->connexion, $tosafe);
   }

   /** 
    * execute request and return a cursor
    *
    * @param string $query SQL to execute
    * @return cursor resource to request result
    */
   public function query($query)
   {
      assert('!empty($query); //* $query should never be empty');

      if(!$this->cursor = @mysqli_query($this->connexion, $query))
         throw new Exception ("Wrong query : $query\n<br/>MySQL: " . $this->errorMessage());

      if($this->showQuery)
         echo $query . '<br/>';

      return $this->cursor;
   }

   /**
    * execute request and return first result of the first line
    *
    * @param string $query SQL to execute
    * @return mixed first result of the first line
    */
   public function loadResult($query)
   {
      assert('!empty($query); //* $query should never be empty');

       if(!$this->cursor = @mysqli_query($this->connexion, $query)):
         throw new Exception ("Wrong query : $query\n<br/>MySQL: " . $this->errorMessage());
       else:       
         $row = mysqli_fetch_row($this->cursor);
         $result = $row[0];
         mysqli_free_result($this->cursor);
       endif;

      if($this->showQuery)
         echo $query . '<br/>';
         
      assert('!is_array($result); //* loadResult() should NOT return an array');
      assert('!is_object($result); //* loadResult() should NOT return an object');
      return $result;
   }


   /**
    * execute request and return an array of the first row of each line
    *
    * @param string $query SQL to execute
    * @return array[int]string tableau array (first row of each line)
    */ 
   public function loadArray($query)
   {
      assert('!empty($query); //* $query should never be empty');

       if(!$this->cursor = @mysqli_query($this->connexion, $query)):
         throw new Exception ("Wrong query : $query\n<br/>MySQL: " . $this->errorMessage());
       else:
         $result = array();
         while($row = mysqli_fetch_row($this->cursor)):     
            $result[] = $row[0];
         endwhile;
         mysqli_free_result($this->cursor);
       endif;

      if($this->showQuery)
         echo $query . '<br/>';
       
       return $result;
   }

   /**
    * execute request and return an object
    *
    * @param string $query SQL to execute
    * @return object first record in object form
    */
   public function loadObject($query)
   {
      assert('!empty($query); //* $query should never be empty');

       if(!$this->cursor = @mysqli_query($this->connexion,$query )):
         throw new Exception ("Wrong query : $query\n<br/>MySQL: " . $this->errorMessage());
       else:
         $result = mysqli_fetch_object($this->cursor);
         mysqli_free_result($this->cursor);
       endif;

      if($this->showQuery)
         echo $query . '<br/>';

       return $result;
   }

   /**
    * execute request and return an array of object
    *
    * @param string $query SQL to execute
    * @return array[int]object all records in object form
    */
   public function loadObjectList($query)
   {
      assert('!empty($query); //* $query should never be empty');

       if(!$this->cursor = @mysqli_query($this->connexion, $query)):
         throw new Exception ("Wrong query : $query\n<br/>MySQL: " . $this->errorMessage());
       else:
         $result = array();
         while($row = mysqli_fetch_object($this->cursor)):
            $result[] = $row;
         endwhile;
         mysqli_free_result($this->cursor);
       endif;

      if($this->showQuery)
         echo $query . '<br/>';

       return $result;
   }

   /**
    * return MySQL error message
    *
    * @return string error message
    */
   private function errorMessage()
   {
      return mysqli_error($this->connexion);
   }

   /**
    * Remove all auto-escaping of HTTP datas
    * 
    * @param array $tableau
    */
   private function normaliseHTTP($tableau)
   {
      assert('isset($tableau); //* $tableau should be set');
      
      foreach ($tableau as $cle => $valeur): 
         if (!is_array($valeur)) 
            $tableau[$cle] = stripSlashes($valeur);
         else  
            $tableau[$cle] = $this->normaliseHTTP($valeur);
      endforeach;      
      return $tableau;
   }

   /**
    * define if sql resquet are to be display (by echo function)
    *
    * @param bool $show if true request would be display
    */
   public function echoQuery($show)
   {
      assert('is_bool($show); //* $show should be a boolean');
      $this->showQuery = $show;
   }
   
   function __destruct()
   {
      mysqli_close($this->connexion);
   }
}
?>
