<!--
This is the Controller for the B+ tree implementation. Here is where the brunt
of the work should be done. At the top we require our files that facilitate the
whole project. This is done to make things look cleaner and easier to parse if
and when it is needed to debug.

"BuildTree.php" is the main file that we'll be using as our jumping off point.
"Node.php" is the file that holds the class and its constituent methods.
"TreeFunctions.php" is the file that has all the logic in the project.

> Finished insertion on 2017-12-23
-->

<?php
require "Node.php";
require "TreeFunctions.php";

error_reporting(0); // Disables error reporting

##### MAIN CODE #####
$root = new Node(); // Create the tree
// $numList = array(4, 1, 3, 20, 5, 6, 23, 17, 18, 24, 13, 10, 22, 11, 14, 7);
$numList = array(280, 170, 120, 350, 250, 270, 310, 340, 130, 480, 140, 200, 20,
                460, 80, 260, 230, 380, 300, 430, 320, 220, 330, 60, 70, 490,
                50, 110, 290, 450, 420, 370, 390, 240, 90, 210, 500, 400, 180,
                10, 160, 150, 100, 40, 190, 470, 30, 440, 410, 360);

echo "<h2>B+ Tree Implementation</h1>";
echo "<h4>Adding a simple list of elements to the tree.</h4>";
echo "<p>List of elements we're processing: <p>";
echo "[".implode(', ',$numList)."]<hr>";

// Insert elements from the list
// sort($numList); // Comment out if not inserting sorted list
foreach($numList as $num){
  $status = NULL;
  // If $num is +ive add it to the tree
  if($num > 0){
    echo "<ol>";
    echo "<h3><strong>Inserting $num into the tree.</strong></h3>";

    $status = insert($root, $num);

    // This section prints the tree after adding each element to the tree
    // If status = "empty"
    // if($status == "success"){
    //   echo "<li>Result Tree: ";
    //   showTree($root);
    //   echo "</li>";
    // }
    // elseif($status == "fail-dupe"){
    //   echo "<li>Value already exists in tree.</li>";
    // }

    echo "</ol><hr>";
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

// require_once "views/view_tree.php";
?>
