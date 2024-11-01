jQuery(document).ready(function ($) {
  var wpbuddyPlugin = wpbuddyPlugin || {};
  wpbuddyPlugin = {
    textareaInititalHeight: 0,
    init: function () {
      this.cacheDom();
      this.bindEvents();
      this.runSettingsValidation();
      this.textareaInititalHeight = this.$textarea.height();
    },
    cacheDom: function () {
      this.$wpbuddyForm = $("#wpbuddy-form");
      this.$wpbuddySettingsForm = $("#wpbuddy-settings-form");
      this.$wpbuddyLicenseStatus = $(".wpbuddy-license-status");
      this.$wpbuddyLicenseField = $("input[name='wpbuddy_license_key']");
      this.$wpbuddyLicenseStatusText = $(".wpbuddy-status-text");
      this.$wpbuddyAdminSpinner = $(".wpbuddy-admin-spinner");
      this.$wpbuddyFormSpinner = $(".wpbuddy-form-spinner");
      this.$wpbuddyFormResult = $(".wpbuddy-form-result");
      this.$wpbuddyFormResultSuccess = $(".wpbuddy-form-result__success");
      this.$wpbuddyFormResultError = $(".wpbuddy-form-result__error");
      this.$wpbuddyFormResultSuccessText = $(
        ".wpbuddy-form-result__success__text"
      );
      this.$wpbuddyFormResultClose = $(".wpbuddy-form-result__close");
      this.$wpbuddyFormResultErrorText = $(".wpbuddy-form-result__error__text");
      this.$wpbuddyFormClose = $(".wpbuddy-form__close");
      this.$wpbuddyFooterIcon = $(".wpbuddy-footer__icon");
      this.$wpbuddyFormContainer = $(".wpbuddy-form__container");
      this.$textarea = $("#wpbuddy-form__field__description");
      this.$accordionHeader = $(".wpbuddy-accordion__header");
    },
    bindEvents: function () {
      this.$wpbuddyForm.on("submit", this.createTicket.bind(this));
      this.$wpbuddyFormResultClose.on("click", this.closeFormResult.bind(this));
      this.$wpbuddyFormClose.on("click", this.toggleForm.bind(this));
      this.$wpbuddyFooterIcon.on("click", this.toggleForm.bind(this));
      this.$textarea.on("input", this.textareaResize.bind(this));
      this.$textarea.on("keyup", this.textareReset.bind(this));
      this.$accordionHeader.on("click", this.toggleAccordion.bind(this));
    },
    runSettingsValidation: function () {
      if (this.$wpbuddySettingsForm.length) {
        this.validateSettings();
      }
    },
    createTicket: function (e) {
      e.preventDefault();
      this.$wpbuddyFormSpinner.css("display", "flex");
      // Add nonce to the form data
      var form_data = new FormData();
      form_data.append("image", $("#wpbuddy-form__field__file")[0].files[0]);
      form_data.append("title", $("#wpbuddy-form__field__title").val());
      form_data.append(
        "description",
        $("#wpbuddy-form__field__description").val()
      );
      form_data.append(
        "priority_id",
        $("#wpbuddy-form__field__priority_id").val()
      );

      $.ajax({
        url: `${WPBuddy.root}wpbuddy/v1/create-ticket`,
        type: "POST",
        data: form_data,
        processData: false,
        contentType: false,
        beforeSend: function (xhr) {
          xhr.setRequestHeader("X-WP-Nonce", WPBuddy.nonce);
        },
        success: function (response) {
          if (response && response.success) {
            this.$wpbuddyForm[0].reset();
            this.$textarea.attr("rows", 1);
            this.$wpbuddyFormResult.css("display", "flex");
            this.$wpbuddyFormResultSuccess.css("display", "block");
            this.$wpbuddyFormContainer.removeClass(
              "wpbuddy-form__container--error"
            );
            this.$wpbuddyFormContainer.addClass(
              "wpbuddy-form__container--success"
            );
            this.$wpbuddyFormResultErrorText.html("");
            $ticket_url = response.data.ticket_url;
            this.$wpbuddyFormResultSuccessText.html(
              `<p>Thank you for contacting us. Your ticket has been created. You can view it <a href="${$ticket_url}" target="_blank">here</a></p>`
            );
          }
          if (response && !response.success) {
            this.$wpbuddyFormResult.css("display", "flex");
            this.$wpbuddyFormResultError.css("display", "block");
            this.$wpbuddyFormContainer.removeClass(
              "wpbuddy-form__container--success"
            );
            this.$wpbuddyFormContainer.addClass(
              "wpbuddy-form__container--error"
            );
            this.$wpbuddyFormResultSuccessText.html("");
            this.$wpbuddyFormResultErrorText.html(response.message);
            const errors = response.data?.errors;
            for (const property in errors) {
              $(`#wpbuddy-form__field__${property}`).addClass(
                "wpbuddy-form__field--error"
              );
              $(`.wpbuddy-form__field__${property}__error`).html(
                errors[property]
              );
            }
          }
          this.$wpbuddyFormSpinner.css("display", "none");
        }.bind(this),
        error: function (error) {
          console.log(error);
        },
      });
    },
    closeFormResult: function () {
      this.$wpbuddyFormResult.css("display", "none");
      this.$wpbuddyFormResultSuccess.css("display", "none");
      this.$wpbuddyFormResultError.css("display", "none");
      this.$wpbuddyFormContainer.removeClass(
        "wpbuddy-form__container--success"
      );
      this.$wpbuddyFormContainer.removeClass("wpbuddy-form__container--error");
    },
    closeForm: function () {
      this.$wpbuddyFormResult.css("display", "none");
      this.$wpbuddyFormResultSuccess.css("display", "none");
      this.$wpbuddyFormResultError.css("display", "none");
      this.$wpbuddyFormContainer.removeClass(
        "wpbuddy-form__container--success"
      );
      this.$wpbuddyFormContainer.removeClass("wpbuddy-form__container--error");
      this.$wpbuddyFormContainer.css("display", "none");
    },
    toggleForm: function (e) {
      e.preventDefault();
      this.$wpbuddyFormContainer.toggle();
    },
    textareaResize: function () {
      const rowHeight = parseInt(this.$textarea.css("line-height"));
      const currentHeight = this.$textarea.prop("scrollHeight");
      if (currentHeight > this.textareaInititalHeight) {
        const rows = Math.floor(currentHeight / rowHeight);
        this.$textarea.attr("rows", rows);
      } else if (this.$textarea.val() === "") {
        this.$textarea.attr("rows", 1);
      }
    },
    textareReset: function () {
      if (this.$textarea.val() === "") {
        this.$textarea.attr("rows", 1);
      }
    },
    validateSettings: function (reload = false) {
      var form_data = new FormData();
      if (this.$wpbuddyLicenseField.val() == "") {
        return;
      }
      form_data.append("wpbuddy_license_key", this.$wpbuddyLicenseField.val());
      this.$wpbuddyAdminSpinner.addClass("is-active");
      this.$wpbuddyLicenseStatusText.html("");
      $.ajax({
        url: `${WPBuddy.root}wpbuddy/v1/validate-license`,
        type: "POST",
        data: form_data,
        processData: false,
        contentType: false,
        beforeSend: function (xhr) {
          xhr.setRequestHeader("X-WP-Nonce", WPBuddy.nonce);
        },
        success: function (response) {
          if (response && response.success) {
            this.$wpbuddyLicenseStatusText.html(
              `<p class="valid">${response.message}</p>`
            );
            this.$wpbuddyAdminSpinner.removeClass("is-active");
            setTimeout(() => {
              if (reload) {
                this.$wpbuddySettingsForm[0].submit();
              }
            }, 200);
          }
          if (response && !response.success) {
            this.$wpbuddyLicenseStatusText.html(
              `<p class="invalid">${response.message}</p>`
            );
            this.$wpbuddyAdminSpinner.removeClass("is-active");
          }
        }.bind(this),
        error: function (error) {
          console.log(error);
        },
        done: function () {
          this.$wpbuddyAdminSpinner.removeClass("is-active");
        }.bind(this),
      });
    },
    toggleAccordion: function (e) {
      e.preventDefault();
      let _this = e.currentTarget;
      $(_this).toggleClass("active");

      // Select all accordion headers except the clicked one and remove active class
      $(_this)
        .parent()
        .siblings()
        .find(".wpbuddy-accordion__header")
        .removeClass("active");
      $(_this).parent().siblings().find(".wpbuddy-accordion__body").slideUp();

      // Toggle the slide for the accordion body of the clicked header
      $(_this).next(".wpbuddy-accordion__body").slideToggle();
    },
  };
  wpbuddyPlugin.init();
});
