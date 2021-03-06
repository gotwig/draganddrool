<?php
require_once 'lib/Session.php';
require_once 'lib/Login.php';
require_once 'lib/Grid.php';
require_once 'conf/Config.php';

$link = mysqli_connect(Config::MYSQL_SERVER, Config::MYSQL_USER, Config::MYSQL_PASS, Config::DEFAULT_DATABASE);

Session::init();
Login::checkLoginRedirect();

header('Content-type: text/html; charset=utf-8');

$ownerid=$_SESSION['id'];

if ($_SESSION['rememberMe']==0)
{
unset( $_SESSION['id'] );
}

if(isset($_GET['logoff']))
{
    Session::destroy();
}

function plural($num) {
    if ($num != 1)
        return "s";
}

function relative_time($date) {
$diff = time() - strtotime($date);
if ($diff<60)
    	return $diff . " second" . plural($diff) . " ago";
	$diff = round($diff/60);
	if ($diff<60)
		return $diff . " minute" . plural($diff) . " ago";
	$diff = round($diff/60);
	if ($diff<24)
		return $diff . " hour" . plural($diff) . " ago";
	$diff = round($diff/24);
	if ($diff<7)
		return $diff . " day" . plural($diff) . " ago";
	$diff = round($diff/7);
	if ($diff<4)
		return $diff . " week" . plural($diff) . " ago";
	return "on " . date("F j, Y", strtotime($date));
}


	$actualgrid = $_SESSION['gridid'];

?>

<!DOCTYPE html>
<html>
<head>
<title>Drag&amp;Drool: Upload, Edit, Manage, Create</title>
<meta name="description" content="Your files in 2D boxes.">
<meta name="author" content="Eduard Gotwig">
<meta name="keywords" content="drag and drool,drag, drool, eduard gotwig, drag, jquery drag, css4, css4 menu, css parent selectors, gridster, filepicker">
  
    <link rel="stylesheet" type="text/css" href="css/grid.css">

    <link rel="stylesheet" type="text/css" href="css/slide.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css" media="screen" />
    
    <script type='text/javascript' src='jquery/jquery-1.9.0.min.js'></script>
    <script type='text/javascript' src='jquery/jquery-ui.js'></script>
    <script type='text/javascript' src="jquery/jquery.gridster.js"></script> 
    <script type='text/javascript' src="jquery/jquery.lightbox_me.js"></script> 
    <script type='text/javascript' src='jquery/jquery.jeditable.mini.js'></script>
        
    <script type="text/javascript">
(function(a){if(window.filepicker){return}var b=a.createElement("script");b.type="text/javascript";b.async=!0;b.src=("https:"===a.location.protocol?"https:":"http:")+"//api.filepicker.io/v1/filepicker.js";var c=a.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c);var d={};d._queue=[];var e="pick,pickMultiple,pickAndStore,read,write,writeUrl,export,convert,store,storeUrl,remove,stat,setKey,constructWidget,makeDropPane".split(",");var f=function(a,b){return function(){b.push([a,arguments])}};for(var g=0;g<e.length;g++){d[e[g]]=f(e[g],d._queue)}window.filepicker=d})(document); 

filepicker.setKey('Put your Filepicker.IO API key here');

