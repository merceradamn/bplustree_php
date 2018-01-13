<!--
This file contains the functions that can be used to manipulate the B+ tree. Most
parts are self-explanatory but I did my best to put comments so it's not as
vague as to what it is that I'm trying to do.
-->

<?php

function split($curr){
  // Print the node otherwise return the count of the node
  if(gettype($curr->getData()) == "array")
    echo "<li>Data of Passed Node: ".implode(', ', $curr->getData())."</li>";
  else {
    echo "<li><strong>Tried to split a non-full node.</strong></li>";
    return count($curr->getData());
  }

  if($curr->getType() == "leaf"){
    echo "<li>Split function was passed a leaf node.</li>";
    // We're passed a leaf node that we need to split
    $parNode = $curr->getParent(); // Check for a parent node and store it
    // Case where current node has no parent
    if($parNode == NULL){
      $currData = $curr->getData(); // Get the node's data

      // Left value goes in left node
      $leftChild = new Node(); // Create the leaf node
      $leftChild->setData($currData[0]); // Add the left value

      // Mid and Right values go in right node
      $rightChild = new Node(); // Create the node
      $rightChild->setData($currData[1]); // Add the mid value
      $rightChild->setData($currData[2]); // Add the right value

      // Mid value also goes in a new parent node
      // Just change current node instead of making new node/reassigning
      $curr->setType("parent"); // Set the type to "parent"
      $curr->setData($currData[1], 1); // Set the data of the node

      // Connect the nodes now
      $curr->setChild("left", $leftChild); // Attach the left child
      $curr->setChild("right", $rightChild); // Attach the right child

      $leftChild->setParent($curr); // Attach the parent to the left child
      $rightChild->setParent($curr); // Attach the parent to the right child

    }
    // Case where current node has a parent
    else if($parNode != NULL){
      $parLC = $parNode->getChild("left"); // Get left child of parent
      $parMC = $parNode->getChild("mid"); // Get mid child of parent
      $parRC = $parNode->getChild("right"); // Get right child of parent
      $currData = $curr->getData(); // Get the passed node's data

      // Case where parent has two children; left & right
      if($parLC and $parRC != NULL and $parMC == NULL){
        echo "<li>Left & right children.</li>";
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
        echo "<li>Left, mid & right children.</li>";

        // Figure out which child we were trying to split
        if($parLC->getData() == $currData){
          echo "<li>We're splitting the left child.</li>";

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
          echo "<li>We're splitting the middle child.</li>";

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
          echo "<li>We're splitting the right child.</li>";

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
        return -99;
      }
    }

  }
  else if($curr->getType() == "parent"){
    echo "<li>Split function was passed a parent node.</li>";

    $parNode = $curr->getParent(); // Get the passed node's parent
    $currData = $curr->getData(); // Store the data of the passed node

    // Get the children of the passed node
    $L = $curr->getChild("left"); // Left child node get
    $M = $curr->getChild("mid"); // Mid child node get
    $R = $curr->getChild("right"); // Right child node get
    $LR = $curr->getChild("LR"); // Try to get the left-right child node
    $MR = $curr->getChild("MR"); // Try to get the mid-right child node
    $RR = $curr->getChild("RR"); // Try to get the right-right child node

    // Show all the base children
    // echo "<br><li><strong>Children of passed node: </strong></li>";
    // if($L != NULL)
    //   echo "<li>Left Child: "; print_r($L->getData()); echo "</li>";
    // if($M != NULL)
    //   echo "<li>Mid Child: "; print_r($M->getData()); echo "</li>";
    // if($R != NULL)
    //   echo "<li>Right Child: "; print_r($R->getData()); echo "</li>";

    // Create and set all values for the new left child
    $newParLeft = new Node(); // Create the new left child for split
    $newParLeft->setData($currData[0]); // Set the left child's data
    $newParLeft->setType("parent"); // Set to parent

    // Create and set all values for the new right child
    $newParRight = new Node(); // Create the new right child for split
    $newParRight->setData($currData[2]); // Set the right child's data
    $newParRight->setType("parent"); // Set to parent

    // Based on which exists we choose a path, LR, MR, or RR
    if($LR != NULL){
      echo "<li>Left-Right Child: "; print_r($LR->getData()); echo "</li>";

      // Par-Left; Left Child = Left;
      // 			Right Child = LR;
      // Set the two children to the new left node of parent
      $newParLeft->setChild("left", $L);
      $newParLeft->setChild("right", $LR);
      $newParLeft->setParent($curr);
      $L->setParent($newParLeft);
      $LR->setParent($newParLeft);

      // Par-right; Left Child = Mid;
      // 			Right Child = Right;
      // Set the two children to the new right node of parent
      $newParRight->setChild("left", $M);
      $newParRight->setChild("right", $R);
      $newParRight->setParent($curr);
      $M->setParent($newParRight);
      $R->setParent($newParRight);

    }
    elseif($MR != NULL){
      echo "<li>Mid-Right Child: "; print_r($MR->getData()); echo "</li>";

      // Par-Left; Left Child = Left;
      //       Right Child = Mid;
      // Set the two children to the new left node of parent
      $newParLeft->setChild("left", $L);
      $newParLeft->setChild("right", $M);
      $newParLeft->setParent($curr);
      $L->setParent($newParLeft);
      $M->setParent($newParLeft);

      // Par-right; Left Child = MR;
      //       Right Child = Right;
      // Set the two children to the new right node of parent
      $newParRight->setChild("left", $MR);
      $newParRight->setChild("right", $R);
      $newParRight->setParent($curr);
      $MR->setParent($newParRight);
      $R->setParent($newParRight);
    }
    elseif($RR != NULL){
      echo "<li>Right-Right Child: "; print_r($RR->getData()); echo "</li>";

      // Par-Left; Left Child = Left;
      //       Right Child = Mid;
      // Set the two children to the new left node of parent
      $newParLeft->setChild("left", $L);
      $newParLeft->setChild("right", $M);
      $newParLeft->setParent($curr);
      $L->setParent($newParLeft);
      $M->setParent($newParLeft);

      // Par-right; Left Child = Right;
      //       Right Child = RR;
      // Set the two children to the new right node of parent
      $newParRight->setChild("left", $R);
      $newParRight->setChild("right", $RR);
      $newParRight->setParent($curr);
      $R->setParent($newParRight);
      $RR->setParent($newParRight);

    }

    // Show the new nodes, debugging purposes
    // echo "<br>newParLeft: "; $newParLeft->showData(); echo "<br>";
    // echo "<br>newParRight: "; $newParRight->showData();

    // If node doesn't have a parent we were passed the root of the tree
    if($parNode == NULL){
      echo "<li>Root node, has no parent.</li>";

      // We need to preserve the integrity of this node
      // Scrub the data of the node to just hold the middle value
      $curr->setData($currData[1], 1);
      // Attach the new children to the node
      $curr->clearChildren(); // Clean out the children
      $curr->setChild("left", $newParLeft);
      $curr->setChild("right", $newParRight);

      // And then return data count of this node as it's the parent
      return count($curr->getData());
    }
    // If node has a parent we need to make sure it maintains it's children
    else{
      echo "<li>Inner node, has a parent.</li>";

      // Since we're moving the middle value up to the parent node and then
      // splitting this node we need to attach the new nodes to the parent
      // node. In this case we'll have to find out if we're splitting parent's
      // left node, mid node or right node.

      // Get the siblings of the parent node to check which we're splitting
      $parLeftChild = $parNode->getChild("left");
      $parMidChild = $parNode->getChild("mid");
      $parRightChild = $parNode->getChild("right");

      // Pass the middle value of the split node to the parent node
      $curr = $curr->getParent();
      $curr->setData($currData[1]);

      // If we're splitting the parent's left child
      if($parLeftChild != NULL and $parLeftChild->getData() === $currData){
        echo "<li>Splitting parent's left child.</li>";

        // No mid child so we can make the newParRight, mid child of parent
        if($parMidChild == NULL){
          echo "<li>No mid child so we can use the spot.</li>";
          $curr->setChild("left", $newParLeft);
          $newParLeft->setParent($curr);
          $curr->setChild("mid", $newParRight);
          $newParRight->setParent($curr);
        }
        // Mid child exists so we need a temp node for parent
        elseif($parMidChild != NULL){
          echo "<li>Parent has a mid already. Use XX-right child spot.</li>";
          $curr->setChild("left", $newParLeft);
          $newParLeft->setParent($curr);
          $curr->setChild("LR", $newParRight);
          $newParRight->setParent($curr);
        }

      }
      // If we're splitting the parent's mid child
      elseif($parMidChild != NULL and $parMidChild->getData() === $currData){
        echo "<li>Split parent's mid node.</li>";

        // Since mid exists and we're splitting it by default MR is needed
        $curr->setChild("mid", $newParLeft);
        $newParLeft->setParent($curr);
        $curr->setChild("MR", $newParRight);
        $newParRight->setParent($curr);
      }
      // If we're splitting the parent's right child
      elseif($parRightChild != NULL and $parRightChild->getData() === $currData){
        echo "<li>Split parent's right node.</li>";

        // No mid child so we can make the newParLeft, mid child of parent
        if($parMidChild == NULL){
          $curr->setChild("mid", $newParLeft);
          $newParLeft->setParent($curr);
          $curr->setChild("right", $newParRight);
          $newParRight->setParent($curr);
        }
        // Mid child exists so we need a temp node for parent
        elseif($parMidChild != NULL){
          $curr->setChild("right", $newParLeft);
          $newParLeft->setParent($curr);
          $curr->setChild("RR", $newParRight);
          $newParRight->setParent($curr);
        }

      }

      // Return count of the current node
      return count($curr->getData());
    }
  }

  // Let's check the parent node's count; if it's 3 we need to return it
  $par = $curr->getParent();
  if($par != NULL){
    // echo "<li>Count of Parent:".count($par->getData())."</li>";
    return count($par->getData());
  }
  // Else let's just return the count of the node we were passed
  else{
    // When finished return the count of the node's data
    // echo "<li>Count of this Node: ".count($curr->getData())."</li>";
    return count($curr->getData());
  }

}

function insert($root, $num){
  // If root is null
  if($root->getData() == NULL){
    $root->setData($num);
    return "success";
  }

  // Root is not null
  $curr = $root; // Set a current node for traversing
  $curr = find($curr, $num); // Call the find function

  // Found an appropriate leaf, check before adding
  $cd = $curr->getData(); // Get the node's Data
  // Case where node data is just a single value
  if(gettype($cd)=="integer"){
    if($num == $cd){
      echo "<li>Value is already in the tree.</li>";
      return "fail-dupe";
    }
    else{
      $curr->setData($num); // Add the data to the node
      return "success";
    }
  }
  // Case where $cd has more than one value
  elseif(gettype($cd)=="array"){
    // Value isn't in the leaf node so let's try to add it
    if(!in_array($num, $cd)){
      $ndc = $curr->setData($num);

      // We should call the split as long as we return $ndc == 3
      while(true){ // Could change true to $ndc >= 3
        echo "<br><li>Calling the split function.</li>";
        $ndc = split($curr); // Call split on node and return data count for par
        if($ndc == 3){
          echo "<li>Loop again to split parent node.</li>";
          $curr = $curr->getParent(); // Traverse up to parent to split parent
        }
        else{
          echo "<li>Parent node isn't full. Done splitting nodes.</li><br>";
          break; // Break out of while loop since we don't need to split
        }
      }

      // Return now since we've inserted properly (fingers crossed!)
      return "success";
    }
    else{
      return "fail-dupe"; // Duplicate value
    }
  }

  echo "<ul><li><strong>ERROR: Hole in logic tree.</strong></li></ul>";
  exit();
}

function find($node, $num){

  // While the node we're at isn't a leaf node we'll traverse appropriately
  while($node->getType() != "leaf"){
    $cd = $node->getData(); // Get the node's Data

    // Case where data is just a single value
    if(gettype($cd)=="integer"){
      // Case where node just has two children; left & right
      // A parent node with just one data value will never have 3 children
      if($num < $cd){
        // Case where $num is lower than value
        echo "<li>Traversed left.</li>";
        $node = $node->getChild("left");
      }
      else{
        // Case where $num is higher than value
        echo "<li>Traversed right.</li>";
        $node = $node->getChild("right");
      }
    }
    // Case where data is an array of values
    elseif(gettype($cd)=="array"){
      // Case where node just has two children; left & right
      if($node->getChild("mid") == NULL){
        if($num < $cd[0]){
          // Case where $num is lower than 1st value
          echo "<li>Traversed left.</li>";
          $node = $node->getChild("left");
        }
        elseif($num < $cd[1]){
          // Case where $num is lower than 2nd value
          echo "<li>Traversed right.</li>";
          $node = $node->getChild("right");
        }
      }
      // Case where node has three children; left, mid & right
      else{
        if($num < $cd[0]){
          // Case where $num is lower than 1st value
          echo "<li>Traversed left.</li>";
          $node = $node->getChild("left");
        }
        elseif($num < $cd[1]){
          // Case where $num is lower than 2nd value
          echo "<li>Traversed mid.</li>";
          $node = $node->getChild("mid");
        }
        elseif($num >= $cd[1]){ // I think here might need to be ">=" over ">"
          echo "<li>Traversed right.</li>";
          $node = $node->getChild("right");
        }
      }
    }

  }

  // Found the leaf node so let's return it now
  return $node;

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
