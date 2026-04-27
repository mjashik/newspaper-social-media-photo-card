<?php
/**
 * Template 2 – Admin Settings Preview HTML (Kalbela Style - Refined)
 *
 * Key design notes:
 *  - Title BG:    fixed light cream (#FFF5F5)
 *  - Title Text:  $prev_title_bg color (brand red), large bold, center-aligned
 *  - Logo:        top-right circular badge in $prev_date_bg color
 *  - Separator:   8px solid $prev_date_bg line at bottom of title area
 *  - Date badge:  straddles the separator (positioned in title area bottom)
 *  - Image:       flex:1, fills remaining space
 *  - Footer dark: $prev_footer_bg, decorative arrows + social/text
 *  - Footer URL:  $prev_date_bg, website URL
 */

if (!defined('ABSPATH')) {
    exit;
}

// Layout heights (at 800×800 card scale)
$t2_title_h = 265;
$t2_separator_h = 8;
$t2_footer_dark_h = 65;   // black overlay on image
$t2_footer_url_h = 90;   // red footer: social + URL
$t2_badge_half = 22;

// Color roles (swapped vs Template 1)
$t2_title_bg = '#FFF5F5';          // always light cream
$t2_title_text = $prev_title_bg;     // brand color → title text
$t2_separator = $prev_date_bg;      // separator line
$t2_logo_circle_bg = $prev_date_bg;      // logo circle fill
$t2_url_fs = max(14, $prev_footer_size - 2);
?>

