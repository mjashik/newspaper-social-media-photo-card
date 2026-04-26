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
$t2_title_h       = 265;   // title area
$t2_separator_h   = 8;     // separator line thickness
$t2_footer_dark_h = 68;    // dark social/text row
$t2_footer_url_h  = 52;    // colored URL row
$t2_badge_half    = 22;    // px by which date badge overlaps (half of badge height approx)

// Color roles (swapped vs Template 1)
$t2_title_bg       = '#FFF5F5';          // always light cream
$t2_title_text     = $prev_title_bg;     // brand color → title text
$t2_separator      = $prev_date_bg;      // separator line
$t2_logo_circle_bg = $prev_date_bg;      // logo circle fill
$t2_url_fs         = max(14, $prev_footer_size - 2);
?>

<!-- Outer wrapper -->
<div style="width:<?php echo esc_attr($scaled_w); ?>px; height:<?php echo esc_attr($scaled_h); ?>px; flex-shrink:0; position:relative;">
    <!-- Scale wrapper 800×800 → 50% -->
    <div style="transform-origin:top left; transform:scale(<?php echo esc_attr($scale); ?>); width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; box-shadow:0 8px 40px rgba(0,0,0,0.22); border-radius:6px; overflow:hidden; position:absolute; top:0; left:0;">
        <!-- Card -->
        <div style="width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; position:relative; overflow:hidden; font-family:'Noto Sans Bengali',sans-serif; background:#fff; display:flex; flex-direction:column;">

            <!-- ═══════════════════════════════════════
                 1. TITLE AREA
                 overflow:visible so date badge can overlap separator
             ═══════════════════════════════════════ -->
            <div style="position:relative; width:100%; flex:0 0 <?php echo esc_attr($t2_title_h); ?>px; height:<?php echo esc_attr($t2_title_h); ?>px; background:<?php echo esc_attr($t2_title_bg); ?>; box-sizing:border-box; padding:24px 36px 24px 36px; border-bottom:<?php echo esc_attr($t2_separator_h); ?>px solid <?php echo esc_attr($t2_separator); ?>; overflow:visible; z-index:10;">

                <!-- Watermark -->
                <?php if ($prev_logo): ?>
                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:<?php echo esc_attr($prev_wm_opacity); ?>; width:60%; z-index:1; pointer-events:none; overflow:hidden;">
                    <img src="<?php echo esc_url($prev_logo); ?>" style="width:100%; height:auto;" />
                </div>
                <?php endif; ?>

                <!-- Logo circle — top-right -->
                <?php if ($prev_logo): ?>
                <div style="position:absolute; top:14px; right:14px; width:96px; height:96px; background:<?php echo esc_attr($t2_logo_circle_bg); ?>; border-radius:50%; border:5px solid #ffffff; box-shadow:0 4px 16px rgba(0,0,0,0.28); display:flex; align-items:center; justify-content:center; overflow:hidden; z-index:20; box-sizing:border-box;">
                    <img src="<?php echo esc_url($prev_logo); ?>" style="width:70%; height:70%; object-fit:contain; display:block;" crossorigin="anonymous">
                </div>
                <?php else: ?>
                <div style="position:absolute; top:14px; right:14px; width:96px; height:96px; background:<?php echo esc_attr($t2_logo_circle_bg); ?>; border-radius:50%; border:5px solid #fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:#fff; z-index:20; text-align:center; box-sizing:border-box; padding:6px;">LOGO</div>
                <?php endif; ?>

                <!-- Headline -->
                <div style="position:relative; z-index:10; width:calc(100% - 120px); height:100%; display:flex; align-items:center; justify-content:center;">
                    <h1 style="margin:0; padding:0; font-size:<?php echo esc_attr($prev_title_size); ?>px; line-height:1.4; font-weight:800; color:<?php echo esc_attr($t2_title_text); ?>; font-family:<?php echo esc_attr($prev_title_font); ?>,sans-serif; word-break:break-word; text-align:center; width:100%; text-shadow:0 1px 0 rgba(0,0,0,0.06);">
                        <?php echo esc_html($demo_title); ?>
                    </h1>
                </div>

                <!-- Date badge — straddles the separator line -->
                <div style="position:absolute; bottom:-<?php echo esc_attr($t2_badge_half); ?>px; left:50%; transform:translateX(-50%); background:<?php echo esc_attr($prev_date_bg); ?>; color:<?php echo esc_attr($prev_date_color); ?>; padding:9px 38px; font-size:<?php echo esc_attr($prev_date_size); ?>px; font-weight:700; border-radius:50px; box-shadow:0 4px 16px rgba(0,0,0,0.30); z-index:40; white-space:nowrap; font-family:<?php echo esc_attr($prev_date_font); ?>,sans-serif;">
                    <?php echo esc_html($demo_date); ?>
                </div>
            </div>

            <!-- ═══════════════════════════════════════
                 2. IMAGE AREA — flex:1, with overlay at bottom
             ═══════════════════════════════════════ -->
            <div style="position:relative; width:100%; flex:1 1 auto; overflow:hidden; background:linear-gradient(135deg,#cdd6e0 0%,#9fb3c8 100%); z-index:1;">

                <!-- Placeholder -->
                <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; color:rgba(255,255,255,0.35); font-size:20px; font-weight:600; padding-bottom:<?php echo esc_attr($t2_footer_dark_h); ?>px;">📷 Post Image</div>

                <!-- ‹‹‹ বিস্তারিত কমেন্টে ››› — black overlay at image bottom -->
                <div style="position:absolute; bottom:0; left:0; width:100%; height:<?php echo esc_attr($t2_footer_dark_h); ?>px; background:#000000; color:#ffffff; display:flex; align-items:center; justify-content:center; gap:12px; font-size:<?php echo esc_attr($prev_footer_size); ?>px; font-weight:700; letter-spacing:3px; box-sizing:border-box; overflow:hidden;">

                    <span style="opacity:0.7; letter-spacing:1px;">&lsaquo;&lsaquo;&lsaquo;</span>

                    <?php if (!empty($prev_social)): ?>
                        <div style="display:flex; align-items:center; gap:12px; letter-spacing:1px;">
                            <?php foreach ($prev_social as $link):
                                if (empty($link['text']) && empty($link['custom_img']) && $link['type'] !== 'custom') continue;
                            ?>
                                <div style="display:flex; align-items:center; gap:5px;">
                                    <?php if ($link['type'] === 'custom' && !empty($link['custom_img'])): ?>
                                        <img src="<?php echo esc_url($link['custom_img']); ?>" style="width:22px; height:22px; border-radius:3px; object-fit:cover;" crossorigin="anonymous">
                                    <?php else: ?>
                                        <span style="display:flex; align-items:center;">
                                            <?php echo $mjashik_social_icon_fn($link['type'], '#ffffff'); ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($link['text'])): ?>
                                        <span style="font-size:<?php echo esc_attr(max(10, $prev_footer_size - 4)); ?>px; letter-spacing:1px;"><?php echo esc_html($link['text']); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <span style="letter-spacing:2px;"><?php esc_html_e('বিস্তারিত কমেন্টে', 'newspaper-social-media-photo-card'); ?></span>
                    <?php endif; ?>

                    <span style="opacity:0.7; letter-spacing:1px;">&rsaquo;&rsaquo;&rsaquo;</span>
                </div>
            </div>

            <!-- ═══════════════════════════════════════
                 4. FOOTER ROW 2 — Brand color, URL
             ═══════════════════════════════════════ -->
            <div style="width:100%; flex:0 0 <?php echo esc_attr($t2_footer_url_h); ?>px; height:<?php echo esc_attr($t2_footer_url_h); ?>px; background:<?php echo esc_attr($prev_date_bg); ?>; color:<?php echo esc_attr($prev_date_color); ?>; display:flex; align-items:center; justify-content:center; font-size:<?php echo esc_attr($t2_url_fs); ?>px; font-weight:700; letter-spacing:1px; box-sizing:border-box; overflow:hidden;">
                <?php if (!empty($prev_website)): ?>
                    <span style="text-shadow:0 2px 4px rgba(0,0,0,0.2);"><?php echo esc_html($prev_website); ?></span>
                <?php endif; ?>
            </div>

        </div><!-- /.card -->
    </div><!-- /.scale-wrapper -->
</div><!-- /.outer-wrapper -->
