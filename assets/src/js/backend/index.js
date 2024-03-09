
(function ($) {
	class FWPListivoBackendJS {
		constructor() {
			this.ajaxUrl = fwpSiteConfig?.ajaxUrl??'';
			this.ajaxNonce = fwpSiteConfig?.ajax_nonce??'';
			var i18n = fwpSiteConfig?.i18n??{};
			this.config = fwpSiteConfig;this.cssImported = false;
			// this.WaveSurfer = WaveSurfer;this.tippy = tippy;
			this.i18n = {submit: 'Submit', ...i18n};
			this.setup_hooks();
		}
		setup_hooks() {
			const thisClass = this;
			window.thisClass = this;
			// 
			this.init_event();
			this.dpc__tfoot_repeater();
		}
		dpc__tfoot_repeater() {
			const thisClass = this;
			document.querySelectorAll('.dpc__tfoot_repeater').forEach(button => {
				const tbody = button.parentElement.parentElement.parentElement.previousElementSibling;
				const currencies = JSON.parse(button.dataset.currencies);
				const columns = JSON.parse(button.dataset.columns);
				const units = JSON.parse(button.dataset.units);
				// 
				button.addEventListener('click', (event) => {
					event.preventDefault();
					const index = (
						parseInt(((tbody?.lastElementChild)?.dataset)?.index??0) + 1
					);
					// 
					const trow = document.createElement('tr');
					trow.classList.add('dpc__trow');
					trow.dataset.index = index;
					Object.values(columns).forEach(column => {
						tdoc = document.createElement('td');
						tdoc.classList.add('dpc__td');
						tdoc.dataset.column = column;
						// 
						switch (column) {
							case 'unit':
							case 'currency':
								const select = document.createElement('select');
								select.classList.add('dpc__select');
								select.name = `prices[${index}][${column}]`;

								Object.keys((column == 'unit')?units:currencies).forEach(unit => {
									const option = document.createElement('option');
									option.value = unit;option.innerHTML = (column == 'unit')?units[unit]:currencies[unit];
									select.appendChild(option);
								});
								tdoc.appendChild(select);
								break;
							case 'trash':
								const trash = document.createElement('span');
								trash.classList.add('dashicons', 'dashicons-trash', 'dpc__trash');
								trash.setAttribute('area-hidden', true);
								trash.dataset.content = 'Remove this row';
								tdoc.appendChild(trash);
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
					// 
					setTimeout(() => {thisClass.init_event();}, 500);
				});
			});
		}
		init_event() {
			document.querySelectorAll('.dpc__trash:not([data-handled])').forEach(trash => {
				trash.dataset.handled = true;
				trash.addEventListener('click', (event) => {
					event.preventDefault();
					if (confirm('Are you sure?')) {
						trash.parentElement.parentElement.remove();
					}
				});
			});
		}
	}

	new FWPListivoBackendJS();
})(jQuery);
