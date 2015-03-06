/*var script = document.createElement('script');
script.src = '';
document.head.appendChild(script);

var scripta = document.createElement('script');
scripta.src = '/fabric.min.js';
document.head.appendChild(scripta);
*/

var canvas;
var element_select = -1;

$(window).unload( function () { alert("Bye now!"); } );

$( document ).ready(function() {

	canvas = this.__canvas = new fabric.Canvas('builder_canvas');
	canvas.selection = false;
	canvas.setWidth(200);
	canvas.setHeight(300);
	
	canvas.on('selection:cleared', function(){selectChange();});
	
	
	var text = new fabric.Text('printabu!!!', { left: 100, top: 100 });//Random text
	text.visible = false;
	canvas.add(text).setActiveObject(text);
	
	$("#builder_menu_font").change(function() {
		var font = $("#builder_menu_font :selected").val();
		var canvasArr = canvas.getObjects();
		canvasArr[element_select].setFontFamily(font);
		canvas.renderAll();
	});	
	
	
	$('#elem_text').change(function(){ 
		var canvasArr = canvas.getObjects();
		canvasArr[element_select].setText($('#elem_text').val());
		canvas.renderAll();	
	});
	
	$('#elem_position_left').change(function(){
		var canvasArr = canvas.getObjects();
		canvasArr[element_select].setLeft(Number($('#elem_position_left').val()));
		canvas.renderAll();		
	});

	$('#elem_position_top').change(function(){
		var canvasArr = canvas.getObjects();
		canvasArr[element_select].setTop(Number($('#elem_position_top').val()));
		canvas.renderAll();		
	});
	
	$('#elem_position_center').click(function(){
		var canvasArr = canvas.getObjects();
		canvasArr[element_select].center();
		canvas.renderAll();		
	});
	
	//$("#builder_mode_div").buttonset();

	
	$("#elem_opacity").slider({ 
		max: 100, 
		min: 0, 
		value: 100,
  		slide: function( event, ui ) {
			var canvasArr = canvas.getObjects();
			if (element_select > 0) //TODO add check
			{
				canvasArr[element_select].set({ opacity : (ui.value / 100) });				
				canvas.renderAll();	
			}			
		}
	});
	
	$( "#elem_font_color" ).slider({
		max: 255, 
		min: 0,
		value: 123,
  		slide: function( event, ui ) {
			var canvasArr = canvas.getObjects();
			if (element_select > 0) //TODO add check
			{
				canvasArr[element_select].set({ backgroundColor: 'rgb('+ui.value+',100,200)'});
				canvas.renderAll();	
			}
		}
	});
	
	/*
	$( "#elem_font_opacity" ).slider({
		max: 100, 
		min: 0,
		value: 0,
  		slide: function( event, ui ) {
			var canvasArr = canvas.getObjects();
			if (element_select > 0) //TODO add check
			{
				canvasArr[element_select].set({ backgroundOpacity: (ui.value / 10000)});
				canvas.renderAll();	
			}
		}
	});
	*/
	
	/*
	$('.elem_text_font').ikSelect({
		customClass: 'intro-select-osx'
	});
	*/
	
	$("#apear_back").on('click', function() {
	  	$("#apear_back").hide("slow");
		$("#apear_input").hide("slow");
	});
	
	$('#elem_position_center').click(function(){
		var canvasArr = canvas.getObjects();
		canvasArr[element_select].center();
		canvas.renderAll();		
	});
	
	$("#builder_menu_add_img").click(function(){ add_image();} );
	$("#builder_menu_add_text").click(function(){ add_text();} );
	$("#builder_menu_save").click(function(){ add_image();} );
	$("#builder_menu_send_to_sell").click(function(){ add_image();} );
	$("#builder_menu_to_cart").click(function(){ add_image();} );

	$("#builder_list").sortable({axis:'y'},{//, containment:'parent'
		'start': function (event, ui) {
			list_select(ui.item);
		},
		'update': function (event, ui) {
			var id_item = ui.item.attr('id'); //TODO найти куда он перемещается
			var id, st = -1, fin = -1;
			var temp = new fabric.Object(), to_replace = new fabric.Object();
			var canvasArr = canvas.getObjects();
			var insert_before = 0;
			
			//alert(ui.helper.attr('id'));
			alert(ui.item.attr('id'));
			$("#builder_list > li").each(function (i, elem) {        
				id = Number($(this).attr('id').replace(/\D+/g,""));
				if (id != i + 1)		
				{	
					$(this).attr('id', "builder_list_" + (i + 1));
					//if active - set elem_select		
					if (st == -1)
					{
						st = i + 1;
					}
					else if (insert_before = 0)
					{
						if (id < i + 1)
							insert_before = 1;
						else
							insert_before = -1;
					}
					
					fin = i + 1;
				}
			});		
			if (insert_before == 1)//TODO
			{
				canvasArr[fin].moveTo(st);			
			}
			else
			{
				canvasArr[st].moveTo(fin);			
			}

			element_select = $(".list_active").attr('id').replace(/\D+/g,"");
			list_select(element_select);
		  canvas.renderAll();
		}
	});
});


