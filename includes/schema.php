<?php
/*
 * Content definition for the whole website.
 * SETTINGS = single values (text / textarea / image / video), grouped for the admin form.
 * SECTIONS = repeatable lists. Each item row has: title, subtitle, body, link, media, extra.
 *            'fields' says which columns a section uses and how the admin form labels them.
 * 'default' / 'seed' values are inserted by install.php.
 */

const SETTINGS = [
    'general' => ['label' => 'General', 'fields' => [
        'site_name'    => ['label' => 'Website name', 'type' => 'text', 'default' => 'a2zwala'],
        'site_tagline' => ['label' => 'Logo tagline', 'type' => 'text', 'default' => 'Digital Production Excellence'],
        'logo'         => ['label' => 'Logo image (optional — replaces text logo)', 'type' => 'image', 'default' => ''],
        'meta_title'   => ['label' => 'Browser title', 'type' => 'text', 'default' => 'a2zwala — Digital production for agencies'],
        'meta_desc'    => ['label' => 'Meta description (SEO)', 'type' => 'textarea', 'default' => 'White-labeled HTML5, EDMs, web and motion graphics — designed, developed and QA-checked by your dedicated offshore team.'],
    ]],
    'hero' => ['label' => 'Hero (top of page)', 'fields' => [
        'hero_eyebrow'  => ['label' => 'Small blue text', 'type' => 'text', 'default' => 'Digital production for agencies'],
        'hero_title'    => ['label' => 'Heading (black part)', 'type' => 'text', 'default' => 'Your offshore production team,'],
        'hero_title2'   => ['label' => 'Heading (grey part)', 'type' => 'text', 'default' => 'ready when the brief lands.'],
        'hero_lead'     => ['label' => 'Paragraph', 'type' => 'textarea', 'default' => 'White-labeled HTML5, EDMs, web, and motion graphics—designed, developed, and QA-checked by your dedicated offshore team.'],
        'hero_btn1'     => ['label' => 'Button 1 text', 'type' => 'text', 'default' => 'Send a brief'],
        'hero_btn1_url' => ['label' => 'Button 1 link', 'type' => 'text', 'default' => '#contact'],
        'hero_btn2'     => ['label' => 'Button 2 text', 'type' => 'text', 'default' => 'View our work'],
        'hero_btn2_url' => ['label' => 'Button 2 link', 'type' => 'text', 'default' => '#work'],
        'clients_more'  => ['label' => 'Text after client logos', 'type' => 'text', 'default' => 'and many more...'],
    ]],
    'headings' => ['label' => 'Section headings', 'fields' => [
        'services_eyebrow' => ['label' => 'Services — small text', 'type' => 'text', 'default' => 'What we do'],
        'services_title'   => ['label' => 'Services — heading', 'type' => 'text', 'default' => 'Four production lines, one team.'],
        'process_eyebrow'  => ['label' => 'Process — small text', 'type' => 'text', 'default' => 'Our process'],
        'process_title'    => ['label' => 'Process — heading', 'type' => 'text', 'default' => 'Brief in. Files out. QA at every step.'],
        'process_link'     => ['label' => 'Process — link text', 'type' => 'text', 'default' => 'Send your first brief ›'],
        'work_eyebrow'     => ['label' => 'Work — small text', 'type' => 'text', 'default' => 'Featured work'],
        'work_title'       => ['label' => 'Work — heading', 'type' => 'text', 'default' => 'Real campaigns. Real formats.'],
        'compare_eyebrow'  => ['label' => 'Compare — small text', 'type' => 'text', 'default' => 'Why agencies switch'],
        'compare_title'    => ['label' => 'Compare — heading', 'type' => 'text', 'default' => 'Freelancer vs in-house vs a2zwala.'],
        'compare_col1'     => ['label' => 'Compare — column 2 name', 'type' => 'text', 'default' => 'Freelancer'],
        'compare_col2'     => ['label' => 'Compare — column 3 name', 'type' => 'text', 'default' => 'In-house'],
        'compare_col3'     => ['label' => 'Compare — highlighted column name', 'type' => 'text', 'default' => 'a2zwala'],
        'compare_badge'    => ['label' => 'Compare — badge above highlighted column', 'type' => 'text', 'default' => 'Recommended'],
        'sleep_eyebrow'    => ['label' => 'Timeline — small text', 'type' => 'text', 'default' => 'While you sleep'],
        'sleep_title'      => ['label' => 'Timeline — heading', 'type' => 'text', 'default' => 'We work while you sleep.'],
        'sleep_sub'        => ['label' => 'Timeline — sub heading', 'type' => 'text', 'default' => "Your brief at the end of the day is our day's starting point."],
    ]],
    'cta' => ['label' => 'Bottom call-to-action', 'fields' => [
        'cta_title'    => ['label' => 'Heading', 'type' => 'text', 'default' => 'Ready when the brief lands.'],
        'cta_text'     => ['label' => 'Text', 'type' => 'text', 'default' => 'Send us your first brief and get a free sample project.'],
        'cta_btn'      => ['label' => 'Button text', 'type' => 'text', 'default' => 'Send a brief'],
        'cta_btn_url'  => ['label' => 'Button link', 'type' => 'text', 'default' => 'mailto:hello@a2zwala.com'],
        'cta_media'    => ['label' => 'Background image / video (optional)', 'type' => 'media', 'default' => ''],
    ]],
    'footer' => ['label' => 'Footer & contact', 'fields' => [
        'footer_about' => ['label' => 'About text', 'type' => 'textarea', 'default' => 'Your dedicated offshore production team for HTML5 banners, EDMs, landing pages, and motion — banners and EDMs in 24–48 hours, landing pages in 3–5 days, motion by scope.'],
        'email'        => ['label' => 'Email', 'type' => 'text', 'default' => 'hello@a2zwala.com'],
        'phone'        => ['label' => 'Phone', 'type' => 'text', 'default' => '+91 00000 00000'],
        'address'      => ['label' => 'Address', 'type' => 'textarea', 'default' => 'Gurugram, Haryana, India'],
        'copyright'    => ['label' => 'Copyright text', 'type' => 'text', 'default' => 'All rights reserved.'],
        'footer_right' => ['label' => 'Footer right text', 'type' => 'text', 'default' => 'Made in India 🇮🇳'],
    ]],
];

