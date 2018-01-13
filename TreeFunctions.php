<!--
This file contains the functions that can be used to manipulate a B+ tree. Most
parts are self-explanatory but I did my best to put comments so it's not as
vague as to what it is that I'm trying to do.
-->

<?php

function split($curr){
  // We're passed a leaf node that we need to split
  echo "<ul>";
  $parNode = $curr->getParent(); // Check for a parent node and store it
  // Case where current node has no parent
  if($parNode == NULL){
    echo "<li>Leaf node has no parent. Let's change that.</li>";
    $currData = $curr->getData(); // Get the node's data

    echo "<li>Working on left node first.</li>";
    // Left value goes in left node
    $leftChild = new Node(); // Create the leaf node
    $leftChild->setData($currData[0]); // Add the left value

    echo "<li>Working on right node now.</li>";
    // Mid and Right values go in right node
    $rightChild = new Node(); // Create the node
    $rightChild->setData($currData[1]); // Add the mid value
    $rightChild->setData($currData[2]); // Add the right value

    echo "<li>Attaching to current node now.</li>";
    // Mid value also goes in a new parent node
    // Just change current node instead of making new node/reassigning
    $curr->setType("parent"); // Set the type to "parent"
    $curr->setData($currData[1], 1); // Set the data of the node

    // Connect the nodes now
    $curr->setChild("left", $leftChild); // Attach the left child
    $curr->setChild("right", $rightChild); // Attach the right child

    $leftChild->setParent($curr); // Attach the parent to the left child
    $rightChild->setParent($curr); // Attach the parent to the right child

    // Check the node changes before we leave
    // echo "<ul>Here's the node changes:";
    // $curr->showData();
    // echo "</ul></ul>";
    echo "</ul>";
  }
  // Case where current node has a parent
  else if($parNode != NULL){
    $parLC = $parNode->getChild("left"); // Get left child of parent
    $parMC = $parNode->getChild("mid"); // Get mid child of parent
    $parRC = $parNode->getChild("right"); // Get right child of parent
    $currData = $curr->getData(); // Get the node's data

    // Case where parent has two children; left & right
    if($parLC and $parRC != NULL){
      // echo "Left child Data: "; print_r($parLC->getData());
      // echo "<br>Right child Data: "; print_r($parRC->getData());
      // echo "<br>Current node Data: "; print_r($curr->getData());

      // Check if we went left or right
      if($parLC->getData() == $curr->getData()){
        echo "We are splitting the left child.<br>";

      }
      elseif($parRC->getData() == $curr->getData()){
        echo "We are splitting the right child.<br>";

        $parNode->setData($currData[1]); // Add middle value in curr to parent
        // Create the new node, set the data, and make it parent's mid child
        $midChild = new Node(); $midChild->setData($currData[0]);
        $midChild->setParent($parNode); // Set mid child's parent
        $parNode->setChild("mid", $midChild);

        $curr->setData($currData[1], 1); // Reset current's data and add mid
        $curr->setData($currData[2]); // Add the right value in current
      }

    }
    // Case where parent has three children; left, mid & right
    elseif($parLC and $parMC and $parRC != NULL){
      echo "Left, mid & right children.<br>";
    }
    else{
      echo "Some children are missing.<br>";
      return -99;
    }

    // Check the node changes before we leave
    // echo "<ul>Here's the node changes:";
    // $curr->showData();
    // echo "</ul></ul>";
    echo "</ul>";
  }

  // When finished return the count of parent node's data
  echo "<li>Count: ".count($curr->getData())."</li>";
  return count($curr->getData());
}

