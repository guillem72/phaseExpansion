<?php
namespace glluch\phaseExpansion;

require_once "Term.php";

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
    protected $positions;
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
        $this->positions=null;
    }

    public function find() {
        if (\count($this->found) > 0) {
            return $this->found;
        }
        foreach ($this->terms as $term) {
            $positions = $this->findTerm($term);
            if ($positions) {
                $this->found[$term] = $positions;
            }
        }
        return $this->found;
    }

    protected function findTerm($term) {
        if (str_word_count($term) > 1) {
            $term_len=strlen($term);
            $found = [];
            $first_pos = strpos($this->doc, $term);
            if ($first_pos === false) {//the term isn't in doc
                return false;
            }
            $found[] =[$first_pos,$first_pos+$term_len];
            
            $last_pos = strrpos($this->doc, $term);
            $pos = $first_pos;
            while ($pos !== $last_pos) {
                $pos = \strpos($this->doc, $term, $pos + \strlen($term) - 1);
                $found[] = [$pos,$pos+$term_len];
            }
            return $found;
        } else {
            return $this->findWord($term);
        }
    }

    protected function findWord($term) {
        $keys = array_keys($this->docWords, $term);
         $term_len=strlen($term);
        //if (!\in_array($term, $this->docWords)) {
        if (\count($keys) < 1) {
            return false;
        }
        $found = [];
        foreach ($keys as $key) {
            $previous0= \array_slice($this->docWords,0, $key);
            $previous=implode(" ",$previous0);
            $found[]=[\strlen($previous)+1, \strlen($previous)+1+$term_len];
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
    protected function deleteFP(){//delete false positives
        foreach ($this->found as $term => $poss){
            
        }
    }

}
