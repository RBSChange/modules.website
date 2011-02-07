if (!pageHandler) { var pageHandler = {}; }
pageHandler.getId = function() { return this.id; };
pageHandler.getLang = function() { return this.lang; };

jQuery(document).ready(function(){
	jQuery('.nojs').hide();
});

function accessiblePopup(elt, width, height)
{
    if (!width) width = screen.width;
    if (!height) height = screen.height;
    var left = Math.floor((screen.width - width) / 2);
    var top = Math.floor((screen.height - height) / 2);
    var popupWindow = window.open(
        elt.getAttribute('href'),
        "popup",
        "top=" + top + ", left=" + left + ", width=" + width + ", height=" + height + ", location=yes, menubar=yes, toolbar=yes, resizable=yes, scrollbars=yes"
    );

    popupWindow.focus();
    return false;
}

function accessiblePrint(elt)
{
    if (window.print)
    {
        window.print();
    }
    else if (elt)
    {
        window.location.href = elt.getAttribute('href');
    }

    if (elt)
    {
        return false;
    }
}

function accessibleAddToFavorite(elt)
{
    if (window.sidebar && window.sidebar.addPanel)
    {
        window.sidebar.addPanel(document.title, document.location.href, '');
    }
    else if (document.all && window.external)
    {
        window.external.AddFavorite(document.location.href, document.title);
    }
    else if (elt)
    {
        window.location.href = elt.getAttribute('href');
    }
    if (elt)
    {
        return false;
    }
}

function getFieldValueByName (aFieldName)
{
    var field = jQuery("[name='formParam[" + aFieldName + "]']");
    if (field && field.length)
    {
        return field[0].value;
    }
    return '';
}

function getRenderBenchCookie(name)
{
	var cookieValue = null;
	if (document.cookie && document.cookie != '') {
	    var cookies = document.cookie.split(';');
	    for (var i = 0; i < cookies.length; i++) {
	        var cookie = jQuery.trim(cookies[i]);
	        if (cookie.substring(0, name.length + 1) == (name + '=')) {
	            cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
	            break;
	        }
	    }
	}
	return cookieValue;
}

function setRenderBenchCookie(name, value, options)
{
	options = options || {};
    if (value === null) 
    {
        value = '';
        options = {expires: -1};
    }
    
    var expires = '';
    if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
        var date;
        if (typeof options.expires == 'number') {
            date = new Date();
            date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
        } else {
            date = options.expires;
        }
        expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
    }
    var path = options.path ? '; path=' + (options.path) : '';
    var domain = options.domain ? '; domain=' + (options.domain) : '';
    var secure = options.secure ? '; secure' : '';
    document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
}

function renderBenchTimes(benchtimes)
{
	var data = [];
	var total = benchtimes.renderTOTAL;
	var btc = document.createElement('div');
	var divstate = getRenderBenchCookie('renderBenchTimes');
	divstate = divstate || 'block';
	data.push('<div class="title" onclick="renderBenchTimesSwitch()">hide / show Bench Infos</div><ul id="benchtimescontent" style="display: '+divstate+';">');
	var blocksTime = benchtimes['blocks'];
	for (var name in blocksTime)
	{
		var block  = document.getElementById(name);
		if (block === null)
		{
			alert(name);
		}
		var classes = block.className.split(' ');
		data.push('<li class="block" onclick="renderBenchTimesHiglight(\''+ name + '\')">'  + getBenchTime(total, blocksTime[name].rendering) + ', ' + name + ', ' + classes[0] + '</li>');
	}
	for (var name in benchtimes)
	{
		if (name != 'blocks')
		{
			data.push('<li>' + getBenchTime(total, benchtimes[name]) + ', ' + name + '</li>');
		}
	}
	data.push('</ul>');
	btc.innerHTML = data.join("\n");
	document.getElementsByTagName('body')[0].appendChild(btc);
	btc.className = 'benchtimes';
}

function renderBenchTimesSwitch()
{
	var ul  = document.getElementById('benchtimescontent');
	var newStyle = 'block';
	if (ul.style.display === newStyle)
	{
		newStyle = 'none';
	}
	
	ul.style.display = newStyle;
	setRenderBenchCookie('renderBenchTimes', newStyle, {expires:10});
}
function getBenchTime(total, duration)
{
	var data = [];
	data.push('<strong>' + (Math.round((1 - (total - duration) / total) * 100)) + '%</strong>');
	data.push((Math.round(duration * 10000) / 10) + 'ms');	
	return data.join(", ");
}

function renderBenchTimesHiglight(id)
{	
	var div  = document.getElementById(id);
	if (div.style.borderColor.substring(0,3) === 'red')
	{
		div.style.borderColor = '';
		div.style.borderWidth = '0';
		div.style.borderStyle = 'none';
	}
	else
	{
		div.style.borderColor = 'red';
		div.style.borderWidth = '1px';
		div.style.borderStyle = 'solid';
	}
}

//Ajax API : http://api.jquery.com/jQuery.ajax/
//Require Css: modules.website.jquery-ui.[smoothness|lightness|south-street]
//Require Js: modules.website.lib.js.jquery-ui-dialog
//openPopIn(13441, {testParam{cmpref:12346,p2:"abcd"}}, {title:"Essai", modal:true, width:800})
// ou avec surcharge du callback
// openPopIn(13441, {testParam{cmpref:12346,p2:"abcd"}}, {title:"Essai", modal:true, width:800}, function(data){alert(data)})
//http://jqueryui.com/demos/dialog/
function openPopIn(pageId, pageParams, dialogParams, successCallback)
{
	var popInContainer = document.getElementById('popInContainer');
	if (popInContainer == null)
	{
		popInContainer = document.createElement('div');
		popInContainer.setAttribute('id', 'popInContainer');
		popInContainer.setAttribute('style', 'display:none');
		document.getElementsByTagName('body')[0].appendChild(popInContainer);
	}
	
	jQuery.ajax({
		  url: '/index.php?module=website&action=PopIn&pageref=' + pageId,
		  type: 'POST',
		  data: pageParams,
		  success: successCallback ? successCallback : function(data) {
			  jQuery(popInContainer).html(data);
			  jQuery(popInContainer).dialog(dialogParams);
		  }
		});
}

function refreshBlock(moduleName, blockId, blockParameters, section)
{
	blockParameters.lang = pageHandler.lang;
	blockParameters.cmpref = pageHandler.id;
	blockParameters.blockId = blockId;
	blockParameters.blockModule = moduleName;
	
	var blockNodeId = blockId;
	if (section)
	{
		blockParameters.section = section;
		blockNodeId = blockId + section;
	}
	else
	{
		section = 'All';
		blockParameters.section = section;
	}	
	
	jQuery.ajax({
		  url: '/index.php?module=website&action=BlockAsynchContent',
		  type: 'POST',
		  data: blockParameters,
		  success: function(data) {
			  jQuery('#' + blockNodeId).html(data[section]);		  
		  }
		});	
}
