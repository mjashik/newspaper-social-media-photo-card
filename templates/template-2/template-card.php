<?php
/**
 * Template 2 – Hidden Card HTML for html2canvas download (Kalbela Style - Refined)
 *
 * Color roles:
 *  Title BG   → fixed #FFF5F5
 *  Title Text → $title_area_bg (brand color)
 *  Logo       → $date_bg circle bg
 *  Separator  → $date_bg (8px border-bottom on title)
 *  Date Badge → straddles separator (positioned at title bottom)
 *  Footer 1   → $footer_bg dark
 *  Footer 2   → $date_bg URL row
 */

if (!defined('ABSPATH')) {
    exit;
}

$t2_title_h       = 265;
$t2_separator_h   = 8;
$t2_footer_dark_h = 68;
$t2_footer_url_h  = 52;
$t2_badge_half    = 22;  // overlap px below separator

$t2_title_bg       = '#FFF5F5';
$t2_title_text     = $title_area_bg;
$t2_separator      = $date_bg;
$t2_logo_circle_bg = $date_bg;
$t2_url_fs         = max(14, $footer_fs - 2);
$t2_wm_opacity     = ((int) get_option('mjashik_npc_watermark_opacity', 8)) / 100;
?>
<div id='npc-hidden-container' style='position:absolute; left:-9999px; top:-9999px;'>
<div id='npc-card-capture' style='width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; position:relative; overflow:hidden; font-family:"Noto Sans Bengali",sans-serif; background:#fff; display:flex; flex-direction:column;'>

    <!-- ═══════════════════════════════════════
         1. TITLE AREA
         overflow:visible — lets date badge overlap separator
     ═══════════════════════════════════════ -->
    <div style='position:relative; width:100%; flex:0 0 <?php echo esc_attr($t2_title_h); ?>px; height:<?php echo esc_attr($t2_title_h); ?>px; background:<?php echo esc_attr($t2_title_bg); ?>; box-sizing:border-box; padding:24px 36px 24px 36px; border-bottom:<?php echo esc_attr($t2_separator_h); ?>px solid <?php echo esc_attr($t2_separator); ?>; overflow:visible; z-index:10;'>

        <!-- Watermark -->
        <?php if ($logo_url): ?>
        <div style='position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:<?php echo esc_attr($t2_wm_opacity); ?>; width:60%; z-index:1; pointer-events:none; overflow:hidden;'>
            <img src='<?php echo esc_url($logo_url); ?>' style='width:100%; height:auto;' crossorigin='anonymous'>
        </div>
        <?php endif; ?>

        <!-- Logo circle — top-right -->
        <?php if ($logo_url): ?>
        <div style='position:absolute; top:14px; right:14px; width:96px; height:96px; background:<?php echo esc_attr($t2_logo_circle_bg); ?>; border-radius:50%; border:5px solid #ffffff; box-shadow:0 4px 16px rgba(0,0,0,0.28); display:flex; align-items:center; justify-content:center; overflow:hidden; z-index:20; box-sizing:border-box;'>
            <img id='npc-logo-img' data-shadow='<?php echo esc_attr($logo_shadow); ?>' src='<?php echo esc_url($logo_url); ?>' style='width:70%; height:70%; object-fit:contain; display:block;' crossorigin='anonymous'>
        </div>
        <?php endif; ?>

        <!-- Headline -->
        <div style='position:relative; z-index:10; width:calc(100% - 120px); height:100%; display:flex; align-items:center; justify-content:center;'>
            <h1 style='margin:0; padding:0; font-size:<?php echo esc_attr($title_fs); ?>px; line-height:1.4; font-weight:800; color:<?php echo esc_attr($t2_title_text); ?>; font-family:<?php echo esc_attr($title_font); ?>,sans-serif; word-break:break-word; text-align:center; width:100%; text-shadow:0 1px 0 rgba(0,0,0,0.06);'>
                <?php echo esc_html($title); ?>
            </h1>
        </div>

        <!-- Date badge — straddles the separator line -->
        <div style='position:absolute; bottom:-<?php echo esc_attr($t2_badge_half); ?>px; left:50%; transform:translateX(-50%); background:<?php echo esc_attr($date_bg); ?>; color:<?php echo esc_attr($date_color); ?>; padding:9px 38px; font-size:20px; font-weight:700; border-radius:50px; box-shadow:0 4px 16px rgba(0,0,0,0.30); z-index:40; white-space:nowrap; font-family:<?php echo esc_attr($date_font); ?>,sans-serif;'>
            <?php echo esc_html($date); ?>
        </div>
    </div>

    <!-- ═══════════════════════════════════════
         2. IMAGE AREA — flex:1 with thumbnail
     ═══════════════════════════════════════ -->
    <div style='position:relative; width:100%; flex:1 1 auto; overflow:hidden; z-index:1; <?php
        if ($thumbnail_url) {
            echo 'background-image:url(' . esc_url($thumbnail_url) . '); background-size:cover; background-position:center top;';
        } else {
            echo 'background:linear-gradient(135deg,#cdd6e0,#9fb3c8);';
        }
    ?>'>
        <!-- No overlay needed; date badge comes from title area above -->
    </div>

    <!-- ═══════════════════════════════════════
         3. FOOTER ROW 1 — Dark, arrows + social/text
     ═══════════════════════════════════════ -->
    <div style='width:100%; flex:0 0 <?php echo esc_attr($t2_footer_dark_h); ?>px; height:<?php echo esc_attr($t2_footer_dark_h); ?>px; background:<?php echo esc_attr($footer_bg); ?>; color:<?php echo esc_attr($footer_color); ?>; display:flex; align-items:center; justify-content:center; gap:12px; font-size:<?php echo esc_attr($footer_fs); ?>px; font-weight:700; letter-spacing:3px; box-sizing:border-box; overflow:hidden;'>

        <span style='opacity:0.7; letter-spacing:1px;'>&lsaquo;&lsaquo;&lsaquo;</span>

        <?php if (!empty($social_links)): ?>
            <div style='display:flex; align-items:center; gap:12px; letter-spacing:1px;'>
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
                            <span style='font-size:<?php echo esc_attr(max(10, $footer_fs - 4)); ?>px; letter-spacing:1px;'><?php echo esc_html($link['text']); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <span style='letter-spacing:2px;'><?php echo esc_html(__('বিস্তারিত কমেন্টে', 'newspaper-social-media-photo-card')); ?></span>
        <?php endif; ?>

        <span style='opacity:0.7; letter-spacing:1px;'>&rsaquo;&rsaquo;&rsaquo;</span>
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
