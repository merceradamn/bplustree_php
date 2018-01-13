<html>

<head>

<title>B+ Tree Implementation</title>

<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

<!-- Optional JavaScript -->
<!-- jQuery first, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script type="text/javascript" src="raphael.min.js"></script>
<script type="text/javascript" src="views/view_tree.js"></script>
<style type="text/css">
            #tree_canvas {
                width: 1000px;
                height: 500px;
                border: 1px solid #aaa;
                align: "center";
            }
        </style>
</head>

<body>
<hr>
<div class="container">
  <form action="/action.php" method="post">
  <div class="row justify-content-center">
    <div class="col-sm">
      <input type="text" id="text-insert" maxlength="10" size="12"/>
      <button type="button" id="btn-insert">Insert</button>
    </div>
    <div class="col-sm">
      <input type="text" id="text-delete" maxlength="10" size="12"/>
      <button type="button" id="btn-delete">Delete</button>
    </div>
    <div class="col-sm">
      <input type="text" name="text-find" maxlength="10" size="12"/>
      <button type="button" id="btn-find">Find</button>
    </div>
  </div>
</div>
<hr>
<div id="tree">
<?php ?>
</div>

<div id="tree_canvas">
</div>

</body>

</html>
