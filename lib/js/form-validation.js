(function($)
{
	// jquery-validation extension: make some methods "preemtive"
	// WARN: this was built using jquery-validation-1.8.0 code and may not work
	// with an other version
	$.validator.preemptivemethods = {
		"maxlength" : true
	};

	$.extend($.fn, {
		rulesPreemtive : function()
		{
			var data = this.rules();
			for ( var validator in data)
			{
				if (!(validator in $.validator.preemptivemethods))
				{
					delete data[validator];
				}
			}
			return data;
		}
	});

	$.extend($.validator.prototype, {
		elementPreemtive : function(element)
		{
			element = this.clean(element);
			this.prepareElement(element);
			this.currentElements = $(element);
			var result = this.checkPreemtive(element);
			if (result)
			{
				delete this.invalid[element.name];
			}
			else
			{
				this.invalid[element.name] = true;
			}
			if (!this.numberOfInvalids())
			{
				// Hide error containers on last error
				this.toHide = this.toHide.add(this.containers);
			}
			this.showErrors();
			return result;
		},

		checkPreemtive : function(element)
		{
			element = this.clean(element);

			// if radio/checkbox, validate first element in group instead
			if (this.checkable(element))
			{
				element = this.findByName(element.name).not(this.settings.ignore)[0];
			}

			var rules = $(element).rulesPreemtive();
			var dependencyMismatch = false;
			for ( var method in rules)
			{
				var rule = {
					method : method,
					parameters : rules[method]
				};
				try
				{
					var result = $.validator.methods[method].call(this, element.value.replace(
							/\r/g, ""), element, rule.parameters);

					// if a method indicates that the field is optional and
					// therefore valid,
					// don't mark it as valid when there are no other rules
					if (result == "dependency-mismatch")
					{
						dependencyMismatch = true;
						continue;
					}
					dependencyMismatch = false;

					if (result == "pending")
					{
						this.toHide = this.toHide.not(this.errorsFor(element));
						return;
					}

					if (!result)
					{
						this.formatAndAdd(element, rule);
						return false;
					}
				}
				catch (e)
				{
					this.settings.debug
							&& window.console
							&& console.log("exception occured when checking element " + element.id
									+ ", check the '" + rule.method + "' method", e);
					throw e;
				}
			}
			if (dependencyMismatch) return;
			if (this.objectLength(rules)) this.successList.push(element);
			return true;
		}
	});

	// Bind validate on forms that were registered converting RBS Change rules
	$(document).ready(function()
	{
		for ( var i = 0; i < form_Validation.length; i++)
		{
			var formInfo = form_Validation[i];
			var jRules = {};
			for ( var fieldName in formInfo.rules)
			{
				var jFieldRules = {};
				var fieldRules = formInfo.rules[fieldName];
				for ( var validator in fieldRules)
				{
					switch (validator)
					{
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
				if (!$.isEmptyObject(jFieldRules))
				{
					jRules[fieldName] = jFieldRules;
				}
			}

			if (!$.isEmptyObject(jRules))
			{
				var form = document.getElementById(formInfo.id);
				$(form).validate({
					errorClass: 'errors form-validation',
					rules: jRules,
					onkeyup: function(element)
					{
						if (element.name in this.submitted || element == this.lastElement)
						{
							this.element(element);
						}
						else
						{
							this.elementPreemtive(element);
						}
					},
					ignoreTitle: true,
					errorPlacement: function(error, element)
					{
						var parents = element.parents("li[id]");
						if (parents.length > 0)
						{
							var li = parents[0];
							var helpTexts = jQuery('#' + li.getAttribute('id') + ' > p.help-text');
							if (helpTexts.length > 0)
							{
								error.insertBefore(helpTexts[0]);
							}
							else
							{
								error.appendTo(li);
							}
						}
						else
						{
							error.appendTo(element.parent());
						}
					}
				});
			}
		}
	});
})(jQuery);