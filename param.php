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
   * Return casted http parameter sent by GET
   * if the parameter is not define null is return
   *
   * @param string $name parameter's name sent by GET
   * @param string $type Type of data to return:
   * @return int|string|bool|float|array parameter sent by GET
   */
function getGet($name, $type)
{
   assert('is_string($name); //* $name should be a string');
   assert('is_string($type); //* $name should be a string');

   if(isSet($_GET[$name]))
   {
   switch ($type)
      {
         case 'int':
            return (int) $_GET[$name] + 0;
            break;
         case 'string':
            return (string) $_GET[$name];
            break;
         case 'bool':
            return (bool) $_GET[$name];
            break;
         case 'float':
            return (float) $_GET[$name];
            break; 
         case 'array':
            return (array) $_GET[$name];
            break;
         default:
            throw new Exception("$type is not a valid type");
      }
   }
   else
      return null;
}

/**
   * Return casted http parameter sent by POST
   * if the parameter is not define null is return
   *
   * @param string $name parameter's name sent by POST
   * @param string $type Type of data to return:
   * @return int|string|bool|float|array parameter sent by POST
   */
function getPost($name, $type)
{
   assert('is_string($name); //* $name should be a string');
   assert('is_string($type); //* $name should be a string');

   if(isSet($_POST[$name]))
   {
      switch ($type)
      {
         case 'int':
            return (int) $_POST[$name] + 0;
            break;
         case 'string':
            return (string) $_POST[$name];
            break;
         case 'bool':
            return (bool) $_POST[$name];
            break;
         case 'float':
            return (float) $_POST[$name];
            break; 
         case 'array':
            return (array) $_POST[$name];
            break;
         default:
            throw new Exception("$type is not a valid type");
      }
   }
   else
      return null;
}

/**
   * Check if given variable is empty 
   * work with array and string too
   *
   * @param mixed $param parameter's name sent by POST
   * @return bool 
   */
function is_empty($param)
{
   if(is_null($param)):
      return true;
   elseif(is_string($param)):
      if(empty($param)):
         return true;
      endif;
   elseif(is_array($param)):
      if(count($param) == 0):         
         return true;
      endif;
   else:
      return false;
   endif;
}
?>
