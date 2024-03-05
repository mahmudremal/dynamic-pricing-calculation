
(function ($) {
	class FWPListivoBackendJS {
		constructor() {
			this.ajaxUrl = fwpSiteConfig?.ajaxUrl??'';
			this.ajaxNonce = fwpSiteConfig?.ajax_nonce??'';
			var i18n = fwpSiteConfig?.i18n??{};this.cssImported = false;
			this.config = fwpSiteConfig?.config??{};
			// this.WaveSurfer = WaveSurfer;this.tippy = tippy;
			this.i18n = {submit: 'Submit', ...i18n};
			this.setup_hooks();
		}
		setup_hooks() {
			const thisClass = this;
			window.thisClass = this;
			// 
			this.dpc__tfoot_repeater();
		}
		dpc__tfoot_repeater() {
			document.querySelectorAll('.dpc__tfoot_repeater').forEach(button => {
				const tbody = button.parentElement.parentElement.parentElement.previousElementSibling;
				const config = JSON.parse(button.dataset.config);
				const units = JSON.parse(button.dataset.units);
				// 
				button.addEventListener('click', (event) => {
					event.preventDefault();
					const index = tbody.lastElementChild.dataset.index;
					// 
					const trow = document.createElement('tr');
					trow.classList.add('dpc__trow');
					trow.dataset.index = index;
					Object.values(config).forEach(column => {
						tdoc = document.createElement('td');
						tdoc.classList.add('dpc__td');
						tdoc.dataset.column = column;
						// 
						switch (column) {
							case 'unit':
								const select = document.createElement('select');
								select.classList.add('dpc__select');
								select.name = `prices[${index}][${column}]`;

								Object.keys(units).forEach(unit => {
									const option = document.createElement('option');
									option.value = unit;option.innerHTML = units[unit];
									select.appendChild(option);
								});
								tdoc.appendChild(select);
								break;
							default:
								const input = document.createElement('input');
								input.classList.add('dpc__input');
								input.name = `prices[${index}][${column}]`;
								input.type = ['title'].includes(column)?'text':'number';
								tdoc.appendChild(input);
								break;
						}
						// 
						trow.appendChild(tdoc);
					});
					// 
					tbody.appendChild(trow);
				});
			})
		}
	}

	new FWPListivoBackendJS();
})(jQuery);
