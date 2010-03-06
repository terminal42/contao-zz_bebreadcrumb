
window.addEvent('domready', function() {
	$('header').getElement('div').inject($('header'), 'top').setStyles({
		'float': 'right',
		'background-color': '#FFFFFF'
	});
	
	$('mod_backendbreadcrumb').inject($('header'), 'bottom').setStyle('display', 'block');
});