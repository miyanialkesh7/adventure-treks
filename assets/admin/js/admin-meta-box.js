/**
 * JavaScript for YatraVanta Trek Booking Manager Meta Box Admin Interface
 */

document.addEventListener('DOMContentLoaded', function() {

	// ==========================================
	// 0. Global Toast & Confirm Notifications
	// ==========================================
	window.yatravanta_admin_toast = function(message, type = 'error') {
		let toast = document.getElementById('yatravanta_admin_toast');
		if (!toast) {
			toast = document.createElement('div');
			toast.id = 'yatravanta_admin_toast';
			document.body.appendChild(toast);
		}
		toast.className = 'yatravanta-toast show ' + type;
		toast.textContent = '';
		const toastIcon = document.createElement('span');
		toastIcon.className = 'dashicons dashicons-warning';
		toast.appendChild(toastIcon);
		toast.appendChild(document.createTextNode(' ' + message));
		
		setTimeout(() => {
			toast.className = toast.className.replace('show', '');
		}, 3500);
	};

	window.yatravanta_admin_confirm = function(message, callback) {
		let overlay = document.getElementById('yatravanta_admin_confirm_overlay');
		if (!overlay) {
			overlay = document.createElement('div');
			overlay.id = 'yatravanta_admin_confirm_overlay';
			overlay.innerHTML = `
				<div class="yatravanta-admin-confirm-box">
					<div class="yatravanta-admin-confirm-icon"><span class="dashicons dashicons-warning"></span></div>
					<div class="yatravanta-admin-confirm-msg"></div>
					<div class="yatravanta-admin-confirm-actions">
						<button type="button" class="button yatravanta-admin-confirm-cancel">Cancel</button>
						<button type="button" class="button button-primary yatravanta-admin-confirm-ok">OK</button>
					</div>
				</div>
			`;
			document.body.appendChild(overlay);

			overlay.querySelector('.yatravanta-admin-confirm-cancel').addEventListener('click', function() {
				overlay.classList.remove('show');
			});
		}

		overlay.querySelector('.yatravanta-admin-confirm-msg').innerText = message;
		
		// Remove old event listener from OK button by cloning it
		let oldOk = overlay.querySelector('.yatravanta-admin-confirm-ok');
		let newOk = oldOk.cloneNode(true);
		oldOk.parentNode.replaceChild(newOk, oldOk);

		newOk.addEventListener('click', function() {
			overlay.classList.remove('show');
			callback();
		});

		overlay.classList.add('show');
	};

	// ==========================================
	// 1. Tab switching logic
	// ==========================================
	const tabLinks = document.querySelectorAll('.yatravanta-meta-tabs-nav a');
	const tabPanels = document.querySelectorAll('.yatravanta-meta-tab-panel');

	if (tabLinks.length > 0) {
		tabLinks.forEach(function(link) {
			link.addEventListener('click', function(e) {
				e.preventDefault();

				// Remove active class from all tabs & panels
				tabLinks.forEach(l => l.classList.remove('nav-tab-active'));
				tabPanels.forEach(p => p.classList.remove('active'));

				// Add active class to clicked tab and corresponding panel
				this.classList.add('nav-tab-active');
				const activePanelId = this.getAttribute('href');
				const activePanel = document.querySelector(activePanelId);
				if (activePanel) {
					activePanel.classList.add('active');
				}
			});
		});
	}

	// ==========================================
	// 2. FAQ Repeater Logic
	// ==========================================
	const faqList = document.getElementById('yatravanta_faq_repeater_list');
	const addFaqBtn = document.getElementById('yatravanta_add_faq_row_btn');

	// An FAQ needs both a question and an answer. Flag half-filled rows and, in the block
	// editor, lock saving until they are completed (the server drops incomplete rows anyway).
	function validateFaqRows() {
		if (!faqList) { return; }
		let incomplete = 0;
		faqList.querySelectorAll('.yatravanta-faq-repeater-row').forEach(function(row) {
			const q = row.querySelector('input[type="text"]');
			const a = row.querySelector('textarea');
			const qFilled = !!(q && q.value.trim());
			const aFilled = !!(a && a.value.trim());
			const bad = (qFilled !== aFilled);
			row.classList.toggle('yatravanta-faq-incomplete', bad);
			let msg = row.querySelector('.yatravanta-faq-row-error');
			if (bad) {
				incomplete++;
				if (!msg) {
					msg = document.createElement('p');
					msg.className = 'yatravanta-faq-row-error';
					row.querySelector('.yatravanta-faq-row-fields').appendChild(msg);
				}
				msg.textContent = qFilled ? 'Please add an answer for this question.' : 'Please add a question for this answer.';
			} else if (msg) {
				msg.remove();
			}
		});
		if (window.wp && wp.data && wp.data.dispatch) {
			const editor = wp.data.dispatch('core/editor');
			if (editor && editor.lockPostSaving) {
				if (incomplete > 0) { editor.lockPostSaving('yatravanta-faq-incomplete'); } else { editor.unlockPostSaving('yatravanta-faq-incomplete'); }
			}
		}
	}

	if (faqList) {
		faqList.addEventListener('input', validateFaqRows);
		validateFaqRows();
	}

	if (addFaqBtn && faqList) {
		addFaqBtn.addEventListener('click', function(e) {
			e.preventDefault();

			// Generate a unique index based on timestamp
			const index = Date.now();

			// Construct row HTML
			const rowHTML = `
				<div class="yatravanta-faq-repeater-row" data-index="${index}">
					<span class="yatravanta-drag-handle">☰</span>
					<div class="yatravanta-faq-row-fields">
						<input type="text" name="yatravanta_faq[${index}][q]" placeholder="Question" aria-label="FAQ question" class="large-text" />
						<textarea name="yatravanta_faq[${index}][a]" rows="3" placeholder="Answer" aria-label="FAQ answer" class="large-text"></textarea>
					</div>
					<button type="button" class="button yatravanta-remove-faq-row-btn">Remove</button>
				</div>
			`;

			// Append new row
			faqList.insertAdjacentHTML('beforeend', rowHTML);
			validateFaqRows();
		});

		// Delegate delete event for dynamically created rows
		faqList.addEventListener('click', function(e) {
			if (e.target && e.target.classList.contains('yatravanta-remove-faq-row-btn')) {
				e.preventDefault();
				const row = e.target.closest('.yatravanta-faq-repeater-row');
				if (row) {
					row.remove();
					validateFaqRows();
				}
			}
		});
	}

	// ==========================================
	// 3. WordPress Media Library Gallery Selector
	// ==========================================
	const selectGalleryBtn = document.getElementById('yatravanta_select_gallery_btn');
	const galleryIdsInput = document.getElementById('yatravanta_gallery_ids');
	const galleryThumbsWrapper = document.getElementById('yatravanta_gallery_thumbs_wrapper');

	if (selectGalleryBtn && galleryIdsInput && galleryThumbsWrapper) {
		let galleryFrame;

		selectGalleryBtn.addEventListener('click', function(e) {
			e.preventDefault();

			// If the media frame already exists, reopen it.
			if (galleryFrame) {
				galleryFrame.open();
				return;
			}

			// Create the media frame.
			galleryFrame = wp.media({
				title: 'Select Gallery Images',
				button: {
					text: 'Use these images'
				},
				multiple: 'add' // plain clicks toggle images; no Ctrl/Shift needed.
			});

			// Start with the images already in the gallery selected, so choosing more adds to
			// them. Without this the frame opens empty and "select" would replace the whole gallery.
			galleryFrame.on('open', function() {
				const selection = galleryFrame.state().get('selection');
				selection.reset();
				galleryIdsInput.value.split(',').filter(Boolean).forEach(function(id) {
					const attachment = wp.media.attachment(id);
					attachment.fetch();
					selection.add(attachment);
				});
			});

			// When images are selected in the media frame...
			galleryFrame.on('select', function() {
				const selection = galleryFrame.state().get('selection');
				const ids = [];
				galleryThumbsWrapper.innerHTML = ''; // Reset markup

				selection.map(function(attachment) {
					attachment = attachment.toJSON();
					ids.push(attachment.id);

					// Render thumbnail
					const thumbnail = (attachment.sizes && attachment.sizes.thumbnail) ? attachment.sizes.thumbnail.url : attachment.url;
					const thumbHTML = `
						<div class="yatravanta-gallery-thumb-item" data-id="${attachment.id}">
							<img src="${thumbnail}" alt="" />
							<a href="#" class="yatravanta-gallery-remove-btn" title="Remove">&times;</a>
						</div>
					`;
					galleryThumbsWrapper.insertAdjacentHTML('beforeend', thumbHTML);
				});

				// Set hidden input value
				galleryIdsInput.value = ids.join(',');
			});

			// Finally, open the modal.
			galleryFrame.open();
		});

		// Remove image from gallery preview and update the hidden IDs field
		galleryThumbsWrapper.addEventListener('click', function(e) {
			if (e.target && e.target.classList.contains('yatravanta-gallery-remove-btn')) {
				e.preventDefault();
				const thumbItem = e.target.closest('.yatravanta-gallery-thumb-item');
				if (thumbItem) {
					const idToRemove = thumbItem.getAttribute('data-id');
					thumbItem.remove();

					// Update input value
					let currentIds = galleryIdsInput.value.split(',');
					currentIds = currentIds.filter(id => id !== idToRemove);
					galleryIdsInput.value = currentIds.join(',');
				}
			}
		});
	}

});