<?php
// Split title: up to 3 lines separated by |
// Line1=red, Line2=black, Line3=footer_bg color
$t2_title_parts = explode('|', $demo_title);
$t2_line1 = trim($t2_title_parts[0]);
$t2_line2 = isset($t2_title_parts[1]) ? trim($t2_title_parts[1]) : '';
$t2_line3 = isset($t2_title_parts[2]) ? trim($t2_title_parts[2]) : '';
$t2_line3_color = $prev_footer_bg; // 3rd line uses footer bg color
?>
<!-- Outer wrapper -->
<div
    style="width:<?php echo esc_attr($scaled_w); ?>px; height:<?php echo esc_attr($scaled_h); ?>px; flex-shrink:0; position:relative;">
    <!-- Scale wrapper 800×800 → 50% -->
    <div
        style="transform-origin:top left; transform:scale(<?php echo esc_attr($scale); ?>); width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; box-shadow:0 8px 40px rgba(0,0,0,0.22); border-radius:6px; overflow:hidden; position:absolute; top:0; left:0;">
        <!-- Card -->
        <div
            style="width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; position:relative; overflow:hidden; font-family:'Noto Sans Bengali',sans-serif; background:#fff; display:flex; flex-direction:column;">

            <!-- ═══════════════════════════════════════
                 1. TITLE AREA — centered layout:
                    Title (red+black split)
                    Logo circle (centered, below title)
                    Date badge (straddles separator)
             ═══════════════════════════════════════ -->
            <div
                style="position:relative; width:100%; flex:0 0 <?php echo esc_attr($t2_title_h); ?>px; height:<?php echo esc_attr($t2_title_h); ?>px; background:<?php echo esc_attr($t2_title_bg); ?>; box-sizing:border-box; padding:0; border-bottom:<?php echo esc_attr($t2_separator_h); ?>px solid <?php echo esc_attr($t2_separator); ?>; overflow:visible; z-index:10; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0;">

                <!-- Watermark (full-area, behind everything) -->
                <?php if ($prev_logo): ?>
                    <div
                        style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:<?php echo esc_attr($prev_wm_opacity); ?>; width:60%; z-index:1; pointer-events:none; overflow:hidden;">
                        <img src="<?php echo esc_url($prev_logo); ?>" style="width:100%; height:auto;" />
                    </div>
                <?php endif; ?>

                <!-- Headline — full width, centered, 10px top/bottom padding -->
                <!-- Line1=red, Line2=black, Line3=footer_bg color -->
                <div style="position:relative; z-index:10; width:100%; padding:10px 30px 10px 30px; box-sizing:border-box; text-align:center;">
                    <h1 style="margin:0; padding:0; font-size:<?php echo esc_attr($prev_title_size); ?>px; line-height:1.35; font-weight:800; color:<?php echo esc_attr($t2_title_text); ?>; font-family:<?php echo esc_attr($prev_title_font); ?>,sans-serif; word-break:break-word; text-align:center; width:100%;">
                        <div><?php echo esc_html($t2_line1); ?></div>
                        <?php if ($t2_line2): ?>
                            <div style="color:#000000; margin-top:6px;"><?php echo esc_html($t2_line2); ?></div>
                        <?php endif; ?>
                        <?php if ($t2_line3): ?>
                            <div style="color:<?php echo esc_attr($t2_line3_color); ?>; margin-top:6px;"><?php echo esc_html($t2_line3); ?></div>
                        <?php endif; ?>
                    </h1>
                </div>

                <!-- Logo — height fixed 45px, width auto; shadow via CSS filter in preview -->
                <?php if ($prev_logo): ?>
                    <img src="<?php echo esc_url($prev_logo); ?>"
                        style="position:relative; z-index:20; height:45px; width:auto; display:block; flex-shrink:0; filter:drop-shadow(0 0 20px <?php echo esc_attr($prev_logo_shadow); ?>) drop-shadow(0 0 10px <?php echo esc_attr($prev_logo_shadow); ?>);"
                        crossorigin="anonymous">
                <?php else: ?>
                    <span
                        style="position:relative; z-index:20; font-size:11px; font-weight:700; color:<?php echo esc_attr($t2_separator); ?>; opacity:0.5; flex-shrink:0;">LOGO</span>
                <?php endif; ?>

                <!-- Date badge — straddles the separator line -->
                <div
                    style="position:absolute; bottom:-<?php echo esc_attr($t2_badge_half); ?>px; left:50%; transform:translateX(-50%); background:<?php echo esc_attr($prev_date_bg); ?>; color:<?php echo esc_attr($prev_date_color); ?>; padding:9px 38px; font-size:<?php echo esc_attr($prev_date_size); ?>px; font-weight:700; border-radius:50px; box-shadow:0 4px 16px rgba(0,0,0,0.30); z-index:40; white-space:nowrap; font-family:<?php echo esc_attr($prev_date_font); ?>,sans-serif;">
                    <?php echo esc_html($demo_date); ?>
                </div>
            </div>


            <!-- ═══════════════════════════════════════
                 2. IMAGE AREA — flex:1, with overlay at bottom
             ═══════════════════════════════════════ -->
            <div
                style="position:relative; width:100%; flex:1 1 auto; overflow:hidden; background:linear-gradient(135deg,#cdd6e0 0%,#9fb3c8 100%); z-index:1;">

                <!-- Placeholder -->
                <div
                    style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.35); font-size:20px; font-weight:600; padding-bottom:<?php echo esc_attr($t2_footer_dark_h); ?>px;">
                    📷 Post Image</div>

                <!-- « « « বিস্তারিত কমেন্টে » » » — Watermark Style -->
                <div
                    style="position:absolute; bottom:20px; left:50%; transform:translateX(-50%); background:rgba(0,0,0,0.45); color:#ffffff; display:flex; align-items:center; justify-content:center; gap:12px; font-size:<?php echo esc_attr($prev_footer_size); ?>px; font-weight:700; padding:6px 28px; border-radius:50px; white-space:nowrap; text-shadow:0 2px 4px rgba(0,0,0,0.9); z-index:10; box-sizing:border-box; backdrop-filter:blur(2px); border:1px solid rgba(255,255,255,0.1);">
                    <span style="opacity:0.9; letter-spacing:0;"><?php echo esc_html('« « «'); ?></span>
                    <span
                        style="letter-spacing:1px;"><?php esc_html_e('বিস্তারিত কমেন্টে', 'newspaper-social-media-photo-card'); ?></span>
                    <span style="opacity:0.9; letter-spacing:0;"><?php echo esc_html('» » »'); ?></span>
                </div>
            </div>

            <!-- RED FOOTER: social links (top) + website URL (bottom) -->
            <div
                style="width:100%; flex:0 0 <?php echo esc_attr($t2_footer_url_h); ?>px; height:<?php echo esc_attr($t2_footer_url_h); ?>px; background:<?php echo esc_attr($prev_date_bg); ?>; color:<?php echo esc_attr($prev_date_color); ?>; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:6px; box-sizing:border-box; overflow:hidden; padding:8px 20px;">

                <!-- Social links row -->
                <?php if (!empty($prev_social)): ?>
                    <div style="display:flex; align-items:center; justify-content:center; gap:16px; flex-wrap:nowrap;">
                        <?php foreach ($prev_social as $link):
                            if (empty($link['text']) && empty($link['custom_img']) && $link['type'] !== 'custom')
                                continue;
                            ?>
                            <div style="display:flex; align-items:center; gap:5px;">
                                <?php if ($link['type'] === 'custom' && !empty($link['custom_img'])): ?>
                                    <img src="<?php echo esc_url($link['custom_img']); ?>"
                                        style="width:20px; height:20px; border-radius:3px; object-fit:cover;"
                                        crossorigin="anonymous">
                                <?php else: ?>
                                    <span style="display:flex; align-items:center;">
                                        <?php echo $mjashik_social_icon_fn($link['type'], $prev_date_color); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($link['text'])): ?>
                                    <span
                                        style="font-size:<?php echo esc_attr(max(12, $prev_footer_size - 6)); ?>px; font-weight:600;"><?php echo esc_html($link['text']); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Website URL row -->
                <?php if (!empty($prev_website)): ?>
                    <div
                        style="font-size:<?php echo esc_attr($t2_url_fs); ?>px; font-weight:700; letter-spacing:1px; text-shadow:0 2px 4px rgba(0,0,0,0.2);">
                        <?php echo esc_html($prev_website); ?>
                    </div>
                <?php endif; ?>

            </div>

        </div><!-- /.card -->
    </div><!-- /.scale-wrapper -->
</div><!-- /.outer-wrapper -->