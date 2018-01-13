<!--
Class definition for nodes, a key component of the B+ tree

When a node is instantiated we set a few data members to defaults for ease of
use.

I've added key-value pairs for the three pertinent child nodes. They're all set
to NULL by default until added. Because they're set to NULL we also need to
set the type of the created node to "leaf". By contrast if the node is a parent
or inside node we would call the respective method to set it to "parent". The
parentNode data member is also set to NULL as we haven't connected the new node
to the tree and we'll do that as appropriate.

On the method side I've tried to make sure all data members have get/set methods
to try to abstract away complex functions and make things easier. I might be
missing some important behaviors but at the time I don't know what I'm missing.
 -->

<?php

class Node {
  private $data;
  private $type;
  private $childNodes;
  private $parentNode;

  public function __construct(){
    $this->data = NULL;
    $this->type = "leaf";

    $this->parentNode = NULL;
    $this->childNodes["left"] = NULL;
    $this->childNodes["mid"] = NULL;
    $this->childNodes["right"] = NULL;
  }

  public function showData(){
    // I think this function is self-explanatory
    echo "<li>Data: ";
    if(gettype($this->data) == "array"){
      print_r(implode(', ', $this->data));
    }
    else{
      print_r($this->data);
    }
    echo "</li>";

    echo "<ul><li>is a ".$this->type."</li>"; // Print the type of the node

    if($this->parentNode != NULL){
      echo "<li>Parent: ";
      if(gettype($this->parentNode->getData()) == "array"){
        print_r(implode(', ', $this->parentNode->getData()));
      }
      else{
        print_r($this->parentNode->getData());
      }

      echo "</li>";
    }
    else{
      echo "<li>has no parent node.</li>";
    }

    // Show left child
    if($this->childNodes["left"] != NULL){
      // echo "<li>Has a left child node.</li>";
      $this->childNodes["left"]->getData();
      $a = 1;
    }
    // ... middle child
    if($this->childNodes["mid"] != NULL){
      // echo "<li>Has a mid child node.</li>";
      $b = 2;
    }
    // ... right child
    if($this->childNodes["right"] != NULL){
      // echo "<li>Has a right child node.</li>";
      $c = 3;
    }

    echo "</ul>";
  }

  public function getData(){
    return $this->data;
  }

  public function setData($d, $r = 0){
    // If $r is 1 then clear data before setting
    if($r == 1){
      echo "<li>Reset node's data.</li>";
      $this->data = NULL;
    }

    // If data is an array push $d onto it
    // If data is not set just set data to $d
    if(!isset($this->data)){
      echo "<ul><li>Data isn't set yet.</li></ul>";
      $this->data = $d;
    }
    else{
      echo "<ul><li>Data is set.</li></ul>";
      if(gettype($this->data)=="integer"){
        $this->data = array($this->data);
      }

      array_push($this->data, $d);
      sort($this->data);
    }

      return count($this->data);
  }

  public function getType(){
    return $this->type;
  }

  public function setType($t){
    $this->type = $t;
  }

  public function getParent(){
      if($this->parentNode != NULL){
        echo "<li>Node has a parent. Returning it.</li>";
        return $this->parentNode;
      }
      else{
        echo "<li>No parent node (Or something borked.)</li>";
        return NULL;
      }
  }

  public function setParent($p){
    $this->parentNode = $p;
  }

  public function getChild($c){
    if($this->childNodes[$c] != NULL){
      return $this->childNodes[$c];
    }
    else{
      return NULL;
    }
  }

  public function setChild($dir, $child){
    $this->childNodes[$dir] = $child;
  }
}
?>
