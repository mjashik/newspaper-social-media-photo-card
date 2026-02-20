=== Newspaper Social Media Photo Card ===
Contributors: mjashik
Tags: news card, photo card, newspaper photo card, newspaper image generator, newspaper thumbnail, newspaper social media card
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Generate beautiful, customizable photo cards for your news and blog posts directly from the WordPress editor.

== Description ==

Newspaper Social Media Photo Card allows you to easily generate beautiful, high-quality photo cards for your articles with a single click. Ideal for news portals, blogs, and social media marketers who want to share visually appealing featured images with titles and branding.

### Features
* **One-Click Generation**: Generate a photo card directly from the WordPress post editor screen.
* **Custom Logo/Watermark**: Upload your logo and set the watermark opacity dynamically.
* **Background Customization**: Choose a specific background image or use dynamic gradients.
* **Color Controls**: Full color picker support for text, date badges, title area backgrounds, and footers.
* **Responsive Layout**: Automatically adjusts font sizing and image scaling depending on the length of your post title.
* **High Quality PNG**: Downloads high-resolution, responsive PNG images perfect for Facebook, Twitter, and LinkedIn.
* **Live Admin Preview**: See exactly how your photo card looks right from the plugin settings page.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/newspaper-social-media-photo-card` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to `Settings -> Photo Card` to upload your logo and customize the colors, backgrounds, and fonts.
4. Go to any Post edit screen. Below the title, you will see a "Download Photo Card" button.

== Frequently Asked Questions ==

= Does this plugin require Imagick or GD Library? =
No. This plugin uses modern browser-side rendering (`html2canvas`) to guarantee perfect Bengali (and complex script) font rendering without server-side font installation issues. 

= Can I use it on Custom Post Types? =
Currently, the button appears on standard WordPress "Posts".

== Screenshots ==

1. The admin settings page with the live preview.
2. The Download Photo Card button in the WordPress editor.
3. Example of a generated News Photo Card.

== Changelog ==

= 1.0.0 =
* Initial release.
* Features: Admin settings, live preview, color pickers, watermark opacity, html2canvas browser-side generation.
