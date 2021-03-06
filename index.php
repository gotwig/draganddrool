<?php
require_once 'lib/Login.php';
require_once 'lib/Session.php';
require_once 'conf/Config.php';

$link = mysqli_connect(Config::MYSQL_SERVER, Config::MYSQL_USER, Config::MYSQL_PASS, Config::DEFAULT_DATABASE);

Session::init();

header('Content-type: text/html; charset=utf-8');

if($_SESSION['id'] && !isset($_COOKIE['tzRemember']) && !$_SESSION['rememberMe'])
{
    // If you are logged in, but you don't have the tzRemember cookie (browser restart)
    // and you have not checked the rememberMe checkbox:

    $_SESSION = array();
	session_destroy();

	// Destroy the session
}

if($_POST['submit']=='Login')
{
	// Checking whether the Login form has been submitted


		$_POST['username'] = mysqli_real_escape_string($link, $_POST['username']);
		$_POST['password'] = mysqli_real_escape_string($link, $_POST['password']);
		$_POST['rememberMe'] = (int)$_POST['rememberMe'];

		// Escaping all input data

		$row = mysqli_fetch_assoc(mysqli_query($link, "SELECT id,name,lastusedgrid,ban FROM login WHERE name='{$_POST['username']}' AND pwd='".md5($_POST['password'])."'"));

		if($row['name'])
		{
			// Check if the user is banned.
			if($row['ban']==1){echo('<h1>Your account was suspended.</h1><p>This could be due to illegal uploaded content. Please check out our FAQ.</p><h2>To get your account back, please send an individual request to <a href="mailto: info@draganddrool.tk">info@draganddrool.tk</a>.
			<h3>We are sorry for the inconveniences, and wish you the best to get your account as-soon-as-possible back and running.</h3>');
			exit;}

			
			// If everything is OK login


			$_SESSION['usr']=$row['name'];
			$_SESSION['id'] = $row['id'];
			$_SESSION['rememberMe'] = $_POST['rememberMe'];
			$_SESSION['gridid'] = $row['lastusedgrid'];

			// Store some data in the session

			setcookie('tzRemember',$_POST['rememberMe']);
			// We create the tzRemember cookie
			header("Location: project.php");
			exit;
		}
		else {echo "<script type='text/javascript'>;
		
		error = 'password';
		
		</script>";		
		}
		
	}

	if($err)
		$_SESSION['msg']['login-err'] = implode('<br />',$err);
		// Save the error messages in the session

$script = '';

if($_SESSION['msg'])
{
	// The script below shows the sliding panel on page load



	
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Drag&amp;Drool: Upload, Edit, Manage, Create</title>
  
  <link rel="stylesheet" type="text/css" href="css/grid.css">

    <link rel="stylesheet" type="text/css" href="css/slide.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css" media="screen" />
    
    <script type='text/javascript' src='jquery/jquery-1.9.0.min.js'></script>
    <script type='text/javascript' src='jquery/jquery-ui.js'></script>
  	<script type='text/javascript' src="jquery/jquery.gridster.js"></script> 
  	<script type='text/javascript' src='jquery/jquery.jeditable.mini.js'></script>
        
    <!-- PNG FIX for IE6 -->
    <!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
    <!--[if lte IE 6]>
        <script type="text/javascript" src="login_panel/js/pngfix/supersleight-min.js"></script>
    <![endif]-->


<script type='text/javascript'>//<![CDATA[ 
$(window).load(function(){

$(function(){ //DOM Ready
    

var grid_size = 50;
var grid_margin = 5;
var grid_height;
var block_params = {
    max_width: 6,
    max_height: 6
};
    
       $(".gridster ul").gridster({
			ready: function() {$('#spinner').remove(); $('.gridster').css({opacity: 0.0, visibility: "visible"}).fadeTo(1050, 1.0);
							if ($("#resizable li").length == 0){ $("#infobox").css('display', 'block');}
							  },
        widget_margins: [grid_margin, grid_margin],
        widget_base_dimensions: [grid_size, grid_size],
			serialize_params: function($w, wgd) {
				return { id: $w.data('id'), col: wgd.col, row: wgd.row, sizex: wgd.sizex, sizey: wgd.sizey };
				},
            draggable: {
            	start: function(event, ui) {

            	dragged = 1;
        	}
         
            }
        });

    gridster = $(".gridster ul").gridster().data('gridster');
    
    $('.gs_w').resizable({
        grid: [grid_size + (grid_margin * 2), grid_size + (grid_margin * 2)],
        animate: false,
        minWidth: grid_size,
        minHeight: grid_size,
        autoHide: true,
        start: function(event, ui) {
        	grid_height = gridster.$el.height();
        },
        resize: function(event, ui) {
        	//set new grid height along the dragging period
        	var delta = grid_size + grid_margin * 2;	        	
        	if (event.offsetY > gridster.$el.height())
        	{
        		var extra = Math.floor((event.offsetY - grid_height) / delta + 1);
	        	var new_height = grid_height + extra * delta;
	        	gridster.$el.css('height', new_height);
        	}
        },
        stop: function(event, ui) {
            var resized = $(this);
            setTimeout(function() {
                resizeBlock(resized);
            }, 300);
        }
    });
    
        $('.ui-resizable-handle').hover(function() {
        gridster.disable();
    }, function() {

        gridster.enable();
    });

    function resizeBlock(elmObj) {

        var elmObj = $(elmObj);
        var w = elmObj.width() - grid_size;
        var h = elmObj.height() - grid_size;

        for (var grid_w = 1; w > 0; w -= (grid_size + (grid_margin * 2))) {

            grid_w++;
        }

        for (var grid_h = 1; h > 0; h -= (grid_size + (grid_margin * 2))) {

            grid_h++;
        }

        gridster.resize_widget(elmObj, grid_w, grid_h);
        gridster.set_dom_grid_height();	        
    }
});
         
});//]]>
</script>

<script type="text/javascript">

function addBox(type, id)  {
	var dynamicVal = "<?php echo "$actualgrid"; ?>";

	if ($('#nogridinfo')){
	
	$('#nogridinfo').remove();

}
	switch (type)	{
case "text": content='<li id="' + id + '"><p data-content-id="'+id+'" onkeypress="return (this.textContent.length &lt;= 60)" class="text editable" contenteditable="true">Click, to add your text content</p>';
			break;

case "quote": content='<li id="' + id + '"><p data-content-id="'+id+'" onkeypress="return (this.textContent.length &lt;= 60)" class="quote editable" contenteditable="true">Click, to enter a quote</p>';
			break;

case "image": content='<li id="'+ id + '"><img alt="image content" data-content-id="'+id+'" class="new image" src="http://c.dart-examples.com/_/rsrc/1338345094325/config/customLogo.gif">';
			break;
			}
	content = content + '<a class="remove_action" data-id="'+id+'">  <img alt="remove element" class="actionicon" src=icons/delete.png> </a></li>'
	  	gridster.add_widget(content, 2, 2);
	}



    $(document).ready(function() {
    	
    	
    	
      	// Reset Font Size
	var originalFontSize = $(this).parents().siblings("p").css('font-size');
	    $(document).on("click", ".resetFont", function(e){
		$(this).parents().siblings("p").css('font-size', originalFontSize);
		$(this).parents().siblings("p").css('font-weight', '400');
		
	});
	
	// Increase Font Size
	    $(document).on("click", ".increaseFont", function(e){
		var currentFontSize = $(this).parents().siblings("p").css('font-size');
		var currentFontSizeNum = parseFloat(currentFontSize, 10);
		var newFontSize = currentFontSizeNum*1.2;
		$(this).parents().siblings("p").css('font-size', newFontSize);
		return false;
	});
	
	// Decrease Font Size
	    $(document).on("click", ".decreaseFont", function(e){
		var currentFontSize = $(this).parents().siblings("p").css('font-size');
		var currentFontSizeNum = parseFloat(currentFontSize, 10);
		var newFontSize = currentFontSizeNum*0.8;
		$(this).parents().siblings("p").css('font-size', newFontSize);
		return false;
	});
	
	// Make bold
	$(document).on("click", ".boldFont", function(e){
		var weight = $(this).parents().siblings("p").css('font-weight');
		if (weight == 400)
		$(this).parents().siblings("p").css('font-weight', 700)
		
		else 
		$(this).parents().siblings("p").css('font-weight', 400)
		
		return false;
	});
	

			$(document).on("click", ".remove_action", function(e){	
			var id = $(this).parent().data('id');

			gridster.remove_widget($('[data-id="'+id+'"]') );

		});
		

		$(".new_action p").click(function (e) {	
			var id = $('#gridname').data('actualgrid')
			var type = $(this).data('type');
	 
		});


		$(document).on("blur", ".text, .quote", function(e){	
			var id = $(this).data('content-id');		
			var content = $(this).text();
			var type = $(this).attr('class').split(' ')[0];
		});
			
	});

</script>

</head>


<body>

<img id="spinner" alt="Loading Boxes..." src="icons/image_381811.gif" />

<!-- Panel -->
	<div class="meny" id="panel">
		<div class="content clearfix">
			<div id="panel1" class="left">
				<a id="logo" href="/project">Drag&amp;<br>Drool</a>
				<h2>Your files in 2D boxes.</h2>		
				<p class="grey"><b>Upload, Edit, Manage, Create</b></p>
			</div> 
			<div id="panel2" class="left">
			
			                      
        <h1 class="icon-home">Member Login</h1>
                		<div id="recover">
                <label class="grey" for="recovermail">Your existing E-Mail adress:</label>
                <input id="recovermail" class="field" type="password" name="password" size="23">
                </div>
        <form id="login" method="post" action="index.php">
                <input tabindex="1" class="field" type="text" name="username" id="username" value="" size="23">
                <input tabindex="3" title="Login to Drag&Drool" alt="Login" type="image" src="icons/login/login_white.png" name="submit" value="Login" class="bt_login" >
                <input type="hidden" name="submit" value="Login">
                <input tabindex="2" class="field" type="password" name="password" id="password" size="23">
				<input title="Check to remember password" name="rememberMe" id="rememberMe" type="checkbox" value="1" checked="checked">
                <div class="clear"></div>
                <input id="recoverbox" type="checkbox" name="recoverbox">
        </form>

						
			</div>
			
			
			
			
			
			<div  class="left right" id="register">			
				<!-- Register Form -->
				<form action="php/add_user.php" method="post">
					<h1 class="icon-user-add">Not a member yet?</h1>		
                     <?php
						
						if($_SESSION['msg']['reg-err'])
						{
							echo '<div class="err">'.$_SESSION['msg']['reg-err'].'</div>';
							unset($_SESSION['msg']['reg-err']);
						}
						
						if($_SESSION['msg']['reg-success'])
						{
							echo '<div class="success">'.$_SESSION['msg']['reg-success'].'</div>';
							unset($_SESSION['msg']['reg-success']);
						}
					?>
                                        		
					<label class="grey" for="new_email">E-Mail:</label>
					<input class="field" type="email" name="new_email" id="new_email" value="" maxlength="28" size="23">
					<label class="grey new_username_password_label" for="new_username">Username:</label>
					<label class="grey new_username_password_label" for="new_password">Password:</label><input class="field" type="text" name="new_username" id="new_username" size="23" maxlength="14">
					
					<input class="field" type="password" name="new_password" id="new_password" size="23" maxlength="20">
                    <input type="submit" name="submit" value="Sign up for free" id="bt_register">
				</form>
				
			</div>
            
		</div>
	</div> <!-- /login -->	

    <!-- The tab on top -->	
	<div class="tab">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
	        <li id="sign_menu">click to <i>Login / Sign Up</i></li>
	    	<li class="right">&nbsp;</li>
		</ul> 
	</div> <!-- / top -->
	
</div> <!--panel -->

<?php

function db_result($result)
{
if ($result->num_rows == 0)
    return;
$result->data_seek(0);
$row=$result->fetch_row();     
return $row[0];
}

	$actualgrid = 2;

	$sql_gridentries = mysqli_query($link,'SELECT * FROM gridentries WHERE gridtable='.$actualgrid);
	$sql_grid = mysqli_query($link,'SELECT * FROM grid WHERE grid.id='.$actualgrid);

	
	$t = mysqli_fetch_array($sql_grid);

// Show the latest change to the actual grid
    	
  	if ( !$sql_gridentries || !$sql_grid) 
       		die(mysqli_error());

 	echo '<div id="status"></div>
 	
	<div class="gridster contents">
	
    	<ul id="resizable">';
	 
    while($t = mysqli_fetch_array($sql_gridentries))
	{
			 if ($t['type'] == 'image')
			 {
				$type='<img alt="image content" class="image" src="' . $t['content'] . '"/>';
			 }
			 
			 if ($t['type'] == 'text')
			 {
				$type='<p style="' . $t['cssstyle'] . '" onkeypress="return (this.textContent.length <= 60)" class="text editable" contentEditable="true">' . $t['content'] . '</p><div class="button_group">	<a href="#" class="gradient button increaseFont">+</a><a href="#" class="gradient button decreaseFont">-</a><a href="#" class="gradient button boldFont">B</a></div>';
			 }
			
			 if ($t['type'] == 'quote')
			 {
				$type='<p style="' . $t['cssstyle'] . '"  onkeypress="return (this.textContent.length <= 60)" class="quote editable" contentEditable="true" >' . $t['content'] . '</p><div class="button_group">	<a href="#" class="gradient button increaseFont">+</a><a href="#" class="gradient button decreaseFont">-</a><a href="#" class="gradient button boldFont">B</a></div><br />';
			 }
			echo ('<li  data-id="' . $t['id'] . '" data-row="' . $t['datarow'].'" data-col="' . $t['datacolumn'] . '" data-sizex="' . $t['data-sizeX'] . '" data-sizey="' . $t['data-sizeY'] . '">' . $type);			
			
			echo '<a class="remove_action">  <img alt="remove element" class="actionicon" src=icons/delete.png> </a>
			</li>';
	}


?>

    </ul>
</div>

        <script src="jquery/jquery.meny.min.js"></script>

<script>
                        // Create an instance of Meny
                        var meny = Meny.create({
                                // The element that will be animated in from off screen
                                menuElement: document.querySelector( '.meny' ),

                                // The contents that gets pushed aside while Meny is active
                                contentsElement: document.querySelector( '.contents' ),

                                // [optional] The alignment of the menu (top/right/bottom/left)
                                position: Meny.getQuery().p || 'top',

                                // [optional] The height of the menu (when using top/bottom position)
                                height: 190,

                                // [optional] The width of the menu (when using left/right position)
                                width: 260,

                                // [optional] Distance from mouse (in pixels) w-1en menu should open
                                threshold: 20
                        });

                        // API Methods:
                        // meny.open();
                        // meny.close();
                        // meny.isOpen();

                        // Events:
                        meny.addEventListener( 'open', function(){ $('.login').animate({"bottom": "-1px"}, 510) } );
                        meny.addEventListener( 'close', function(){ $('.login').animate({"bottom": "183px"}, 310) } );

                        // Embed an iframe if a URL is passed in
                        if( Meny.getQuery().u && Meny.getQuery().u.match( /^http/gi ) ) {
                                var contents = document.querySelector( '.contents' );
                                contents.style.padding = '0px';
                                contents.innerHTML = '<div class="cover"></div><iframe src="'+ Meny.getQuery().u +'" style="width: 100%; height: 100%; border: 0; position: absolute;"></iframe>';
                        }
                        $("#sign_menu").click(function (e) {	
			meny.open();				
			});
			
			if (typeof(error) != "undefined"){
				meny.open();
				$('.gridster').prepend('<li style="display:block" id="infobox"><h3 id="infobox_title">Please recheck.</h3><p id="wrongpassword_info"><i class="icon-emo-thumbsup"></i>You entered a wrong<br> Password or Username</p></li>');
				$('#infobox').delay(13000).fadeOut();
			}

                </script>
</body>



</html>