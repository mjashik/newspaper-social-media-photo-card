<?php
/**
 * Template 2 – Admin Settings Preview HTML (Kalbela Style)
 *
 * Color Role Mapping (different from Template 1):
 *  Title BG      → fixed light cream (#FFF5F5)
 *  Title Text    → $prev_title_bg  (the brand/primary color, e.g. red)
 *  Logo Circle   → $prev_date_bg   (brand color, solid circle bg)
 *  Separator     → $prev_date_bg   (5px line)
 *  Date Badge    → $prev_date_bg bg, $prev_date_color text
 *  Footer Dark   → $prev_footer_bg bg, $prev_footer_color text
 *  Footer URL    → $prev_date_bg bg, $prev_date_color text
 */

if (!defined('ABSPATH')) {
    exit;
}

// Template 2 layout heights (at 800×800 card scale)
$t2_title_h       = 280;   // title area height
$t2_footer_dark_h = 65;    // dark footer row (social)
$t2_footer_url_h  = 52;    // colored footer row (URL)

// Template 2 color roles (swapped from Template 1)
$t2_title_bg        = '#FFF5F5';           // always light cream
$t2_title_text      = $prev_title_bg;      // brand color → text
$t2_separator_color = $prev_date_bg;       // brand color → separator line
$t2_logo_circle_bg  = $prev_date_bg;       // brand color → logo circle bg
$t2_url_fs          = max(14, $prev_footer_size - 2);
?>

