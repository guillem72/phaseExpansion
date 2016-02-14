<?php

namespace glluch\phaseExpansion;

/**
 * This class is for search a list of word (in array format) in a document 
 *
 * @author Guillem LLuch Moll <guillem72@gmail.com>
 * @version 14022016
 */
class FindTerms {

    /**
     * @var $terms string[] The array of terms in lower case. The function setTermsFromTaxo
     * can populated it from a list of terms not necessarely in lower case.
     * */
    protected $terms; //array of terms
    /**
     * @var $doc string The text where the terms in $terms will be search.
     * */
    protected $doc;

    /**
     * @var $found mixed[] Array with all the terms and their positions in doc. Each term is 
     * a key and the value is another array with one element for each occurence of the term. 
     * The key of this in-array is the start position and its value is the final position.
     * */
    protected $found; //the terms found and their start and end

    function getDoc() {
        return $this->doc;
    }

    function setDoc($doc) {
        $this->doc = \strtolower($doc);
    }

    public function reset() {
        $this->terms = null; //array of terms
        $this->doc = null;
        $this->found = null; //the terms found and their position
    }

    /**
     * This function does the job and find all the occurences of the terms in the current doc.
     * @return mixed[] The array with all the terms and their positions in *doc*. Each term is 
     * a key and the value is another array with one element for each occurence of the term. 
     * The key of this in-array is the start position and its value is the final position. 
     * @uses findTerm
     * @uses deleteFP
     */
    public function find() {
        if (\count($this->found) > 0) {
            return $this->found;
        }
        foreach ($this->terms as $term) {
            $positions = $this->findTerm($term, $this->doc);
            if ($positions) {
                $this->found[$term] = $positions;
            }
        }
        $this->deleteFP();
        return $this->found;
    }

    /**
     * Find all the occurences of one term in the doc. If the term has more than one word, the
     * the function does the process, but if it only have one single word it delegates 
     * to findWord function.
     * @param  string $term The term to be found in *doc*.
     * @param string $doc . The string which maybe contain the term.
     * @return int[]. Every key is the start point in the document of an occurence 
     * and its value is the final position.
     * @uses findWord
     */
    protected function findTerm($term, $doc) {
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
        } else {//term with only one word
            return $this->findWord($term, \explode(" ", $doc));
        }
    }

    /**
     * Find all the occurences of one word in the doc. Only works for terms with one word. It's call
     * by findTerm which is a more general function.
     * @param string $term The term to be found in doc.
     * @param string[] $docWords The strings that form the doc.
     * @return int[]. Every key is the start point in the document of an occurence 
     * and its value is the final position.
     */
    protected function findWord($term, $docWords) {
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
            $start = \strlen($previous) + 1;
            $found[$start] = $start + $term_len;
        }
        return $found;
    }

    /**
     * Save an array of terms as keys. First, it transform the terms to lower case. 
     * @uses toLower 
     * @param string[] $arr An array of terms. Note that the **values are ignored**.
     * @return string[]. The array saved made by the terms in lower case.
     */
    public function setTermsFromTaxo($arr) {//arr child=>parents
        $arr0 = \array_keys($arr);
        $this->terms = $this->toLower($arr0);
        return $this->terms;
    }
    
    /**
     * Transform an array of strings to lower case.
     * @param string[] $arr The strings to be transformed.
     * @return string[] The strings in lower case.
     */
    protected function toLower($arr) {
        $lower = [];
        foreach ($arr as $key => $value) {
            $lower[\strtolower($key)] = \strtolower($value);
        }
        return $lower;
    }

    /**
     * Delete terms that actualy are part of other larger terms.
     * @uses twice
     */
    protected function deleteFP() {//delete false positives
        $deleted = 0;
        $i = 0;
        while ($i < \count($this->found)) {
            $deleted+=$this->twice($i);
            $i++;
        }
    }
    
    /**
     * Find if any term is part of another one. Search form a given position in *$found* 
     * and delete it. All the occurences inside another term will be deleted, but not outside.
     * @param int  $i The position in *$found* with the actual element to be check against 
     * the others. It's suposed that the previous have been checked before.
     * @return int. The number of deletions made.
     * @uses delete
     */
    protected function twice($i) {
        $terms = $this->found;
        $keys = array_keys($terms);

        $actual_term = $keys[$i];
        $actual_index = $i;
        $dels = 0;
        //var_dump($keys);
        while ($i < \count($terms) - 1) {
            $i++;
            //echo $i.PHP_EOL;
            $found = $this->findTerm($keys[$i], $actual_term); //the word could be part of actual.
            $found0 = $this->findTerm($actual_term, $keys[$i]); //actual could be part of the term.
            if ($found !== false AND \count($found) > 0) {
                $dels+= $this->delete($actual_index, $i, array_keys($found)[0]);
                //echo $keys[$actual_index]." CONTAINS ".  $keys[$i].PHP_EOL;
                //var_dump($found);
            }
            if ($found0 !== false AND \count($found0) > 0) {
                $dels+=$this->delete($i, $actual_index, array_keys($found)[0]);
                //echo $keys[$actual_index]." IS IN ".  $keys[$i].PHP_EOL;
                //var_dump($found0);
            }
        }
        return $dels;
    }//twice
    
    
    /**
     * Delete a term that is part of another one.  
     * @param int $large Description int $large int. The index in *$found* of the larger term .
     * @param int  $small The index in *$found* of the smaller term.
     * @param int  $offset The position of the smaller term in the larger term.
     * @return int. The number of deletions made.
     */
    protected function delete($large, $small, $offset) {//small is part of large
        $terms = $this->found;
        $keys = array_keys($terms);
        $dels = 0;
        foreach ($terms[$keys[$large]] as $lstart => $lend) {
            foreach ($terms[$keys[$small]] as $sstart => $send) {
                //echo $lstart+$offset."=?". $sstart.PHP_EOL;
                if ($lstart + $offset === $sstart OR $lstart + $offset - 1 === $sstart) {
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
