<?php
/**
 * Global Theme Configuration & Tokens Loader
 * "Fresh light + coral" Design System
 * includes/theme.php
 */
if (!defined('BASE_URL')) {
    $docRoot  = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\')) : '';
    $projRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    if ($docRoot !== '' && $projRoot !== false && strpos($projRoot, $docRoot) === 0) {
        $subPath = trim(substr($projRoot, strlen($docRoot)), '/');
        define('BASE_URL', $subPath === '' ? '/' : '/' . $subPath . '/');
    } else {
        define('BASE_URL', '/');
    }
}
?>
<!-- Google Fonts: Plus Jakarta Sans (600, 700) & Inter (400, 500) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

<!-- Global CSS Tokens -->
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/tokens.css">

<!-- Tailwind Theme Configuration Mapped to CSS Custom Properties -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
tailwind.config = {
  theme: {
    extend: {
      colors: {
        bg:            "var(--bg)",
        surface:       "var(--surface)",
        tint:          "var(--tint)",
        teal:          "var(--teal)",
        "teal-text":   "var(--teal-text)",
        ink:           "var(--ink)",
        muted:         "var(--muted)",
        border:        "var(--border)",
        coral:         "var(--coral)",
        "coral-hover": "var(--coral-hover)",
        dark:          "var(--dark)",
        "on-dark":     "var(--on-dark)",
        /* Legacy aliases mapped to tokens so existing classes resolve cleanly */
        primary:             "var(--teal-text)",
        "primary-container": "var(--tint)",
        background:          "var(--bg)",
        "background-light":  "var(--bg)",
        "background-dark":   "var(--dark)",
        "on-surface":        "var(--ink)",
        "on-surface-variant":"var(--muted)",
        "surface-container-low":    "var(--tint)",
        "surface-container-lowest": "var(--surface)",
        "outline-variant":   "var(--border)",
        "dark-blue":         "var(--ink)",
        "accent-deep":       "var(--ink)",
        "accent-blue":       "var(--ink)"
      },
      fontFamily: {
        headline: ["Plus Jakarta Sans", "system-ui", "-apple-system", "sans-serif"],
        heading:  ["Plus Jakarta Sans", "system-ui", "-apple-system", "sans-serif"],
        display:  ["Inter", "system-ui", "-apple-system", "sans-serif"],
        body:     ["Inter", "system-ui", "-apple-system", "sans-serif"],
        sans:     ["Inter", "system-ui", "-apple-system", "sans-serif"],
        label:    ["Inter", "system-ui", "-apple-system", "sans-serif"]
      },
      borderRadius: {
        btn:  "10px",
        card: "12px",
        DEFAULT: "10px",
        lg:   "12px",
        xl:   "12px",
        "2xl":"12px",
        full: "9999px"
      },
      maxWidth: {
        "7xl": "1200px"
      }
    }
  }
};
</script>
