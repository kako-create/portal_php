<?php
function buildMenu($items, $parent = 0, $isRoot = true) {
    $html = $isRoot ? '<ul id="menu">' : '<ul class="submenu">';
    if (isset($items[$parent])) {
        foreach ($items[$parent] as $item) {
            $link   = $item['de_link']   ?: '#';
            $target = $item['de_target'] ?: '_self';
            $html .= "<li>";
            $html .= "<a href='{$link}' target='{$target}'>{$item['nm_menu']}</a>";
            if (isset($items[$item['cd_menu']])) {
                $html .= buildMenu($items, $item['cd_menu'], false);
            }
            $html .= "</li>";
        }
    }
    $html .= "</ul>";
    return $html;
}

function renderLogo() {
    if (defined("PORTAL_LOGO")) {
        echo '<div class="logo"
            style="background: linear-gradient(to right, white 230px, '. PORTAL_LOGO_BACKGROUND .' 0%, transparent 100%);
                   background-size: auto 100%, 100% 100%;">
            <img src="' . PORTAL_LOGO . '" alt="Logo do Portal"></div>';
    } else {
        echo '<div class="logo"><span>Logo não configurada</span></div>';
    }
}
