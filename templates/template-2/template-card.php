<?php
/**
 * Template 2 – Hidden Card HTML for html2canvas download (Kalbela Style)
 *
 * Color Role Mapping (different from Template 1):
 *  Title BG      → fixed light cream (#FFF5F5)
 *  Title Text    → $title_area_bg  (brand/primary color e.g. red)
 *  Logo Circle   → $date_bg        (brand color solid bg)
 *  Separator     → $date_bg        (5px bottom border on title)
 *  Date Badge    → $date_bg bg, $date_color text
 *  Footer Dark   → $footer_bg bg,  $footer_color text
 *  Footer URL    → $date_bg bg,    $date_color text
 */

if (!defined('ABSPATH')) {
    exit;
}

// Template 2 layout heights
$t2_title_h       = 280;
$t2_footer_dark_h = 65;
$t2_footer_url_h  = 52;

// Color role mapping
$t2_title_bg        = '#FFF5F5';      // fixed light cream for title area
$t2_title_text      = $title_area_bg; // brand color used for title text
$t2_separator_color = $date_bg;       // separator line color
$t2_logo_circle_bg  = $date_bg;       // logo circle background
$t2_url_fs          = max(14, $footer_fs - 2);

// Watermark opacity
$t2_wm_opacity = ((int) get_option('mjashik_npc_watermark_opacity', 8)) / 100;
?>
<div id='npc-hidden-container' style='position:absolute; left:-9999px; top:-9999px;'>
<div id='npc-card-capture' style='width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; position:relative; overflow:hidden; font-family:"Noto Sans Bengali",sans-serif; background:#fff; display:flex; flex-direction:column;'>

    <!-- ═══════════════════════════════════════
         1. TITLE AREA — light bg, brand-colored text
     ═══════════════════════════════════════ -->
    <div style='position:relative; width:100%; flex:0 0 <?php echo esc_attr($t2_title_h); ?>px; height:<?php echo esc_attr($t2_title_h); ?>px; background:<?php echo esc_attr($t2_title_bg); ?>; overflow:hidden; box-sizing:border-box; padding:28px 38px 28px 38px; border-bottom:5px solid <?php echo esc_attr($t2_separator_color); ?>;'>

        <!-- Watermark (faint, centered in title area) -->
        <?php if ($logo_url): ?>
        <div style='position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:<?php echo esc_attr($t2_wm_opacity); ?>; width:60%; z-index:1; pointer-events:none;'>
            <img src='<?php echo esc_url($logo_url); ?>' style='width:100%; height:auto;' crossorigin='anonymous'>
        </div>
        <?php endif; ?>

        <!-- Logo — top-right circle badge -->
        <?php if ($logo_url): ?>
        <div style='position:absolute; top:16px; right:16px; width:92px; height:92px; background:<?php echo esc_attr($t2_logo_circle_bg); ?>; border-radius:50%; border:4px solid #ffffff; box-shadow:0 4px 14px rgba(0,0,0,0.3); display:flex; align-items:center; justify-content:center; overflow:hidden; z-index:20; box-sizing:border-box;'>
            <img id='npc-logo-img' data-shadow='<?php echo esc_attr($logo_shadow); ?>' src='<?php echo esc_url($logo_url); ?>' style='width:72%; height:72%; object-fit:contain; display:block;' crossorigin='anonymous'>
        </div>
        <?php endif; ?>

        <!-- Headline — brand color bold text, centered -->
        <div style='position:relative; z-index:10; width:calc(100% - 116px); height:100%; display:flex; align-items:center; justify-content:center;'>
            <h1 style='margin:0; padding:0; font-size:<?php echo esc_attr($title_fs); ?>px; line-height:1.45; font-weight:800; color:<?php echo esc_attr($t2_title_text); ?>; font-family:<?php echo esc_attr($title_font); ?>,sans-serif; word-break:break-word; text-align:center; width:100%;'>
                <?php echo esc_html($title); ?>
            </h1>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         2. IMAGE AREA — flex:1, post thumbnail
     ═══════════════════════════════════════ -->
    <div style='position:relative; width:100%; flex:1 1 auto; overflow:hidden; <?php
        if ($thumbnail_url) {
            echo 'background-image:url(' . esc_url($thumbnail_url) . '); background-size:cover; background-position:center top;';
        } else {
            echo 'background:linear-gradient(135deg,#dde3ea,#b2bec3);';
        }
    ?>'>
        <!-- Date badge — top center of image -->
        <div style='position:absolute; top:12px; left:50%; transform:translateX(-50%); background:<?php echo esc_attr($date_bg); ?>; color:<?php echo esc_attr($date_color); ?>; padding:8px 36px; font-size:20px; font-weight:700; border-radius:50px; box-shadow:0 4px 14px rgba(0,0,0,0.30); z-index:30; white-space:nowrap; font-family:<?php echo esc_attr($date_font); ?>,sans-serif;'>
            <?php echo esc_html($date); ?>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         3. FOOTER ROW 1 — Dark, social + decorative arrows
     ═══════════════════════════════════════ -->
    <div style='width:100%; flex:0 0 <?php echo esc_attr($t2_footer_dark_h); ?>px; height:<?php echo esc_attr($t2_footer_dark_h); ?>px; background:<?php echo esc_attr($footer_bg); ?>; color:<?php echo esc_attr($footer_color); ?>; display:flex; align-items:center; justify-content:center; gap:14px; font-size:<?php echo esc_attr($footer_fs); ?>px; font-weight:700; letter-spacing:2px; box-sizing:border-box; overflow:hidden;'>

        <span style='opacity:0.65; font-size:<?php echo esc_attr($footer_fs - 2); ?>px;'>❮❮❮</span>

        <?php if (!empty($social_links)): ?>
            <div style='display:flex; align-items:center; gap:14px;'>
                <?php foreach ($social_links as $link):
                    if (empty($link['text']) && empty($link['custom_img']) && $link['type'] !== 'custom') continue;
                ?>
                    <div style='display:flex; align-items:center; gap:5px;'>
                        <?php if ($link['type'] === 'custom' && !empty($link['custom_img'])): ?>
                            <img src='<?php echo esc_url($link['custom_img']); ?>' style='width:22px; height:22px; border-radius:3px; object-fit:cover;' crossorigin='anonymous'>
                        <?php else: ?>
                            <span style='display:flex; align-items:center;'>
                                <?php echo call_user_func($mjashik_social_icon_fn, $link['type'], $footer_color); ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($link['text'])): ?>
                            <span style='font-size:<?php echo esc_attr(max(10, $footer_fs - 4)); ?>px;'><?php echo esc_html($link['text']); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <span><?php echo esc_html(__('বিস্তারিত কমেন্টে', 'newspaper-social-media-photo-card')); ?></span>
        <?php endif; ?>

        <span style='opacity:0.65; font-size:<?php echo esc_attr($footer_fs - 2); ?>px;'>❯❯❯</span>
    </div>

    <!-- ═══════════════════════════════════════
         4. FOOTER ROW 2 — Brand color, website URL
     ═══════════════════════════════════════ -->
    <div style='width:100%; flex:0 0 <?php echo esc_attr($t2_footer_url_h); ?>px; height:<?php echo esc_attr($t2_footer_url_h); ?>px; background:<?php echo esc_attr($date_bg); ?>; color:<?php echo esc_attr($date_color); ?>; display:flex; align-items:center; justify-content:center; font-size:<?php echo esc_attr($t2_url_fs); ?>px; font-weight:700; letter-spacing:1px; box-sizing:border-box; overflow:hidden;'>
        <?php if (!empty($website_url)): ?>
            <span style='text-shadow:0 2px 4px rgba(0,0,0,0.2);'><?php echo esc_html($website_url); ?></span>
        <?php endif; ?>
    </div>

</div><!-- /#npc-card-capture -->
</div><!-- /#npc-hidden-container -->