// ==========================================
// Plain-text spec fields (Duration, Altitude, Region, ...): strip special characters
// such as !@#$%^&*()= as the user types or pastes. "+" stays allowed on Age Limit only.
// The server applies the same rule on save.
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
	document.querySelectorAll('input[data-yatravanta-plain-text]').forEach(function(input) {
		const allowPlus = input.getAttribute('data-yatravanta-plain-text') === 'plus';
		const disallowed = allowPlus ? /[^\p{L}\p{N}\s\/\-,.+]/gu : /[^\p{L}\p{N}\s\/\-,.]/gu;
		input.addEventListener('input', function() {
			const cleaned = input.value.replace(disallowed, '');
			if (cleaned !== input.value) {
				const caret = input.selectionStart - (input.value.length - cleaned.length);
				input.value = cleaned;
				input.setSelectionRange(Math.max(caret, 0), Math.max(caret, 0));
			}
		});
	});
});

// ==========================================
// Age rules: Adults / Children age labels must fit the trek's Age Limit.
//   Adults   "12" or "12+", not below the minimum age (nor above the maximum).
//   Children "a-b", starting at/above the minimum age and ending before the adults age.
// Mirrors TrekMetaBoxController::validate_ages() (the server re-checks on save).
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
	const limitInput = document.getElementById('yatravanta_age_limit');
	const adultInput = document.getElementById('yatravanta_adult_age');
	const childInput = document.getElementById('yatravanta_child_age');
	if (!limitInput || !adultInput || !childInput) {
		return;
	}

	function parseLimit(text) {
		const nums = (text.match(/\d+/g) || []).map(Number);
		return { min: nums.length > 0 ? nums[0] : null, max: nums.length > 1 ? nums[1] : null };
	}

	function validate() {
		const limit = parseLimit(limitInput.value);
		const errors = { adult: '', child: '' };
		let adult = null;

		const adultText = adultInput.value.trim();
		if (adultText !== '') {
			const m = adultText.match(/^(\d{1,2})\+?$/);
			if (!m) {
				errors.adult = 'Adults age must be a number, optionally followed by +, e.g. 12+.';
			} else {
				adult = parseInt(m[1], 10);
				if (limit.min !== null && adult < limit.min) {
					errors.adult = 'Adults age (' + adult + ') cannot be below the trek minimum age (' + limit.min + ').';
				} else if (limit.max !== null && adult > limit.max) {
					errors.adult = 'Adults age (' + adult + ') cannot be above the trek maximum age (' + limit.max + ').';
				}
			}
		}

		const childText = childInput.value.trim();
		if (childText !== '') {
			const m = childText.match(/^(\d{1,2})\s*-\s*(\d{1,2})$/);
			if (!m) {
				errors.child = 'Children age must be a range, e.g. 10-11.';
			} else {
				const from = parseInt(m[1], 10);
				const to = parseInt(m[2], 10);
				if (from > to) {
					errors.child = 'Children age range must go from the lower age to the higher age, e.g. 10-11.';
				} else if (limit.min !== null && from < limit.min) {
					errors.child = 'Children age cannot start at ' + from + ', the trek minimum age is ' + limit.min + '.';
				} else if (adult !== null && to >= adult) {
					errors.child = 'Children age range must end before the adults age (' + adult + ').';
				}
			}
		}

		return errors;
	}

	function showError(input, message) {
		let el = input.parentNode.querySelector('.yatravanta-age-error');
		if (!message) {
			if (el) { el.remove(); }
			input.style.borderColor = '';
			return;
		}
		if (!el) {
			el = document.createElement('p');
			el.className = 'yatravanta-age-error';
			el.style.cssText = 'color:#b32d2e; margin:4px 0 0; font-size:12px;';
			input.parentNode.appendChild(el);
		}
		el.textContent = message;
		input.style.borderColor = '#b32d2e';
	}

	function render() {
		const errors = validate();
		showError(adultInput, errors.adult);
		showError(childInput, errors.child);
		return !errors.adult && !errors.child;
	}

	[limitInput, adultInput, childInput].forEach(function(input) {
		input.addEventListener('input', render);
	});
	render();

	// Block "Update" / "Publish" until the ages fit the Age Limit.
	const postForm = document.getElementById('post');
	if (postForm) {
		postForm.addEventListener('submit', function(e) {
			if (!render()) {
				e.preventDefault();
				const tabLink = document.querySelector('.yatravanta-meta-tabs-nav a[href="#yatravanta-tab-general"]');
				if (tabLink) { tabLink.click(); }
				adultInput.scrollIntoView({ block: 'center' });
				if (typeof window.yatravanta_admin_toast === 'function') {
					window.yatravanta_admin_toast('Please fix the age settings before saving.');
				}
				// WordPress disables the submit button while saving; undo that so the editor can retry.
				setTimeout(function() {
					document.querySelectorAll('#publishing-action .spinner').forEach(function(s) { s.classList.remove('is-active'); });
					document.querySelectorAll('#publish, #save-post').forEach(function(b) { b.classList.remove('disabled'); b.removeAttribute('disabled'); });
				}, 50);
			}
		});
	}
});
