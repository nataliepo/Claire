<?php

include_once('tp-utilities.php');

class TPDate {
   var $year;
   var $month;
   var $day;
   var $hour;
   var $min;
   var $sec;

   // contructor
   function TPDate($timestring) {
     
     // TypePad 
     $pattern = '/Z/';
     preg_match($pattern, $timestring, $matches);
     
     # a 'Z' in the timestamp comes from TypePad.
     if (sizeof($matches) > 0) {
        $this->create_tp_date($timestring);
     }
     else {
        $this->create_fb_date($timestring);
     }
   }
    
    function create_tp_date($timestamp) {
       
       // EXAMPLE:
       //  2010-03-16T18:58:20Z
       $pattern = '/^([\d]{4})-([\d]{2})-([\d]{2})T([\d]{2}):([\d]{2}):([\d]{2})Z/';
       preg_match($pattern, $timestamp, $matches);

       $this->year   = $matches[1];
       $this->month  = $matches[2];
       $this->day    = $matches[3];
       $this->hour   = $matches[4];
       $this->min    = $matches[5];
       $this->sec    = $matches[6];
    }
    
    function create_fb_date($timestring) {
       // return date("m d, Y g:ia", $time);        
       $this->year   =  date("Y", $timestring);
       $this->month  =  date("m", $timestring);
       $this->day    =  date("d", $timestring);
       $this->hour   =  date("H", $timestring);
       $this->min    =  date("i", $timestring);
       $this->sec    =  date("s", $timestring);
            
    }


    function print_sortable_time() {
       return $this->year . $this->month . $this->day . $this->hour . $this->min . $this->sec;
    }
    
    function print_readable_time() {
       return $this->month . " " . $this->day . ", " . $this->year . " at " . $this->hour . ":" .
         $this->min;
    }
    
  
    
 }


?>