const SECTIONS = [
    'menu' => [
        'label'  => 'Header menu',
        'fields' => [
            'title' => ['label' => 'Menu text', 'type' => 'text'],
            'link'  => ['label' => 'Link', 'type' => 'text'],
            'extra' => ['label' => 'Dropdown items (optional)', 'type' => 'textarea', 'help' => 'One per line: Label|link   e.g.  HTML5 Banners|#services'],
        ],
        'seed' => [
            ['title' => 'Services', 'link' => '#services', 'extra' => "HTML5 Banners|#services\nEDM / Newsletters|#services\nWebsite Development|#services\nMotion Graphics|#services"],
            ['title' => 'Work', 'link' => '#work'],
            ['title' => 'Pricing', 'link' => '#compare', 'extra' => "Retainer Packages|#contact\nGet Quote|#contact"],
            ['title' => '🚀 Studio-Tools', 'link' => '#'],
            ['title' => '🤖 AI Bot', 'link' => '#'],
            ['title' => 'Contact', 'link' => '#contact'],
        ],
    ],
    'trust' => [
        'label'  => 'Hero ✓ points',
        'fields' => ['title' => ['label' => 'Text', 'type' => 'text']],
        'seed'   => [['title' => 'No lock-in'], ['title' => 'Free sample project'], ['title' => 'Onboard in 48–72 hours'], ['title' => 'Net 30 · no upfront payment'], ['title' => 'NDA on request, before any brief']],
    ],
    'stats' => [
        'label'  => 'Hero stats',
        'fields' => ['title' => ['label' => 'Number', 'type' => 'text'], 'subtitle' => ['label' => 'Label', 'type' => 'text']],
        'seed'   => [['title' => '20+', 'subtitle' => 'Years in production'], ['title' => '24–48h', 'subtitle' => 'Turnaround'], ['title' => '50+', 'subtitle' => 'Agency partners'], ['title' => '6', 'subtitle' => 'Global markets']],
    ],
    'showcase' => [
        'label'  => 'Showcase tabs (banner / EDM / website / motion)',
        'fields' => [
            'title' => ['label' => 'Tab name', 'type' => 'text'],
            'media' => ['label' => 'Image or video shown in tab', 'type' => 'media', 'help' => 'If empty, a built-in demo graphic is shown.'],
            'extra' => ['label' => 'Demo graphic when no media', 'type' => 'select', 'options' => ['banner' => 'Banner', 'edm' => 'EDM', 'web' => 'Website', 'motion' => 'Motion']],
        ],
        'seed' => [['title' => 'HTML5 banner', 'extra' => 'banner'], ['title' => 'EDM', 'extra' => 'edm'], ['title' => 'Website', 'extra' => 'web'], ['title' => 'Motion', 'extra' => 'motion']],
    ],
    'clients' => [
        'label'  => 'Client logos',
        'fields' => ['title' => ['label' => 'Client name', 'type' => 'text'], 'media' => ['label' => 'Logo image (optional)', 'type' => 'media', 'help' => 'If empty, the name is shown as text.']],
        'seed'   => [['title' => 'NORTHWIND'], ['title' => 'Acme Corp'], ['title' => 'Globex'], ['title' => 'Umbrella'], ['title' => 'Initech'], ['title' => 'Hooli']],
    ],
    'services' => [
        'label'  => 'Services cards',
        'fields' => [
            'title' => ['label' => 'Heading', 'type' => 'text'],
            'body'  => ['label' => 'Description', 'type' => 'textarea'],
            'link'  => ['label' => '"Learn more" link', 'type' => 'text'],
            'media' => ['label' => 'Card image or video', 'type' => 'media', 'help' => 'If empty, a built-in demo graphic is shown.'],
            'extra' => ['label' => 'Demo graphic when no media', 'type' => 'select', 'options' => ['banner' => 'Banner', 'edm' => 'EDM', 'web' => 'Website', 'motion' => 'Motion']],
        ],
        'seed' => [
            ['title' => 'HTML5 and rich media', 'body' => 'Interactive banners, resizing, localisation and dynamic creatives.', 'link' => '#contact', 'extra' => 'banner'],
            ['title' => 'EDM production', 'body' => 'Responsive EDMs, dark-mode optimisation and ESP-ready.', 'link' => '#contact', 'extra' => 'edm'],
            ['title' => 'Landing pages', 'body' => 'Pixel-perfect development, QA and performance optimisation.', 'link' => '#contact', 'extra' => 'web'],
            ['title' => 'Motion production', 'body' => 'Social films, animated ads, explainers and campaign cuts.', 'link' => '#contact', 'extra' => 'motion'],
        ],
    ],
    'process' => [
        'label'  => 'Process steps',
        'fields' => ['title' => ['label' => 'Step name', 'type' => 'text'], 'body' => ['label' => 'Description', 'type' => 'textarea']],
        'seed'   => [
            ['title' => 'Brief received', 'body' => 'Figma, PSD, storyboard or master creative.'],
            ['title' => 'Production', 'body' => 'Design adaptation, animation and development.'],
            ['title' => 'Technical QA', 'body' => 'Dimensions, weight, clickTags, browsers and platform specs.'],
            ['title' => 'Delivery', 'body' => 'Structured ZIPs, backup JPGs, source files and QA report.'],
        ],
    ],
    'work' => [
        'label'  => 'Featured work',
        'fields' => [
            'subtitle' => ['label' => 'Category', 'type' => 'text'],
            'title'    => ['label' => 'Project name', 'type' => 'text'],
            'link'     => ['label' => 'Link', 'type' => 'text'],
            'media'    => ['label' => 'Thumbnail image or video', 'type' => 'media', 'help' => 'If empty, a built-in demo graphic is shown.'],
            'extra'    => ['label' => 'Demo graphic when no media', 'type' => 'select', 'options' => ['w1' => 'Dark / car', 'w2' => 'Beige / site', 'w3' => 'Red / promo']],
        ],
        'seed' => [
            ['subtitle' => 'Automotive', 'title' => 'Velocity Motors — Display set', 'link' => '#', 'extra' => 'w1'],
            ['subtitle' => 'Personal brand', 'title' => 'Coach Arjun — Business site', 'link' => '#', 'extra' => 'w2'],
            ['subtitle' => 'Beverage', 'title' => 'FizzUp — Promo portal', 'link' => '#', 'extra' => 'w3'],
        ],
    ],
    'compare' => [
        'label'  => 'Comparison table rows',
        'fields' => [
            'title'    => ['label' => 'Capability', 'type' => 'text'],
            'subtitle' => ['label' => 'Column 2 value', 'type' => 'text', 'help' => 'Type yes for ✓, no for —, or any text.'],
            'body'     => ['label' => 'Column 3 value', 'type' => 'text', 'help' => 'Type yes for ✓, no for —, or any text.'],
        ],
        'seed' => [
            ['title' => 'Dedicated team', 'subtitle' => 'no', 'body' => 'yes'],
            ['title' => 'Backup resources', 'subtitle' => 'no', 'body' => 'no'],
            ['title' => 'Scale during peak workloads', 'subtitle' => 'no', 'body' => 'no'],
            ['title' => 'Platform expertise (DV360, Celtra, SFMC...)', 'subtitle' => 'Varies', 'body' => 'Varies'],
            ['title' => '24–48 hour turnaround on banners and EDMs', 'subtitle' => 'no', 'body' => 'no'],
            ['title' => 'Structured QA on every delivery', 'subtitle' => 'no', 'body' => 'Varies'],
        ],
    ],
    'timeline' => [
        'label'  => 'While-you-sleep cards',
        'fields' => [
            'title'    => ['label' => 'Time', 'type' => 'text'],
            'subtitle' => ['label' => 'City', 'type' => 'text'],
            'body'     => ['label' => 'Caption', 'type' => 'text'],
            'media'    => ['label' => 'City image or video', 'type' => 'media', 'help' => 'If empty, a drawn skyline is shown.'],
            'extra'    => ['label' => 'Sky style when no media', 'type' => 'select', 'options' => ['dusk' => 'Evening', 'night' => 'Night', 'dawn' => 'Morning']],
        ],
        'seed' => [
            ['title' => '5:00 PM', 'subtitle' => 'New York', 'body' => 'Brief submitted', 'extra' => 'dusk'],
            ['title' => '8:00 AM', 'subtitle' => 'Gurugram', 'body' => 'Production runs', 'extra' => 'night'],
            ['title' => '9:00 AM', 'subtitle' => 'London', 'body' => 'Files ready for review', 'extra' => 'dawn'],
        ],
    ],
    'footer_company' => [
        'label'  => 'Footer — Company links',
        'fields' => ['title' => ['label' => 'Text', 'type' => 'text'], 'link' => ['label' => 'Link', 'type' => 'text']],
        'seed'   => [['title' => 'About Us', 'link' => '#'], ['title' => 'Services', 'link' => '#services'], ['title' => 'Why Us', 'link' => '#compare'], ['title' => 'Work', 'link' => '#work'], ['title' => 'Pricing', 'link' => '#'], ['title' => 'Blog', 'link' => '#'], ['title' => 'Contact', 'link' => '#contact']],
    ],
    'footer_services' => [
        'label'  => 'Footer — Services links',
        'fields' => ['title' => ['label' => 'Text', 'type' => 'text'], 'link' => ['label' => 'Link', 'type' => 'text']],
        'seed'   => [['title' => 'HTML5 Banners', 'link' => '#services'], ['title' => 'EDM / Newsletters', 'link' => '#services'], ['title' => 'Website Development', 'link' => '#services'], ['title' => 'Motion Graphics', 'link' => '#services']],
    ],
    'footer_resources' => [
        'label'  => 'Footer — Resources links',
        'fields' => ['title' => ['label' => 'Text', 'type' => 'text'], 'link' => ['label' => 'Link', 'type' => 'text']],
        'seed'   => [['title' => 'Retainer Packages', 'link' => '#'], ['title' => 'Why Offshore India', 'link' => '#'], ['title' => 'Get Quote', 'link' => '#contact'], ['title' => 'Security & IP', 'link' => '#'], ['title' => 'Privacy Policy', 'link' => '#'], ['title' => 'Cookie settings', 'link' => '#']],
    ],
    'footer_tools' => [
        'label'  => 'Footer — Free tools links',
        'fields' => ['title' => ['label' => 'Text', 'type' => 'text'], 'link' => ['label' => 'Link', 'type' => 'text']],
        'seed'   => [
            ['title' => 'Banner Resizer', 'link' => '#'], ['title' => 'HTML5 Ad Maker', 'link' => '#'], ['title' => 'Video Editor', 'link' => '#'],
            ['title' => 'PSD to HTML', 'link' => '#'], ['title' => 'Image Optimizer', 'link' => '#'], ['title' => 'HTML5 to GIF', 'link' => '#'],
            ['title' => 'HTML5 Ad Validator', 'link' => '#'], ['title' => 'Backup JPG Generator', 'link' => '#'], ['title' => 'SVG Optimizer', 'link' => '#'],
            ['title' => 'Email Preview', 'link' => '#'], ['title' => 'PDF Toolkit', 'link' => '#'], ['title' => 'Image Slicer', 'link' => '#'],
        ],
    ],
];
