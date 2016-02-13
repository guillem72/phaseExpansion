<?php

namespace glluch\phaseExpansion;

/**
 * Description of Position
 *
 * @author Guillem LLuch Moll <guillem72@gmail.com>
 */
class Interval {
   public $start;
   public $end;
   
   function __construct($start, $end) {
       if ($end>$start){
       $this->start = $start;
       $this->end = $end;
       }
       if ($end<=$start){
       $this->start = $end;
       $this->end = $start;
       }
      
   }

   
   public function lenght(){
       return $this->end-$this->start;
   }
   
   /**
    * Check if an interval is inside another. 
    * @var $pos Interval The Inverval to compare
    * @return int 1 If the var $pos is completely inside the interval; 0 if the they don't 
    * ovelap; -1 if the actual interval is inside $pos; false otherwise (there is some overlap, 
    * but not completely )  
    *      */
   public function contain($pos){
       if ($this->start < $pos->start AND $this->end > $pos->end) {
            return 1; //pos is in this
        }
        if ($pos->end < $this->start OR $pos->start > $this->end) {
            return 0;
        }
        if ($pos->start < $this->start AND $pos->end > $this->end) {
            return -1;//this is in pos
        }
        return false;
   }
   
   public function arrayContain($positions){
       $cont=[];
       foreach ($positions as $key=>$pos){
           $cont[$key]=$this->contain($pos);
       }
       return $cont;
   }
}
