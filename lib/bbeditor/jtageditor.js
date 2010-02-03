// ----------------------------------------------------------------------------
// j(Universal?)TagEditor, JQuery plugin
// v 1.0.01 beta
// ----------------------------------------------------------------------------
// Copyright (C) 2007 Jay Salvat
// http://www.jaysalvat.com/jquery/jtageditor/
// ----------------------------------------------------------------------------
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
// 
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
// 
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.
// ----------------------------------------------------------------------------
(function($) {
	$.fn.jTagEditor = function(settings) {	
		var field 		= this;
		options =  {	editorClassName:		"jTagEditor-editor",
						toolBarClassName:		"jTagEditor-toolBar",
						buttonClassName:		"jTagEditor-button",
						resizeHandleClassName:	"jTagEditor-resizeHandle",
						previewId:				"jTagEditor-previewWindow",
						previewClassName:		"jTagEditor-preview",
						previewCss:				"",
						previewParser:			"",
						previewVarName:			"data",
						insertOnEnter:			{openTag:"", 	closeTag:"",		callBack:"",		preventDefault:true},
						insertOnShiftEnter:		{openTag:"\n", 	closeTag:"",		callBack:"", 		preventDefault:false},
						insertOnCtrlEnter:		{openTag:"\n", 	closeTag:"",		callBack:"", 		preventDefault:false},
						insertOnTab:			{openTag:" ", 	closeTag:"",		callBack:"", 		preventDefault:false},
						tagMask:				"\[(.*?)\]",
						tagSet 				:[
	{label:"&modules.website.bbeditor.Bold;", accessKey:"b", closeTag:"[/b]", openTag:"[b]", className:"button"}, 
	{label:"&modules.website.bbeditor.Italic;", accessKey:"i", closeTag:"[/i]", openTag:"[i]", className:"button"}, 
	{label:"&modules.website.bbeditor.Underline;", accessKey:"u", closeTag:"[/u]", openTag:"[u]", className:"button"}, 
	{label:"&modules.website.bbeditor.Strike;", accessKey:"s", closeTag:"[/s]", openTag:"[s]", className:"button"}, 	
	{label:"&modules.website.bbeditor.Picture;", accessKey:"p", closeTag:"", openTag:"[img]@Url@[/img]", className:"button"}, 
	{label:"&modules.website.bbeditor.Link;", accessKey:"l", closeTag:"[/url]", openTag:"[url=@Url@]", className:"button"}, 
	{label:"&modules.website.bbeditor.Citation;", accessKey:"", closeTag:"[/quote]", openTag:"[quote=@Author@]", className:"button"},
	{label:"&modules.website.bbeditor.Code;", accessKey:"", closeTag:"[/code]", openTag:"[code]", className:"button"}
											]
					};
		var options = $.extend(options, settings);

		var openPos = 0; 
		var closePos = 0;
		var scrollPos = 0;
		var openTags = new Array();
		var keyCtrl = false;
		var keyShift = false;
		var keyAlt = false;
		
		return this.each(function()	{							
			var field 		= this;
			var $$			= $(field);
			var oId 		= $$.attr("id") || "";
			var oClassName	= $$.attr("class") || "";
			var oTitle		= $$.attr("title") || "";
			var oName		= $$.attr("name") || "";

			// wrap textfield in a container div
			var jField = $(field);
			var jFieldParentLeft = $(field.parentNode).position().left;
			jField.wrap("<div id=\"" + oId + "\" title=\"" + oTitle + "\" class=\"" + oClassName + "\"></div>");

			// add the toolbar et statusbar
			var toolbar = $("<div class=\"" + options.toolBarClassName + "\"><ul></ul></div>").insertBefore(field);
			$("<br style=\"clear:both; line-height: 2px; display: block; height: 2px;\"/><div class=\"textfield-label\">&nbsp;</div>").insertBefore(field);
			var toolbarcontent = $("ul:first", toolbar);
			// copy attributes
			jField.attr("class", options.editorClassName);
			
			// load tags from file if file is set
			if (typeof(options.tagSet) == "string")	{
				$.ajaxSetup( {async:false} ); 
				$.getJSON(options.tagSet, function(json)	{ 
					options.tagSet = json;  
				});
			}
			
			// fill the toolbarcontent with tag buttons
			$(options.tagSet).each(function(i)	{
				var button = this;
				$("<li class='" + button.className + " " + options.buttonClassName+(i+1) + "'><a href=\"\" accesskey=\"" + button.accessKey + "\" title=\"" + button.label + "\">" + button.label + "</a></li>")
				.click(function()	{ 
							tag(button); 
							return false;	
						})
				.appendTo(toolbarcontent);
			});
			
			var minHeight = jField.height();

			// add the resize handle after textfield
			var resizeHandle = $("<div class=\"" + options.resizeHandleClassName + "\"></div>")
									.insertAfter($(this))
									.bind("mousedown", function(evt) {
										var height = $(field).height();
										var y = evt.clientY;
										var mouseMove = function(evt) {
											var newHeight = Math.max(20, evt.clientY + height - y);
											if (newHeight >= minHeight) $(field).height(newHeight);
										};
										var mouseUp = function(evt) {
								 			$("html").unbind("mousemove", mouseMove).unbind("mouseup", mouseUp);
										};
										$("html").bind("mousemove", mouseMove).bind("mouseup", mouseUp);
								  });
			resizeHandle.width(jField.width());
			resizeHandle.css("left", (jField.position().left-jFieldParentLeft)+"px");
									
			// listen key events
			jField.bind("keydown", keyUpDown).bind("keyup", keyUpDown).bind("keypress", keyPress);						  

			// create attribute
			function attribute(string)	{
				if (string)	{
					return string.replace(/@(.*?)@/g, function (a) { return prompt(a.replace(/@/g, ""), "") });	
				} else {
					return "";
				}
			}

			function tag(button)	{	
				get();
				// if it's a function to fire
				if ($.isFunction(eval(button.callBack)))	{
					eval(button.callBack).call();
				// if it's tag to apply
				} else {
					openTag = attribute(button.openTag);
					closeTag = attribute(button.closeTag);
					if (selection != "")	{
						wrap(openTag, closeTag);	
					} else {
						if (!tagIsOpen(closeTag))	{
							if (closeTag)	{
								openTags.push(closeTag);
							}
							wrap(openTag, "");
						} else {
							openTags.pop() ;
							wrap("", closeTag);
						}	
					}
				}
			}

			// add tag
			function wrap(openTag, closeTag)	{
				string = openTag + selection + closeTag;
				// if Ctrl, Alt or Shift key pressed
				if (keyCtrl == true && keyShift == true)	{
					lines = selection.replace(new RegExp("\r?\n", "g"), "~�~"); // ie hack
					lines = lines.split("~�~");
					n = lines.length;
					for (i = n - 1; i >= 0; i--) {
						lines[i] = (lines[i] != "") ? openTag + lines[i] + closeTag : "";
					}
					string = lines.join("\r\n");
					start = openPos;
					end = openPos + string.length - n + 1;				
				} else if (keyCtrl == true)	{
					start = openPos + openTag.length;
					end = openPos + openTag.length + selection.length;
				} else if (keyShift == true)	{
					start = openPos;
					end = openPos + string.length;
				} else {
					start = openPos + openTag.length + selection.length + closeTag.length;	
					end = start;
				}
				// replace selection by the new string
				if (document.selection) {					
					newSelection = document.selection.createRange();
					newSelection.text = string;
				} else if (openPos || openPos == "0") {
					field.value = field.value.substring(0, openPos) + string + field.value.substring(closePos, field.value.length);
				} else {
					field.value += string;
				}
				set(start, end);
			}
			
			// set a selection
			function set(start, end)	{		
				if (field.createTextRange){
					range = field.createTextRange();
					range.collapse(true);
					range.moveStart("character", start);
					range.moveEnd("character", end - start);
					range.select();
				} else if (field.setSelectionRange ){
					field.setSelectionRange(start, end);
				}
				field.scrollTop = scrollPos;
				field.focus();	
			}
			
			// get the selection
			function get()	{
				field.focus();
				scrollPos = field.scrollTop;
				if (document.selection) {		
					selection = document.selection.createRange().text;
					if ($.browser.msie)	{ // ie
						range = field.createTextRange();
						range.moveToBookmark(document.selection.createRange().getBookmark());
						range.moveEnd("character", field.value.length);
						openPos = field.value.length - range.text.length;
						openPos = openPos - field.value.substr(0, openPos).split("\r\n").length; // ie hack	
						if (selection.length > 0) openPos -= 1;
						closePos = selection.length;
					} else { // opera
						openPos = field.selectionStart;
						closePos =  field.selectionEnd;
					}
				} else if (field.selectionStart || field.selectionStart == "0") { // gecko
					openPos = field.selectionStart;
					closePos = field.selectionEnd;
					selection = field.value.substring(openPos, closePos);
				} else {
					selection = "";
				}
				return selection;
			}

			// clear all tags
			function cleanAll()	{
				get();
				selection = selection.replace(new RegExp(options.tagMask, "g"), "");
				wrap("", "");
			}
		
			// close all open tags
			function closeAll() {
				var tagsToClose = "";
				n = openTags.length;
				for (i = n - 1; i >= 0; i--) {
					tagsToClose += openTags[i];
					openTags.pop();
				}
				get();
				wrap("", tagsToClose);
				field.focus();
			}
					
			// open preview window
			function preview()	{
				var html;
				if (options.previewParser != "")	{
					$.ajax({ 
						type: "POST", 
						async: false,
						url: options.previewParser, 
						data: options.previewVarName + "=" + $(field).val(), 
						success: function(data) { 
							html = data; 
						}
					});
				} else {
					html = "<html>";
					html+= "<head><title>Preview</title>";
					html+= "<link href=\"" + options.previewCss + "\" rel=\"stylesheet\" type=\"text/css\">";
					html+= "</head>";
					html+= "<body id=\"" + options.previewId + "\" class=\"" + options.previewClassName + "\">" + $(field).val() + "</body>";
					html+= "</html>";
				}
				win = window.open("", "preview", "scrollbars=yes, width=" + $(field).width() + ", height=" + $(field).height());
				win.document.open();
				win.document.write(html);
				win.document.close();
				win.focus();	
			}

			// check if tag is already open
			function tagIsOpen(tag)	{
				var n = openTags.length;
				for (var i = 0; i < n; i++) {
					if (openTags[i] == tag) {
						return true;
					}
				}
				return false;
			}
			
			// set keys pressed
			function keyUpDown(evt)	{ // safari doesn't fire event on shift and control key
				keyCtrl = evt.ctrlKey;
				keyShift = evt.shiftKey;
				keyAlt = evt.altKey;
			}

			function keyPress(evt)	{
				get();
				if (evt.keyCode == 13 || evt.keyCode == 10)	{
					if (keyCtrl == true)	{ 
						keyCtrl = false;
						tag(options.insertOnCtrlEnter);	
						return options.insertOnCtrlEnter.preventDefault;
					} else if (keyShift == true)	{
						keyShift = false;
						tag(options.insertOnShiftEnter);		
						return options.insertOnShiftEnter.preventDefault;
					}
					/*
					else 
					{
						tag(options.insertOnEnter);	
						return options.insertOnEnter.preventDefault;
					}
					*/
				}
				if (evt.keyCode == 9)	{
					tag(options.insertOnTab);	
					return options.insertOnTab.preventDefault;
				}	
			}
		});		
	};
})(jQuery);

$(document).ready(function() {$(".jTagEditor").jTagEditor();});
