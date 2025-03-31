(function( $ ) {
    'use strict';

    // --- Function Definitions ---

    // Media Uploader helper function
    function updateLogoAlignment() {
        var alignment = $('#passpro_logo_alignment').val() || 'center';
        var previewContainer = $('.passpro-logo-preview');
        var previewImg = previewContainer.find('img');
        
        if (previewImg.length) {
            // Reset all alignment styles first
            previewImg.css({
                'margin-left': '',
                'margin-right': ''
            });
            
            // Apply the selected alignment
            switch(alignment) {
                case 'left':
                    previewImg.css({
                        'margin-left': '0',
                        'margin-right': 'auto'
                    });
                    previewContainer.css('text-align', 'left');
                    break;
                case 'right':
                    previewImg.css({
                        'margin-left': 'auto',
                        'margin-right': '0'
                    });
                    previewContainer.css('text-align', 'right');
                    break;
                case 'center':
                default:
                    previewImg.css({
                        'margin-left': 'auto',
                        'margin-right': 'auto'
                    });
                    previewContainer.css('text-align', 'center');
                    break;
            }
        }
    }

    // --- Document Ready --- 
    $(function() { 
        // Password visibility toggle
        $('.passpro-password-toggle').on('click', function(e) {
            e.preventDefault();
            
            var passwordField = $(this).parent().find('input');
            var icon = $(this).find('.dashicons');
            
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            } else {
                passwordField.attr('type', 'password');
                icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            }
        });

        // Protection toggle icon update
        $('#passpro_enabled').on('change', function() {
            var isChecked = $(this).is(':checked');
            var iconElement = $('.passpro-protection-icon .dashicons');
            
            if (isChecked) {
                iconElement.removeClass('dashicons-unlock').addClass('dashicons-lock');
            } else {
                iconElement.removeClass('dashicons-lock').addClass('dashicons-unlock');
            }
        });

        // Initialize Settings Tabs (if elements exist)
        var tabsNav = $('#passpro-settings-tabs-nav');
        var tabsContent = $('#passpro-settings-tabs > div');

        // --- Fix for Initial Tab State ---
        // Find the initially active tab link
        var initialActiveTabLink = tabsNav.find('a.nav-tab-active');
        if (initialActiveTabLink.length) {
            // Get the target content ID from its href
            var initialTargetId = initialActiveTabLink.attr('href');
            // Add the active class to the corresponding content div
            $(initialTargetId).addClass('passpro-tab-active');
        } else {
            // Fallback: If no link is active by default, activate the first one
            tabsNav.find('a').first().addClass('nav-tab-active');
            tabsContent.first().addClass('passpro-tab-active');
        }
        // --- End Fix ---

        // --- Tab Switching Logic ---
        tabsNav.on('click', 'a', function(e) {
            e.preventDefault();
            var $this = $(this);
            var targetId = $this.attr('href');

            // Update active class on tab links
            tabsNav.find('a').removeClass('nav-tab-active');
            $this.addClass('nav-tab-active');

            // Update active class on content divs (using class instead of show/hide)
            tabsContent.removeClass('passpro-tab-active');
            $(targetId).addClass('passpro-tab-active');
        });
        // --- End Tab Switching Logic ---

        // Fix headings in Button Styles tab
        var buttonTab = $('#passpro-tab-button');
        if (buttonTab.length) {
            // Find heading rows and add section-heading class
            buttonTab.find('tr th').each(function() {
                var $th = $(this);
                var text = $th.text().trim();
                
                // Check if this is a section heading
                if ((text.includes('Button Colors') || 
                    text.includes('Button Border') || 
                    text.includes('Button Size & Position') || 
                    text.includes('Button Effects')) && 
                    !text.includes('Button Text')) {
                    
                    // Add a class to style it
                    $th.addClass('section-heading');
                    
                    // Hide its associated empty TD
                    $th.next('td').hide();
                }
                
                // Special handling for Button Text heading - don't hide the TD
                if (text === 'Button Text') {
                    $th.addClass('section-heading');
                    // We deliberately don't hide the next TD here
                }
            });
            
            // Add a live preview of the button
            var previewContainer = $('<div class="button-preview"></div>');
            var previewTitle = $('<div class="button-preview-title">Login Form Preview</div>');
            var previewForm = $('<div class="preview-form"></div>');
            var previewLabel = $('<label class="preview-label">Password</label>');
            var previewInput = $('<input type="password" class="preview-input" value="password123" />');
            var buttonContainer = $('<div class="preview-button-container" id="button-container"></div>');
            var previewButton = $('<button type="button" class="preview-button" id="button-preview">Enter</button>');
            
            buttonContainer.append(previewButton);
            previewForm.append(previewLabel).append(previewInput).append(buttonContainer);
            previewContainer.append(previewTitle).append(previewForm);

            // Initial styling
            updateFormPreview();
            
            // Update preview on any settings change
            buttonTab.find('input, select').on('change input keyup', function() {
                updateFormPreview();
            });
            
            // Special handling for the button text label to ensure it updates immediately
            $('#passpro_button_text_label').on('change input keyup', function() {
                var buttonText = $(this).val() || 'Enter';
                if (previewButton) {
                    previewButton.text(buttonText);
                }
            });
            
            // Function to update the preview based on current settings
            function updateFormPreview() {
                // Skip if button preview was removed
                if (!previewButton.length) {
                    return;
                }
                
                // Get all the button settings
                var backgroundColor = $('#passpro_button_bg_color').val() || '#0073aa';
                var textColor = $('#passpro_button_text_color').val() || '#ffffff';
                var borderColor = $('#passpro_button_border_color').val() || '#0073aa';
                var borderWidth = $('#passpro_button_border_width').val() || '1';
                var borderRadius = $('#passpro_button_border_radius').val() || '3';
                var fontSize = $('#passpro_button_font_size').val() || '14';
                var fontWeight = $('#passpro_button_font_weight').val() || 'normal';
                var textTransform = $('#passpro_button_text_transform').val() || 'none';
                var buttonWidth = $('#passpro_button_width').val() || 'auto';
                var buttonHeight = $('#passpro_button_height').val() || 'auto';
                var paddingTop = $('#passpro_button_padding_top').val() || '10';
                var paddingRight = $('#passpro_button_padding_right').val() || '15';
                var paddingBottom = $('#passpro_button_padding_bottom').val() || '10';
                var paddingLeft = $('#passpro_button_padding_left').val() || '15';
                var buttonAlignment = $('#passpro_button_alignment').val() || '';
                var boxShadow = $('#passpro_button_box_shadow').val() || 'none';
                var transition = $('#passpro_button_transition').val() || 'normal';
                var buttonText = $('#passpro_button_text_label').val() || 'Enter';
                
                // Get input field settings
                var inputBgColor = $('#passpro_input_bg_color').val() || '#ffffff';
                var inputTextColor = $('#passpro_input_text_color').val() || '#2c3338';
                var inputBorderColor = $('#passpro_input_border_color').val() || '#8c8f94';
                var inputFocusBorderColor = $('#passpro_input_focus_border_color').val() || '#2271b1';
                var inputBorderWidth = $('#passpro_input_border_width').val() || '1';
                var inputBorderRadius = $('#passpro_input_border_radius').val() || '3';
                var inputFontSize = $('#passpro_input_font_size').val() || '24';
                var inputPadding = $('#passpro_input_padding').val() || '3';
                
                // Style the input field if it exists
                if (previewInput.length) {
                    previewInput.css({
                        'background-color': inputBgColor,
                        'color': inputTextColor,
                        'border': inputBorderWidth + 'px solid ' + inputBorderColor,
                        'border-radius': inputBorderRadius + 'px',
                        'font-size': inputFontSize + 'px',
                        'padding': inputPadding + 'px',
                        'width': '100%',
                        'box-sizing': 'border-box',
                        'margin': '0 0 15px 0',
                        'display': 'block'
                    });
                }
                
                // Skip further button styling if elements don't exist
                if (!previewButton.length) {
                    return;
                }
                
                // Add focus styles for the input field
                $('.preview-input-focus-style').remove();
                $('<style class="preview-input-focus-style">' +
                    '.preview-input:focus {' +
                    '  outline: none;' +
                    '  border-color: ' + inputFocusBorderColor + ' !important;' +
                    '  box-shadow: 0 0 0 1px ' + inputFocusBorderColor + ' !important;' +
                    '}' +
                    '</style>'
                ).appendTo('head');
                
                // Calculate box shadow value
                var shadowValue = 'none';
                switch(boxShadow) {
                    case 'light':
                        shadowValue = '0 2px 4px rgba(0,0,0,0.1)';
                        break;
                    case 'medium':
                        shadowValue = '0 4px 8px rgba(0,0,0,0.15)';
                        break;
                    case 'heavy':
                        shadowValue = '0 6px 12px rgba(0,0,0,0.2)';
                        break;
                    default:
                        shadowValue = 'none';
                }
                
                // Calculate transition value
                var transitionValue = 'all 0.3s ease';
                switch(transition) {
                    case 'fast':
                        transitionValue = 'all 0.1s ease';
                        break;
                    case 'slow':
                        transitionValue = 'all 0.5s ease';
                        break;
                    case 'none':
                        transitionValue = 'none';
                        break;
                    default:
                        transitionValue = 'all 0.3s ease'; // normal
                }
                
                // Calculate padding
                var padding = paddingTop + 'px ' + paddingRight + 'px ' + paddingBottom + 'px ' + paddingLeft + 'px';
                
                // Apply button styles
                previewButton.css({
                    'background-color': backgroundColor,
                    'color': textColor,
                    'border': borderWidth + 'px solid ' + borderColor,
                    'border-radius': borderRadius + 'px',
                    'font-size': fontSize + 'px',
                    'font-weight': fontWeight,
                    'text-transform': textTransform,
                    'width': buttonWidth,
                    'height': buttonHeight,
                    'padding': padding,
                    'box-shadow': shadowValue,
                    'transition': transitionValue,
                    'cursor': 'pointer',
                    'text-align': 'center',
                    'min-width': '100px'
                });
                
                // Handle button alignment
                if (buttonContainer.length) {
                    switch(buttonAlignment) {
                        case 'left':
                            buttonContainer.css('text-align', 'left');
                            break;
                        case 'center':
                            buttonContainer.css('text-align', 'center');
                            break;
                        case 'right':
                            buttonContainer.css('text-align', 'right');
                            break;
                        default:
                            buttonContainer.css('text-align', 'left');
                    }
                }
                
                // Apply button text
                previewButton.text(buttonText);
            }
        }

        // Media Uploader
        var mediaUploader;

        $(document).on('click', '.passpro-upload-button', function(e) {
            e.preventDefault();
            var button = $(this);
            var urlInput = button.siblings('.passpro-media-url');
            var previewContainer = button.siblings('.passpro-logo-preview');

            // If the uploader object has already been created, reopen the dialog
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }

            // Extend the wp.media object
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Logo',
                button: {
                    text: 'Choose Logo'
                },
                multiple: false // Set to true if you want multiple files
            });

            // When a file is selected, grab the URL
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                urlInput.val(attachment.url);
                // Show preview
                previewContainer.html('<img src="' + attachment.url + '" style="max-width: 200px; max-height: 100px;" />');
                button.siblings('.passpro-remove-button').show();
                // Update logo alignment after new logo is selected
                updateLogoAlignment();
            });

            // Open the uploader dialog
            mediaUploader.open();
        });

        // Remove Logo Button
        $(document).on('click', '.passpro-remove-button', function(e) {
            e.preventDefault();
            var button = $(this);
            var urlInput = button.siblings('.passpro-media-url');
            var previewContainer = button.siblings('.passpro-logo-preview');

            urlInput.val('');
            previewContainer.html('');
            button.hide();
        });
        
        // Update logo alignment when the setting changes
        $('#passpro_logo_alignment').on('change', function() {
            updateLogoAlignment();
        });
        
        // Initialize logo alignment on page load
        updateLogoAlignment();

        // When a tab changes, reinitialize the color pickers in the new tab
        tabsNav.on('click', 'a', function() {
            var tabId = $(this).attr('href');
            if (tabId) {
                // Add a slight delay to ensure the tab content is visible
                setTimeout(function() {
                    $(tabId + ' .passpro-color-picker').each(function() {
                        // Destroy existing color picker if it exists
                        if ($(this).hasClass('wp-color-picker')) {
                            // Unfortunately, we can't easily destroy an existing color picker
                            // So we'll just initialize it if it doesn't have the class already
                        } else {
                            $(this).wpColorPicker();
                        }
                    });
                }, 50);
            }
        });

        // Add data attributes to settings field rows for easier targeting
        $('.passpro-settings-wrapper tr').each(function() {
            var inputId = $(this).find('input, select, textarea').first().attr('id');
            if (inputId) {
                $(this).attr('data-field', inputId);
            }
        });

    }); // End of document ready

})( jQuery ); 