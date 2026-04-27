# News Photo Card Generator

A WordPress plugin that generates beautiful 800x800px photo cards for news posts with customizable logo, background, date, title, and featured image.

## Features

- 📸 **Automatic Photo Card Generation** - Creates stunning photo cards from your WordPress posts
- 🎨 **Customizable Design** - Upload your own logo and background image
- 🌐 **Bengali Text Support** - Full support for Bengali language in titles and dates
- 📅 **Flexible Date Formatting** - Customize date display format
- 🎯 **800x800px Output** - Perfect size for social media sharing
- ⚡ **One-Click Download** - Download button automatically appears on posts
- 🎨 **Color Customization** - Choose your preferred text color

## Installation

1. Upload the `newspaper-social-media-photo-card` folder to `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **Photo Card** in the admin menu to configure settings

## Configuration

### Admin Settings

Navigate to **Photo Card** in your WordPress admin panel to configure:

1. **Logo Image** - Upload your site logo (will appear at the top of the card)
2. **Background Image** - Upload a background image for the photo card
3. **Font Color** - Choose the color for text elements (default: white)
4. **Title Font Size** - Set the size for the news title (default: 32px)
5. **Date Font Size** - Set the size for the date text (default: 20px)
6. **Date Format** - PHP date format (default: `d F Y`)
7. **Show Download Button** - Enable/disable the download button on posts

### Photo Card Elements

Each generated photo card includes:

- **Logo** - Your site logo (centered at top)
- **Featured Image** - Post's featured image (circular, centered)
- **Date** - Post publication date (formatted as configured)
- **Title** - Post title (automatically wrapped, max 3 lines)
- **Background** - Custom background image with overlay

## Usage

### For Visitors

1. Visit any single post on your site
2. Scroll to find the "Download Photo Card" button
3. Click the button to generate and download the photo card
4. The image will automatically download to your device

### For Administrators

The plugin automatically adds a download button to all single posts. No shortcodes or manual integration required!

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- GD Library enabled (for image processing)
- Bengali font support (included with plugin)

## File Structure

```
newspaper-social-media-photo-card/
├── assets/
│   ├── css/
│   │   ├── admin.css
│   │   └── frontend.css
│   ├── js/
│   │   ├── admin.js
│   │   └── frontend.js
│   └── fonts/
│       └── NotoSansBengali-Regular.ttf
├── includes/
│   ├── class-admin-settings.php
│   ├── class-image-generator.php
│   └── class-post-integration.php
└── news-photo-card.php
```

## Function Naming Convention

All functions in this plugin start with the prefix `mjashik_` to avoid conflicts with other plugins.

## Technical Details

### Image Generation

- Uses PHP GD Library for image manipulation
- Output size: 800x800 pixels
- Format: JPEG (90% quality)
- Supports Bengali text rendering with Noto Sans Bengali font

### AJAX Integration

The plugin uses WordPress AJAX for seamless photo card generation:
- Action: `mjashik_generate_photo_card`
- Nonce verification for security
- Returns image URL on success

## Troubleshooting

### Images not generating?

1. Check if GD Library is enabled: `php -m | grep -i gd`
2. Verify write permissions on the uploads directory
3. Ensure featured images are set on posts

### Bengali text not displaying correctly?

1. Make sure the font file exists at `assets/fonts/NotoSansBengali-Regular.ttf`
2. Check PHP GD Library supports TrueType fonts

### Download button not appearing?

1. Verify the plugin is activated
2. Check "Show Download Button" is enabled in settings
3. Ensure you're viewing a single post (not archive or home page)

## Support

For issues and feature requests, please contact the plugin developer.

## License

This plugin is licensed under the GPL v2 or later.

## Changelog

### Version 1.2.0
- Added: New template system with multiple professional photo card designs (Template 1 & Template 2).
- Added: Dynamic line-colorization for multi-line titles without breaking tags.
- Fixed: Frontend CSS isolation issues ensuring identical rendering between admin and frontend.

### Version 1.1.0
- Added: Option to select from 20 bundled popular Bangla fonts.
- Added: Social Media Badges repeater option with SVG or custom image icons.

### Version 1.0.0
- Initial release
- Photo card generation with Bengali text support
- Admin settings panel
- One-click download functionality
- Customizable logo, background, and colors