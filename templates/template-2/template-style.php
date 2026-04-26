<?php
/**
 * Template 2 – Admin Settings Preview HTML (Kalbela Style)
 *
 * Layout:
 *  [TITLE AREA]  — white/light bg, large colored text, logo in circle top-right
 *  [DATE BADGE]  — centered pill, overlapping title/image border
 *  [IMAGE AREA]  — flex:1, fills remaining space
 *  [FOOTER ROW1] — dark bg, social links with decorative arrows
 *  [FOOTER ROW2] — colored bg (date_bg), website URL
 *
 * Variables passed via extract() from class-admin-settings.php:
 *  $prev_logo, $prev_logo_shadow, $prev_font_color, $prev_title_bg,
 *  $prev_date_bg, $prev_date_color, $prev_footer_bg, $prev_footer_color,
 *  $prev_title_size, $prev_date_size, $prev_footer_size,
 *  $prev_title_font, $prev_date_font,
 *  $prev_website, $prev_social, $prev_wm_opacity,
 *  $demo_title, $demo_date,
 *  $card_w, $card_h, $scale, $scaled_w, $scaled_h, $footer_h,
 *  $mjashik_social_icon_fn
 */

if (!defined('ABSPATH')) {
    exit;
}

// Template 2 specific heights (px, at 800×800 scale)
$t2_title_h       = 270;  // title area
$t2_date_overlap  = 28;   // how much date badge overlaps down into image
$t2_footer_dark_h = 65;   // dark social row
$t2_footer_url_h  = 55;   // colored URL row

// font size for URL row is slightly smaller
$t2_url_fs = max(14, $prev_footer_size - 4);
?>

