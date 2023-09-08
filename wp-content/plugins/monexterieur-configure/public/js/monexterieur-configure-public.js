(function ($) {
	'use strict';

	var CONFIGURE = {
		init: function () {
			this.cacheDOM();
			this.eventListener();

			this.$form.on('click', '.quantity-inc .button', function () {
				var $button = $(this),
					$parent = $button.parent(),
					$input = $parent.find('.input'),
					oldValue = $input.val(),
					newVal = 0,
					dataVal = $input.attr('data-value'),
					min = $input.attr('min'),
					max = $input.attr('max'),
					step = $input.attr('step');

				if (!oldValue) {
					oldValue = 0;
				}

				if ($button.text() === '+') {
					if (0 === parseFloat(oldValue)) {
						newVal = parseFloat(min);
					} else {
						newVal = parseFloat(oldValue) + parseFloat(step);
						if (max) {
							if (newVal > parseFloat(max)) {
								newVal = oldValue;
							}
						}
					}
				} else {
					// Don't allow decrementing below zero
					if (parseFloat(oldValue) > parseFloat(min)) {
						newVal = parseFloat(oldValue) - parseFloat(step);
					} else {
						newVal = parseFloat(dataVal);
					}
				}

				$(document.body).trigger('number_value_change', [$input, $button.text()]);

				$input.val(parseFloat(newVal.toFixed(2))).trigger('change');
			});
		},
		cacheDOM: function () {
			this.$form = $('#configurateur-form');
			this.$nextBtn = $('#configure-next-step');
			this.$prevBtn = $('#configure-prev-step');
			this.$configureBreadcrumb = $('#configure-steps-breadcrumb');
			this.$configureOptions = $('#configure-options');
			this.$mobileCount = $('.config-step-mobile .count');
		},
		eventListener: function () {
			this.$form.on('submit', this.preventFormSubmit.bind(this));
			this.$form.on('change input', 'input', this.formInputChanged.bind(this));
			this.$form.on('change', 'input[name="configure-cat"]', this.getConfigBreadcrumb.bind(this));
			this.$form.on('change', '.config-pro-indv input[type="radio"]', this.optionSelected.bind(this));
			this.$configureBreadcrumb.on('click', '.crumb', this.goBack.bind(this));
			this.$nextBtn.on('click', this.goToNextStep.bind(this));
			this.$prevBtn.on('click', this.goToPrevStep.bind(this));
			this.$form.on('click', '.custom-modal', this.showModal.bind(this));
			this.$form.on('click', '.close', this.hideModal.bind(this));
		},
		doingAjax: function () {
			$('#configurateur-form, #configure-next-step').css({ opacity: '0.5', 'pointer-events': 'none' });
		},
		ajaxEnd: function () {
			$('#configurateur-form, #configure-next-step').css({ opacity: '', 'pointer-events': '' });
		},
		preventFormSubmit: function (e) {
			e.preventDefault();
			return false;
		},
		formInputChanged: function () {
			this.$nextBtn.removeClass('disabled');
		},
		getConfigBreadcrumb: function (e) {
			e.preventDefault();
			var $this = $(e.currentTarget),
				that = this,
				value = $this.val();

			if (!value) {
				return false;
			}

			this.doingAjax();
			$.ajax({
				url: me.AJAX,
				type: 'POST',
				data: {
					action: 'configure_breadcrumb',
					product_cat: value,
				},
				success: function (response) {
					if (response.success) {
						that.$configureBreadcrumb.html(response.data.breadcrumb);
						that.$nextBtn.attr('data-next_step', '0');
					} else {
						that.$configureBreadcrumb.html('');
					}

					that.ajaxEnd();
				},
				error: function (a, b, c) {
					that.ajaxEnd();
				},
			});
		},
		formSubmit: function () {
			this.doingAjax();
			var that = this;
			$.ajax({
				url: me.AJAX,
				type: 'POST',
				data: {
					action: 'configure_submit',
					data: this.$form.serialize(),
				},
				success: function (response) {
					if (response.success) {
						window.location = response.data.cart;
						return;
					}

					alert(response.data);
					that.ajaxEnd();
				},
				error: function (e) {
					that.ajaxEnd();
				},
			});
		},
		goBack: function (e) {
			e.preventDefault();
			var $this = $(e.currentTarget),
				thisStep = $this.attr('data-place'),
				steps = this.$configureBreadcrumb.find('.config-breadcrumb').attr('data-total_steps');

			if (0 > thisStep) {
				return;
			}

			if ($this.hasClass('current') || !$this.hasClass('completed')) {
				return;
			}

			$this.nextAll('.crumb').removeClass('current').removeClass('completed').removeClass('step-disabled');
			$this.removeClass('completed').addClass('current');

			this.$form
				.find('.cust-row:eq(' + thisStep + ')')
				.show()
				.nextAll('.cust-row')
				.remove();
			this.$nextBtn.attr('data-next_step', thisStep).removeClass('disabled');

			this.$form
				.siblings('.config-title')
				.find('> p > span[data-place="' + thisStep + '"]')
				.css('display', '')
				.nextAll()
				.remove();
			this.$form
				.parent()
				.find('.notice > span[data-place="' + thisStep + '"]')
				.css('display', '')
				.nextAll()
				.remove();

			if (0 >= parseInt(thisStep)) {
				this.$prevBtn.css('display', 'none');
			}

			var mobileText = this.$mobileCount.find('> span:eq(0)').text(),
				mobileCount = thisStep + '/' + ( parseInt(steps) + 1 ) + ' <span>' + mobileText + '</span>';
			this.$mobileCount.html(mobileCount);
		},
		goToNextStep: function (e) {
			e.preventDefault();
			var $this = $(e.currentTarget),
				that = this,
				steps = this.$configureBreadcrumb.find('.config-breadcrumb').attr('data-total_steps'),
				nextStep = $this.attr('data-next_step');

			if ($this.hasClass('disabled')) {
				return false;
			}

			if (typeof undefined === typeof nextStep) {
				return false;
			}

			nextStep = parseInt(nextStep);

			if (parseInt(nextStep) >= parseInt(steps)) {
				this.formSubmit();
				return false;
			}

			this.doingAjax();
			$.ajax({
				url: me.AJAX,
				type: 'POST',
				data: {
					action: 'configure_next_step',
					step: nextStep,
					data: this.$form.serialize(),
				},
				success: function (response) {
					if (response.success) {
						var heading = '',
							notice = '',
							disabledStep = false;

						if (typeof undefined !== typeof response.data.notice) {
							if (response.data.notice) {
								notice = response.data.notice;
							}
						}

						that.$form
							.parent()
							.find('.notice')
							.css('display', '')
							.append('<span data-place="' + (parseInt(nextStep) + 1) + '">' + notice + '</span>')
							.find('> span:not(:last-child)')
							.hide();

						if (typeof undefined !== typeof response.data.heading) {
							if (response.data.heading) {
								heading = response.data.heading;
							}
						}

						that.$form
							.siblings('.config-title')
							.find('> p')
							.css('display', '')
							.append('<span data-place="' + (parseInt(nextStep) + 1) + '">' + heading + '</span>')
							.find('> span:not(:last-child)')
							.hide();

						that.$configureOptions.children().hide();
						that.$configureOptions.append(response.data.html);

						var currentStep = that.$configureBreadcrumb
							.find('[data-place="' + nextStep + '"]')
							.removeClass('current')
							.addClass('completed')
							.attr('data-step');

						if (that.$form.find('input[name="' + currentStep + '"]')) {
							var value = that.$form.find('input[name="' + currentStep + '"]:checked').val();
							that.$configureBreadcrumb
								.find('[data-place="' + nextStep + '"]')
								.find('span')
								.text(value);
						}

						if ( false !== response.data.disabledStep ) {
							that.$configureBreadcrumb.find('.crumb[data-place="' +  response.data.disabledStep + '"]').addClass('step-disabled');
							if ( nextStep + 1 ===  parseInt( response.data.disabledStep ) ) {
								nextStep = response.data.disabledStep;
							}
						} else {
							that.$configureBreadcrumb.find('.crumb[data-place="' +  response.data.disabledStep + '"]').removeClass('step-disabled');
						}

						that.$nextBtn.attr('data-next_step', parseInt(nextStep) + 1).addClass('disabled');

						that.$configureBreadcrumb
							.find('[data-place="' + nextStep + '"]')
							.next()
							.addClass('current');

						that.$configureBreadcrumb.find('.config-breadcrumb').addClass('height');
					} else {
					}

					var mobileText = that.$mobileCount.find('> span:eq(0)').text(),
						mobileCount = parseInt(nextStep) + 2 + '/' + ( parseInt(steps) + 1 ) + ' <span>' + mobileText + '</span>';
					that.$mobileCount.html(mobileCount);

					that.$prevBtn.css('display', '');
					that.ajaxEnd();
				},
				error: function (a, b, c) {
					that.ajaxEnd();
				},
			});
		},
		goToPrevStep: function (e) {
			e.preventDefault();
			var $this = $(e.currentTarget),
				currentStep = parseInt(this.$nextBtn.attr('data-next_step')),
				stepName = this.$configureBreadcrumb.find('.crumb[data-place="' + currentStep + '"]').attr('data-step'),
				prevStep = currentStep - 1,
				steps = this.$configureBreadcrumb.find('.config-breadcrumb').attr('data-total_steps'),
				$current = this.$form.find('.cust-row[data-step="' + stepName + '"]');

			if (0 >= currentStep) {
				return;
			}

			if ($current) {
				this.$configureBreadcrumb
					.find('.crumb[data-place="' + currentStep + '"]')
					.removeClass('current')
					.removeClass('completed');

				if ( this.$configureBreadcrumb.find('.crumb[data-place="' +  prevStep + '"]').hasClass('step-disabled') ) {
					this.$configureBreadcrumb.find('.crumb[data-place="' +  prevStep + '"]').removeClass('step-disabled')
					prevStep = prevStep - 1;
					currentStep = currentStep - 1;
				}

				var prevStepName = this.$configureBreadcrumb.find('.crumb[data-place="' + prevStep + '"]').attr('data-step')

				if (0 >= prevStep) {
					this.$prevBtn.css('display', 'none');
				}

				this.$configureBreadcrumb
					.find('.crumb[data-place="' + prevStep + '"]')
					.removeClass('completed')
					.addClass('current');

					this.$form.find('.cust-row[data-step="' + prevStepName + '"]').show().nextAll().remove();
				this.$nextBtn.attr('data-next_step', prevStep).removeClass('disabled');

				this.$form
					.siblings('.config-title')
					.find('> p > span[data-place="' + prevStep + '"]')
					.css('display', '')
					.nextAll()
					.remove();
				this.$form
					.parent()
					.find('.notice > span[data-place="' + prevStep + '"]')
					.css('display', '')
					.nextAll()
					.remove();

				var mobileText = this.$mobileCount.find('> span:eq(0)').text(),
					mobileCount = currentStep + '/' + ( parseInt(steps) + 1 ) + ' <span>' + mobileText + '</span>';
				this.$mobileCount.html(mobileCount);
			}
		},
		optionSelected: function (e) {
			var $this = $(e.currentTarget),
				$parent = $this.parent().parent();

			$parent
				.addClass('selected')
				.parent()
				.siblings()
				.find('.config-pro-indv')
				.removeClass('selected')
				.find('input.inside')
				.attr('disabled', 'disabled');
			$parent.find('input.inside').attr('disabled', false);
		},
		showModal: function (e) {
			e.preventDefault();
			console.log('information clicked');
			var target = $(e.currentTarget).attr('data-target'),
				$modal = $('#' + target + '-custom-modal');

			if ($modal.length) {
				$modal.show();
			}
		},
		hideModal: function (e) {
			e.preventDefault();
			$(e.currentTarget).closest('.modal').fadeOut();
		},
	};

	CONFIGURE.init();
})(jQuery);
