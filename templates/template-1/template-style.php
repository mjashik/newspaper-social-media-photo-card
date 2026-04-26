<?php
/**
 * Template 1 – Admin Settings Preview HTML
 *
 * Variables available (passed via extract() from class-admin-settings.php):
 *  $prev_logo, $prev_logo_shadow, $prev_font_color, $prev_title_bg,
 *  $prev_date_bg, $prev_date_color, $prev_footer_bg, $prev_footer_color,
 *  $prev_title_size, $prev_date_size, $prev_footer_size,
 *  $prev_title_font, $prev_date_font,
 *  $prev_website, $prev_social, $prev_wm_opacity,
 *  $demo_title, $demo_date,
 *  $card_w, $card_h, $scale, $scaled_w, $scaled_h, $footer_h
 *
 * NOTE: Do NOT change $demo_title / $demo_date — they are set by the caller.
 */

if (!defined('ABSPATH')) {
    exit;
}

$title_bg_style = 'background-color:' . esc_attr($prev_title_bg) . ';';
?>

<!-- Outer wrapper holds scaled space -->
<div style="width:<?php echo esc_attr($scaled_w); ?>px; height:<?php echo esc_attr($scaled_h); ?>px; flex-shrink:0; position:relative;">
    <!-- Scale wrapper (800×800 → 50%) -->
    <div style="transform-origin:top left; transform:scale(<?php echo esc_attr($scale); ?>); width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; box-shadow:0 8px 40px rgba(0,0,0,0.22); border-radius:6px; overflow:hidden; position:absolute; top:0; left:0;">
        <!-- Card: flex column -->
        <div style="width:<?php echo esc_attr($card_w); ?>px; height:<?php echo esc_attr($card_h); ?>px; position:relative; overflow:hidden; font-family:'Noto Sans Bengali',sans-serif; background:#fff; display:flex; flex-direction:column;">

            <!-- 1. IMAGE AREA -->
            <div style="position:relative; width:100%; flex:1 1 auto; min-height:200px; overflow:hidden; background:linear-gradient(135deg,#dde3ea 0%,#b2bec3 100%);">

                <!-- Gradient overlay -->
                <div style="position:absolute; bottom:0; left:0; width:100%; height:160px; background:linear-gradient(to top,rgba(0,0,0,0.65),transparent); z-index:10;"></div>

                <!-- Logo (Top Left) -->
                <div style="position:absolute; top:28px; left:28px; z-index:30; filter:drop-shadow(0 2px 6px <?php echo esc_attr($prev_logo_shadow); ?>);">
                    <?php if ($prev_logo): ?>
                        <img id="npc-logo-img" data-shadow="<?php echo esc_attr($prev_logo_shadow); ?>" src="<?php echo esc_url($prev_logo); ?>" style="height:auto; width:auto; max-width:240px; display:block;" crossorigin="anonymous">
                    <?php else: ?>
                        <div style="background:rgba(255,255,255,0.9); color:#2c3e50; padding:8px 18px; font-size:24px; font-weight:700; border-radius:4px;">YOUR LOGO</div>
                    <?php endif; ?>
                </div>

                <!-- Date badge -->
                <div style="position:absolute; top:28px; right:28px; background:<?php echo esc_attr($prev_date_bg); ?>; color:<?php echo esc_attr($prev_date_color); ?>; padding:10px 22px; font-weight:bold; font-size:18px; border-radius:50px; box-shadow:0 4px 12px rgba(0,0,0,0.3); z-index:30; border:2px solid rgba(255,255,255,0.5); font-family:<?php echo esc_attr($prev_date_font); ?>,sans-serif;">
                    <?php echo esc_html($demo_date); ?>
                </div>
            </div>

            <!-- 2. TITLE AREA -->
            <div style="position:relative; width:100%; flex:0 0 auto; border-top:5px solid <?php echo esc_attr($prev_date_bg); ?>; box-sizing:border-box; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:28px 50px; text-align:center; overflow:hidden; <?php echo esc_attr($title_bg_style); ?>">
                <!-- Watermark logo -->
                <?php if ($prev_logo): ?>
                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:<?php echo esc_attr($prev_wm_opacity); ?>; width:55%; z-index:2;">
                    <img src="<?php echo esc_url($prev_logo); ?>" style="width:100%; height:auto;" />
                </div>
                <?php endif; ?>

                <!-- Headline -->
                <div style="position:relative; z-index:10; width:100%;">
                    <h1 style="margin:0; padding:0; font-size:<?php echo esc_attr($prev_title_size); ?>px; line-height:1.5; font-weight:700; color:<?php echo esc_attr($prev_font_color); ?>; width:100%; text-shadow:0 1px 2px rgba(0,0,0,0.06); font-family:<?php echo esc_attr($prev_title_font); ?>,sans-serif;">
                        <?php echo esc_html($demo_title); ?>
                    </h1>
                </div>
            </div>

            <!-- 3. FOOTER -->
            <div style="width:100%; height:<?php echo esc_attr($footer_h); ?>px; background:<?php echo esc_attr($prev_footer_bg); ?>; color:<?php echo esc_attr($prev_footer_color); ?>; display:flex; align-items:center; justify-content:center; gap:20px; font-size:<?php echo esc_attr($prev_footer_size); ?>px; font-weight:600; letter-spacing:1px; flex:0 0 <?php echo esc_attr($footer_h); ?>px; position:relative; overflow:hidden;">
                <div style="position:absolute; top:0; left:0; width:100%; height:4px; background:rgba(255,255,255,0.1);"></div>

                <!-- Web URL -->
                <?php if (!empty($prev_website)): ?>
                    <span style="text-shadow:0 2px 4px rgba(0,0,0,0.2); white-space:nowrap;"><?php echo esc_html($prev_website); ?></span>
                <?php endif; ?>

                <!-- Social Links -->
                <?php if (!empty($prev_social)): ?>
                    <?php if (!empty($prev_website)): ?>
                    <span style="opacity:0.4;">|</span>
                    <?php endif; ?>

                    <div style="display:flex; align-items:center; gap:18px;">
                        <?php foreach ($prev_social as $link):
                            if (empty($link['text']) && empty($link['custom_img']) && $link['type'] !== 'custom') continue;
                        ?>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <?php if ($link['type'] === 'custom' && !empty($link['custom_img'])): ?>
                                    <img src="<?php echo esc_url($link['custom_img']); ?>" style="width:22px; height:22px; border-radius:4px; object-fit:cover;" crossorigin="anonymous">
                                <?php else: ?>
                                    <span style="display:flex; align-items:center;">
                                        <?php echo $mjashik_social_icon_fn($link['type'], $prev_footer_color); ?>
                                    </span>
                                <?php endif; ?>

                                <?php if (!empty($link['text'])): ?>
                                    <span style="text-shadow:0 2px 4px rgba(0,0,0,0.2); font-size:<?php echo esc_attr(max(10, $prev_footer_size - 4)); ?>px;"><?php echo esc_html($link['text']); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div><!-- /.scale-wrapper -->
</div><!-- /.outer-wrapper -->