jQuery.fn.css2 = jQuery.fn.css;
jQuery.fn.css = function() {
    if (arguments.length) return jQuery.fn.css2.apply(this, arguments);
    var attr = ['font-size','font-weight'];
    var len = attr.length, obj = {};
    var string = '';
    for (var i = 0; i < len; i++) 
        var string = string + attr[i] + ':' + jQuery.fn.css2.call(this, attr[i]) + ';';
    return string;
}

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
                            dragged=0;
							  },
        widget_margins: [grid_margin, grid_margin],
        widget_base_dimensions: [grid_size, grid_size],
			serialize_params: function($w, wgd) {
				return { id: $w.data('id'), col: wgd.col, row: wgd.row, sizex: wgd.size_x, sizey: wgd.size_y };
				},
            draggable: {
            	start: function(event, ui) {

            	dragged = 1;
        	},
            	
                stop: function(event, ui){
					$.post('php/save_position.php', {data: this.serialize_changed()}, function(ret) {
						//your callback
					});
					
					console.log(this);
					
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

    	    var gridster1 = $(".gridster ul").gridster().data('gridster');
	    var positions = gridster1.serialize();

	
            $.post('php/save_position.php', {data: positions}, function(ret) {
						//your callback
					});
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

function saveBox(type,id,content,cssstyle){
	
	
    if (cssstyle){
	var cssstyle = $('[data-id="'+id+'"] p').css();
    }
	

    if (!cssstyle){
		cssstyle='NULL';
	}
			$.ajax({
				url: 'php/save.php',
				type: 'POST',
				data: {
		    id:	id,
            content: content,
		    type: type,
		    cssstyle: cssstyle
				},				
			});
			}

function addBox(type, id)  {

    if ($("#resizable li").length == 0){$("#infobox").css('display', 'none');}

	var dynamicVal = "<?php echo "$actualgrid"; ?>";

	switch (type)	{
case "text": content='<li data-id="' + id + '"><p onkeypress="return (this.textContent.length &lt;= 60)" class="text editable" contenteditable="true">Click, to add your text content</p><div class="button_group">	<a href="#" class="gradient button increaseFont">+</a><a href="#" class="gradient button decreaseFont">-</a><a href="#" class="gradient button boldFont">B</a></div>';
			break;

case "quote": content='<li data-id="' + id + '"><p onkeypress="return (this.textContent.length &lt;= 60)" class="quote editable" contenteditable="true">Click, to enter a quote</p><div class="button_group">	<a href="#" class="gradient button increaseFont">+</a><a href="#" class="gradient button decreaseFont">-</a><a href="#" class="gradient button boldFont">B</a></div>';
			break;

case "image": content='<li data-id="'+ id + '"><img alt="image content" class="new image" src="http://c.dart-examples.com/_/rsrc/1338345094325/config/customLogo.gif">';
			break;
			}
	content = content + '<a class="remove_action">  <img alt="remove element" class="actionicon" src=icons/delete.png> </a></li>'
	  	gridster.add_widget(content, 2, 2);

var grid_size = 50;
var grid_margin = 5;
var grid_height;
var block_params = {
    max_width: 6,
    max_height: 6
};
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
            $.post('php/save_position.php', {data: gridster.serialize()}, function(ret) {
						//your callback
					});
        }
    });

        $('.ui-resizable-handle').hover(function() {
        gridster.disable();
    }, function() {

        gridster.enable();
    });

	}



    $(document).ready(function() {

function css(a){
    var sheets = document.styleSheets, o = {};
    for(var i in sheets) {
        var rules = sheets[i].rules || sheets[i].cssRules;
        for(var r in rules) {
            if(a.is(rules[r].selectorText)) {
                o = $.extend(o, css2json(rules[r].style), css2json(a.attr('style')));
            }
        }
    }
    return o;
}

function css2json(css){
        var s = {};
        if(!css) return s;
        if(css instanceof CSSStyleDeclaration) {
            for(var i in css) {
                if((css[i]).toLowerCase) {
                    s[(css[i]).toLowerCase()] = (css[css[i]]);
                }
            }
        } else if(typeof css == "string") {
            css = css.split("; ");          
            for (var i in css) {
                var l = css[i].split(": ");
                s[l[0].toLowerCase()] = (l[1]);
            };
        }
        return s;
    }    	


    	$('body').delegate('input','focus', function() {
    $(this).attr('maxLength',21);
});
    	
    // Editing the Gridname in the loginbar
   $("#gridname").editable("php/save_gridinfo.php", {
      submitdata : function(value, settings) { return {id: $('#gridname').data('actualgrid')}}, 
      indicator : "<img src='icons/indicator.gif'>",
      tooltip   : "Click to edit",
      style  : "inherit"
      
  });
   
   
   // To switch grids
	    $(document).on("click", ".gridinfo_data", function(e){
			var id = $(this).data('gridid');

			$.ajax({
				url: 'php/switch_grid.php',
				type: 'POST',
				data: {
			id:	id,
				},
				success: function (data) {
                location.reload();
                }
			});
		
	});
   
   
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

		saveBox('',$(this).parent().parent().data('id'),'',true);

		return false;
	});
	
	// Decrease Font Size
	    $(document).on("click", ".decreaseFont", function(e){
		var currentFontSize = $(this).parents().siblings("p").css('font-size');
		var currentFontSizeNum = parseFloat(currentFontSize, 10);
		var newFontSize = currentFontSizeNum*0.8;
		$(this).parents().siblings("p").css('font-size', newFontSize);

		saveBox('',$(this).parent().parent().data('id'),'',true);	

		return false;
	});
	
	// Make bold
	$(document).on("click", ".boldFont", function(e){
		var weight = $(this).parents().siblings("p").css('font-weight');
		if (weight == 400){
		$(this).parents().siblings("p").css('font-weight', 700)
		}
		else{
		$(this).parents().siblings("p").css('font-weight', 400)
		}
        
        saveBox('',$(this).parent().parent().data('id'),'',true);

		return false;
	});

