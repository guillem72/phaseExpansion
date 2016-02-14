<?php

namespace glluch\phaseExpansion;

/**
 * Description of wordSearch
 *
 * @author Guillem LLuch Moll <guillem72@gmail.com>
 */
class Expand {

    protected $terms; //array of terms
    protected $doc;
    protected $docWords;
    protected $expansion; //array of new sentences with some terms changed
    protected $found; //the terms found and their start and end

    function getDoc() {
        return $this->doc;
    }

    function setDoc($doc) {
        $this->doc = \strtolower($doc);
        $this->docWords = \explode(" ", $this->doc);
    }

    public function reset() {
        $this->terms = null; //array of terms
        $this->doc = null;
        $this->expansion = null; //array of new sentences with some terms changed
        $this->found = null; //the terms found and their position
        $this->docWords = null;
    }

    public function find() {
        if (\count($this->found) > 0) {
            return $this->found;
        }
        foreach ($this->terms as $term) {
            $positions = $this->findTerm($term,$this->doc);
            if ($positions) {
                $this->found[$term] = $positions;
            }
        }
        $this->deleteFP();
        return $this->found;
    }

    protected function findTerm($term,$doc) {
        if (str_word_count($term) > 1) {
            $term_len = strlen($term);
            $found = [];
            $first_pos = strpos($doc, $term);
            if ($first_pos === false) {//the term isn't in doc
                return false;
            }

            $found[$first_pos] = $first_pos + $term_len;

            $last_pos = strrpos($doc, $term);
            $pos = $first_pos;
            while ($pos !== $last_pos) {
                $pos = \strpos($doc, $term, $pos + \strlen($term) - 1);
                $found[$pos] = $pos + $term_len;
            }
            return $found;
        } else {
            return $this->findWord($term,\explode(" ", $doc));
        }
    }

    protected function findWord($term,$docWords) {
        $keys = array_keys($docWords, $term);
        $term_len = strlen($term);
        //if (!\in_array($term, $this->docWords)) {
        if (\count($keys) < 1) {
            return false;
        }
        $found = [];
        foreach ($keys as $key) {
            $previous0 = \array_slice($docWords, 0, $key);
            $previous = implode(" ", $previous0);
            $start=\strlen($previous) + 1;
            $found[$start] = $start + $term_len;
        }
        return $found;
    }

    public function setTermsFromTaxo($arr) {//arr child=>parents
        $arr0 = \array_keys($arr);
        $this->terms = $this->toLower($arr0);
        return $this->terms;
    }

    protected function toLower($arr) {
        $lower = [];
        foreach ($arr as $key => $value) {
            $lower[\strtolower($key)] = \strtolower($value);
        }
        return $lower;

//return \array_filter($arr, "\strtolower");
    }

    function deleteFP() {//delete false positives
        $deleted = 0;
        $i=0;
        while ($i<\count($this->found)) {
            $deleted+=$this->twice($i);
            $i++;
        }
    }

    protected function twice($i) {
        $terms=$this->found;
       $keys=  array_keys($terms);
        
       $actual_term=  $keys[$i];
       $actual_index=$i;
       $dels=0;
       //var_dump($keys);
       while ($i<\count($terms)-1)
      {
           $i++;
           //echo $i.PHP_EOL;
           $found=$this->findTerm($keys[$i],$actual_term); //the word could be part of actual.
          $found0=$this->findTerm($actual_term,$keys[$i]); //actual could be part of the term.
          if ($found!==false AND \count($found) > 0) {
              $dels+= $this->delete($actual_index,$i,array_keys($found)[0]);
              //echo $keys[$actual_index]." CONTAINS ".  $keys[$i].PHP_EOL;
              //var_dump($found);
            }
            if ($found0!==false AND \count($found0) > 0) {
                $dels+=$this->delete($i,$actual_index,array_keys($found)[0]);
                //echo $keys[$actual_index]." IS IN ".  $keys[$i].PHP_EOL;
                //var_dump($found0);
            }
           
        }
        return $dels;
    }//twice
    
    protected function delete($large,$small,$offset){//small is part of large
        $terms=$this->found; 
        $keys=  array_keys($terms);
        $dels=0;
    foreach ($terms[$keys[$large]] as $lstart => $lend){
        foreach ($terms[$keys[$small]] as $sstart => $send){
            //echo $lstart+$offset."=?". $sstart.PHP_EOL;
            if ($lstart+$offset === $sstart OR $lstart+$offset-1 === $sstart){
                //echo $keys[$small]." is in ".$keys[$large]." at ". $sstart.PHP_EOL;
                unset($this->found[$keys[$small]][$sstart]);
                $dels++;
                if (\count($this->found[$keys[$small]]) < 1) {//all terms found are deleted
                        unset($this->found[$keys[$small]]);
                    }
                }
        }
    }        
        
        return $dels;
    }
    

}
