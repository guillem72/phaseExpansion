<?php

namespace glluch\phaseExpansion;

require_once "Positions.php";

/**
 * Description of term
 *
 * @author Guillem LLuch Moll <guillem72@gmail.com>
 */
class Term  {
   protected $term;
   protected $positions;
   
   function getTerm() {
       return $this->term;
   }

   function getPositions() {
       return $this->positions;
   }

   function setTerm($term) {
       $this->term = $term;
   }

   

   function __construct($term) {
       $this->term = $term;
       $this->positions=new Positions();
   }

   
}