<!-- Outer wrapper: holds the 400×400 scaled space -->
<div style="width:<?php echo esc_attr($scaled_w); ?>px; height:<?php echo esc_attr($scaled_h); ?>px; flex-shrink:0; position:relative;">
    <!-- Scale wrapper (800×800 → 50%) -->
    <div style="transform-origin:top left; transform:scale(<?php echo esc_attr($scale); ?>); width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; box-shadow:0 8px 40px rgba(0,0,0,0.22); border-radius:6px; overflow:hidden; position:absolute; top:0; left:0;">
        <!-- Card wrapper: flex column -->
        <div style="width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; position:relative; overflow:hidden; font-family:'Noto Sans Bengali',sans-serif; background:#fff; display:flex; flex-direction:column;">

            <!-- ═══════════════════════════════════
                 1. TITLE AREA — light bg, colored text
             ═══════════════════════════════════ -->
            <div style="position:relative; width:100%; flex:0 0 <?php echo esc_attr($t2_title_h); ?>px; height:<?php echo esc_attr($t2_title_h); ?>px; background:<?php echo esc_attr($t2_title_bg); ?>; overflow:hidden; box-sizing:border-box; padding:28px 38px 28px 38px; border-bottom:5px solid <?php echo esc_attr($t2_separator_color); ?>;">

                <!-- Watermark logo (faint, centered in title area) -->
                <?php if ($prev_logo): ?>
                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:<?php echo esc_attr($prev_wm_opacity); ?>; width:60%; z-index:1; pointer-events:none;">
                    <img src="<?php echo esc_url($prev_logo); ?>" style="width:100%; height:auto;" />
                </div>
                <?php endif; ?>

                <!-- Logo — top-right circle badge -->
                <?php if ($prev_logo): ?>
                <div style="position:absolute; top:16px; right:16px; width:92px; height:92px; background:<?php echo esc_attr($t2_logo_circle_bg); ?>; border-radius:50%; border:4px solid #ffffff; box-shadow:0 4px 14px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; overflow:hidden; z-index:20; box-sizing:border-box;">
                    <img src="<?php echo esc_url($prev_logo); ?>" style="width:72%; height:72%; object-fit:contain; display:block;" crossorigin="anonymous">
                </div>
                <?php else: ?>
                <div style="position:absolute; top:16px; right:16px; width:92px; height:92px; background:<?php echo esc_attr($t2_logo_circle_bg); ?>; border-radius:50%; border:4px solid #ffffff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:#fff; z-index:20; text-align:center; box-sizing:border-box;">LOGO</div>
                <?php endif; ?>

                <!-- Headline — colored bold text, center aligned -->
                <div style="position:relative; z-index:10; width:calc(100% - 116px); height:100%; display:flex; align-items:center; justify-content:center;">
                    <h1 style="margin:0; padding:0; font-size:<?php echo esc_attr($prev_title_size); ?>px; line-height:1.45; font-weight:800; color:<?php echo esc_attr($t2_title_text); ?>; font-family:<?php echo esc_attr($prev_title_font); ?>,sans-serif; word-break:break-word; text-align:center; width:100%;">
                        <?php echo esc_html($demo_title); ?>
                    </h1>
                </div>
            </div>

            <!-- ═══════════════════════════════════
                 2. IMAGE AREA — flex:1, with date badge at top center
             ═══════════════════════════════════ -->
            <div style="position:relative; width:100%; flex:1 1 auto; overflow:hidden; background:linear-gradient(135deg,#dde3ea 0%,#b2bec3 100%);">

                <!-- Date badge — top center, overlapping the separator line -->
                <div style="position:absolute; top:12px; left:50%; transform:translateX(-50%); background:<?php echo esc_attr($prev_date_bg); ?>; color:<?php echo esc_attr($prev_date_color); ?>; padding:8px 36px; font-size:<?php echo esc_attr($prev_date_size); ?>px; font-weight:700; border-radius:50px; box-shadow:0 4px 14px rgba(0,0,0,0.30); z-index:30; white-space:nowrap; font-family:<?php echo esc_attr($prev_date_font); ?>,sans-serif;">
                    <?php echo esc_html($demo_date); ?>
                </div>

                <!-- Placeholder in admin preview -->
                <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; color:rgba(0,0,0,0.12); font-size:22px; font-weight:600; padding-top:60px;">📷 Post Image</div>
            </div>

            <!-- ═══════════════════════════════════
                 3. FOOTER ROW 1 — Dark, social + arrows
             ═══════════════════════════════════ -->
            <div style="width:100%; flex:0 0 <?php echo esc_attr($t2_footer_dark_h); ?>px; height:<?php echo esc_attr($t2_footer_dark_h); ?>px; background:<?php echo esc_attr($prev_footer_bg); ?>; color:<?php echo esc_attr($prev_footer_color); ?>; display:flex; align-items:center; justify-content:center; gap:14px; font-size:<?php echo esc_attr($prev_footer_size); ?>px; font-weight:700; letter-spacing:2px; box-sizing:border-box; overflow:hidden;">

                <span style="opacity:0.65; font-size:<?php echo esc_attr($prev_footer_size - 2); ?>px;">❮❮❮</span>

                <?php if (!empty($prev_social)): ?>
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
                <?php else: ?>
                    <span><?php esc_html_e('বিস্তারিত কমেন্টে', 'newspaper-social-media-photo-card'); ?></span>
                <?php endif; ?>

                <span style="opacity:0.65; font-size:<?php echo esc_attr($prev_footer_size - 2); ?>px;">❯❯❯</span>
            </div>

            <!-- ═══════════════════════════════════
                 4. FOOTER ROW 2 — Brand color, website URL
             ═══════════════════════════════════ -->
            <div style="width:100%; flex:0 0 <?php echo esc_attr($t2_footer_url_h); ?>px; height:<?php echo esc_attr($t2_footer_url_h); ?>px; background:<?php echo esc_attr($prev_date_bg); ?>; color:<?php echo esc_attr($prev_date_color); ?>; display:flex; align-items:center; justify-content:center; font-size:<?php echo esc_attr($t2_url_fs); ?>px; font-weight:700; letter-spacing:1px; box-sizing:border-box; overflow:hidden;">
                <?php if (!empty($prev_website)): ?>
                    <span style="text-shadow:0 2px 4px rgba(0,0,0,0.2);"><?php echo esc_html($prev_website); ?></span>
                <?php endif; ?>
            </div>

        </div><!-- /.card -->
    </div><!-- /.scale-wrapper -->
</div><!-- /.outer-wrapper -->
