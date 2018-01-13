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

    // These are temporary when a parent needs to split
    $this->childNodes["LR"] = NULL;
    $this->childNodes["MR"] = NULL;
    $this->childNodes["RR"] = NULL;
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

    // echo "<li>Children: </li>";
    // // Show left child
    // if($this->childNodes["left"] != NULL){
    //   $d = $this->childNodes["left"]->getData();
    //
    //   if(gettype($d) == "array"){
    //     echo "L: ".implode(', ', $d)."\t";
    //   }
    //   else{
    //     echo "L: ".$d."\t";
    //   }
    //
    // }
    // // Show left-right child if we have it
    // if($this->childNodes["LR"] != NULL){
    //   $d = $this->childNodes["LR"]->getData();
    //
    //   if(gettype($d) == "array"){
    //     echo "LR: ".implode(', ', $d)."\t";
    //   }
    //   else{
    //     echo "LR: ".$d."\t";
    //   }
    // }
    //
    // // ... middle child
    // if($this->childNodes["mid"] != NULL){
    //   $d = $this->childNodes["mid"]->getData();
    //
    //   if(gettype($d) == "array"){
    //     echo "M: ".implode(', ', $d)."\t";
    //   }
    //   else{
    //     echo "M: ".$d."\t";
    //   }
    // }
    // // ... middle-right child
    // if($this->childNodes["MR"] != NULL){
    //   $d = $this->childNodes["MR"]->getData();
    //
    //   if(gettype($d) == "array"){
    //     echo "MR: ".implode(', ', $d)."\t";
    //   }
    //   else{
    //     echo "MR: ".$d."\t";
    //   }
    // }
    //
    // // ... right child
    // if($this->childNodes["right"] != NULL){
    //   $d = $this->childNodes["right"]->getData();
    //   if(gettype($d) == "array"){
    //     echo "R: ".implode(', ', $d)."\t";
    //   }
    //   else{
    //     echo "R: ".$d."\t";
    //   }
    // }
    // // ... right-right child
    // if($this->childNodes["RR"] != NULL){
    //   $d = $this->childNodes["RR"]->getData();
    //
    //   if(gettype($d) == "array"){
    //     echo "RR: ".implode(', ', $d)."\t";
    //   }
    //   else{
    //     echo "RR: ".$d."\t";
    //   }
    // }

    echo "</ul>";
  }

  public function getData(){
    return $this->data;
  }

  public function setData($d, $r = 0){
    // If $r is 1 then clear data before setting with passed data
    if($r == 1){
      echo "<li>Reset node's data.</li>";
      $this->data = NULL;
    }

    // If data is an array push $d onto it
    // If data is not set just set data to $d
    if(!isset($this->data)){
      // echo "<ul><li>Data isn't set yet.</li></ul>";
      $this->data = $d;
    }
    else{
      // echo "<ul><li>Data is set.</li></ul>";
      if(gettype($this->data)=="integer"){
        $this->data = array($this->data);
      }

      array_push($this->data, $d);
      sort($this->data);
    }

    // Let's return the count of the node we just added data to for working with
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
        return $this->parentNode;
      }
      else{
        // echo "<li>Node is a leaf.</li>";
        return NULL;
      }
  }

  public function setParent($p){
    $this->parentNode = $p;
  }

  public function getChild($c){
    // Check for $c if not a temp node
    if($this->childNodes[$c] != NULL){
      return $this->childNodes[$c];
    }
    else{
      return NULL;
    }
  }

  public function getChildren(){
      return $this->childNodes;
  }

  public function setChild($dir, $child){
    $this->childNodes[$dir] = $child;
  }

  public function clearChildren(){
    $this->childNodes = array();
  }
}
?>
