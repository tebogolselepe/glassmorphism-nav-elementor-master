<?php
namespace Elementor;

class Fluid_Navbar_Widget extends Widget_Base {

    public function get_name() {
        return 'fluid-glass-navbar';
    }

    public function get_title() {
        return 'Fluid Glass Navbar';
    }

    public function get_icon() {
        return 'eicon-nav-menu';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_script_depends() {
        return ['jquery'];
    }

    protected function register_controls() {
        // Logo Section
        $this->start_controls_section('logo_section', [
            'label' => 'Logo',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('logo_type', [
            'label' => 'Logo Type',
            'type' => Controls_Manager::SELECT,
            'default' => 'icon',
            'options' => [
                'icon' => 'Icon/SVG',
                'image' => 'Image Upload',
                'dynamic' => 'Dynamic Tag',
            ],
        ]);

        $this->add_control('logo_svg', [
            'label' => 'SVG Icon',
            'type' => Controls_Manager::TEXTAREA,
            'default' => '<svg viewBox="0 0 24 24" fill="none" stroke="rgba(200,198,190,0.72)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-9-9" /><polyline points="21 3 21 9 15 9" /></svg>',
            'condition' => ['logo_type' => 'icon'],
        ]);

        $this->add_control('logo_image', [
            'label' => 'Upload Logo',
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
            'condition' => ['logo_type' => 'image'],
        ]);

        $this->add_control('logo_dynamic_tag', [
            'label' => 'Dynamic Tag',
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'site.logo or custom_logo',
            'condition' => ['logo_type' => 'dynamic'],
        ]);

        $this->add_control('logo_link', [
            'label' => 'Logo Link',
            'type' => Controls_Manager::URL,
            'default' => ['url' => '/', 'is_external' => false],
        ]);

        $this->add_control('logo_link_dynamic', [
            'label' => 'Use Dynamic Link',
            'type' => Controls_Manager::SWITCHER,
            'description' => 'Override link with WordPress dynamic tag',
        ]);

        $this->add_control('logo_link_dynamic_tag', [
            'label' => 'Dynamic Link Tag',
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'post.url or site.url',
            'condition' => ['logo_link_dynamic' => 'yes'],
        ]);

        $this->end_controls_section();

        // Navbar Label Section
        $this->start_controls_section('label_section', [
            'label' => 'Navbar Label',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('nav_label', [
            'label' => 'Default Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Menu',
        ]);

        $this->end_controls_section();

        // Menu Items Section
        $this->start_controls_section('menu_section', [
            'label' => 'Menu Items',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $repeater = new Repeater();

        $repeater->add_control('menu_title', [
            'label' => 'Menu Title',
            'type' => Controls_Manager::TEXT,
            'default' => 'Menu Item',
        ]);

        $repeater->add_control('menu_link_source', [
            'label' => 'Link Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'static',
            'options' => [
                'static' => 'Static',
                'dynamic' => 'Dynamic Tag',
            ],
        ]);

        $repeater->add_control('menu_link', [
            'label' => 'Link URL',
            'type' => Controls_Manager::URL,
            'default' => ['url' => '#'],
            'condition' => ['menu_link_source' => 'static'],
        ]);

        $repeater->add_control('menu_link_dynamic_tag', [
            'label' => 'Dynamic Tag',
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'post.url',
            'condition' => ['menu_link_source' => 'dynamic'],
        ]);

        $repeater->add_control('inline_pair', [
            'label' => 'Inline Pair',
            'type' => Controls_Manager::SWITCHER,
            'description' => 'Display this item inline with the next item (50/50 side by side)',
            'default' => '',
        ]);

        $this->add_control('menu_items', [
            'label' => 'Menu Items',
            'type' => Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                ['menu_title' => 'Home', 'menu_link' => ['url' => '#']],
                ['menu_title' => 'Services', 'menu_link' => ['url' => '#']],
                ['menu_title' => 'About', 'menu_link' => ['url' => '#']],
                ['menu_title' => 'Security', 'menu_link' => ['url' => '#']],
                ['menu_title' => 'Agent', 'menu_link' => ['url' => '#'], 'inline_pair' => 'yes'],
                ['menu_title' => 'Client', 'menu_link' => ['url' => '#'], 'inline_pair' => 'yes'],
            ],
            'title_field' => '{{menu_title}}',
        ]);

        $this->end_controls_section();

        // CTA Buttons Section
        $this->start_controls_section('cta_section', [
            'label' => 'CTA Buttons',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $cta_repeater = new Repeater();

        $cta_repeater->add_control('cta_text', [
            'label' => 'Button Text',
            'type' => Controls_Manager::TEXT,
            'default' => 'Get a quote',
        ]);

        $cta_repeater->add_control('cta_link_source', [
            'label' => 'Link Source',
            'type' => Controls_Manager::SELECT,
            'default' => 'static',
            'options' => [
                'static' => 'Static',
                'dynamic' => 'Dynamic Tag',
            ],
        ]);

        $cta_repeater->add_control('cta_link', [
            'label' => 'Link URL',
            'type' => Controls_Manager::URL,
            'default' => ['url' => '#'],
            'condition' => ['cta_link_source' => 'static'],
        ]);

        $cta_repeater->add_control('cta_link_dynamic_tag', [
            'label' => 'Dynamic Tag',
            'type' => Controls_Manager::TEXT,
            'placeholder' => 'post.url',
            'condition' => ['cta_link_source' => 'dynamic'],
        ]);

        $this->add_control('cta_buttons', [
            'label' => 'CTA Buttons',
            'type' => Controls_Manager::REPEATER,
            'fields' => $cta_repeater->get_controls(),
            'default' => [
                ['cta_text' => 'Get a quote', 'cta_link' => ['url' => '#']],
            ],
            'title_field' => '{{cta_text}}',
        ]);

        $this->end_controls_section();

        // Secondary Links Section
        $this->start_controls_section('secondary_section', [
            'label' => 'Secondary Links',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('phone_label', [
            'label' => 'Phone Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Call',
        ]);

        $this->add_control('phone_number', [
            'label' => 'Phone Number',
            'type' => Controls_Manager::TEXT,
            'default' => '020 8156 7290',
        ]);

        $this->add_control('email_label', [
            'label' => 'Email Label',
            'type' => Controls_Manager::TEXT,
            'default' => 'Email',
        ]);

        $this->add_control('email_address', [
            'label' => 'Email Address',
            'type' => Controls_Manager::TEXT,
            'default' => 'info@example.com',
        ]);

        $this->end_controls_section();

        // Effects Section
        $this->start_controls_section('effects_section', [
            'label' => 'Effects',
            'tab' => Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('enable_proximity_opacity', [
            'label' => 'Proximity Opacity',
            'type' => Controls_Manager::SWITCHER,
            'description' => 'Navbar fades out as the cursor moves away from it (desktop only).',
            'default' => 'yes',
            'return_value' => 'yes',
            'label_on' => 'On',
            'label_off' => 'Off',
        ]);

        $this->add_control('proximity_radius', [
            'label' => 'Proximity Radius',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 350],
            'range' => ['px' => ['min' => 100, 'max' => 800]],
            'description' => 'Distance (px) at which the navbar reaches minimum opacity.',
            'condition' => ['enable_proximity_opacity' => 'yes'],
        ]);

        $this->add_control('proximity_min_opacity', [
            'label' => 'Minimum Opacity',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 25],
            'range' => ['%' => ['min' => 5, 'max' => 80]],
            'description' => 'Lowest opacity the navbar reaches when cursor is far away.',
            'condition' => ['enable_proximity_opacity' => 'yes'],
        ]);

        $this->end_controls_section();

        // Navbar Style Section
        $this->start_controls_section('navbar_style_section', [
            'label' => 'Navbar',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('navbar_bg', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(22,22,20,0.45)',
            'selectors' => ['{{WRAPPER}} .fgn-navbar-pill' => 'background: {{VALUE}}'],
        ]);

        $this->add_control('navbar_border', [
            'label' => 'Border Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(255,255,255,0.08)',
            'selectors' => ['{{WRAPPER}} .fgn-navbar-pill' => 'border-color: {{VALUE}}'],
        ]);

        $this->add_control('navbar_blur', [
            'label' => 'Blur Amount',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 24],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => ['{{WRAPPER}} .fgn-navbar-pill' => 'backdrop-filter: blur({{SIZE}}px) saturate(200%); -webkit-backdrop-filter: blur({{SIZE}}px) saturate(200%);'],
        ]);

        $this->add_control('navbar_padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'default' => ['top' => 7, 'right' => 7, 'bottom' => 7, 'left' => 8],
            'selectors' => ['{{WRAPPER}} .fgn-navbar-pill' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px'],
        ]);

        $this->add_control('navbar_bottom', [
            'label' => 'Position from Bottom',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 28],
            'range' => ['px' => ['min' => 0, 'max' => 100]],
            'selectors' => ['{{WRAPPER}} .fgn-navbar' => 'bottom: {{SIZE}}px'],
        ]);

        $this->end_controls_section();

        // Label Style Section
        $this->start_controls_section('label_style_section', [
            'label' => 'Label',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('label_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(220,218,208,0.82)',
            'selectors' => ['{{WRAPPER}} .fgn-nav-label' => 'color: {{VALUE}}'],
        ]);

        $this->add_control('label_font_size', [
            'label' => 'Font Size',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 10.5],
            'range' => ['px' => ['min' => 8, 'max' => 20]],
            'selectors' => ['{{WRAPPER}} .fgn-nav-label' => 'font-size: {{SIZE}}px'],
        ]);

        $this->add_control('label_letter_spacing', [
            'label' => 'Letter Spacing',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 0.14],
            'range' => ['em' => ['min' => 0, 'max' => 0.5]],
            'selectors' => ['{{WRAPPER}} .fgn-nav-label' => 'letter-spacing: {{SIZE}}em'],
        ]);

        $this->end_controls_section();

        // Hamburger Style Section
        $this->start_controls_section('hamburger_style_section', [
            'label' => 'Hamburger Menu',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('hamburger_color', [
            'label' => 'Line Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(220,218,208,0.72)',
            'selectors' => ['{{WRAPPER}} .fgn-hamburger-btn span' => 'background: {{VALUE}}'],
        ]);

        $this->add_control('hamburger_hover_color', [
            'label' => 'Hover Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['{{WRAPPER}} .fgn-hamburger-btn:hover span' => 'background: {{VALUE}}'],
        ]);

        $this->add_control('hamburger_width', [
            'label' => 'Line Width',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 17],
            'range' => ['px' => ['min' => 10, 'max' => 30]],
            'selectors' => ['{{WRAPPER}} .fgn-hamburger-btn span' => 'width: {{SIZE}}px'],
        ]);

        $this->end_controls_section();

        // Menu Box Style Section
        $this->start_controls_section('menu_box_style_section', [
            'label' => 'Menu Box',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('menu_box_bg', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(10,10,9,0.88)',
            'selectors' => ['{{WRAPPER}} .fgn-menu-panel' => 'background: {{VALUE}}'],
        ]);

        $this->add_control('menu_box_border', [
            'label' => 'Border Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(255,255,255,0.1)',
            'selectors' => ['{{WRAPPER}} .fgn-menu-panel' => 'border-color: {{VALUE}}'],
        ]);

        $this->add_control('menu_box_blur', [
            'label' => 'Blur Amount',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 28],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => ['{{WRAPPER}} .fgn-menu-panel' => 'backdrop-filter: blur({{SIZE}}px) saturate(180%); -webkit-backdrop-filter: blur({{SIZE}}px) saturate(180%);'],
        ]);

        $this->add_control('menu_box_width', [
            'label' => 'Width',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 220],
            'range' => ['px' => ['min' => 150, 'max' => 400]],
            'selectors' => ['{{WRAPPER}} .fgn-menu-panel' => 'width: {{SIZE}}px'],
        ]);

        $this->add_control('menu_overlay_bg', [
            'label' => 'Overlay Background',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(10,10,9,0.85)',
            'selectors' => ['{{WRAPPER}} .fgn-navbar-open' => 'background: {{VALUE}}'],
        ]);

        $this->add_control('menu_overlay_blur', [
            'label' => 'Overlay Blur',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 16],
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => ['{{WRAPPER}} .fgn-navbar-open' => 'backdrop-filter: blur({{SIZE}}px) saturate(180%); -webkit-backdrop-filter: blur({{SIZE}}px) saturate(180%);'],
        ]);

        $this->end_controls_section();

        // Menu Items Style Section
        $this->start_controls_section('menu_items_style_section', [
            'label' => 'Menu Items',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('menu_item_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(228,226,216,0.6)',
            'selectors' => ['{{WRAPPER}} .fgn-menu-links a' => 'color: {{VALUE}}'],
        ]);

        $this->add_control('menu_item_hover_color', [
            'label' => 'Hover Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['{{WRAPPER}} .fgn-menu-links a:hover' => 'color: {{VALUE}}'],
        ]);

        $this->add_control('menu_item_font_size', [
            'label' => 'Font Size',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 26],
            'range' => ['px' => ['min' => 14, 'max' => 40]],
            'selectors' => ['{{WRAPPER}} .fgn-menu-links a' => 'font-size: {{SIZE}}px'],
        ]);

        $this->add_control('menu_item_font_family', [
            'label' => 'Font Family',
            'type' => Controls_Manager::FONT,
            'default' => 'Cormorant Garamond',
            'selectors' => ['{{WRAPPER}} .fgn-menu-links a' => 'font-family: "{{VALUE}}"'],
        ]);

        $this->end_controls_section();

        // CTA Button Style Section
        $this->start_controls_section('cta_style_section', [
            'label' => 'CTA Buttons',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('cta_bg', [
            'label' => 'Background Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#181816',
            'selectors' => ['{{WRAPPER}} .fgn-menu-cta' => 'background: {{VALUE}}'],
        ]);

        $this->add_control('cta_hover_bg', [
            'label' => 'Hover Background',
            'type' => Controls_Manager::COLOR,
            'default' => '#242420',
            'selectors' => ['{{WRAPPER}} .fgn-menu-cta:hover' => 'background: {{VALUE}}'],
        ]);

        $this->add_control('cta_color', [
            'label' => 'Text Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(220,218,208,0.85)',
            'selectors' => ['{{WRAPPER}} .fgn-menu-cta' => 'color: {{VALUE}}'],
        ]);

        $this->add_control('cta_hover_color', [
            'label' => 'Hover Text Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['{{WRAPPER}} .fgn-menu-cta:hover' => 'color: {{VALUE}}'],
        ]);

        $this->add_control('cta_border_color', [
            'label' => 'Border Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(255,255,255,0.18)',
            'selectors' => ['{{WRAPPER}} .fgn-menu-cta' => 'border-color: {{VALUE}}'],
        ]);

        $this->add_control('cta_font_size', [
            'label' => 'Font Size',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 9.5],
            'range' => ['px' => ['min' => 6, 'max' => 16]],
            'selectors' => ['{{WRAPPER}} .fgn-menu-cta' => 'font-size: {{SIZE}}px'],
        ]);

        $this->add_control('cta_padding', [
            'label' => 'Padding',
            'type' => Controls_Manager::DIMENSIONS,
            'default' => ['top' => 13, 'right' => 16, 'bottom' => 13, 'left' => 16],
            'selectors' => ['{{WRAPPER}} .fgn-menu-cta' => 'padding: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px'],
        ]);

        $this->end_controls_section();

        // Secondary Links Style Section
        $this->start_controls_section('secondary_style_section', [
            'label' => 'Secondary Links',
            'tab' => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('secondary_color', [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(140,138,128,0.75)',
            'selectors' => ['{{WRAPPER}} .fgn-menu-secondary span, {{WRAPPER}} .fgn-menu-secondary a' => 'color: {{VALUE}}'],
        ]);

        $this->add_control('secondary_hover_color', [
            'label' => 'Hover Color',
            'type' => Controls_Manager::COLOR,
            'default' => 'rgba(200,198,190,0.9)',
            'selectors' => ['{{WRAPPER}} .fgn-menu-secondary a:hover' => 'color: {{VALUE}}'],
        ]);

        $this->add_control('secondary_font_size', [
            'label' => 'Font Size',
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 10],
            'range' => ['px' => ['min' => 8, 'max' => 16]],
            'selectors' => ['{{WRAPPER}} .fgn-menu-secondary span, {{WRAPPER}} .fgn-menu-secondary a' => 'font-size: {{SIZE}}px'],
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Handle logo
        $logo_html = '';
        if ($settings['logo_type'] === 'icon') {
            $logo_html = !empty($settings['logo_svg']) ? $settings['logo_svg'] : '';
        } elseif ($settings['logo_type'] === 'image' && !empty($settings['logo_image']['url'])) {
            $logo_html = '<img src="' . esc_url($settings['logo_image']['url']) . '" alt="Logo" style="width:100%;height:100%;object-fit:contain;">';
        } elseif ($settings['logo_type'] === 'dynamic' && !empty($settings['logo_dynamic_tag'])) {
            $logo_html = '<img src="{{' . $settings['logo_dynamic_tag'] . '}}" alt="Logo" class="dynamic-logo" data-dynamic-tag="' . esc_attr($settings['logo_dynamic_tag']) . '">';
        }

        // Handle logo link
        $logo_url = $settings['logo_link']['url'];
        if ($settings['logo_link_dynamic'] === 'yes' && !empty($settings['logo_link_dynamic_tag'])) {
            $logo_url = '{{' . $settings['logo_link_dynamic_tag'] . '}}';
        }

        // Proximity effect settings
        $proximity_enabled = isset($settings['enable_proximity_opacity']) ? $settings['enable_proximity_opacity'] : 'yes';
        $proximity_radius = isset($settings['proximity_radius']['size']) ? intval($settings['proximity_radius']['size']) : 350;
        $proximity_min_opacity = isset($settings['proximity_min_opacity']['size']) ? intval($settings['proximity_min_opacity']['size']) : 25;
        ?>
        <nav class="fgn-navbar" id="fgnNavbar" aria-label="Site navigation"
            data-proximity-enabled="<?php echo esc_attr($proximity_enabled); ?>"
            data-proximity-radius="<?php echo esc_attr($proximity_radius); ?>"
            data-proximity-min-opacity="<?php echo esc_attr($proximity_min_opacity / 100); ?>">
            <div class="fgn-navbar-pill">
                <a class="fgn-logo-circle" href="<?php echo esc_attr($logo_url); ?>" aria-label="Home">
                    <?php echo $logo_html; ?>
                </a>
                <span class="fgn-nav-label" id="fgnNavLabel" data-default="<?php echo esc_attr($settings['nav_label']); ?>"><?php echo esc_html($settings['nav_label']); ?></span>
                <button class="fgn-hamburger-btn" id="fgnOpenBtn" aria-label="Open menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </nav>

        <div class="fgn-navbar-open" id="fgnNavOpen" role="dialog" aria-modal="true" aria-label="Main menu">
            <div class="fgn-menu-panel-wrap">
                <div class="fgn-menu-panel">
                    <p class="fgn-menu-panel-label">Menu</p>
                    <ul class="fgn-menu-links">
                        <?php
                        $items = $settings['menu_items'];
                        $i = 0;
                        $count = count($items);
                        while ($i < $count) :
                            $item = $items[$i];
                            $is_inline = (isset($item['inline_pair']) && $item['inline_pair'] === 'yes');
                            $link = ($item['menu_link_source'] === 'dynamic' && !empty($item['menu_link_dynamic_tag'])) ? '{{' . $item['menu_link_dynamic_tag'] . '}}' : esc_url($item['menu_link']['url']);
                            $dynamic_class = $item['menu_link_source'] === 'dynamic' ? 'dynamic-link' : '';
                            $dynamic_attr = $item['menu_link_source'] === 'dynamic' ? ' data-dynamic-tag="' . esc_attr($item['menu_link_dynamic_tag']) . '"' : '';

                            if ($is_inline && ($i + 1) < $count) :
                                $next_item = $items[$i + 1];
                                $next_link = ($next_item['menu_link_source'] === 'dynamic' && !empty($next_item['menu_link_dynamic_tag'])) ? '{{' . $next_item['menu_link_dynamic_tag'] . '}}' : esc_url($next_item['menu_link']['url']);
                                $next_dynamic_class = $next_item['menu_link_source'] === 'dynamic' ? 'dynamic-link' : '';
                                $next_dynamic_attr = $next_item['menu_link_source'] === 'dynamic' ? ' data-dynamic-tag="' . esc_attr($next_item['menu_link_dynamic_tag']) . '"' : '';
                        ?>
                            <li class="fgn-menu-inline-pair">
                                <a href="<?php echo esc_attr($link); ?>" class="<?php echo $dynamic_class; ?>" <?php echo $dynamic_attr; ?>><?php echo esc_html($item['menu_title']); ?></a>
                                <a href="<?php echo esc_attr($next_link); ?>" class="<?php echo $next_dynamic_class; ?>" <?php echo $next_dynamic_attr; ?>><?php echo esc_html($next_item['menu_title']); ?></a>
                            </li>
                        <?php
                                $i += 2;
                            else :
                        ?>
                            <li><a href="<?php echo esc_attr($link); ?>" class="<?php echo $dynamic_class; ?>" <?php echo $dynamic_attr; ?>><?php echo esc_html($item['menu_title']); ?></a></li>
                        <?php
                                $i += 1;
                            endif;
                        endwhile; ?>
                    </ul>
                    <div class="fgn-menu-secondary">
                        <span><?php echo esc_html($settings['phone_label']); ?></span>
                        <a href="tel:<?php echo esc_attr(str_replace(' ', '', $settings['phone_number'])); ?>"><?php echo esc_html($settings['phone_number']); ?></a>
                        <span><?php echo esc_html($settings['email_label']); ?></span>
                        <a href="mailto:<?php echo esc_attr($settings['email_address']); ?>"><?php echo esc_html($settings['email_address']); ?></a>
                    </div>
                    <?php foreach ($settings['cta_buttons'] as $cta) :
                        $cta_link = ($cta['cta_link_source'] === 'dynamic' && !empty($cta['cta_link_dynamic_tag'])) ? '{{' . $cta['cta_link_dynamic_tag'] . '}}' : esc_url($cta['cta_link']['url']);
                        $cta_classes = 'fgn-menu-cta' . ($cta['cta_link_source'] === 'dynamic' ? ' dynamic-link' : '');
                        $cta_dynamic_attr = $cta['cta_link_source'] === 'dynamic' ? ' data-dynamic-tag="' . esc_attr($cta['cta_link_dynamic_tag']) . '"' : '';
                    ?>
                        <a href="<?php echo esc_attr($cta_link); ?>" class="<?php echo $cta_classes; ?>" <?php echo $cta_dynamic_attr; ?>><?php echo esc_html($cta['cta_text']); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php
    }
}