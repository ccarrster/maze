<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=yes" />

    <title></title>


    <script type="text/javascript" src="javascript/jquery-1.7.1.min.js"></script>

    <script type="text/javascript">



      $(function(){
        $('#sendMoves').live('click', function() {
            var body = 'Finished Puzzle in ' + moves + ' moves. Play an amazing labyrinth clone: https://vivid-frost-3885.herokuapp.com';
            //alert(body);
            FB.api('/me/feed', 'post', { message: body }, function(response) {
            	//do nothing
            	//alert(response);
            });
        });
      });
    </script>

    <!--[if IE]>
      <script type="text/javascript">
        var tags = ['header', 'section'];
        while(tags.length)
          document.createElement(tags.pop());
      </script>
    <![endif]-->
  </head>
  <body>
    <div>
    	<div id="gamemode"><span style="font-weight:bold;">Rotate, Push Extra piece on Arrow.</span> Move through the Maze.</div>
    	<div id="mazetable" style="float:left;"></div>
    	<div style="float:left;">
		    <div>Goals Reached: <span id="goalsreached">0 of 25</span></div>
    		<div>Moves Made: <span id="movesmade">0</span></div>
			<div id="sparetile" style="padding:5px;">
				<div id="spare" style="position:relative;" onclick="flip();"></div>
				<a href="" onclick="flip(); return false;">Rotate Tile</a>
			</div>
		</div>
    </div>
  <script type="text/javascript">


		var moves = 0;

		var positionj = 0;
		var positionk = 0;

		var tpiece = 4;
		var lpiece = 18;
		var spiece = 12;
		var lastj = -1;
		var lastk = -1;
		var sparePiece;
		var size = 60;

		var userimage = "token";
		if(size == 60){
			userimage += "b";
		}
		userimage = userimage += ".png";
		var user_id = 0;


      var table = new Array(7);
      for(i = 0 ; i < table.length; i++){
      	table[i] = new Array(7);
      }


      for(j = 0 ; j < table.length; j++){
		  for(k = 0 ; k < table[j].length; k++){
			table[j][k] = new Object();
			if(j == 0 && k == 0){
				table[j][k].northOpen = false;
				table[j][k].eastOpen = true;
				table[j][k].southOpen = true;
				table[j][k].westOpen = false;
			} else if(j == 6 && k == 0){
				table[j][k].northOpen = true;
				table[j][k].eastOpen = true;
				table[j][k].southOpen = false;
				table[j][k].westOpen = false;
			} else if(j == 0 && k == 6){
				table[j][k].northOpen = false;
				table[j][k].eastOpen = false;
				table[j][k].southOpen = true;
				table[j][k].westOpen = true;
			} else if(j == 6 && k == 6){
				table[j][k].northOpen = true;
				table[j][k].eastOpen = false;
				table[j][k].southOpen = false;
				table[j][k].westOpen = true;
			} else if(j == 2 && k == 2){
				table[j][k].northOpen = false;
				table[j][k].eastOpen = true;
				table[j][k].southOpen = true;
				table[j][k].westOpen = true;
			} else if(j == 2 && k == 4){
				table[j][k].northOpen = true;
				table[j][k].eastOpen = false;
				table[j][k].southOpen = true;
				table[j][k].westOpen = true;
			} else if(j == 4 && k == 2){
				table[j][k].northOpen = true;
				table[j][k].eastOpen = true;
				table[j][k].southOpen = true;
				table[j][k].westOpen = false;
			} else if(j == 4 && k == 4){
				table[j][k].northOpen = true;
				table[j][k].eastOpen = true;
				table[j][k].southOpen = false;
				table[j][k].westOpen = true;
			} else if(j == 0 && k == 2){
				table[j][k].northOpen = false;
				table[j][k].eastOpen = true;
				table[j][k].southOpen = true;
				table[j][k].westOpen = true;
			} else if(j == 0 && k == 4){
				table[j][k].northOpen = false;
				table[j][k].eastOpen = true;
				table[j][k].southOpen = true;
				table[j][k].westOpen = true;
			} else if(j == 2 && k == 0){
				table[j][k].northOpen = true;
				table[j][k].eastOpen = true;
				table[j][k].southOpen = true;
				table[j][k].westOpen = false;
			} else if(j == 4 && k == 0){
				table[j][k].northOpen = true;
				table[j][k].eastOpen = true;
				table[j][k].southOpen = true;
				table[j][k].westOpen = false;
			} else if(j == 6 && k == 2){
				table[j][k].northOpen = true;
				table[j][k].eastOpen = true;
				table[j][k].southOpen = false;
				table[j][k].westOpen = true;
			} else if(j == 6 && k == 4){
				table[j][k].northOpen = true;
				table[j][k].eastOpen = true;
				table[j][k].southOpen = false;
				table[j][k].westOpen = true;
			} else if(j == 2 && k == 6){
				table[j][k].northOpen = true;
				table[j][k].eastOpen = false;
				table[j][k].southOpen = true;
				table[j][k].westOpen = true;
			} else if(j == 4 && k == 6){
				table[j][k].northOpen = true;
				table[j][k].eastOpen = false;
				table[j][k].southOpen = true;
				table[j][k].westOpen = true;
			} else {
				randomShape(table[j][k]);
			}
			table[j][k].visited = false;
		  }
      }

	  function randomShape(position){
		var totalShapes = tpiece + lpiece + spiece;
		var shapeNumber = Math.floor(Math.random() * totalShapes);
		var rotation = Math.floor(Math.random() * 4)
		position.northOpen = true;
		position.eastOpen = true;
		position.southOpen = true;
		position.westOpen = true;
		if(shapeNumber < tpiece){
			//t
			if(rotation == 0){
				position.northOpen = false;
			} else if(rotation == 1){
				position.eastOpen = false;
			} else if(rotation == 2){
				position.southOpen = false;
			} else {
				position.westOpen = false;
			}
			tpiece = tpiece - 1;
		} else if(shapeNumber < tpiece + lpiece){
			//l
			if(rotation == 0){
				position.northOpen = false;
				position.eastOpen = false;
			} else if(rotation == 1){
				position.eastOpen = false;
				position.southOpen = false;
			} else if(rotation == 2){
				position.southOpen = false;
				position.westOpen = false;
			} else {
				position.westOpen = false;
				position.northOpen = false;
			}
			lpiece = lpiece - 1;
		} else {
			//s
			if(rotation == 0){
				position.northOpen = false;
				position.southOpen = false;
			} else {
				position.eastOpen = false;
				position.westOpen = false;
			}
			spiece = spiece - 1;
		}
	  }

      function drawTable(){
        var tableText = "";
        tableText += "<div style='position:relative;'>";
        for(j = 0; j < table.length; j++){
            for(k = 0; k < table[j].length; k++){
            	var image = getImageForPiece(table[j][k]);
            	if(size == 60){
            		image = image + "b";
            	}
                tableText += '<img id="p' + j + k + '" style="display:block; float:left;" onclick="movepush(' + j + ', ' + k + ');" onmouseup="movepush(' + j + ', ' + k + ');" src="' + image + '.png"/>';
            }
            tableText += "<div style='clear:both;'></div>";
        }

		var goalImage = "goal";
		var arrownImage = "arrown";
		var arroweImage = "arrowe";
		var arrowsImage = "arrows";
		var arrowwImage = "arroww";
		if(size == 60){
			goalImage += goalNumber + "b";
			arrownImage += "b";
			arroweImage += "b";
			arrowsImage += "b";
			arrowwImage += "b";
        }

        tableText += '<p id="token" onclick="movepushtoken();" onmouseup="movepushtoken();" style="position:absolute; background-position:center 50%; opacity:0.7; background-size: ' + (size - 5) + 'px auto; background-repeat:no-repeat; width:' + size + 'px; height:' + size + 'px; top:' + (positionk * size) + 'px; left:' + (positionj * size) + 'px; background-image:url(' + userimage + ');"></p>';
        tableText += '<img id="goal" onclick="movepushgoal();" onmouseup="movepushgoal();" style="position:absolute; opacity:0.9; top:0px; left:0px;" src="' + goalImage + '.png">';

		tableText += '<img id="a01" style="position:absolute; top:0px; left:' + (size) + 'px; opacity:0.5;" src="' +arrownImage + '.png" onclick="movepush(0, 1);" onmouseup="movepush(0, 1);">';
		tableText += '<img id="a03" style="position:absolute; top:0px; left:' + (size * 3) + 'px; opacity:0.5;" src="' +arrownImage + '.png" onclick="movepush(0, 3);" onmouseup="movepush(0, 3);">';
		tableText += '<img id="a05" style="position:absolute; top:0px; left:' + (size * 5) + 'px; opacity:0.5;" src="' +arrownImage + '.png" onclick="movepush(0, 5);" onmouseup="movepush(0, 5);">';

		tableText += '<img id="a10" style="position:absolute; top:' + (size) + 'px; left:0px; opacity:0.5;" src="' +arrowwImage + '.png" onclick="movepush(1, 0);" onmouseup="movepush(1, 0);">';
		tableText += '<img id="a30" style="position:absolute; top:' + (size * 3) + 'px; left:0px; opacity:0.5;" src="' +arrowwImage + '.png" onclick="movepush(3, 0);" onmouseup="movepush(3, 0);">';
		tableText += '<img id="a50" style="position:absolute; top:' + (size * 5) + 'px; left:0px; opacity:0.5;" src="' +arrowwImage + '.png" onclick="movepush(5, 0);" onmouseup="movepush(5, 0);">';

		tableText += '<img id="a61" style="position:absolute; top:' + (size * 6) + 'px; left:' + (size) + 'px; opacity:0.5;" src="' +arrowsImage + '.png" onclick="movepush(6, 1);" onmouseup="movepush(6, 1);">';
		tableText += '<img id="a63" style="position:absolute; top:' + (size * 6) + 'px; left:' + (size * 3) + 'px; opacity:0.5;" src="' +arrowsImage + '.png" onclick="movepush(6, 3);" onmouseup="movepush(6, 3);">';
		tableText += '<img id="a65" style="position:absolute; top:' + (size * 6) + 'px; left:' + (size * 5) + 'px; opacity:0.5;" src="' +arrowsImage + '.png" onclick="movepush(6, 5);" onmouseup="movepush(6, 5);">';

		tableText += '<img id="a16" style="position:absolute; top:' + (size) + 'px; left:' + (size * 6) + 'px; opacity:0.5;" src="' +arroweImage + '.png" onclick="movepush(1, 6);" onmouseup="movepush(1, 6);">';
		tableText += '<img id="a36" style="position:absolute; top:' + (size * 3) + 'px; left:' + (size * 6) + 'px; opacity:0.5;" src="' +arroweImage + '.png" onclick="movepush(3, 6);" onmouseup="movepush(3, 6);">';
		tableText += '<img id="a56" style="position:absolute; top:' + (size * 5) + 'px; left:' + (size * 6) + 'px; opacity:0.5;" src="' +arroweImage + '.png" onclick="movepush(5, 6);" onmouseup="movepush(5, 6);">';

        tableText += "</div>";
        document.getElementById("mazetable").innerHTML = tableText;
		if(lastj == 0){
			document.getElementById("a" + "6" + lastk).style.display="none";
		} else if(lastj == 6){
			document.getElementById("a" + "0" + lastk).style.display="none";
		} else if(lastk == 0){
			document.getElementById("a" + lastj + "6").style.display="none";
		} else if(lastk == 6){
			document.getElementById("a" + lastj + "0").style.display="none";
		}
      }

      function getImageForPiece(piece){
		  var image;
		  if(piece.northOpen == true && piece.southOpen == true && piece.westOpen == false && piece.eastOpen == false){
			image = "i1";
		  } else if(piece.northOpen == false && piece.southOpen == false && piece.westOpen == true && piece.eastOpen == true){
			image = "i0";
		  } else if(piece.northOpen == false && piece.southOpen == true && piece.westOpen == false && piece.eastOpen == true){
			image = "l1";
		  } else if(piece.northOpen == false && piece.southOpen == true && piece.westOpen == true && piece.eastOpen == false){
			image = "l2";
		  } else if(piece.northOpen == true && piece.southOpen == false && piece.westOpen == true && piece.eastOpen == false){
			image = "l3";
		  } else if(piece.northOpen == true && piece.southOpen == false && piece.westOpen == false && piece.eastOpen == true){
			image = "l0";
		  } else if(piece.northOpen == false && piece.southOpen == true && piece.westOpen == true && piece.eastOpen == true){
			image = "t2";
		  } else if(piece.northOpen == true && piece.southOpen == false && piece.westOpen == true && piece.eastOpen == true){
			image = "t0";
		  } else if(piece.northOpen == true && piece.southOpen == true && piece.westOpen == false && piece.eastOpen == true){
			image = "t1";
		  } else if(piece.northOpen == true && piece.southOpen == true && piece.westOpen == true && piece.eastOpen == false){
			image = "t3";
		  }
		  return image;
      }

      function highlightPushable(){
      	for(j = 0; j < table.length; j++){
			for(k = 0; k < table.length; k++){
				if(!isValidPush(j, k)){
					document.getElementById("p" + j + k).style.opacity = 0.7;
				}
			}
        }
      }

	function highlightMoveable(){
		for(j = 0; j < table.length; j++){
			for(k = 0; k < table.length; k++){
				if(!isValidMove(j, k)){
					document.getElementById("p" + j + k).style.opacity = 0.7;
				}
			}
	  	}
	 }

      function movepushtoken(){
          movepush(positionj, positionk);
      }

      function movepushgoal(){
          movepush(goalj, goalk);
      }

      function drawSpare(){
          if(sparePiece == undefined){
          	sparePiece = new Object();
          	sparePiece.visited = false;
          	randomShape(sparePiece);
          }
          var spareImage = getImageForPiece(sparePiece);
          var goalImage = "goal";
          var rotateImage = "rotate";
          if(size == 60){
          	spareImage += "b";
          	goalImage += goalNumber + "b";
          	rotateImage += "b";
          }
      	  if(goalOnSpare){
          	document.getElementById('spare').innerHTML = '<img src="' + spareImage + '.png"><img style="position:absolute; top:0px; left:0px; opacity:0.9;" src="' + goalImage + '.png"><img style="position:absolute; top:0px; left:0px; opacity:0.7;" src="' + rotateImage + '.png">';
          } else {
          	document.getElementById('spare').innerHTML = '<img src="' + spareImage + '.png"><img style="position:absolute; top:0px; left:0px; opacity:0.9; display:none;" src="' + goalImage + '.png"><img style="position:absolute; top:0px; left:0px; opacity:0.7;" src="' + rotateImage + '.png">';
          }
      }

      function drawToken(j, k){
          document.getElementById('token').style.top = (j * size) + "px";
          document.getElementById('token').style.left = (k * size) + "px";
      }

      function drawGoal(j, k){
          if(goalOnSpare == false){
          	  document.getElementById('goal').style.display = "inline";
			  document.getElementById('goal').style.top = (j * size) + "px";
			  document.getElementById('goal').style.left = (k * size) + "px";
          } else {
			document.getElementById('goal').style.display = "none";
          }
      }

      function isValidMove(j, k){
      	  for(var j1 = 0; j1 < 7; j1++){
			  for(var k1 = 0; k1 < 7; k1++){
			  	table[j1][k1].visited = false;
			  }
          }
          return canIGetThereFromHere(positionj, positionk, j, k);
      }

      function isValidPush(j, k){
      	  if(lastj == 0){
      	  	if(j == 6 && k == lastk){
      	  		return false;
      	  	}
      	  } else if(lastj == 6){
      	  	if(j == 0 && k == lastk){
      	  		return false;
      	  	}
      	  } else if(lastk == 0){
      	  	if(j == lastj && k == 6){
      	  		return false;
      	  	}
      	  } else if (lastk == 6){
      	  	if(j == lastj && k == 0){
      	  		return false;
      	  	}
      	  }

          if((j == 0 || j == 6) && (k % 2) == 1){
              return true;
          }
          if((k == 0 || k == 6) && (j % 2) == 1){
              return true;
          }
          return false;
      }

      function reachedGoal(j, k){
          return j == goalj && k == goalk;
      }

      function canNorth(j, k){
		return table[j][k].northOpen && table[j - 1][k].southOpen;
      }
      function canEast(j, k){
		return table[j][k].eastOpen && table[j][k + 1].westOpen;
      }
      function canSouth(j, k){
		return table[j][k].southOpen && table[j + 1][k].northOpen;
      }
      function canWest(j, k){
		return table[j][k].westOpen && table[j][k - 1].eastOpen;
      }

      function canIGetThereFromHere(j1, k1, j2, k2){
      		//j is y k is x
	  		if(j1 == j2 && k1 == k2){
	  			return true;
	  		} else {
	  			//Mark as visited
	  			table[j1][k1].visited = true;
	  			if(k1 != 0 && canWest(j1, k1) && !table[j1][k1 - 1].visited){
	  				if(canIGetThereFromHere(j1, k1 - 1, j2, k2)){
	  					table[j1][k1].visited = false;
	  					return true;
	  				}
	  			}
	  			if(j1 != 6 && canSouth(j1, k1) && !table[j1 + 1][k1].visited){
	  				if(canIGetThereFromHere(j1 + 1, k1, j2, k2)){
	  					table[j1][k1].visited = false;
	  					return true;
	  				}
	  			}
	  			if(k1 != 6 && canEast(j1, k1) && !table[j1][k1 + 1].visited ){
	  				if(canIGetThereFromHere(j1, k1 + 1, j2, k2)){
	  					table[j1][k1].visited = false;
	  					return true;
	  				}
	  			}
	  			if(j1 != 0 && canNorth(j1, k1) && !table[j1 - 1][k1].visited){
	  				if(canIGetThereFromHere(j1 - 1, k1, j2, k2)){
	  					table[j1][k1].visited = false;
	  					return true;
	  				}
	  			}
	  			//Unmark as visited
	  			table[j1][k1].visited = false;
	  			return false;
	  		}
	}



      var goalsReached = 0;
      var goalj = 0;
      var goalk = 0;
      var goalOnSpare = false;
      var goalNumber = Math.ceil(Math.random() * 4);

      function generateNewGoal(){
          if(goalsReached < 24){
          	  if(goalsReached != 0){
          	  	goalNumber = Math.ceil(Math.random() * 4);
          	  }
          	  spareGoal = Math.floor(Math.random() * 34);
          	  if(spareGoal == 0){
          	  	goalOnSpare = true;
          	  } else {
          	  	goalOnSpare = false;
          	  	goalj = Math.floor(Math.random() * 7);
				goalk = Math.floor(Math.random() * 7);
				if((goalj == 0 && goalk == 0) || goalj == positionj && goalk == positionk){
					generateNewGoal();
					return;
              	}
          	  }
          	  drawGoal(goalj, goalk);
          	  drawSpare();
          } else {
              goalj = 0;
              goalk = 0;
          }
      }

      function movepush(j, k){
      		if(mode == undefined){
      			mode = "push";
      		}
          if(mode == "move"){
              if(isValidMove(j, k)){
                  positionj = j;
                  positionk = k;
                  moves = moves + 1;
                  document.getElementById("movesmade").innerHTML = moves;
                  drawToken(j, k);
                  if(reachedGoal(j, k)){
                      if(j == 0 && k == 0){
                          document.getElementById("mazetable").style.display = "none";
                          document.getElementById('goal').style.display = "none";
                          document.getElementById('token').style.display = "none";
                          document.getElementById('sparetile').style.display = "none";
                       	  document.getElementById("goalsreached").innerHTML = goalsReached + " of 25";
                          return;
                      }
                      goalsReached = goalsReached + 1;
                      document.getElementById("goalsreached").innerHTML = goalsReached + " of 25";
                      generateNewGoal();
                  }
                  mode = "push";
                  document.getElementById("gamemode").innerHTML = '<span style="font-weight:bold;">Rotate, Push Extra piece on Arrow.</span> Move through the Maze.';
                  drawTable();
                  highlightPushable();
                  drawGoal(goalj, goalk);
                  drawToken(positionj, positionk);
                  document.getElementById('spare').style.opacity = 1.0;
              }
          } else if(mode == "push"){
              if(isValidPush(j, k)){
              	  lastj = j;
              	  lastk = k;
              	  var goalAlreadyOnSpare = goalOnSpare;
				  if(lastj == 0){
					var tempSpare = sparePiece;
					sparePiece = table[6][k];
					for(i = 6; i > 0; i--){
						table[i][k]	= table[i - 1][k];
					}
					table[0][k] = tempSpare;
					if(positionk == k){
						if(positionj < 6){
							positionj = positionj + 1;
						} else {
							positionj = 0;
						}
					}

					if(goalOnSpare){
					   goalj = 0;
					   goalk = k;
				    } else {
						if(goalk == k){
							if(goalj < 6){
								goalj = goalj + 1;
							} else {
								goalOnSpare = true;
							}
						}
				    }
				  } else if(lastj == 6){
					var tempSpare = sparePiece;
					sparePiece = table[0][k];
					for(i = 0; i < 6; i++){
						table[i][k]	= table[i + 1][k];
					}
					table[6][k] = tempSpare;
					if(positionk == k){
						if(positionj > 0){
							positionj = positionj - 1;
						} else {
							positionj = 6;
						}
					}

					if(goalOnSpare){
					   goalj = 6;
					   goalk = k;
				    } else {
						if(goalk == k){
							if(goalj > 0){
								goalj = goalj - 1;
							} else {
								goalOnSpare = true;
							}
						}
				    }
				  } else if(lastk == 0){
					var tempSpare = sparePiece;
					sparePiece = table[j][6];
					for(i = 6; i > 0; i--){
						table[j][i]	= table[j][i - 1];
					}
					table[j][0] = tempSpare;
					if(positionj == j){
						if(positionk < 6){
							positionk = positionk + 1;
						} else {
							positionk = 0;
						}
					}

					if(goalOnSpare){
					   goalj = j;
					   goalk = 0;
				    } else {
						if(goalj == j){
							if(goalk < 6){
								goalk = goalk + 1;
							} else {
								goalOnSpare = true;
							}
						}
				    }
				  } else if (lastk == 6){
					var tempSpare = sparePiece;
					sparePiece = table[j][0];
					for(i = 0; i < 6; i++){
						table[j][i]	= table[j][i + 1];
					}
					table[j][6] = tempSpare;
					if(positionj == j){
						if(positionk > 0){
							positionk = positionk - 1;
						} else {
							positionk = 6;
						}
					}

					if(goalOnSpare){
					   goalj = j;
					   goalk = 6;
				    } else {
						if(goalj == j){
							if(goalk > 0){
								goalk = goalk - 1;
							} else {
								goalOnSpare = true;
							}
						}
				    }
      	  		  }

  				  if(goalAlreadyOnSpare){
  				  	goalOnSpare = false;
				  }

                  mode = "move";
                  document.getElementById("gamemode").innerHTML = 'Rotate, Push Extra piece on Arrow. <span style="font-weight:bold;">Move through the Maze.</span>';
                  drawSpare();
                  drawTable();
                  highlightMoveable();
                  drawGoal(goalj, goalk);
                  drawToken(positionj, positionk);
                  document.getElementById('spare').style.opacity = 0.7;
              }
          }
      }

      function flip(){
          var tempNorth = sparePiece.northOpen;
          var tempEast = sparePiece.eastOpen;
          var tempSouth = sparePiece.southOpen;
          var tempWest = sparePiece.westOpen;
		  sparePiece.eastOpen = tempNorth;
		  sparePiece.southOpen = tempEast;
          sparePiece.westOpen = tempSouth;
          sparePiece.northOpen = tempWest;
          drawSpare();
      }
	  mode = "push";
      drawTable();
      highlightPushable();
      generateNewGoal();
      drawSpare();
      drawGoal(goalj, goalk);
  </script>
  <div style="clear:both;"></div>
  <div id="showlegend">
  <a href="" onclick="document.getElementById('legend').style.display = 'block'; document.getElementById('showlegend').style.display = 'none'; return false;">Show Legend</a>
  </div>
  <div id="legend" style="display:none;">
  <h3>Legend</h3>
  <ol>
  <li>Elbow tile <img src="l0.png" style="border:1px solid black"> <img src="l1.png" style="border:1px solid black"> <img src="l2.png" style="border:1px solid black"> <img src="l3.png" style="border:1px solid black"></li>
  <li>T tile <img src="t0.png" style="border:1px solid black"> <img src="t1.png" style="border:1px solid black"> <img src="t2.png" style="border:1px solid black"> <img src="t3.png" style="border:1px solid black"></li>
  <li>Straight tile <img src="i0.png" style="border:1px solid black"> <img src="i1.png" style="border:1px solid black"></li>
  <li>Clickable <img src="t0.png" style="border:1px solid black"></li>
  <li>Not Clickable <img src="t0.png" style="opacity:0.5; border:1px solid black;"></li>
  <li>Avitar <img src="token.png" style="border:1px solid black"></li>
  <li>Goal <img src="goal.png" style="border:1px solid black"></li>
  </ol>
  <a href="" onclick="document.getElementById('legend').style.display = 'none'; document.getElementById('showlegend').style.display = 'block'; return false;">Hide Legend</a>
  </div>

  <div id="showhowtoplay">
  <a href="" onclick="document.getElementById('howtoplay').style.display = 'block'; document.getElementById('showhowtoplay').style.display = 'none'; return false;">Show How to Play</a>
  </div>
  <div id="howtoplay" style="display:none;">
  <h3>How to Play</h3>
  <ol>
  <li>Shift the maze</li>
  <li>Move the Avitar onto the goal</li>
  </ol>
  <h3>Game mechanics</h3>
  <ol>
  <li>Shift the maze by flipping the spare tile and adding it to one of the 12 sides. You can not slide in where you last slid out.</li>
  <li>Move the red piece to the tile the green piece is on. Do this 25 times to win the game.</li>
  <li>If the Avitar gets slid out, it appears on the newly added tile.</li>
  <li>If the goal gets slid out, it stays on the spare tile.</li>
  </ol>
  <a href="" onclick="document.getElementById('howtoplay').style.display = 'none'; document.getElementById('showhowtoplay').style.display = 'block'; return false;">Hide How to Play</a>
  </div>
  <div>Made by: <a href="mailto:ccarrster@gmail.com">ccarrster@gmail.com</a> </div>
  </body>
</html>