function insert($root, $num){

  // If root is null
  if($root->getData() == NULL){
    $root->setData($num);
    echo "<li>Inserted ".$num." into the tree.</li>";
    return 0;
  }

  // Root is not null
  $curr = $root; // Set a current node for traversing
  while($curr->getType() != "leaf"){
    $cd = $curr->getData(); // Get the node's Data
    echo "<li>Not at a leaf node yet.</li>";
    echo "<li>Current Node Data: "; print_r($cd); echo "</li>";

    // Case where data is just a single value
    if(gettype($cd)=="integer"){
      // Case where node just has two children; left & right
      // A parent node with just one data value will never have 3 children
      if($curr->getChild("mid") == NULL){
        if($num < $cd){
          // Case where $num is lower than value
          echo "<li>Moved left.</li>";
          $curr = $curr->getChild("left");
        }
        else{
          // Case where $num is higher than value
          echo "<li>Moved right.</li>";
          $curr = $curr->getChild("right");
        }
      }
    }
    // Case where data is an array of values
    elseif(gettype($cd)=="array"){
      // Case where node just has two children; left & right
      if($curr->getChild("mid") == NULL){
        if($num < $cd[0]){
          // Case where $num is lower than 1st value
          echo "<li>Moved left.</li>";
          $curr = $curr->getChild("left");
        }
        elseif($num < $cd[1]){
          // Case where $num is lower than 2nd value
          echo "<li>Moved right.</li>";
          $curr = $curr->getChild("right");
        }
      }
      // Case where node has three children; left, mid & right
      else{
        if($num < $cd[0]){
          // Case where $num is lower than 1st value
          echo "<li>Moved left.</li>";
          $curr = $curr->getChild("left");
        }
        elseif($num < $cd[1]){
          // Case where $num is lower than 2nd value
          echo "<li>Moved mid.</li>";
          $curr = $curr->getChild("mid");
        }
        elseif($num > $cd[1]){
          echo "<li>Moved right.</li>";
          $curr = $curr->getChild("right");
        }
      }
    }
    else{
      echo "It's a steak sandwich?<br>";
    }
  }

  // Found an appropriate leaf, check before adding
  $cd = $curr->getData(); // Get the node's Data
  // Case where node data is just a single value
  if(gettype($cd)=="integer"){
    if($num == $cd){
      echo "<li>Value is already in the tree.</li>";
      return 0;
    }
    else{
      echo "<li>Value isn't here. Add it.</li>";
      $ndc = $curr->setData($num);
      echo "<ul><li>Node Data Count: ".$ndc."</li></ul>";
      echo "<ul><li>      Node Data: ";
      print_r($curr->getData());
      echo "</li></ul>";

      // We should call the split as long as we return $ndc == 3
      while($ndc >= 3){
        echo "<li>Calling the split on the current node</li>";
        $ndc = split($curr);
      }

      echo "<li>Finished splitting nodes.</li>";
      return 0;
    }
  }
  // Case where $cd has more than one value
  elseif(gettype($cd)=="array"){
    if(in_array($num, $cd)){
      echo "<li>Value is already in the tree.</li>";
      return 0;
    }
    else{
      echo "<li>Value isn't here. Add it.</li>";
      $ndc = $curr->setData($num);
      // echo "<ul><li>Node Data Count: ".$ndc."</li></ul>";
      // echo "<ul><li>      Node Data: ";
      print_r($curr->getData());
      // echo "</li></ul>";

      // We should call the split as long as we return $ndc == 3
      while($ndc >= 3){
        echo "<li>Calling the split on the current node</li>";
        $ndc = split($curr);
      }

      echo "<li>Finished splitting nodes.</li>";
      return 0;
    }
  }

  echo "<ul><li><strong>ERROR: Hole in logic tree.</strong></li></ul>";
  return -1;
}

function showTree($node){
  if($node->getType() == "leaf"){
      // Print the data of the node
      echo "<ul>";
      $node->showData();
      echo "</ul>";
      return 0;
  }

  // Show this node's data
  echo "<ul>";
  $node->showData();

  // Route to the children nodes
  if($node->getChild("left") != NULL){
    echo "<ul><li>Left Child: </li>";
    showTree($node->getChild("left"));
    echo "</ul>";
  }

  if($node->getChild("mid") != NULL){
    echo "<ul><li>Mid Child: </li>";
    showTree($node->getChild("mid"));
    echo "</ul>";
  }

  if($node->getChild("right") != NULL){
    echo "<ul><li>Right Child: </li>";
    showTree($node->getChild("right"));
    echo "</ul>";
  }

  echo "</ul>";
  return 0;
}
 ?>
