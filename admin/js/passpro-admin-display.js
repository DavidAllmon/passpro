/**
 * PassPro Admin JavaScript
 * Handles all admin panel interactions and UI updates
 */
jQuery(document).ready(function($) {
    
    // ---- Color Picker Initialization ----
    function initColorPickers() {
        // Initialize color pickers the standard WordPress way
        $('.passpro-color-picker').wpColorPicker({
            change: function(event, ui) {
                // Get the ID of the changed element to determine what to update
                var id = $(this).attr('id');
                var newColor = ui.color.toString();
                
                console.log('Color changed for', id, 'to', newColor);
                
                // Trigger updates based on what changed
                if (id.indexOf('box_') !== -1) {
                    updateLoginBoxPreview();
                } else if (id.indexOf('button_') !== -1) {
                    updateButtonPreview();
                }
                // We no longer have text previews to update
            }
        });
        
        // Fix positioning of color pickers to ensure they appear on top
        $('.wp-picker-holder, .iris-picker').css({
            'z-index': '999999',
            'position': 'absolute'
        });
        
        // Make parent elements not clip the picker
        $('.wp-picker-container').parents().css('overflow', 'visible');
    }
    
    // Add CSS fixes for color picker positioning without expanding boxes
    $('head').append('<style>' +
        // Position the iris color picker absolutely so it doesn't affect parent layout
        '.wp-picker-holder { position: absolute !important; z-index: 999999 !important; }' +
        '.iris-picker { z-index: 999999 !important; }' +
        '.wp-picker-container { position: relative !important; }' +
        // Ensure parent elements don't clip the picker
        '.form-table td, .form-table th, .form-table tr, ' +
        '.passpro-setting-card-content, .passpro-color-field { overflow: visible !important; }' +
        // Prevent iris picker from expanding parent container
        '.passpro-setting-card { overflow: visible !important; }' +
        '.passpro-setting-card-content { overflow: visible !important; height: auto !important; }' +
        // Make sure WP admin menu doesn't overlap with color picker
        '.iris-picker, .wp-picker-holder { margin-top: 2px; }' +
    '</style>');
    
    // ---- Tab Navigation ----
    $('#passpro-settings-tabs-nav a').on('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all tabs and add to the clicked tab
        $('#passpro-settings-tabs-nav a').removeClass('passpro-tab-active');
        $(this).addClass('passpro-tab-active');
        
        // Hide all tab content and show the one that corresponds to the clicked tab
        $('.passpro-tab-content').removeClass('passpro-tab-active');
        $($(this).attr('href')).addClass('passpro-tab-active');
        
        // Update previews for the active tab
        var targetTabId = $(this).attr('href');
        setTimeout(function() {
            if (targetTabId === '#passpro-tab-loginbox') {
                updateLoginBoxPreview();
            } else if (targetTabId === '#passpro-tab-button') {
                updateButtonPreview();
            }
            // No longer need to update text previews
            
            // Make sure color pickers are properly initialized in the newly active tab
            initColorPickers();
        }, 100);
    });
    
    // ---- Media Uploader for Logo ----
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
    
    // ---- Preview Update Functions ----
    
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
    
    // ---- Event Handlers ----
    
    // Attach change event handlers to login box settings
    $('#passpro_box_border_width, #passpro_box_border_radius').on('input change', updateLoginBoxPreview);
    
    // Remove text style event handlers since there are no previews to update
    
    // Attach change handlers to button text, border, size, alignment and padding
    $('#passpro_button_text_label, #passpro_button_border_radius, #passpro_button_border_width').on('input change', updateButtonPreview);
    $('#passpro_button_width, #passpro_button_height, #passpro_button_alignment').on('input change', updateButtonPreview);
    $('#passpro_button_padding_top, #passpro_button_padding_right, #passpro_button_padding_bottom, #passpro_button_padding_left').on('input change', updateButtonPreview);
    
    // ---- Cleanup Functions ----
    function cleanupColorPickers() {
        // Remove all old HTML5 color inputs
        $('input[type="color"].passpro-color-preview').remove();
        
        // Remove old wrappers - be more specific to avoid affecting the WP color picker
        $('.passpro-color-field-wrapper').each(function() {
            var textInput = $(this).find('input[type="text"]');
            if (textInput.length > 0) {
                // Add the right class if it doesn't have it
                if (!textInput.hasClass('passpro-color-picker')) {
                    textInput.addClass('passpro-color-picker');
                }
                // Move the input outside the wrapper
                $(this).after(textInput);
                // Remove the wrapper
                $(this).remove();
            }
        });
    }
    
    // Clean up any old color picker elements
    cleanupColorPickers();
    
    // Initialize the JavaScript after cleanup
    initColorPickers();
    
    // Update active tab previews
    var activeTabId = '#' + $('.passpro-tab-content.passpro-tab-active').attr('id');
    if (activeTabId === '#passpro-tab-loginbox') {
        updateLoginBoxPreview();
    } else if (activeTabId === '#passpro-tab-button') {
        updateButtonPreview();
    }
    // No longer need to update text previews
}); 