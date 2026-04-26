<?php
/**
 * Template 2 – Hidden Card HTML for html2canvas download (Kalbela Style)
 *
 * Layout:
 *  [TITLE AREA]  — colored bg, large bold text, logo top-right circle, watermark
 *  [DATE BADGE]  — centered pill overlapping title/image border
 *  [IMAGE AREA]  — flex:1 with post thumbnail
 *  [FOOTER ROW1] — dark bg (footer_bg), social links with decorative arrows
 *  [FOOTER ROW2] — colored bg (date_bg), website URL
 *
 * Variables available via extract():
 *  $logo_url, $logo_shadow, $font_color, $title_area_bg,
 *  $date_bg, $date_color, $footer_bg, $footer_color,
 *  $date_format, $website_url, $title_fs, $footer_fs,
 *  $title_font, $date_font, $social_links,
 *  $thumbnail_url, $date, $title,
 *  $card_w, $card_h, $footer_h,
 *  $mjashik_social_icon_fn
 */

if (!defined('ABSPATH')) {
    exit;
}

// Template 2 specific heights
$t2_title_h       = 270;
$t2_date_overlap  = 28;
$t2_footer_dark_h = 65;
$t2_footer_url_h  = 55;
$t2_url_fs        = max(14, $footer_fs - 4);
$t2_wm_opacity    = ((int) get_option('mjashik_npc_watermark_opacity', 8)) / 100;
?>
<div id='npc-hidden-container' style='position:absolute; left:-9999px; top:-9999px;'>
<div id='npc-card-capture' style='width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; position:relative; overflow:hidden; font-family:"Noto Sans Bengali",sans-serif; background:#fff; display:flex; flex-direction:column;'>

    <!-- ═══════════════════════════════════════
         1. TITLE AREA (top, fixed height)
     ═══════════════════════════════════════ -->
    <div style='position:relative; width:100%; flex:0 0 <?php echo esc_attr($t2_title_h); ?>px; height:<?php echo esc_attr($t2_title_h); ?>px; background:<?php echo esc_attr($title_area_bg); ?>; overflow:hidden; box-sizing:border-box; padding:30px 38px 30px 38px;'>

        <!-- Watermark (centered in title area) -->
        <?php if ($logo_url): ?>
        <div style='position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:<?php echo esc_attr($t2_wm_opacity); ?>; width:70%; z-index:1; pointer-events:none;'>
            <img src='<?php echo esc_url($logo_url); ?>' style='width:100%; height:auto;' crossorigin='anonymous'>
        </div>
        <?php endif; ?>

        <!-- Logo — top-right circle badge -->
        <?php if ($logo_url): ?>
        <div style='position:absolute; top:18px; right:18px; width:88px; height:88px; background:<?php echo esc_attr($date_bg); ?>; border-radius:50%; border:4px solid #ffffff; box-shadow:0 4px 12px rgba(0,0,0,0.25); display:flex; align-items:center; justify-content:center; overflow:hidden; z-index:20; box-sizing:border-box;'>
            <img id='npc-logo-img' data-shadow='<?php echo esc_attr($logo_shadow); ?>' src='<?php echo esc_url($logo_url); ?>' style='width:75%; height:75%; object-fit:contain; display:block;' crossorigin='anonymous'>
        </div>
        <?php endif; ?>

        <!-- Headline text -->
        <div style='position:relative; z-index:10; width:calc(100% - 110px);'>
            <h1 style='margin:0; padding:0; font-size:<?php echo esc_attr($title_fs); ?>px; line-height:1.45; font-weight:800; color:<?php echo esc_attr($font_color); ?>; font-family:<?php echo esc_attr($title_font); ?>,sans-serif; word-break:break-word;'>
                <?php echo esc_html($title); ?>
            </h1>
        </div>
    </div>


    <!-- IMAGE AREA (flex:1 fills remaining) -->
    <div style='position:relative; width:100%; flex:1 1 auto; overflow:hidden; <?php
        if ($thumbnail_url) {
            echo 'background-image:url(' . esc_url($thumbnail_url) . '); background-size:cover; background-position:center top;';
        } else {
            echo 'background:linear-gradient(135deg,#dde3ea,#b2bec3);';
        }
    ?>'>
        <!-- Date badge — top center of image area -->
        <div style='position:absolute; top:18px; left:50%; transform:translateX(-50%); background:<?php echo esc_attr($date_bg); ?>; color:<?php echo esc_attr($date_color); ?>; padding:8px 34px; font-size:20px; font-weight:700; border-radius:50px; box-shadow:0 4px 14px rgba(0,0,0,0.28); z-index:30; white-space:nowrap; font-family:<?php echo esc_attr($date_font); ?>,sans-serif;'>
            <?php echo esc_html($date); ?>
        </div>

        <!-- Subtle top fade -->
        <div style='position:absolute; top:0; left:0; width:100%; height:50px; background:linear-gradient(to bottom,rgba(0,0,0,0.08),transparent); z-index:5;'></div>
    </div>

    <!-- ═══════════════════════════════════════
         3. FOOTER ROW 1 — Dark bg, social links
     ═══════════════════════════════════════ -->
    <div style='width:100%; flex:0 0 <?php echo esc_attr($t2_footer_dark_h); ?>px; height:<?php echo esc_attr($t2_footer_dark_h); ?>px; background:<?php echo esc_attr($footer_bg); ?>; color:<?php echo esc_attr($footer_color); ?>; display:flex; align-items:center; justify-content:center; gap:16px; font-size:<?php echo esc_attr($footer_fs); ?>px; font-weight:700; letter-spacing:2px; box-sizing:border-box; overflow:hidden;'>

        <?php if (!empty($social_links)): ?>

            <span style='opacity:0.6; font-size:<?php echo esc_attr($footer_fs - 2); ?>px;'>❮❮❮</span>

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

            <span style='opacity:0.6; font-size:<?php echo esc_attr($footer_fs - 2); ?>px;'>❯❯❯</span>

        <?php else: ?>
            <span style='opacity:0.6; font-size:<?php echo esc_attr($footer_fs - 2); ?>px;'>❮❮❮</span>
            <span><?php echo esc_html(!empty($website_url) ? $website_url : 'বিস্তারিত কমেন্টে'); ?></span>
            <span style='opacity:0.6; font-size:<?php echo esc_attr($footer_fs - 2); ?>px;'>❯❯❯</span>
        <?php endif; ?>

    </div>

    <!-- ═══════════════════════════════════════
         4. FOOTER ROW 2 — Colored, website URL
     ═══════════════════════════════════════ -->
    <div style='width:100%; flex:0 0 <?php echo esc_attr($t2_footer_url_h); ?>px; height:<?php echo esc_attr($t2_footer_url_h); ?>px; background:<?php echo esc_attr($date_bg); ?>; color:<?php echo esc_attr($date_color); ?>; display:flex; align-items:center; justify-content:center; font-size:<?php echo esc_attr($t2_url_fs); ?>px; font-weight:700; letter-spacing:1px; box-sizing:border-box; overflow:hidden;'>
        <?php if (!empty($website_url)): ?>
            <span style='text-shadow:0 2px 4px rgba(0,0,0,0.2);'><?php echo esc_html($website_url); ?></span>
        <?php endif; ?>
    </div>

</div><!-- /#npc-card-capture -->
</div><!-- /#npc-hidden-container -->
