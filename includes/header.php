<?php
include_once(CLASS_PATH . "/AppConfig.php");
include_once(INCLUDE_PATH . "/functions.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="<?php echo CHARSET; ?>">
<title><?php echo PORTAL_TITLE; ?></title>

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL; ?>/css/style.css">

<!-- JS -->
<script src="<?php echo BASE_URL; ?>/js/jquery.js"></script>
<script src="<?php echo BASE_URL; ?>/js/menu.js" defer></script>
</head>
<body>

<?php
renderLogo();
$result = $db->callProcedure("sp_get_menu", ["adm"]);
$tree = [];
while ($row = $db->fetch($result)) {
    $tree[$row['cd_menu_parent']][] = $row;
}
echo buildMenu($tree);
?>
<hr>