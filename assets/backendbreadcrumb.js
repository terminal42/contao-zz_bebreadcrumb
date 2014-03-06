window.addEvent('domready', function() {
    var header = document.getElementById('header');
    if (header) {
        header.getElement('div').inject(header, 'top').setStyles({
            'float': 'right',
            'background-color': '#FFFFFF'
        });

        document.getElement('.mod_backendbreadcrumb').inject(header, 'bottom').setStyle('display', 'block');
    }
});