$(document).on("click", ".remove_action", function(e){	
			var id = $(this).parent().data('id');

			gridster.remove_widget($('[data-id="'+id+'"]') );
			
			if ($('[data-id="'+id+'"] img').attr('src').match(new RegExp('https://www.filepicker.io/api'))){
				filepicker.remove($('[data-id="'+id+'"] img').attr('src'), function(){});
  			}

			$.ajax({
				url: 'php/remove.php',
				type: 'POST',
				data: {
			id:	id
				},
                                success: function (data) {
$.post('php/save_position.php', {data: gridster.serialize_changed()}, function(ret) {
                                                //your callback
                        });                }
                        });


		});

		$(document).on("click", ".image", function(e){
			
		if(!dragged){	
		
    	var instance = this;
		this.id = $(this).parent().data('id');
		this.src = $(this).attr('src');
	
		filepicker.pick({
    		mimetypes: ['image/*'],
    		container: 'modal',
    		services:['COMPUTER', 'URL', 'IMAGE_SEARCH', 'FACEBOOK', 'GOOGLE_DRIVE', 'GMAIL', 'INSTAGRAM', 'WEBCAM', 'DROPBOX', 'FLICKR', 'FTP'],
  			},
  function(FPFile){
  		if (instance.src.match(new RegExp('https://www.filepicker.io/api'))){
		filepicker.remove(instance.src, function(){});
  		}
  		 instance.src='icons/image_381811.gif'
  		 instance.src=FPFile.url;
  		 saveBox('image',instance.id,FPFile.url);
  		})
  		
		}
  		
  		dragged = 0;
  		
		});
		

		$(".new_action").click(function (e) {	
			var type = $(this).data('type');
	 		$.ajax({
				url: 'php/add.php',
				type: 'POST',
				data: {
                type: type
				},
				success: function (data) {
                addBox(type, data);
                }				
			}); 
		
	 		
		});
		
		$(document).on("click", "#new_grid", function(e){
			$.ajax({
				url: 'php/add_grid.php',
				type: 'POST',
				data: {},
				success: function (data) {
	                $('#grid_overview').prepend('<div class="gridlevel"><hr><a class="gridinfo_data" data-gridid="'+data+'" href="#">Put new Gridname here</a><p class="gridinfo_data2">created just now<i style="color:white;cursor:pointer;font-size:20px;"  class="remove_grid icon-trash"></i></p></div>')
                }
			}); 
	});



		$(document).on("click", ".remove_grid", function(e){
			
			var id_gridlevel = $(this).parent().parent();
			
			var id = $(this).parent().siblings('a').data('gridid');
			
			$.ajax({
				url: 'php/remove_grid.php',
				type: 'POST',
				data: {
					id: id
				},
				success: function (data) {
	                id_gridlevel.remove();
                }
			}); 
	});

		$(document).on("blur", ".text, .quote", function(e){	
			var id = $(this).parent().data('id');	
			var content = $(this).text();
			var type = $(this).attr('class').split(' ')[0];
			saveBox(type,id,content);
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
				<a id="logo" href="#">Drag&amp;<br>Drool</a>
				<h2>Your files in 2D boxes.</h2>		
				<p class="grey"><b>Upload, Edit, Manage, Create</b></p>
			</div>
            
            
			<div id="panel2" class="left">
            
            <h1>Members panel</h1>
            
            <p>What about...</p>
            <a href="settings.php">Changing your Settings<i class="icon-cog"></i></a>
			<br>
            <a href="faq.html">Reading the <b>F-A-Q</b></a>
			<br>
            <a href="mailto:info@draganddrool.tk">Contacting us for help (via mail)</a>
			<br>
            <p>- or -</p>
            <a href="?logoff">Logging out<i class="icon-logout"></i></a>
            
            </div>
			
			
			
			
			
			<div id="panel3" class="left right">			
				
					<!-- Overview about grids -->
				<h1>Grid Overview</h1> | <span class="icon-plus" id="new_grid">Add a new Grid</span>
                    <div id="grid_overview">

				<?php                    
                   	$sql_gridoverview = mysqli_query($link,'SELECT * FROM grid WHERE ownerid='.$ownerid.' ORDER BY lastchange DESC');
                    
                    while($t = mysqli_fetch_array($sql_gridoverview)){
                    
                    echo ('
                 	<div class="gridlevel"><hr><a class="gridinfo_data" data-gridid="'.$t['id'].'" href="#">'.$t['name'].'</a><p class="gridinfo_data2">modified '.relative_time($t['lastchange']).'<i style="color:white;cursor:pointer;font-size:20px;" class="remove_grid icon-trash"></i></p></div>');	
                    }
				
					?>

					
				</div>
			</div>
            
            
            
            
		</div>
	</div> <!-- /login -->	

    <!-- The tab on top -->	
	<div class="tab">
<ul class="login">
	    	<li class="left begin_end">&nbsp;</li>
	        <li id="li_first"><i class="icon-user"></i> <?php 
	        
	        $sql_gridinfo = mysqli_query($link,'SELECT name FROM grid WHERE id='.$actualgrid);

	        
	        $t = db_result($sql_gridinfo);
	        
	        echo $_SESSION['usr'].'</li>
			<li> <b><span id="gridname" data-actualgrid="'.$actualgrid.'" id="'.$actualgrid.'" class="click editable" title="Click to edit the name of your grid">'.$t.'</span></b></li>';
			?>
			<li id="li_last">
			<input type="checkbox" id="toggle">
			<label id="menulabel" for="toggle"></label>		
			</li>
	    	<li class="right begin_end">&nbsp;</li>
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


	$sql_gridentries = mysqli_query($link,'SELECT * FROM gridentries WHERE gridtable='.$actualgrid);
	$sql_grid = mysqli_query($link,'SELECT * FROM grid WHERE grid.id='.$actualgrid);

	
	$t = mysqli_fetch_array($sql_grid);

// Show the latest change to the actual grid
    	
  	if ( !$sql_gridentries || !$sql_grid) 
       		die(mysqli_error());

 	echo '<div id="status"></div>
 	
 	<ul id="newactions">

<li id="newactions_li">

<p id="underline_addnewaction">  ADD</p>

<div id="newactions_div">
<div class="new_action"  data-type="image">
<i class="icon-picture"></i></div>
<div class="new_action"  data-type="text">
<i class="icon-pencil"></i></div>
<div class="new_action"  data-type="quote">
<i class="icon-quote"></i></div>
</div>
</li>
</ul>

	<div class="gridster contents">
	
    <li id="infobox">
<h3 id="infobox_title">You have no boxes.</h3><p id="nogrid_info">
<i class="icon-emo-thumbsup"></i>Add a box from <br> the bar at your left</p></li>
    
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
                        	  		$("#menulabel").click(function (e) {	
			meny.open();				
			});  		
                        
                </script>
</body>



</html>