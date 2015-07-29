# QPhoneTextBox and jquery.phonetextbox.js

## QPhoneTextBox
QPhoneTextBox is a simple extension of the QTextBox control that 
allows you to easily validate and format the phone numbers that users provide. 
That format is (xxx) xxx-xxxx. Validation and re-formatting happen when
the user tabs out or somehow else leaves the QPhoneTextBox, giving focus
to another control.

If the user enters something different - for example, a phone number without 
spaces like 1112223344, it will be reformatted to (111) 222-3344 if possible. 
Same with inputs like 111-222-3344, etc.

QPhoneTextBox allows you to specify an optional default area code - in those cases
when you can predict what the user's area code most likely is. In the example below,
we're using 650 as the default area code. That said, the user can freely delete
that default and put in any area code they want.
		
Note that the control currently supports only North American phone formats - 
it can be easily extended to support other formats as well. If you do end up
writing an extension of this control, please do share it.

This control is now installable by Composer. To install, add the following to the corresponding sections of your composer.json root file:
```
	"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/qcubed/plugin_phonetextbox"
        }
    ],
```    
and
```
	"require": {
		"qcubed/plugin_phonetextbox": "dev-master"
	},

```


## jquery.phonetextbox.js
The jQuery code this is based on is also a standalone jQuery plugin. The plugin requires jQuery UI 1.9 or greater, as it uses the jQuery widget factory. See the comments there for usage.