function getController()
{
	return parent.document.getElementById('wcontroller');
}

function getModule()
{
	return parent.document.getElementById('wmodule');
}

function getCurrentPageData()
{		    
    return pageData;
}

function setCurrentPageData(name, value)
{		    
    pageData[name] = value;
}

function setPageLabel()
{
    
}

function backToContentEditing()
{
	document.getElementById('mainEditor').backToContentEditing();
}

function CDATA(string){
	return new XML("<!\[CDATA\[" + string + "\]\]>");
}