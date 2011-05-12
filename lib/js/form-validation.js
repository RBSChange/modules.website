jQuery(document).ready(function() {
    for (var i = 0; i < form_Validation.length; i++) {
            var formInfo = form_Validation[i];
            var jRules = {};
            for (var fieldName in formInfo.rules) {
                    var jFieldRules = {};
                    var fieldRules = formInfo.rules[fieldName];
                    for (var validator in fieldRules) {
                            switch (validator) {
                                    case "blank":
                                            jFieldRules["required"] = fieldRules[validator] == "false";
                                            break;
                                    case "maxSize":
                                            jFieldRules["maxlength"] = parseInt(fieldRules[validator]);
                                            break;
                                    case "minSize":
                                            jFieldRules["minlength"] = parseInt(fieldRules[validator]);
                                            break;
                                    case "email":
                                            jFieldRules["email"] = true;
                                            break;
                            }
                    }
                    if (!jQuery.isEmptyObject(jFieldRules)) {
                            jRules[fieldName] = jFieldRules;
                    }
            }

            if (!jQuery.isEmptyObject(jRules)) {
                    var form = document.getElementById(formInfo.id);
                    jQuery(form).validate({rules: jRules});
            }
    }
});