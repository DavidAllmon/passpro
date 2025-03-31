=== PassPro ===
Contributors: davidallmon
Donate link: https://techitdave.com/
Tags: password, protection, security, site protection, password protection
Requires at least: 5.2
Tested up to: 6.5
Requires PHP: 7.2
Stable tag: 1.0.8
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Password protect your entire WordPress site with a single password or multiple passwords, with extensive customization options.

== Description ==

PassPro is a powerful WordPress plugin that lets you protect your entire site behind a password wall. Visitors must enter the correct password to access any page on your site.

Unlike other password protection plugins, PassPro offers extensive customization options and advanced features to create a professional, branded experience.

### Key Features

* **Full Site Protection** - Protect your entire WordPress site with a password
* **Multiple Passwords** - Create and manage multiple passwords with different access rules
* **Customizable Login Page** - Fully customize the appearance of your login screen
* **IP Whitelist** - Allow specific IP addresses to bypass the password requirement
* **Administrator Access** - Logged-in administrators can always access the site
* **Expiring Passwords** - Create passwords with expiration dates
* **Limited-Use Passwords** - Set passwords to expire after a certain number of uses
* **Bypass URLs** - Create special URLs that bypass the password requirement
* **Custom Branding** - Add your own logo, colors, and styling to match your brand
* **Session Management** - Control how long passwords remain valid
* **Logout Button** - Optionally show a logout button for visitors to end their session
* **Security Enhancements** - Block chat widgets and third-party scripts on the login page

### Use Cases

* Staging/development sites that need client approval
* Event websites that should only be accessible to attendees
* Membership sites during pre-launch
* Private client portals
* Preventing search engines from indexing a site in development

### Professional Support

For questions, feature requests, or support needs, please visit our [support page](https://techitdave.com/).

== Installation ==

1. Upload the `passpro` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to PassPro > Settings in your WordPress admin area
4. Enable password protection and set your password
5. Customize the login page appearance to match your brand
6. Save your settings

== Frequently Asked Questions ==

= Will logged-in administrators be able to access the site? =

Yes, users with administrator privileges (manage_options capability) will automatically bypass the password protection.

= Can I create multiple passwords? =

Yes, PassPro allows you to create and manage multiple passwords. Each password can have its own rules, such as expiration dates and usage limits.

= Can I whitelist certain IP addresses? =

Yes, you can add IP addresses to an allowed list, and visitors from those IPs will bypass the password requirement.

= Can I customize the appearance of the login page? =

Absolutely! PassPro offers extensive customization options including custom logos, background colors, text colors, fonts, padding, and more to create a professional, branded login experience.

= Will the plugin slow down my site? =

No, PassPro is designed to be lightweight and efficient. It only loads resources when needed and performs minimal database queries.

= Can I create temporary passwords that expire? =

Yes, you can create passwords with expiration dates or set them to expire after a certain number of uses.

= Is it compatible with caching plugins? =

Yes, PassPro works with most popular caching plugins.

== Screenshots ==

1. Password protection login screen with custom branding
2. Admin settings page for configuring protection options
3. Customization options for the login page appearance
4. Multiple password management interface
5. Advanced options for IP whitelisting and session control

== Changelog ==

= 1.0.8 =
* Fixed email input field styling to match other form fields
* Improved form field consistency across the password management interface
* Enhanced user experience with consistent input field sizes

= 1.0.7 =
* Improved Text Styles tab with dropdown font selectors for easier font selection
* Fixed WordPress color picker functionality in the Appearance tab
* Removed text preview sections from the Text Styles settings to streamline the interface
* Fixed z-index issues with color pickers appearing behind other elements
* Optimized admin JavaScript for better color picker handling

= 1.0.6 =
* Added option to toggle frontend logout button visibility (General Settings)
* Moved frontend logout button to bottom-left and reduced size
* Added option to set text alignment (Left, Center, Right) for the login page message (Text Styles)

= 1.0.1 =
* Added enhanced security features to block chat widgets and floating elements on the password page
* Added JavaScript to intercept and prevent loading of third-party scripts during password protection
* Added CSS to hide any chat widgets that might bypass script blocking
* Improved security by dequeuing chat scripts and styles when protection is active

== Upgrade Notice ==

= 1.0.8 =
This update fixes styling issues with the email input field and improves the overall consistency of the form interface in the password management page.

= 1.0.7 =
This update improves the admin interface with dropdown font selectors, fixes color picker issues, and streamlines the settings page by removing unnecessary previews.

= 1.0.6 =
Introduces new options for controlling the frontend logout button visibility and message text alignment.

= 1.0.1 =
Security enhancement: This update blocks chat widgets and third-party scripts on the password page to prevent potential information leakage. 