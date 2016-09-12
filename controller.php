<?php

class Controller
{
   protected $db;
   protected $view = null;
   protected $toshow = null;
   protected $view_data = null;
   protected $skin = null;

   /**
    * 
    * @param string $actionName http parametter name used to call controller action (ex: ?action=delete, ?decision=delete)
    * @param object $db DBO object of your choice (will be used by models)
    * @param string $forcedAction if define this action will be call regarless of http param sent by users
    */
   public function __construct($actionName, $db = null, $forcedAction = null)
   {
      assert('!empty($actionName); //* $actionName should not be empty');

      if($db != null)
         $this->db =& $db; // give database acces

      $this->view_data = new stdClass();
      
      if(is_null($forcedAction)):  // no action called manually 
         if(isSet($_GET[$actionName])): // action sent by GET?
            $action = $_GET[$actionName];

            if(method_exists($this, $action) and array_key_exists($action, static::$action_get)):
               call_user_func(array($this, $action));
            else:
               throw New Exception("'$action' controller action not exists", 404);
            endif;
         elseif(isSet($_POST[$actionName])): // action sent by POST?
            $action = $_POST[$actionName];

            if(method_exists($this, $action) and array_key_exists($action, static::$action_post)):
               call_user_func(array($this, $action));
            else:
               throw new Exception("'$action' controller action not exists", 404);
            endif;
         else: // no action given -> index function call by default
            $action = "index";
            if(method_exists($this, $action) and array_key_exists($action, static::$action_get)):
               call_user_func(array($this, $action));
            else:
               throw New Exception("'$action' controller action not exists", 404);
            endif;
         endif;
       else: // forced action
         if(method_exists($this, $forcedAction) and (array_key_exists($forcedAction, static::$action_get) or  array_key_exists($forcedAction, static::$action_post))):
            call_user_func(array($this, $forcedAction));
         else:
            throw New Exception("'$forcedAction' controller action not exists", 404);
         endif;
       endif;
   }

   /**
    * Return DBO acces object
    *
    */
   public function getDB()
   {
      return $this->db;
   }   

   /**
    * Return view's content
    *
    */
   public function display()
   {
      if(!($this->view != null xor $this->toshow != null))
         throw new Exception ('view confusion!');

      assert('($this->view != null xor $this->toshow != null); //* view confusion!');

      if($this->toshow == null):
         ob_start();
         require_once('inc/views/'.$this->view.'.php');
         $this->toshow = ob_get_contents();
         ob_end_clean();
      endif;

      assert('!is_null($this->toshow); //* $this->toshow should NOT be empty');
      return $this->toshow;
   }

   /**
    * Return asked model
    * (instanciates modelnameModel class
    *  supposedly declared in inc/models/modelname.php file)
    * 
    * @param string $modelname model's name
    */
   protected function getModel($modelname)
   {
      assert('!empty($modelname); //* $modelname should not be empty');

      if(!isset($this->db))
         throw New Exception ("Try to get model and you didn't give DB acces to controller");

      if(!file_exists('inc/models/'. $modelname . '.php')):
         throw New Exception("'$modelname.php' file don't exists");
      else:
         require_once('inc/models/'. $modelname . '.php');
      endif;

      if(!class_exists($modelname.'Model')):
         throw new Exception("$modelname.php cant find ".$modelname."Model class in there");
      else:
         $modelname .= 'Model';
         $model = new $modelname($this->db);
      endif;

      assert('is_object($model); //* $model should be an object');
      return $model;
   }

   /**
    * Define view used by the current action 
    * (include inc/views/$view.php file
    *  this is this file content that deplay() return) 
    *
    * @param string $view view's name
    */
   protected function setView($view)
   {
      assert('!empty($view); //* $view should NOT be empty');

      if(!file_exists ('inc/views/'.$view.'.php')):
         throw new Exception ("'$view' view don't exists.");
      else:
         $this->view = $view;
      endif;

      assert('!empty($this->view); //* $this->view should NOT be empty');
   }
   
   /**
    * Return view's name currently used by the controller
    *
    */
   public function getView()
   {
      assert('!empty($this->view); //* $view should NOT be empty');
      return $this->view;
   }
   
   /**
    * Return data handed over to the view 
    *
    */
   public function getViewData()
   {
      return $this->view_data;
   }

   public function setViewData($data)
   {
      $this->view_data = $data;
   }
   
   public function getSkin()
   {
      return $this->skin;
   }
   
   public function setSkin($skin)
   {
      $this->skin = $skin;
   }
}

?>
