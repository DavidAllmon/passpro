jQuery(document).ready(function($) {
    // Tab navigation functionality
    $('#passpro-settings-tabs-nav a').on('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all tabs and add to the clicked tab
        $('#passpro-settings-tabs-nav a').removeClass('passpro-tab-active');
        $(this).addClass('passpro-tab-active');
        
        // Hide all tab content and show the one that corresponds to the clicked tab
        $('.passpro-tab-content').removeClass('passpro-tab-active');
        $($(this).attr('href')).addClass('passpro-tab-active');
        
        // Re-initialize color pickers in the newly activated tab
        var targetTabId = $(this).attr('href');
        setTimeout(function() {
            // Initialize WP color pickers only for tabs that need them
            if (targetTabId !== '#passpro-tab-button') {
                // Re-initialize custom color pickers for Text tab, Loginbox tab, and Appearance tab
                if (targetTabId === '#passpro-tab-text' || targetTabId === '#passpro-tab-loginbox' || targetTabId === '#passpro-tab-appearance') {
                    initCustomColorPickers(targetTabId);
                }
            } else {
                // Re-initialize custom color pickers for Button tab
                initButtonCustomColorPickers();
            }
        }, 100);
    });
    
    // Media uploader for logo
    $('.passpro-upload-button').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var logoUrlField = button.prev('.passpro-media-url');
        var logoPreview = button.parent().find('.passpro-logo-preview');
        var removeButton = button.next('.passpro-remove-button');
        
        // Create a new media frame
        var frame = wp.media({
            title: 'Select or Upload Logo',
            button: {
                text: 'Use this logo'
            },
            multiple: false
        });
        
        // When a logo is selected in the media frame...
        frame.on('select', function() {
            // Get media attachment details from the frame state
            var attachment = frame.state().get('selection').first().toJSON();
            
            // Set the logo URL in the text field
            logoUrlField.val(attachment.url);
            
            // Update the preview
            logoPreview.html('<img src="' + attachment.url + '" style="max-width: 200px; max-height: 100px;" />');
            
            // Show the remove button
            removeButton.show();
        });
        
        // Finally, open the modal
        frame.open();
    });
    
    // Remove logo
    $('.passpro-remove-button').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var logoUrlField = button.prevAll('.passpro-media-url');
        var logoPreview = button.parent().find('.passpro-logo-preview');
        
        // Clear the logo URL
        logoUrlField.val('');
        
        // Clear the preview
        logoPreview.html('');
        
        // Hide the remove button
        button.hide();
    });
    
    // Update the preview of the login box when settings change
    function updateLoginBoxPreview() {
        var preview = $('#loginbox-preview');
        var bgColor = $('#passpro_box_bg_color').val() || '#ffffff';
        var borderColor = $('#passpro_box_border_color').val() || '#dddddd';
        var borderWidth = $('#passpro_box_border_width').val() || '1';
        var borderRadius = $('#passpro_box_border_radius').val() || '0';
        
        // Apply styles to preview
        preview.css({
            'background-color': bgColor,
            'border-color': borderColor,
            'border-width': borderWidth + 'px',
            'border-radius': borderRadius + 'px',
            'border-style': 'solid'
        });
    }
    
    // Initialize custom color pickers for any tab
    function initCustomColorPickers(tabId) {
        // Close any existing WP color pickers
        $(tabId + ' .passpro-color-picker').each(function() {
            if ($(this).hasClass('wp-color-picker')) {
                $(this).wpColorPicker('close');
            }
        });
        
        // Convert standard color pickers to custom ones
        $(tabId + ' .passpro-color-picker').each(function() {
            var $this = $(this);
            var colorVal = $this.val() || $this.data('default-color') || '#ffffff';
            var id = $this.attr('id');
            var defaultColor = $this.data('default-color') || '#ffffff';
            
            // Don't modify if already converted
            if ($this.parent().hasClass('passpro-color-field-wrapper')) {
                return;
            }
            
            // Create new custom color picker structure
            $this.wrap('<div class="passpro-color-field-wrapper"></div>');
            $this.addClass('passpro-color-picker-button').removeClass('passpro-color-picker');
            $this.after('<input type="color" class="passpro-color-preview" value="' + colorVal + '" data-target="' + id + '" />');
            
            // Set up event handlers
            $this.siblings('.passpro-color-preview').on('input', function() {
                var colorValue = $(this).val();
                var targetId = $(this).data('target');
                $('#' + targetId).val(colorValue);
                
                // Update any relevant previews
                if (tabId === '#passpro-tab-text') {
                    updateTextPreviews();
                } else if (tabId === '#passpro-tab-loginbox') {
                    updateLoginBoxPreview();
                }
            });
            
            $this.on('input', function() {
                var colorValue = $(this).val();
                var id = $(this).attr('id');
                $('[data-target="' + id + '"]').val(colorValue);
                
                // Update any relevant previews
                if (tabId === '#passpro-tab-text') {
                    updateTextPreviews();
                } else if (tabId === '#passpro-tab-loginbox') {
                    updateLoginBoxPreview();
                }
            });
        });
    }
    
    // Attach change event handlers to login box settings
    $('#passpro_box_border_width, #passpro_box_border_radius').on('input change', updateLoginBoxPreview);
    
    // Initial preview update for login box
    setTimeout(function() {
        if ($('#passpro-tab-loginbox').hasClass('passpro-tab-active')) {
            updateLoginBoxPreview();
        }
    }, 300);
    
    // Update the preview of text styles when settings change
    function updateTextPreviews() {
        // Headline preview
        var headlinePreview = $('#headline-preview-text');
        var headlineFontSize = $('#passpro_headline_font_size').val() || '20';
        var headlineFontColor = $('#passpro_headline_font_color').val() || '#444444';
        var headlineFontFamily = $('#passpro_headline_font_family').val() || 'inherit';
        
        headlinePreview.css({
            'font-size': headlineFontSize + 'px',
            'color': headlineFontColor,
            'font-family': headlineFontFamily || 'inherit'
        });
        
        // Message preview
        var messagePreview = $('#message-preview-text');
        var messageFontSize = $('#passpro_message_font_size').val() || '14';
        var messageFontColor = $('#passpro_message_font_color').val() || '#444444';
        var messageFontFamily = $('#passpro_message_font_family').val() || 'inherit';
        
        messagePreview.css({
            'font-size': messageFontSize + 'px',
            'color': messageFontColor,
            'font-family': messageFontFamily || 'inherit'
        });
        
        // Label preview
        var labelPreview = $('#label-preview-text');
        var labelFontSize = $('#passpro_label_font_size').val() || '14';
        var labelFontColor = $('#passpro_label_font_color').val() || '#444444';
        var labelFontFamily = $('#passpro_label_font_family').val() || 'inherit';
        
        labelPreview.css({
            'font-size': labelFontSize + 'px',
            'color': labelFontColor,
            'font-family': labelFontFamily || 'inherit'
        });
    }
    
    // Attach change event handlers to text style settings
    $('#passpro_headline_font_size, #passpro_message_font_size, #passpro_label_font_size').on('input change', updateTextPreviews);
    $('#passpro_headline_font_family, #passpro_message_font_family, #passpro_label_font_family').on('input change', updateTextPreviews);
    
    // Initial preview update for text styles
    setTimeout(function() {
        if ($('#passpro-tab-text').hasClass('passpro-tab-active')) {
            updateTextPreviews();
        }
    }, 300);
    
    // Update the preview of the button when settings change
    function updateButtonPreview() {
        var buttonPreview = $('#button-preview');
        var buttonContainer = $('#button-container');
        
        // Get all button style values
        var buttonText = $('#passpro_button_text_label').val() || 'Enter';
        var bgColor = $('#passpro_button_bg_color').val() || '#2271b1';
        var textColor = $('#passpro_button_text_color').val() || '#ffffff';
        var borderRadius = $('#passpro_button_border_radius').val() || '3';
        var borderWidth = $('#passpro_button_border_width').val() || '1';
        var borderColor = $('#passpro_button_border_color').val() || '#2271b1';
        
        // Get size and alignment settings
        var buttonWidth = $('#passpro_button_width').val() || '';
        var buttonHeight = $('#passpro_button_height').val() || '';
        var buttonAlignment = $('#passpro_button_alignment').val() || '';
        
        // Get padding settings
        var paddingTop = $('#passpro_button_padding_top').val() || '10';
        var paddingRight = $('#passpro_button_padding_right').val() || '20';
        var paddingBottom = $('#passpro_button_padding_bottom').val() || '10';
        var paddingLeft = $('#passpro_button_padding_left').val() || '20';
        
        // Set button text
        buttonPreview.text(buttonText);
        
        // Reset container styles
        buttonContainer.css({
            'text-align': '',
            'display': 'block',
            'width': '100%',
            'margin-top': '24px',
            'overflow': 'hidden'
        });
        
        // Apply alignment to container
        if (buttonAlignment) {
            buttonContainer.css('text-align', buttonAlignment);
        }
        
        // Reset button styles first
        buttonPreview.css({
            'float': '',
            'margin-left': '',
            'margin-right': '',
            'display': 'inline-block',
            'min-width': '',
            'width': '',
            'height': '',
            'transform': ''
        });
        
        // Special handling for center alignment with width
        if (buttonAlignment === 'center' && buttonWidth) {
            buttonPreview.css({
                'display': 'block',
                'margin-left': 'auto',
                'margin-right': 'auto'
            });
        } 
        // Special handling for center alignment without width
        else if (buttonAlignment === 'center' && !buttonWidth) {
            buttonPreview.css({
                'display': 'inline-block',
                'float': 'none',
                'position': 'relative',
                'left': '50%',
                'transform': 'translateX(-50%)'
            });
        }
        // Handle left alignment
        else if (buttonAlignment === 'left') {
            buttonPreview.css('float', 'left');
        }
        // Handle right alignment
        else if (buttonAlignment === 'right') {
            buttonPreview.css('float', 'right');
        }
        
        // Apply size settings
        if (buttonWidth) {
            buttonPreview.css('width', buttonWidth);
        } else {
            buttonPreview.css('min-width', '100px');
        }
        
        if (buttonHeight) {
            buttonPreview.css({
                'height': buttonHeight,
                'line-height': 'normal',
                'display': 'flex',
                'align-items': 'center',
                'justify-content': 'center'
            });
        }
        
        // Calculate padding - use individual values or defaults
        var padding = '';
        if (paddingTop || paddingRight || paddingBottom || paddingLeft) {
            padding = paddingTop + 'px ' + paddingRight + 'px ' + paddingBottom + 'px ' + paddingLeft + 'px';
        } else {
            padding = '10px 20px';
        }
        
        // Apply styles to preview button
        buttonPreview.css({
            'background-color': bgColor,
            'color': textColor,
            'border-radius': borderRadius + 'px',
            'border-width': borderWidth + 'px',
            'border-color': borderColor,
            'border-style': 'solid',
            'padding': padding,
            'font-size': '14px',
            'cursor': 'pointer',
            'transition': 'all 0.3s ease',
            'text-align': 'center',
            'box-shadow': 'none',
            'outline': 'none',
            'text-decoration': 'none',
            'vertical-align': 'middle'
        });
        
        // Set up hover effect using jQuery hover
        buttonPreview.off('mouseenter mouseleave');
        buttonPreview.hover(
            function() {
                // Mouse enter - hover state
                var bgHoverColor = $('#passpro_button_hover_bg_color').val() || '#135e96';
                var textHoverColor = $('#passpro_button_hover_text_color').val() || '#ffffff';
                $(this).css({
                    'background-color': bgHoverColor,
                    'color': textHoverColor
                });
            },
            function() {
                // Mouse leave - normal state
                $(this).css({
                    'background-color': bgColor,
                    'color': textColor
                });
            }
        );
    }
    
    // Initialize custom color pickers for Button Styles tab
    function initButtonCustomColorPickers() {
        // Set up the color preview inputs to update the text inputs
        $('.passpro-color-preview').off('input').on('input', function() {
            var colorValue = $(this).val();
            var targetId = $(this).data('target');
            $('#' + targetId).val(colorValue);
            
            // Update any UI that depends on this color
            updateButtonPreview();
        });
        
        // Set up the text inputs to update the color preview inputs
        $('.passpro-color-picker-button').off('input').on('input', function() {
            var colorValue = $(this).val();
            var id = $(this).attr('id');
            $('[data-target="' + id + '"]').val(colorValue);
            
            // Update any UI that depends on this color
            updateButtonPreview();
        });
    }
    
    // Attach change handlers to button text, border, size, alignment and padding
    $('#passpro_button_text_label, #passpro_button_border_radius, #passpro_button_border_width').on('input change', updateButtonPreview);
    $('#passpro_button_width, #passpro_button_height, #passpro_button_alignment').on('input change', updateButtonPreview);
    $('#passpro_button_padding_top, #passpro_button_padding_right, #passpro_button_padding_bottom, #passpro_button_padding_left').on('input change', updateButtonPreview);
    
    // Initialize on page load based on which tab is active
    setTimeout(function() {
        // Initialize color pickers for the active tab
        if ($('#passpro-tab-button').hasClass('passpro-tab-active')) {
            initButtonCustomColorPickers();
            updateButtonPreview();
        } else if ($('#passpro-tab-text').hasClass('passpro-tab-active')) {
            initCustomColorPickers('#passpro-tab-text');
            updateTextPreviews();
        } else if ($('#passpro-tab-loginbox').hasClass('passpro-tab-active')) {
            initCustomColorPickers('#passpro-tab-loginbox');
            updateLoginBoxPreview();
        } else if ($('#passpro-tab-appearance').hasClass('passpro-tab-active')) {
            initCustomColorPickers('#passpro-tab-appearance');
        }
    }, 300);
    
    // Extra cleanup for Button tab - remove any unwanted elements
    function cleanupButtonTab() {
        // Find and remove any unwanted "Login Form Preview" text or elements in the Button tab
        // This is a comprehensive cleanup that should remove anything that isn't part of our intentional design
        $('#passpro-tab-button').find('.passpro-box-preview, .passpro-preview-label, .passpro-preview-input, .passpro-preview-button, #loginbox-preview').remove();
        
        // Remove any text nodes that might be directly under the tab content
        $('#passpro-tab-button').contents().filter(function() {
            return this.nodeType === 3; // Text nodes
        }).remove();
        
        // Find any divs that aren't part of our intended structure
        $('#passpro-tab-button > div:not(.passpro-settings-header):not(.passpro-settings-grid)').remove();
    }
    
    // Call cleanup when the button tab is shown
    $('#passpro-settings-tabs-nav a[href="#passpro-tab-button"]').on('click', function() {
        setTimeout(cleanupButtonTab, 10);
    });
    
    // Also run cleanup on page load if the button tab is active
    if ($('#passpro-tab-button').hasClass('passpro-tab-active')) {
        setTimeout(cleanupButtonTab, 10);
    }
}); 