// picked from http://www.rubynotes.net/en/software-development/programming-languages/javascript/item/71-disable-copy/paste-in-text-field.html?tmpl=component&print=1
function website_PreventCopyPaste(txtID) {
	var txt = jQuery("#" + txtID);
	var cancelFunc = function(e) {
		return false;
	};
	txt.bind('contextmenu', cancelFunc);
	if (!jQuery.browser.opera) {
		txt.bind('cut copy paste', cancelFunc);
	} else {
		if (txt.size() > 0) {
			txt.bind('input', function(e) {
				var origTxt = txt.get(0);
				if (!origTxt.prev_value) {
					origTxt.prev_value = '';
				}
				if (txt.val().length - origTxt.prev_value.length > 1) {
					txt.val(origTxt.prev_value);
				} else {
					origTxt.prev_value = txt.val();
				}
			});
		}
	}
}