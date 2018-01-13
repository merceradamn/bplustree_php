$(function(){
  console.log("Loaded the js file.")

  $("#btn-insert").on("click",function(){
    console.log("Clicked insert button.")
  })

  $("#btn-delete").on("click",function(){
    console.log("Clicked delete button.")
  })

  $("#btn-find").on("click",function(){
    console.log("Clicked Find button.")
  })

  // Drawing shapes in the canvas
  var paper = new Raphael(document.getElementById('tree_canvas'), 1000, 500);
  var circle = paper.circle(500, 250, 80);

})
