<!--
This is the Controller for the B+ tree implementation. Here is where the brunt
of the work should be done. At the top we require our files that facilitate the
whole project. This is done to make things look cleaner and easier to parse if
and when it is needed to debug.

"BuildTree.php" is the main file that we'll be using as our jumping off point.
"Node.php" is the file that holds the class and its constituent methods.
"TreeFunctions.php" is the file that has all the logic in the project.
-->

<?php
require "Node.php";
require "TreeFunctions.php";

// error_reporting(0); // Disables error reporting

function printNumList($nl){
  if(isset($nl)){
    $c = count($nl);
    echo "[";

    foreach($nl as $n){
      echo $n;
      if($c > 1){
        echo ", ";
        $c -= 1;
      }
    }

    echo "]";
  }
  else{
    echo "<li>No elements in array.</li>";
  }

}

##### MAIN CODE #####

$root = new Node(); // Create the tree

// Test lists for the B tree
// $numList = array(4, 1, 3, 20, 5, 6, 23, 17, 18, 24, 13, 10, 22, 11, 14, 7);
// $numList = array(30,40,30,-30,20,10,50,31);
// $numList = array(5,10,15,4,3,2,1,6,11); // 1:left, 6:mid, 11:right
$numList = array(1,2,3,4,5);

echo "<h2>B+ Tree Implementation</h1>";
echo "<h4>Adding a simple list of elements to the tree.</h4>";
echo "<p>List of elements we're processing: ";
echo "<li>"; printNumList($numList); echo "</li></p><hr>";

// Insert elements from the list
// sort($numList); // Comment out if not inserting sorted list
foreach($numList as $num){
  // If $num is +ive add it to the tree
  if($num > 0){
    echo "<ol><li>Adding ".$num." to the tree.</li>";
    insert($root, $num);
    echo "<li>Result Tree: ";
    showTree($root);
    echo "</li></ol><hr>";
  }
  // If $num is -ive delete it from the tree
  else if($num < 0){
    echo "<ol><li>Deleting ".$num." from the tree.</li>";
    // Convert number to positive before passing to delete function
    $num *= -1;
    echo "<li>\"Deleted\" ".$num."</li></ol><hr>";
  }

}

// Show the tree after inserting
echo "<h2><strong>Tree after list processing: </strong></h2>";
showTree($root);
echo "<hr>";

// Add a test value for root splitting
// insert($root, 25); // Should split left child and then split the root/parent
// echo "<h2><strong>Final Tree</strong></h2>";
// showTree($root);

// require_once "views/view_tree.php";
?>
