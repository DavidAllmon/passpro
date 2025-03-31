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
    
    // Attach change event handlers to login box settings
    $('#passpro_box_bg_color, #passpro_box_border_color').wpColorPicker({
        change: function(event, ui) {
            setTimeout(updateLoginBoxPreview, 100);
        }
    });
    
    $('#passpro_box_border_width, #passpro_box_border_radius').on('input change', updateLoginBoxPreview);
    
    // Initial preview update
    setTimeout(updateLoginBoxPreview, 300);
    
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
    $('#passpro_headline_font_color, #passpro_message_font_color, #passpro_label_font_color').wpColorPicker({
        change: function(event, ui) {
            setTimeout(updateTextPreviews, 100);
        }
    });
    
    $('#passpro_headline_font_size, #passpro_message_font_size, #passpro_label_font_size').on('input change', updateTextPreviews);
    $('#passpro_headline_font_family, #passpro_message_font_family, #passpro_label_font_family').on('input change', updateTextPreviews);
    
    // Initial preview update
    setTimeout(updateTextPreviews, 300);
    
    // Update the preview of the button when settings change
    function updateButtonPreview() {
        var buttonPreview = $('#button-preview');
        var buttonText = $('#passpro_button_text').val() || 'Enter';
        var bgColor = $('#passpro_button_bg_color').val() || '#2271b1';
        var textColor = $('#passpro_button_text_color').val() || '#ffffff';
        var borderRadius = $('#passpro_button_border_radius').val() || '3';
        var borderWidth = $('#passpro_button_border_width').val() || '1';
        var borderColor = $('#passpro_button_border_color').val() || '#2271b1';
        
        // Set button text
        buttonPreview.text(buttonText);
        
        // Apply styles to preview button
        buttonPreview.css({
            'background-color': bgColor,
            'color': textColor,
            'border-radius': borderRadius + 'px',
            'border-width': borderWidth + 'px',
            'border-color': borderColor,
            'border-style': 'solid',
            'padding': '10px 20px',
            'font-size': '14px',
            'cursor': 'pointer',
            'transition': 'all 0.3s ease',
            'display': 'inline-block',
            'min-width': '100px',
            'text-align': 'center',
            'box-shadow': 'none',
            'outline': 'none'
        });
        
        // Set up hover effect using jQuery hover
        buttonPreview.off('mouseenter mouseleave');
        buttonPreview.hover(
            function() {
                // Mouse enter - hover state
                var bgHoverColor = $('#passpro_button_bg_hover_color').val() || '#135e96';
                var textHoverColor = $('#passpro_button_text_hover_color').val() || '#ffffff';
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
    
    // Attach change event handlers to button settings
    $('#passpro_button_bg_color, #passpro_button_bg_hover_color, #passpro_button_text_color, #passpro_button_text_hover_color, #passpro_button_border_color').wpColorPicker({
        change: function(event, ui) {
            setTimeout(updateButtonPreview, 100);
        }
    });
    
    $('#passpro_button_text, #passpro_button_border_radius, #passpro_button_border_width').on('input change', updateButtonPreview);
    
    // Initial button preview update
    setTimeout(updateButtonPreview, 300);
    
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