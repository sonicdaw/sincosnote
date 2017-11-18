<head>
<title>Math Girl Secret Note Chapter1</title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
</head>
数学ガールの秘密ノート<br>
<a href="http://www.amazon.co.jp/dp/479737568X/ref=cm_sw_r_tw_dp_QrUBtb1T1SC6H" target="_blank">丸い三角関数1章note</a><br><br>
touch/D&DでA移動。[c=1] 長さ固定<br>
<!--[if IE]><script type="text/javascript" src="excanvas.js"></script><![endif]-->
<canvas id="cvs" width="300" height="300"></canvas>
<script type="text/javascript">
window.onload = function() {

  const WIDTH = 300;
  const HEIGHT = 300;
  const SCALE = 100;   // length 1 = SCALE

  var mouseX, mouseY;
  var mousedrug = false;
  var touchX = 0;
  var touchY = 0;
  var timer;
  var canvas = document.getElementById('cvs');
  if (!canvas.getContext) {
    return false;
  }


  var ctx = canvas.getContext('2d');
  var interval = 10;



  // Button Area
  const FIXC1_BUTTON_X = WIDTH - 110;
  const FIXC1_BUTTON_Y = HEIGHT - 39;
  const FIXC1_BUTTON_WIDTH  = 90;
  const FIXC1_BUTTON_HEIGHT = 30;

  const SCROLL_BUTTON_X = WIDTH - 110;  // 110
  const SCROLL_BUTTON_Y = HEIGHT - 115;  // 77
  const SCROLL_BUTTON_WIDTH  = 90;
  const SCROLL_BUTTON_HEIGHT = 30;

  const THETAFIX_BUTTON_X = WIDTH - 110;  // 110
  const THETAFIX_BUTTON_Y = HEIGHT - 77;  // 115
  const THETAFIX_BUTTON_WIDTH  = 90;
  const THETAFIX_BUTTON_HEIGHT = 30;

  var fix_c1_mouseover = false;;
  var fix_c1 = false;

  var scroll_mouseover = false;;
  var scroll = false;

  var thetafix_mouseover = false;;
  var thetafix = false;


  // A, B, C
  var A_x = WIDTH  / 2 + Math.sqrt(0.5) * SCALE;   // mouseX
  var A_y = HEIGHT / 2 - Math.sqrt(0.5) * SCALE;   // mouseY
  var B_x = WIDTH  / 2; // center
  var B_y = HEIGHT / 2; // center
  var C_x;
  var C_y;
  var sin;
  var cos;

  var a_length;
  var b_length;
  var c_length;

  var theta;

  var fixed_theta;


  // Mouse Shadow
  const NUM_OF_SHADOWS = 500;
  const SHADOW_COUNTER_MAX = 200;
  var shadow_x =  new Array(NUM_OF_SHADOWS);
  var shadow_y =  new Array(NUM_OF_SHADOWS);
  var shadow_counter =  new Array(NUM_OF_SHADOWS);
  var shadow_index = 0;

  for(var i = 0; i < NUM_OF_SHADOWS; i++)
  {
    shadow_x[i] =  0;
    shadow_y[i] =  0;
    shadow_counter[i] =  0;
  }


  // Graffiti Area
  const GRAFFITI_WIDTH = WIDTH * 3;
  const GRAFFITI_HEIGHT = HEIGHT / 3;
  const NUM_OF_GRAFFITI = 1000;
  var graffiti_x =  new Array(NUM_OF_GRAFFITI);
  var graffiti_y =  new Array(NUM_OF_GRAFFITI);
  var graffiti_offset = 0;
  var graffiti_index = 0;
  var prev_shadow_x = 0;	// for graffiti log
  var prev_shadow_y = 0;	// for graffiti log
  var graffiti_on = true;

  for(var i = 0; i < NUM_OF_GRAFFITI; i++)
  {
    graffiti_x[i] =  0;
    graffiti_y[i] =  0;
  }



  // ----------------------------------------------------------------
  // mouse or touch

  // mouse
  canvas.onmousemove=function(e){
    if(mousedrug)     adjustLocation(e);

    return false;
  }

  canvas.onmousedown=function(e){
    adjustLocation(e);
    fix_c1_button();
    scroll_button();
    thetafix_button();
    mousedrug = true;

    return false;
  }

  canvas.onmouseup=function(e){
    mousedrug = false;
    return false;
  }

  // touch
  canvas.ontouchstart=function(){
    e=event.touches[0];    // first touch only
    adjustLocation(e);
    event.preventDefault();

    return false;
  }

  canvas.ontouchmove=function(e){
    e=event.touches[0];    // first touch only
    adjustLocation(e);
    event.preventDefault();
    return false;
  }

  canvas.ontouchend=function(e){
    fix_c1_button();
    fix_c1_mouseover = false;
    scroll_button();
    scroll_mouseover = false;
    thetafix_button();
    thetafix_mouseover = false;

    return false;
  }


  function adjustLocation(e){
    // adjust
    var rect = e.target.getBoundingClientRect();
    mouseX = e.clientX - rect.left;
    mouseY = e.clientY - rect.top;

    // fix c=1 button detect
    if (mouseX > FIXC1_BUTTON_X && mouseX < FIXC1_BUTTON_X + FIXC1_BUTTON_WIDTH){
      if (mouseY > FIXC1_BUTTON_Y && mouseY < FIXC1_BUTTON_Y + FIXC1_BUTTON_HEIGHT){
        fix_c1_mouseover = true;
        return;
      }else{
        fix_c1_mouseover = false;
      }
    }else{
        fix_c1_mouseover = false;
    }


    // scroll button detect
    if (mouseX > SCROLL_BUTTON_X && mouseX < SCROLL_BUTTON_X + SCROLL_BUTTON_WIDTH){
      if (mouseY > SCROLL_BUTTON_Y && mouseY < SCROLL_BUTTON_Y + SCROLL_BUTTON_HEIGHT){
        scroll_mouseover = true;
        return;
      }else{
        scroll_mouseover = false;
      }
    }else{
        scroll_mouseover = false;
    }


    // theta fix button detect
    if (mouseX > THETAFIX_BUTTON_X && mouseX < THETAFIX_BUTTON_X + THETAFIX_BUTTON_WIDTH){
      if (mouseY > THETAFIX_BUTTON_Y && mouseY < THETAFIX_BUTTON_Y + THETAFIX_BUTTON_HEIGHT){
        thetafix_mouseover = true;
        return;
      }else{
        thetafix_mouseover = false;
      }
    }else{
        thetafix_mouseover = false;
    }




    A_x = mouseX;
    A_y = mouseY; 

  } // -------------------------

  function fix_c1_button(){
    if (mouseX > FIXC1_BUTTON_X && mouseX < FIXC1_BUTTON_X + FIXC1_BUTTON_WIDTH){
      if (mouseY > FIXC1_BUTTON_Y && mouseY < FIXC1_BUTTON_Y + FIXC1_BUTTON_HEIGHT){
        if(fix_c1 == false) fix_c1 = true; else fix_c1 = false;
      }
    }
  }

  function scroll_button(){
    if (mouseX > SCROLL_BUTTON_X && mouseX < SCROLL_BUTTON_X + SCROLL_BUTTON_WIDTH){
      if (mouseY > SCROLL_BUTTON_Y && mouseY < SCROLL_BUTTON_Y + SCROLL_BUTTON_HEIGHT){
        if(scroll == false) scroll = true; else scroll = false;
      }
    }
  }

  function thetafix_button(){
    if (mouseX > THETAFIX_BUTTON_X && mouseX < THETAFIX_BUTTON_X + THETAFIX_BUTTON_WIDTH){
      if (mouseY > THETAFIX_BUTTON_Y && mouseY < THETAFIX_BUTTON_Y + THETAFIX_BUTTON_HEIGHT){
        if(thetafix == false){
          thetafix = true;
          fixed_theta = theta;
       } else thetafix = false;
      }
    }
  }

  function calculate(){  // calculate from A(x,y)
    // Calculate
    B_x = WIDTH  / 2; // center
    B_y = HEIGHT / 2; // center

    C_x = A_x;
    C_y = B_y;

    a_length = (C_x - B_x) / SCALE;
    b_length = (C_y - A_y) / SCALE;
    c_length = Math.sqrt(Math.pow(a_length,2) + Math.pow(b_length,2));

    sin = b_length / c_length;
    cos = a_length / c_length;

    theta = Math.acos(cos)/(Math.PI/180);
    if(sin<0) theta=360-theta;
  }

  // DRAW
  function draw(){
    calculate();

    // c = 1 adjust
    if(fix_c1 == true){
      A_x = (A_x - B_x) / c_length + B_x;
      A_y = (A_y - B_y) / c_length + B_y;
      calculate(); // again
      c_length = 1;
    }

    // theta fix adjust
    if(thetafix == true){
      A_x = B_x + Math.cos(fixed_theta * Math.PI / 180) * c_length * SCALE;
      A_y = B_y - Math.sin(fixed_theta * Math.PI / 180) * c_length * SCALE;
      calculate(); // again
    }

    // add latest A(x,y) to shadow and graffiti
    // add shadow
    if(scroll)
      shadow_x[shadow_index] = B_x;
    else
      shadow_x[shadow_index] = A_x;

    shadow_y[shadow_index] = A_y;
    shadow_counter[shadow_index] = SHADOW_COUNTER_MAX;
 
    // add graffiti
    var last_graffiti_x = -1;  // only for drawing
    var last_graffiti_y = -1;
    if((scroll == false && (prev_shadow_x != shadow_x[shadow_index] || prev_shadow_y != shadow_y[shadow_index])) || // scroll off and mouse moved
       (scroll == true  && prev_shadow_y != shadow_y[shadow_index])                                                 // scroll on  and mouse y moved
      ){
      graffiti_x[graffiti_index] = shadow_x[shadow_index] + graffiti_offset;
      graffiti_y[graffiti_index] = shadow_y[shadow_index];
      prev_shadow_x = shadow_x[shadow_index];
      prev_shadow_y = shadow_y[shadow_index];
      graffiti_index++;
      if(graffiti_index > NUM_OF_GRAFFITI - 1) graffiti_index = 0;
    }else{
      last_graffiti_x = shadow_x[shadow_index] + graffiti_offset;	// not moved
      last_graffiti_y = shadow_y[shadow_index];
    }

    shadow_index++;
    if(shadow_index > NUM_OF_SHADOWS - 1) shadow_index=0;


    // shadow scroll
    if(scroll){
      for(var i = 0; i < NUM_OF_SHADOWS; i++)
      {
        shadow_x[i]-=0.5;
      }
      graffiti_offset++;
    }



    // DRAW --------------------------------------

    ctx.clearRect(0, 0, WIDTH, HEIGHT);

    ctx.beginPath();
    ctx.fillStyle = 'black';
    ctx.fillRect(0, 0, WIDTH, HEIGHT);
    ctx.fillStyle = 'white';
    ctx.fillRect(2, 2, WIDTH-4, HEIGHT-4);

    ctx.strokeStyle = 'rgb(220, 220, 220)';
    for (var i = 0; i < HEIGHT / 10 ; i++){
      ctx.moveTo(5, HEIGHT / 10 * i);
      ctx.lineTo(WIDTH - 5, HEIGHT / 10 * i);
    }
    ctx.fillStyle = 'black';
    ctx.stroke();



    // draw point
    ctx.strokeStyle = 'rgba(0, 0, 0, 0.5)';
    ctx.beginPath();
    ctx.arc(A_x, A_y, 1, 0, Math.PI*2, true);
    ctx.stroke();

    ctx.beginPath();
    ctx.arc(B_x, B_y, 1, 0, Math.PI*2, true);
    ctx.stroke();

    ctx.beginPath();
    ctx.arc(C_x, C_y, 1, 0, Math.PI*2, true);
    ctx.stroke();


    // draw line
    ctx.strokeStyle = 'rgba(0, 0, 0, 0.3)';
    ctx.beginPath();
    ctx.moveTo(B_x, B_y);
    ctx.lineTo(C_x, C_y);
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(C_x, C_y);
    ctx.lineTo(A_x, A_y);
    ctx.stroke();


    ctx.beginPath();
    if(fix_c1 == true)  ctx.strokeStyle = 'red';
    ctx.moveTo(A_x, A_y);
    ctx.lineTo(B_x, B_y);
    ctx.stroke();

    ctx.strokeStyle = 'rgba(0, 0, 0, 0.5)';


    // draw shadow
    var draw_index = shadow_index;

    for(var i = 0; i < NUM_OF_SHADOWS; i++)
    {
      var next_draw_index;
      if(draw_index > 0){
        next_draw_index = draw_index - 1;
      }else{	// = 0
        next_draw_index = NUM_OF_SHADOWS - 1;
      }

      if(shadow_counter[draw_index] > 0 && shadow_counter[next_draw_index] > 0){
        ctx.beginPath();
        ctx.strokeStyle = 'rgba(60, 60, 60, ' + shadow_counter[draw_index]/SHADOW_COUNTER_MAX + ')';
        ctx.moveTo(shadow_x[     draw_index], shadow_y[     draw_index]);
        ctx.lineTo(shadow_x[next_draw_index], shadow_y[next_draw_index]);
        ctx.stroke();
        shadow_counter[draw_index]--;
      }

      draw_index--;
      if(draw_index<0)draw_index=NUM_OF_SHADOWS;
    }


    // draw graffiti
if(graffiti_on){
    ctx.strokeStyle = 'rgba(0, 0, 200, 0.5)';
    var draw_offset = 0;

    var max_graffiti_x = 0;    // get max x
    for(var i = 0; i < NUM_OF_GRAFFITI; i++){
      if(graffiti_x[i] > max_graffiti_x) max_graffiti_x = graffiti_x[i];
    }
    if(max_graffiti_x < last_graffiti_x) max_graffiti_x = last_graffiti_x;

    if(max_graffiti_x > GRAFFITI_WIDTH) draw_offset = max_graffiti_x - GRAFFITI_WIDTH;


    for(var i = 1; i < NUM_OF_GRAFFITI; i++)
    {
       var draw_index = graffiti_index + i;
       if(draw_index > NUM_OF_GRAFFITI - 1) draw_index = draw_index - NUM_OF_GRAFFITI;

       ctx.beginPath();
       ctx.moveTo((graffiti_x[draw_index-1]             - draw_offset) * WIDTH / GRAFFITI_WIDTH, graffiti_y[draw_index-1] * GRAFFITI_HEIGHT / HEIGHT);
       ctx.lineTo((graffiti_x[draw_index]               - draw_offset) * WIDTH / GRAFFITI_WIDTH, graffiti_y[draw_index]   * GRAFFITI_HEIGHT / HEIGHT);
       ctx.stroke();
    }
    if(last_graffiti_x != -1){
       ctx.beginPath();  // last note
       ctx.moveTo((graffiti_x[graffiti_index-1] - draw_offset) * WIDTH / GRAFFITI_WIDTH, graffiti_y[graffiti_index-1] * GRAFFITI_HEIGHT / HEIGHT);
       ctx.lineTo((last_graffiti_x              - draw_offset) * WIDTH / GRAFFITI_WIDTH, last_graffiti_y              * GRAFFITI_HEIGHT / HEIGHT);
       ctx.stroke();
    }
}
//    ctx.fillText(fixed_theta, 10, 20);	// debug



    // draw sin/cos bar
    ctx.strokeStyle = 'rgba(0, 200, 0, 0.5)';
    ctx.lineWidth = 2.5;
    ctx.beginPath();
    ctx.moveTo(B_x, B_y);
    ctx.lineTo(B_x, B_y-sin*SCALE);
    ctx.stroke();

    ctx.strokeStyle = 'rgba(200, 0, 200, 0.5)';
    ctx.beginPath();
    ctx.moveTo(B_x, B_y);
    ctx.lineTo(B_x+cos*SCALE, B_y);
    ctx.stroke();
    ctx.lineWidth = 1;



    // draw text
    ctx.font = "bold 10pt Courier";
    ctx.textAlign = "center";

    ctx.fillText("A", A_x + 5, A_y);
    ctx.fillText("B", B_x - 5, B_y - 5);
    ctx.fillText("C", C_x + 5, C_y + 5);

    ctx.fillText("a:" + Math.abs(Math.floor( a_length * 1000 ) / 1000), (B_x+C_x)/2    , (B_y+C_y)/2 + 10);
    ctx.fillText("b:" + Math.abs(Math.floor( b_length * 1000 ) / 1000), (A_x+C_x)/2 + 5, (A_y+C_y)/2);
    ctx.fillText("c:" + Math.abs(Math.floor( c_length * 1000 ) / 1000), (A_x+B_x)/2    , (A_y+B_y)/2 - 5);

    var offset = 6;
    ctx.fillText("θ", (A_x-B_x)/offset + B_x   , (A_y-C_y)/2/offset + B_y);


    ctx.textAlign = "left";

    ctx.fillText("θ:", 10 , HEIGHT - 95);
    ctx.fillText(Math.floor(theta),     50 , HEIGHT -95);

    ctx.fillStyle = 'black';
    ctx.fillText("sinθ:", 10 , HEIGHT - 65);
    ctx.fillStyle = 'green';
    ctx.fillText(Math.floor( sin * 1000 ) / 1000,     50 , HEIGHT -65);

    ctx.fillStyle = 'black';
    ctx.fillText("cosθ:", 10 , HEIGHT - 35);
    ctx.fillStyle = 'rgba(200, 0, 200, 1.0)';
    ctx.fillText(Math.floor( cos * 1000 ) / 1000,     50 , HEIGHT - 35);

    ctx.fillStyle = 'black';



    // draw fix c=1 button
    ctx.beginPath();
    if(fix_c1_mouseover == false)
      ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';  // mouse not over
    else
      ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';  // mouse over
    ctx.fillRect(FIXC1_BUTTON_X    , FIXC1_BUTTON_Y,     FIXC1_BUTTON_WIDTH,     FIXC1_BUTTON_HEIGHT);

    if(fix_c1_mouseover == false)
      ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';   // mouse not over
    else
      ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';  // mouse over
    ctx.fillRect(FIXC1_BUTTON_X    , FIXC1_BUTTON_Y   ,  FIXC1_BUTTON_WIDTH - 2, FIXC1_BUTTON_HEIGHT - 2);

    ctx.fillStyle = 'black';
    ctx.fillText("c=1 :", WIDTH - 100 , FIXC1_BUTTON_Y + FIXC1_BUTTON_HEIGHT / 2 + 4);
    if(fix_c1 == true){
      ctx.fillStyle = 'red';
      ctx.fillText("ON",  WIDTH - 55 , FIXC1_BUTTON_Y + FIXC1_BUTTON_HEIGHT / 2 + 4);
    }else{
      ctx.fillStyle = 'black';
      ctx.fillText("OFF", WIDTH - 55 , FIXC1_BUTTON_Y + FIXC1_BUTTON_HEIGHT / 2 + 4);
    }

    ctx.stroke();



    // draw scroll button
    ctx.beginPath();
    if(scroll_mouseover == false)
      ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';  // mouse not over
    else
      ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';  // mouse over
    ctx.fillRect(SCROLL_BUTTON_X    , SCROLL_BUTTON_Y,     SCROLL_BUTTON_WIDTH,     SCROLL_BUTTON_HEIGHT);

    if(scroll_mouseover == false)
      ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';   // mouse not over
    else
      ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';  // mouse over
    ctx.fillRect(SCROLL_BUTTON_X    , SCROLL_BUTTON_Y   ,  SCROLL_BUTTON_WIDTH - 2, SCROLL_BUTTON_HEIGHT - 2);

    ctx.fillStyle = 'black';
    ctx.fillText("scroll:", WIDTH - 105 , SCROLL_BUTTON_Y + FIXC1_BUTTON_HEIGHT / 2 + 4);
    if(scroll == true){
      ctx.fillStyle = 'red';
      ctx.fillText("ON",  WIDTH - 50 , SCROLL_BUTTON_Y + FIXC1_BUTTON_HEIGHT / 2 + 4);
    }else{
      ctx.fillStyle = 'black';
      ctx.fillText("OFF", WIDTH - 50 , SCROLL_BUTTON_Y + FIXC1_BUTTON_HEIGHT / 2 + 4);
    }

    ctx.stroke();



    // draw theta fix button
    ctx.beginPath();
    if(thetafix_mouseover == false)
      ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';  // mouse not over
    else
      ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';  // mouse over
    ctx.fillRect(THETAFIX_BUTTON_X    , THETAFIX_BUTTON_Y,     THETAFIX_BUTTON_WIDTH,     THETAFIX_BUTTON_HEIGHT);

    if(thetafix_mouseover == false)
      ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';   // mouse not over
    else
      ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';  // mouse over
    ctx.fillRect(THETAFIX_BUTTON_X    , THETAFIX_BUTTON_Y   ,  THETAFIX_BUTTON_WIDTH - 2, THETAFIX_BUTTON_HEIGHT - 2);

    ctx.fillStyle = 'black';
    ctx.fillText("θfix :", WIDTH - 105 , THETAFIX_BUTTON_Y + THETAFIX_BUTTON_HEIGHT / 2 + 4);
    if(thetafix == true){
      ctx.fillStyle = 'red';
      ctx.fillText("ON",  WIDTH - 50 , THETAFIX_BUTTON_Y + THETAFIX_BUTTON_HEIGHT / 2 + 4);
    }else{
      ctx.fillStyle = 'black';
      ctx.fillText("OFF", WIDTH - 50 , THETAFIX_BUTTON_Y + THETAFIX_BUTTON_HEIGHT / 2 + 4);
    }

    ctx.stroke();




  }

  var move = function() {
    draw();

    clearTimeout(timer);
    timer = setTimeout(move, interval);
  };

  move();
};
</script><BR>
v0.007 theta fix, mouse D&D<br>
v0.006 theta value, sin/cos bar<br>
v0.005 graffiti area<br>
v0.004 shadow scroll<br>
v0.003 shadow ink<br>
v0.002 c = 1 button<br>
v0.001 sin/cos note
</CENTER></body>
</html>
