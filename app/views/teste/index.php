<?php
$basePath = isset($_SERVER['APP_BASE_PATH']) ? (string)$_SERVER['APP_BASE_PATH'] : '';
$testeScriptPath = __DIR__ . '/../../../public/js/teste/script.js';
$testeScriptVersion = file_exists($testeScriptPath) ? filemtime($testeScriptPath) : time();
$globalStylePath = __DIR__ . '/../../../public/css/global/style.css';
$globalStyleVersion = file_exists($globalStylePath) ? filemtime($globalStylePath) : time();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <input type="text" id="texto">
        TdASDHakjdhakJHDKJAHDKJASHJ
    </div>

    <?php include_once __DIR__ . '/../../../includes/footer.php'; ?>
    <?php include_once __DIR__ . '/../../../includes/infoAside.php'; ?>
    <?php include_once __DIR__ . '/../../../includes/dependencies.php'; ?>

    <script src="<?= $basePath ?>/public/js/shared/aside-chatbot.js"></script>
    <script src="<?= $basePath ?>/public/js/teste/script.js?v=<?= $testeScriptVersion ?>"></script>
</body>

</html>