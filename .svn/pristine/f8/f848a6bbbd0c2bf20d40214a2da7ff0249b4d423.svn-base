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

$t2_title_h = 265;
$t2_separator_h = 8;
$t2_footer_dark_h = 65;   // black overlay on image
$t2_footer_url_h = 90;   // red footer: social links + URL (two lines)
$t2_badge_half = 22;

$t2_title_bg = '#FFF5F5';
$t2_title_text = $title_area_bg;
$t2_separator = $date_bg;
$t2_logo_circle_bg = $date_bg;
$t2_url_fs = max(14, $footer_fs - 2);
$t2_wm_opacity = ((int) get_option('mjashik_npc_watermark_opacity', 8)) / 100;
?>
<?php
// Colors for dynamic line wrapping
$t2_line1_color = $t2_title_text;
$t2_line2_color = '#000000';
$t2_line3_color = $footer_bg;
?>
<div id='npc-hidden-container' style='position:fixed; left:-9999px; top:-9999px; z-index:-9999;'>
    <div id='npc-card-capture'
        style='width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; position:relative; overflow:hidden; font-family:"Noto Sans Bengali",sans-serif; background:#fff; display:flex; flex-direction:column;'>

        <!-- ═══════════════════════════════════════
         1. TITLE AREA — centered layout:
            Title (red+black split)
            Logo circle (center, below title)
            Date badge (straddles separator)
         ═══════════════════════════════════════ -->
        <div
            style='position:relative; width:100%; flex:0 0 <?php echo esc_attr($t2_title_h); ?>px; height:<?php echo esc_attr($t2_title_h); ?>px; background:<?php echo esc_attr($t2_title_bg); ?>; box-sizing:border-box; padding:0; border-bottom:<?php echo esc_attr($t2_separator_h); ?>px solid <?php echo esc_attr($t2_separator); ?>; overflow:visible; z-index:10; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0;'>

            <!-- Watermark (full-area, behind everything) -->
            <?php if ($logo_url): ?>
                <div
                    style='position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:<?php echo esc_attr($t2_wm_opacity); ?>; width:60%; z-index:1; pointer-events:none; overflow:hidden;'>
                    <img src='<?php echo esc_url($logo_url); ?>' style='width:100%; height:auto;' crossorigin='anonymous'>
                </div>
            <?php endif; ?>

            <!-- Headline — full width, centered, 10px top/bottom padding -->
            <!-- Auto-colored by JS based on wrapped lines -->
            <div style='position:relative; z-index:10; width:100%; padding:10px 30px 10px 30px; box-sizing:border-box; text-align:center;'>
                <h1 id='npc-t2-headline'
                    data-color-1='<?php echo esc_attr($t2_line1_color); ?>'
                    data-color-2='<?php echo esc_attr($t2_line2_color); ?>'
                    data-color-3='<?php echo esc_attr($t2_line3_color); ?>'
                    style='margin:0; padding:0; font-size:<?php echo esc_attr($title_fs); ?>px; line-height:1.35; font-weight:800; font-family:<?php echo esc_attr($title_font); ?>,sans-serif; word-break:break-word; text-align:center; width:100%; color:<?php echo esc_attr($t2_line1_color); ?>;'>
                    <?php echo esc_html($title); ?>
                </h1>
            </div>

            <!-- Logo — height fixed 45px, width auto; shadow baked by JS bakeLogoShadow() -->
            <?php if ($logo_url): ?>
                <div style='position:relative; z-index:20; display:inline-flex; align-items:center; justify-content:center; flex-shrink:0;'>
                    <img id='npc-logo-img' data-shadow='<?php echo esc_attr($logo_shadow); ?>'
                        src='<?php echo esc_url($logo_url); ?>'
                        style='height:45px; width:auto; display:block;'
                        crossorigin='anonymous'>
                </div>
            <?php endif; ?>

            <!-- Date badge — straddles the separator line -->
            <div
                style='position:absolute; bottom:-<?php echo esc_attr($t2_badge_half); ?>px; left:50%; transform:translateX(-50%); background:<?php echo esc_attr($date_bg); ?>; color:<?php echo esc_attr($date_color); ?>; padding:9px 38px; font-size:20px; font-weight:700; border-radius:50px; box-shadow:0 4px 16px rgba(0,0,0,0.30); z-index:40; white-space:nowrap; font-family:<?php echo esc_attr($date_font); ?>,sans-serif;'>
                <?php echo esc_html($date); ?>
            </div>
        </div>


        <!-- IMAGE AREA — flex:1 with post thumbnail, black overlay at bottom -->
        <div style='position:relative; width:100%; flex:1 1 auto; overflow:hidden; z-index:1; <?php
        if ($thumbnail_url) {
            echo 'background-image:url(' . esc_url($thumbnail_url) . '); background-size:cover; background-position:center top;';
        } else {
            echo 'background:linear-gradient(135deg,#cdd6e0,#9fb3c8);';
        }
        ?>'>

            <!-- « « « বিস্তারিত কমেন্টে » » » — Watermark Style -->
            <div
                style='position:absolute; bottom:20px; left:50%; transform:translateX(-50%); background:rgba(0,0,0,0.45); color:#ffffff; display:flex; align-items:center; justify-content:center; gap:12px; font-size:<?php echo esc_attr($footer_fs); ?>px; font-weight:700; padding:6px 28px; border-radius:50px; white-space:nowrap; text-shadow:0 2px 4px rgba(0,0,0,0.9); z-index:10; box-sizing:border-box; backdrop-filter:blur(10px); border:1px solid rgba(0, 0, 0, 0.1);'>
                <span style='opacity:0.9; letter-spacing:0;'><?php echo esc_html('« « «'); ?></span>
                <span
                    style='letter-spacing:1px;'><?php echo esc_html(__('বিস্তারিত কমেন্টে', 'newspaper-social-media-photo-card')); ?></span>
                <span style='opacity:0.9; letter-spacing:0;'><?php echo esc_html('» » »'); ?></span>
            </div>
        </div>

        <!-- RED FOOTER: social links (top) + website URL (bottom) -->
        <div
            style='width:100%; flex:0 0 <?php echo esc_attr($t2_footer_url_h); ?>px; height:<?php echo esc_attr($t2_footer_url_h); ?>px; background:<?php echo esc_attr($date_bg); ?>; color:<?php echo esc_attr($date_color); ?>; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:6px; box-sizing:border-box; overflow:hidden; padding:8px 20px;'>

            <!-- Social links row -->
            <?php if (!empty($social_links)): ?>
                <div style='display:flex; align-items:center; justify-content:center; gap:16px; flex-wrap:nowrap;'>
                    <?php foreach ($social_links as $link):
                        if (empty($link['text']) && empty($link['custom_img']) && $link['type'] !== 'custom')
                            continue;
                        ?>
                        <div style='display:flex; align-items:center; gap:5px;'>
                            <?php if ($link['type'] === 'custom' && !empty($link['custom_img'])): ?>
                                <img src='<?php echo esc_url($link['custom_img']); ?>'
                                    style='width:20px; height:20px; border-radius:3px; object-fit:cover;' crossorigin='anonymous'>
                            <?php else: ?>
                                <span style='display:flex; align-items:center;'>
                                    <?php echo call_user_func($mjashik_social_icon_fn, $link['type'], $date_color); ?>
                                </span>
                            <?php endif; ?>
                            <?php if (!empty($link['text'])): ?>
                                <span
                                    style='font-size:<?php echo esc_attr(max(12, $footer_fs - 6)); ?>px; font-weight:600;'><?php echo esc_html($link['text']); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Website URL row -->
            <?php if (!empty($website_url)): ?>
                <div
                    style='font-size:<?php echo esc_attr($t2_url_fs); ?>px; font-weight:700; letter-spacing:1px; text-shadow:0 2px 4px rgba(0,0,0,0.2);'>
                    <?php echo esc_html($website_url); ?>
                </div>
            <?php endif; ?>

        </div>

    </div><!-- /#npc-card-capture -->
</div><!-- /#npc-hidden-container -->