<!-- Outer wrapper: holds the 400×400 scaled space -->
<div style="width:<?php echo esc_attr($scaled_w); ?>px; height:<?php echo esc_attr($scaled_h); ?>px; flex-shrink:0; position:relative;">
    <!-- Scale wrapper (800×800 → 50%) -->
    <div style="transform-origin:top left; transform:scale(<?php echo esc_attr($scale); ?>); width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; box-shadow:0 8px 40px rgba(0,0,0,0.22); border-radius:6px; overflow:hidden; position:absolute; top:0; left:0;">
        <!-- Card wrapper: flex column -->
        <div style="width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; position:relative; overflow:hidden; font-family:'Noto Sans Bengali',sans-serif; background:#fff; display:flex; flex-direction:column;">

            <!-- ═══════════════════════════════════════
                 1. TITLE AREA (top, fixed height)
             ═══════════════════════════════════════ -->
            <div style="position:relative; width:100%; flex:0 0 <?php echo esc_attr($t2_title_h); ?>px; height:<?php echo esc_attr($t2_title_h); ?>px; background:<?php echo esc_attr($prev_title_bg); ?>; overflow:hidden; box-sizing:border-box; padding:30px 38px 50px 38px;">

                <!-- Watermark logo (centered in title area) -->
                <?php if ($prev_logo): ?>
                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:<?php echo esc_attr($prev_wm_opacity); ?>; width:70%; z-index:1; pointer-events:none;">
                    <img src="<?php echo esc_url($prev_logo); ?>" style="width:100%; height:auto;" />
                </div>
                <?php endif; ?>

                <!-- Logo — top-right circle badge -->
                <?php if ($prev_logo): ?>
                <div style="position:absolute; top:18px; right:18px; width:88px; height:88px; background:<?php echo esc_attr($prev_date_bg); ?>; border-radius:50%; border:4px solid #ffffff; box-shadow:0 4px 12px rgba(0,0,0,0.25); display:flex; align-items:center; justify-content:center; overflow:hidden; z-index:20; box-sizing:border-box;">
                    <img src="<?php echo esc_url($prev_logo); ?>" style="width:75%; height:75%; object-fit:contain; display:block;" crossorigin="anonymous">
                </div>
                <?php else: ?>
                <div style="position:absolute; top:18px; right:18px; width:88px; height:88px; background:<?php echo esc_attr($prev_date_bg); ?>; border-radius:50%; border:4px solid #ffffff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:#fff; z-index:20; text-align:center; box-sizing:border-box;">LOGO</div>
                <?php endif; ?>

                <!-- Headline text -->
                <div style="position:relative; z-index:10; width:calc(100% - 110px);">
                    <h1 style="margin:0; padding:0; font-size:<?php echo esc_attr($prev_title_size); ?>px; line-height:1.45; font-weight:800; color:<?php echo esc_attr($prev_font_color); ?>; font-family:<?php echo esc_attr($prev_title_font); ?>,sans-serif; word-break:break-word;">
                        <?php echo esc_html($demo_title); ?>
                    </h1>
                </div>

                <!-- Date badge — centered, overlapping bottom edge -->
                <div style="position:absolute; bottom:-<?php echo esc_attr($t2_date_overlap); ?>px; left:50%; transform:translateX(-50%); background:<?php echo esc_attr($prev_date_bg); ?>; color:<?php echo esc_attr($prev_date_color); ?>; padding:8px 34px; font-size:<?php echo esc_attr($prev_date_size); ?>px; font-weight:700; border-radius:50px; box-shadow:0 4px 14px rgba(0,0,0,0.28); z-index:30; white-space:nowrap; font-family:<?php echo esc_attr($prev_date_font); ?>,sans-serif;">
                    <?php echo esc_html($demo_date); ?>
                </div>
            </div>

            <!-- ═══════════════════════════════════════
                 2. IMAGE AREA (flex:1, fills remaining)
             ═══════════════════════════════════════ -->
            <div style="position:relative; width:100%; flex:1 1 auto; overflow:hidden; background:linear-gradient(135deg,#dde3ea 0%,#b2bec3 100%);">
                <!-- Placeholder text in preview (no real image in admin preview) -->
                <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; color:rgba(0,0,0,0.15); font-size:22px; font-weight:600; letter-spacing:1px;">📷 Post Image</div>
            </div>

            <!-- ═══════════════════════════════════════
                 3. FOOTER ROW 1 — Dark, social/decorative
             ═══════════════════════════════════════ -->
            <div style="width:100%; flex:0 0 <?php echo esc_attr($t2_footer_dark_h); ?>px; height:<?php echo esc_attr($t2_footer_dark_h); ?>px; background:<?php echo esc_attr($prev_footer_bg); ?>; color:<?php echo esc_attr($prev_footer_color); ?>; display:flex; align-items:center; justify-content:center; gap:16px; font-size:<?php echo esc_attr($prev_footer_size); ?>px; font-weight:700; letter-spacing:2px; box-sizing:border-box; overflow:hidden;">

                <?php if (!empty($prev_social)): ?>
                    <!-- Decorative left arrows -->
                    <span style="opacity:0.6; font-size:<?php echo esc_attr($prev_footer_size - 2); ?>px;">❮❮❮</span>

                    <!-- Social badges -->
                    <div style="display:flex; align-items:center; gap:14px;">
                        <?php foreach ($prev_social as $link):
                            if (empty($link['text']) && empty($link['custom_img']) && $link['type'] !== 'custom') continue;
                        ?>
                            <div style="display:flex; align-items:center; gap:5px;">
                                <?php if ($link['type'] === 'custom' && !empty($link['custom_img'])): ?>
                                    <img src="<?php echo esc_url($link['custom_img']); ?>" style="width:22px; height:22px; border-radius:3px; object-fit:cover;" crossorigin="anonymous">
                                <?php else: ?>
                                    <span style="display:flex; align-items:center;">
                                        <?php echo $mjashik_social_icon_fn($link['type'], $prev_footer_color); ?>
                                    </span>
                                <?php endif; ?>
                                <?php if (!empty($link['text'])): ?>
                                    <span style="font-size:<?php echo esc_attr(max(10, $prev_footer_size - 4)); ?>px;"><?php echo esc_html($link['text']); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Decorative right arrows -->
                    <span style="opacity:0.6; font-size:<?php echo esc_attr($prev_footer_size - 2); ?>px;">❯❯❯</span>

                <?php elseif (!empty($prev_website)): ?>
                    <!-- Fallback if no social links: decorative text -->
                    <span style="opacity:0.6; font-size:<?php echo esc_attr($prev_footer_size - 2); ?>px;">❮❮❮</span>
                    <span><?php esc_html_e('বিস্তারিত কমেন্টে', 'newspaper-social-media-photo-card'); ?></span>
                    <span style="opacity:0.6; font-size:<?php echo esc_attr($prev_footer_size - 2); ?>px;">❯❯❯</span>
                <?php else: ?>
                    <span style="opacity:0.6; font-size:<?php echo esc_attr($prev_footer_size - 2); ?>px;">❮❮❮</span>
                    <span><?php esc_html_e('বিস্তারিত কমেন্টে', 'newspaper-social-media-photo-card'); ?></span>
                    <span style="opacity:0.6; font-size:<?php echo esc_attr($prev_footer_size - 2); ?>px;">❯❯❯</span>
                <?php endif; ?>
            </div>

            <!-- ═══════════════════════════════════════
                 4. FOOTER ROW 2 — Colored, website URL
             ═══════════════════════════════════════ -->
            <div style="width:100%; flex:0 0 <?php echo esc_attr($t2_footer_url_h); ?>px; height:<?php echo esc_attr($t2_footer_url_h); ?>px; background:<?php echo esc_attr($prev_date_bg); ?>; color:<?php echo esc_attr($prev_date_color); ?>; display:flex; align-items:center; justify-content:center; font-size:<?php echo esc_attr($t2_url_fs); ?>px; font-weight:700; letter-spacing:1px; box-sizing:border-box; overflow:hidden;">
                <?php if (!empty($prev_website)): ?>
                    <span style="text-shadow:0 2px 4px rgba(0,0,0,0.2);"><?php echo esc_html($prev_website); ?></span>
                <?php endif; ?>
            </div>

        </div><!-- /.card -->
    </div><!-- /.scale-wrapper -->
</div><!-- /.outer-wrapper -->
