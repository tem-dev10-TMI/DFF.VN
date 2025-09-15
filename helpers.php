<?php

function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function flash($k, $v = null){
    if ($v === null) {
        $v = $_SESSION['flash'][$k] ?? null;
        unset($_SESSION['flash'][$k]);
        return $v;
    } else {
        $_SESSION['flash'][$k] = $v;
    }
}
function redirect($url){ header('Location: ' . $url); exit; }
