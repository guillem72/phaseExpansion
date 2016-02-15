<?php
namespace glluch\phaseExpansion;

/**
 *
 * @author Guillem LLuch Moll <guillem72@gmail.com>
 */
class Expand
{

    protected $doc;
    protected $taxonomy;
    protected $positions;
    protected $expanded;
    protected $maxShifts;
    protected $maxLevel;
    protected $numShifts;
    protected $root="IEEE";

    /**
     * @return mixed
     */
    public function getTaxonomy()
    {
        //TODO check that the array is a proper taxonomy
        return $this->taxonomy;
    }

    /**
     * @param mixed $taxonomy
     */
    public function setTaxonomy($taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param string $root
     */
    public function setRoot($root)
    {
        $this->root = $root;
    }



    /**
     * @param $term
     * @return mixed
     */
    public function getChilds($term){
        @$childs=array_search($term, $this->taxonomy);
        return $childs;
    }

    /**
     * @param $term
     * @return array
     */
    public function getParents($term){
        $parents=[];

        while($term!==$this->root){
            $term=$this->taxonomy[$term];
            $parents[]=$term;
        }
        return $parents;
    }
}