function selectChange(type = 0, id = -1)
{
	element_select = id;
	
	//$(".list_active").removeClass("list_active");!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	
	switch (type)
	{
		case 0:			
			$("[name=img]").hide("fast");
			$("[name=txt]").hide("fast");
			$("[name=img_txt]").hide("fast");
			$("[name=help]").show("fast");			
			break;
		case 'text':
			$("[name=lolek]").hide("fast");
			$("[name=txt]").show("fast");
			$("[name=img_txt]").show("fast");
			$("[name=help]").hide("fast");
			break;
		case 'image':
			$("[name=img]").show("fast");
			$("[name=txt]").hide("fast");
			$("[name=img_txt]").show("fast");
			$("[name=help]").hide("fast");
			break;
	}
}

function add_to_list(elem, type)
{
	//TODO check for type
	var name;
	var canvasArr = canvas.getObjects();
	var num = canvasArr.length;
	
	switch (type)
	{
		case 'text':
			name = elem.getText();
			break;
		case 'img':
			name = elem;
			break;
		default:
			alert(type);
			return;
	}
	
	$("#builder_list").append(
	"<li class = 'builder_list_"+type+"' id = 'builder_list_"+num +"' name = "+type+">"+
	"<input type='checkbox' checked />"+
	"<img src = '/img/tool/alert.png' style = 'visibility:hidden'>"
	+"<nobr class = 'builder_list_name'>" +name+"</nobr>"+ //TODO nobr == p
	" <a>×</a></li>");
	//selection in menu
	$("#builder_list_"+num+">input").change(function() {
		var p = $(this).parent();
		var id = Number(p.attr('id').replace(/\D+/g,""));
        if($(this).is(":checked")) {						
			canvasArr[id].visible = true;
        }
		else{
			canvasArr[id].visible = false;	
		}
		canvas.renderAll();
    });
	
	$("#builder_list_"+num).on('click', function(){
		list_select($(this));  
	});	
	$("#builder_list_"+num+">a").click(function() {
		var p = $(this).parent();
		var id = Number(p.attr('id').replace(/\D+/g,""));
        var id_el, type_el, ind = 0;
		canvasArr[id].remove();
		p.remove();
		//alert(id);
		$("#builder_list > li").each(function (i, elem) { //TODO li:gt("+id+") - not working
			id_el = Number($(elem).attr('id').replace(/\D+/g,""));
			type_el = ($(elem).attr('id').split("_")[2]);
			//alert(id_el);
			if (id_el >= id)
			{
				ind++;
				$(this).attr('id', "builder_list_"+type_el+"_"+(id + ind -1));
			}
			
		});
    });
	
}

function add_image()
{
	$("#apear_back").show("slow");
	$("#apear_input").show("slow");
}

function include_image(img_src)
{
	//var canvasArr = canvas.getObjects();
	//var num = canvasArr.length;
	
	fabric.Image.fromURL(img_src, function(oImg) {
  			canvas.add(oImg);
		});
	
	add_to_list(img_src, 'img');
	
	canvas.renderAll();
}

function add_text()
{	
	var text = new fabric.Text('printabu!!!', { left:100, top: 100 },{
  fontFamily: 'Comic Sans'
});//Random text
	text.setFontFamily('Comic Sans');
	add_to_list(text, 'text');
	canvas.add(text).setActiveObject(text);
	
	
	text.on('selected', function() {
			var canvasArr = canvas.getObjects();
			num = canvasArr.indexOf(text);
			if (element_select != num)
				list_select(num);
		});
	text.on('moving', function() {
			var canvasArr = canvas.getObjects();
			num = canvasArr.indexOf(text);
			$("#elem_position_left").val(text.getLeft());
			$("#elem_position_top").val(text.getTop());
		});		
	
	canvas.renderAll();
}

function list_select(item)
{
	var id;
	if ($.isNumeric(item))
	{
		id = item;
		item = $("#builder_list_"+id);
	}
	else
	{
		id = Number(item.attr('id').replace(/\D+/g,""));	
	}
	
	
	$(".list_active").removeClass("list_active");
	item.addClass("list_active");
	element_select = id;
	
	var canvasArr = canvas.getObjects();
	
	selectChange(canvasArr[id].get('type'), id);
	
	canvas.discardActiveObject();
	canvas.setActiveObject(canvasArr[id]);
	
}

function image_save()
{
	//canvas.getAsFile("test.jpg", "image/jpeg");
	//canvas.toDataURL();
	//alert(canvas.toDataURL());
	//document.body.appendChild(newImg);
}