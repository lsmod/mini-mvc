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
 
assert_options(ASSERT_CALLBACK, 'assertCallback');

function assertCallback($file, $line, $msg )
{
   echo "<hr>Assertion Failed: <br/>
      File '$file'<br />
      Line '$line'<br />
      Error: ". preg_replace( '/^.+\/\/\*/', '', $msg ) ."<br /><hr />";
   exit;
} 

?>