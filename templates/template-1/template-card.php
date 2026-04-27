<?php
/**
 * Template 1 – Hidden Card HTML (used for html2canvas download)
 *
 * Variables available (passed via extract() from class-post-integration.php):
 *  $logo_url, $logo_shadow, $font_color, $title_area_bg,
 *  $date_bg, $date_color, $footer_bg, $footer_color,
 *  $date_format, $website_url, $title_fs, $footer_fs,
 *  $title_font, $date_font,
 *  $social_links, $thumbnail_url,
 *  $date, $title,
 *  $card_w, $card_h, $footer_h,
 *  $mjashik_social_icon_fn  ← callable that returns SVG string
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div id='npc-hidden-container' style='position:fixed; left:-9999px; top:-9999px; z-index:-9999;'>
    <div id='npc-card-capture' style='width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; position:relative; overflow:hidden; font-family:"Noto Sans Bengali",sans-serif; background:#fff; display:flex; flex-direction:column;'>

        <!-- 1. IMAGE AREA -->
        <div style='position:relative; width:100%; flex:1 1 auto; min-height:200px; overflow:hidden; <?php
            if ($thumbnail_url) {
                echo 'background-image:url(' . esc_url($thumbnail_url) . '); background-size:cover; background-position:center top;';
            } else {
                echo 'background:linear-gradient(135deg,#dde3ea,#b2bec3);';
            }
        ?>'>
            <!-- Gradient Overlay -->
            <div style='position:absolute; bottom:0; left:0; width:100%; height:160px; background:linear-gradient(to top,rgba(0,0,0,0.65),transparent); z-index:10;'></div>

            <!-- Logo (Top Left) -->
            <div style='position:absolute; top:28px; left:28px; z-index:30; display:inline-flex; align-items:center; justify-content:center;'>
                <?php if ($logo_url): ?>
                    <img src='<?php echo esc_url($logo_url); ?>' id='npc-logo-img' data-shadow='<?php echo esc_attr($logo_shadow); ?>' style='height:auto; width:auto; max-width:240px; display:block; filter:drop-shadow(0 0 20px <?php echo esc_attr($logo_shadow); ?>) drop-shadow(0 0 10px <?php echo esc_attr($logo_shadow); ?>);' crossorigin='anonymous'>
                <?php endif; ?>
            </div>

            <!-- Date Badge (Top Right) -->
            <div style='position:absolute; top:28px; right:28px; background:<?php echo esc_attr($date_bg); ?>; color:<?php echo esc_attr($date_color); ?>; padding:10px 22px; font-weight:bold; font-size:18px; border-radius:50px; box-shadow:0 4px 12px rgba(0,0,0,0.3); z-index:30; border:2px solid rgba(255,255,255,0.6); font-family:<?php echo esc_attr($date_font); ?>,sans-serif;'>
                <?php echo esc_html($date); ?>
            </div>
        </div>

        <!-- 2. TITLE AREA -->
        <div style='position:relative; width:100%; flex:0 0 auto; border-top:5px solid <?php echo esc_attr($date_bg); ?>; box-sizing:border-box; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:28px 50px; text-align:center; overflow:hidden; background-color:<?php echo esc_attr($title_area_bg); ?>;'>

            <!-- Watermark logo -->
            <div style='position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:<?php echo esc_attr(((int) get_option('mjashik_npc_watermark_opacity', 8)) / 100); ?>; width:55%; pointer-events:none; z-index:2;'>
                <?php if ($logo_url): ?>
                    <img src='<?php echo esc_url($logo_url); ?>' style='width:100%; height:auto;' crossorigin='anonymous'>
                <?php endif; ?>
            </div>

            <!-- Headline -->
            <div style='position:relative; z-index:10; width:100%;'>
                <h1 style='margin:0; padding:0; font-size:<?php echo esc_attr($title_fs); ?>px; line-height:1.5; font-weight:700; color:<?php echo esc_attr($font_color); ?>; width:100%; text-shadow:0 1px 2px rgba(0,0,0,0.06); font-family:<?php echo esc_attr($title_font); ?>,sans-serif;'>
                    <?php echo esc_html($title); ?>
                </h1>
            </div>
        </div>

        <!-- 3. FOOTER -->
        <div style='width:100%; height:<?php echo esc_attr($footer_h); ?>px; background:<?php echo esc_attr($footer_bg); ?>; color:<?php echo esc_attr($footer_color); ?>; display:flex; align-items:center; justify-content:center; gap:20px; font-size:<?php echo esc_attr($footer_fs); ?>px; font-weight:600; letter-spacing:1px; flex:0 0 <?php echo esc_attr($footer_h); ?>px; position:relative; overflow:hidden;'>
            <div style='position:absolute; top:0; left:0; width:100%; height:4px; background:rgba(255,255,255,0.1);'></div>

            <?php if (!empty($website_url)): ?>
                <span style='text-shadow:0 2px 4px rgba(0,0,0,0.2); white-space:nowrap;'><?php echo esc_html($website_url); ?></span>
            <?php endif; ?>

            <?php if (!empty($social_links)): ?>
                <?php if (!empty($website_url)): ?>
                <span style='opacity:0.4;'>|</span>
                <?php endif; ?>
                <div style='display:flex; align-items:center; gap:18px;'>
                    <?php foreach ($social_links as $link):
                        if (empty($link['text']) && empty($link['custom_img']) && $link['type'] !== 'custom') continue;
                    ?>
                        <div style='display:flex; align-items:center; gap:6px;'>
                            <?php if ($link['type'] === 'custom' && !empty($link['custom_img'])): ?>
                                <img src='<?php echo esc_url($link['custom_img']); ?>' style='width:22px; height:22px; border-radius:4px; object-fit:cover;' crossorigin='anonymous'>
                            <?php else: ?>
                                <span style='display:flex; align-items:center;'>
                                    <?php echo call_user_func($mjashik_social_icon_fn, $link['type'], $footer_color); ?>
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($link['text'])): ?>
                                <span style='text-shadow:0 2px 4px rgba(0,0,0,0.2); font-size:<?php echo esc_attr(max(10, $footer_fs - 4)); ?>px;'><?php echo esc_html($link['text']); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>
