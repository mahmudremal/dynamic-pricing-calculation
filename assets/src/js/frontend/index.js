/**
 * Frontend Script.
 * 
 * @package TeddyBearCustomizeAddon
 */

// import Swal from "sweetalert2";
// // import Awesomplete from "awesomplete";
// import PROMPTS from "./prompts";
// import Toastify from 'toastify-js';
// import voiceRecord from "./voicerecord";
// import popupCart from "./popupcart";

// import flatpickr from "flatpickr";
// import KeenSlider from 'keen-slider';
import tippy from 'tippy.js';
// // import Splide from '@splidejs/splide';

// // import icons from "./icons";
// import { keenSliderNavigation } from "./slider";
// import Exim from "./exim"
// import Tidio_Chat from "./tidio";
// import Twig from 'twig';

( function ( $ ) {
	class FutureWordPress_Frontend {
		constructor() {
			this.ajaxUrl = fwpSiteConfig?.ajaxUrl??'';
			this.ajaxNonce = fwpSiteConfig?.ajax_nonce??'';
			var i18n = fwpSiteConfig?.i18n??{};
			this.i18n = {confirming: 'Confirming', ...i18n};
			this.config = fwpSiteConfig;this.noToast = true;
			// this.KeenSlider = KeenSlider;this.tippy = tippy;
			this.setup_hooks();
		}
		setup_hooks() {
			const thisClass = this;
			window.thisClass = this;

			this.local_store_n_calc();
		}
		local_store_n_calc() {
			document.querySelectorAll('.dpc__table:not([data-handled])').forEach(table => {
				table.dataset.handled = true;
				const tableID = table.dataset?.tableId;
				if (tableID) {
					const quantityInputs = table.querySelectorAll('.dpc__input[data-index]');
					const quantityStored = localStorage.getItem('dpc-' + tableID);
					const quantityRow = (quantityStored)?Object.values(JSON.parse(quantityStored)):false;

					quantityInputs.forEach(input => {
						// Sync Events
						if (quantityStored && quantityRow) {
							const InputRow = quantityRow.find(row => row?.index && row.index == input.dataset.index);
							if (InputRow && InputRow?.qty && InputRow.qty != '') {
								input.value = InputRow.qty;
								// Calculation
								const calculations = (
									input.value * parseFloat(input.dataset.price)
								);
								// Priting output
								document.querySelectorAll(`.dpc__cost[data-index="${input.dataset.index}"]`).forEach(elem => elem.innerHTML = calculations.toFixed(2));
							}
						}
						
						// Updating Events
						['input', 'change'].forEach(hook => {
							input.addEventListener(hook, (event) => {
								// Calculation functions
								const calculations = (
									event.target.value * parseFloat(input.dataset.price)
								);
								// Priting output
								document.querySelectorAll(`.dpc__cost[data-index="${input.dataset.index}"]`).forEach(elem => elem.innerHTML = calculations.toFixed(2));
								
								// Storing Functions
								if (hook == 'change') {
									const toStore = [...quantityInputs].map(elem => {return {name: elem.name, index: elem.dataset.index, qty: elem.value};});
									if (toStore) {
										window.localStorage.setItem("dpc-" + tableID, JSON.stringify(toStore));
									}
								}
								
							});
						});
						
					});
					
				}
			});
		}
		
	}
	new FutureWordPress_Frontend();
} )( jQuery );
