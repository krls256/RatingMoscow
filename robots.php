<?php
    header("content-type: text/plain; charset=utf-8");

    $protocol = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://';
    $URL = $protocol . $_SERVER['SERVER_NAME'];
    
    echo "User-agent: *" . PHP_EOL;
    echo "Disallow: /admin/" . PHP_EOL . PHP_EOL;
    
    echo "Sitemap: " . $URL . "/sitemap.xml";
?>