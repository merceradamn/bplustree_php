<!--
This file contains the functions that can be used to manipulate a B+ tree. Most
parts are self-explanatory but I did my best to put comments so it's not as
vague as to what it is that I'm trying to do.
-->

<?php

function split($curr){
  if($curr->getType() == "leaf"){
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

      echo "</ul>"; // Formatting HTML; should be gone after we polish
    }
    // Case where current node has a parent
    else if($parNode != NULL){
      $parLC = $parNode->getChild("left"); // Get left child of parent
      $parMC = $parNode->getChild("mid"); // Get mid child of parent
      $parRC = $parNode->getChild("right"); // Get right child of parent
      $currData = $curr->getData(); // Get the passed node's data

      // Case where parent has two children; left & right
      if($parLC and $parRC != NULL and $parMC == NULL){
        // echo "Left child Data: "; print_r($parLC->getData());
        // echo "<br>Right child Data: "; print_r($parRC->getData());
        // echo "<br>Current node Data: "; print_r($curr->getData());

        // Check if we went left or right
        if($parLC->getData() == $currData){
          echo "We are splitting the left child.<br>";

          $parNode->setData($currData[1]); // Add middle value in curr to parent

          $midChild = new Node(); // Create node that'll be parNode's mid child
          $midChild->setData($currData[1]); $midChild->setData($currData[2]);
          $midChild->setParent($parNode); // Set mid child's parent
          $parNode->setChild("mid", $midChild);

          // Reset the data in left child and add first value
          $curr->setData($currData[0], 1);

        }
        elseif($parRC->getData() == $currData){
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

        // Figure out which child we were trying to split
        if($parLC->getData() == $currData){
          echo "We're splitting the left child.<br>";

          // We're making a new node called "LR" to be added to parent
          $leftRightChild = new Node();
          $leftRightChild->setData($currData[1]);// Set node's data with middle
          $leftRightChild->setData($currData[2]); // Add the right value to node
          $leftRightChild->setParent($parNode); // Set parent of LRC to parent
          $parNode->setData($currData[1]); // Middle value gets pushed to parent
          $parNode->setChild("LR", $leftRightChild); // Set child of parent
          $curr->setData($currData[0], 1); // Adjust curr child node's data now

        }
        else if($parMC->getData() == $currData){
          echo "We're splitting the middle child.<br>";

          // We're making a new node called "MR" to be added to parent
          $midRightChild = new Node();
          $midRightChild->setData($currData[1]);// Set node's data with middle
          $midRightChild->setData($currData[2]); // Add the right value to node
          $midRightChild->setParent($parNode); // Set parent of MRC to parent
          $parNode->setData($currData[1]); // Middle value gets pushed to parent
          $parNode->setChild("MR", $midRightChild); // Set child of parent
          $curr->setData($currData[0], 1); // Adjust curr child node's data now

        }
        else if($parRC->getData() == $currData){
          echo "We're splitting the right child.<br>";

          // We're making a new node called "RR" to be added to parent
          $rightRightChild = new Node();
          $rightRightChild->setData($currData[1]);// Set node's data with middle
          $rightRightChild->setData($currData[2]); // Add the right value to node
          $rightRightChild->setParent($parNode); // Set parent of MRC to parent
          $parNode->setData($currData[1]); // Middle value gets pushed to parent
          $parNode->setChild("RR", $rightRightChild); // Set child of parent
          $curr->setData($currData[0], 1); // Adjust curr child node's data now
        }

      }
      else{
        echo "ERROR: Logic hole in split function.<br>";
        echo "</ul>";
        return -99;
      }

      $pchild = $parNode->getChildren(); // Get the children of the parent
      echo "</ul>";
    }

  }
  else if($curr->getType() == "parent"){
    echo "<li>Passed a parent node to this function.</li>";

    // Check if the node has a parent
    $parNode = $curr->getParent(); // Get the parent node if one exists

    // If node doesn't have a parent we were passed the root of the tree
    if($parNode == NULL){
      echo "<li>Root node, has no parent.</li>";

      $LR = $curr->getChild("LR"); // Try to get the left-right child node
      $MR = $curr->getChild("MR"); // Try to get the mid-right child node
      $RR = $curr->getChild("RR"); // Try to get the right-right child node

      // Based on which exists we choose a path, LR, MR, or RR
      if($LR != NULL){
        echo "<li>Left-Right child exists.</li>";
        // Parent splits;
      	// Par-Left; Left Child = Left;
      	// 			Right Child = LR;
        //
      	// Par-right; Left Child = Mid;
      	// 			Right Child = Right;
      }
      elseif($MR != NULL){
        echo "<li>Mid-Right child exists.</li>";
        // Parent splits;
        // Par-Left; Left Child = Left;
        //       Right Child = Mid;
        //
        // Par-right; Left Child = MR;
        //       Right Child = Right;
      }
      elseif($RR != NULL){
        echo "<li>Right-Right child exists.</li>";
        // Parent splits;
        // Par-Left; Left Child = Left;
        //       Right Child = Mid;
        //
        // Par-right; Left Child = Right;
        //       Right Child = RR;
      }

      return 0;
    }
    // If node has a parent we need to make sure it maintains it's children
    else{
      echo "<li>Inner node, has a parent.</li>";
      return -1;
    }
  }


  // Let's check the parent node's count; if it's 3 we need to return it
  $par = $curr->getParent();
  if($par != NULL){
    echo "<li>Count of Parent:".count($par->getData())."</li>";
    return count($par->getData());
  }
  // Else let's just return the count of the node we were passed
  else{
    // When finished return the count of the node's data
    echo "<li>Count: ".count($curr->getData())."</li>";
    return count($curr->getData());
  }

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
        elseif($num >= $cd[1]){ // I think here might need to be ">=" over ">"
          echo "<li>Moved right.</li>";
          $curr = $curr->getChild("right");
        }
      }
    }
    else{
      echo "ERROR: Logic hole here.<br>"; // Array or Integer, problem if here
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
      $curr->setData($num); // Add the data to the node

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

      // We should call the split as long as we return $ndc == 3
      while(true){ // Could change true to $ndc >= 3
        echo "<li>Calling the split on the current node.</li>";
        $ndc = split($curr); // Call split on node and return data count for par

        if($ndc == 3){
          echo "<li>Need to split the parent now.</li>";
          $curr = $curr->getParent(); // Traverse up to parent to split parent
        }
        else{
          break; // Break out since we don't need to call split
        }

      }

      echo "<li>Finished splitting nodes.</li>";
      return 0;
    }
  }

  exit("<ul><li><strong>ERROR: Hole in logic tree.</strong></li></ul>");
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
