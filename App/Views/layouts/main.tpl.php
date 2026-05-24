<!doctype html>
<html lang="en" dir="ltr" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title ?? config('WISE.name')) ?></title>
    <meta name="description" content="<?= e($Description ?? config('WISE.Description')) ?>">
    <meta name="keywords" content="<?= e($Keywords ?? config('WISE.Keywords')) ?>">
    <link href="<?= asset('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?= asset('assets/css/main.css'); ?>" rel="stylesheet">
    <script src="<?= asset('assets/js/mode.js'); ?>"></script>

    <link rel="manifest" href="<?= url('manifest.json'); ?>" />
    <meta property="og:title" content="<?= config('WISE.name') ?>" />
    <meta property="og:description" content="<?= e($Description ?? config('WISE.Description')) ?>" />
    <meta property="og:url" content="<?= config('WISE.base_url'); ?>" />
    <meta property="og:image" content="<?= url('assets/img/manifest/icon-310x310.png'); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="en_US" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@xDayeh" /> <!-- Project account needs to be changed -->
    <meta name="twitter:creator" content="@xDayeh" /> <!-- Project account needs to be changed -->
    <meta name="msapplication-TileColor" content="#2b3035">
    <meta name="msapplication-TileImage" content="<?= url('assets/img/manifest/icon-310x310.png'); ?>">
    <meta name="theme-color" content="#212529">
</head>
<body>
<?php if (!isset($hiddenNavbar) || !$hiddenNavbar) include_once 'navbar.tpl.php'; ?>
<div class="container-fluid mt-5 mt-print-0">
    <div class="row">
        <?php if (!empty($_SESSION['user'])): include_once 'sidebar.tpl.php'; ?>
        <main class="col-lg-11 col-sm-10 col-10 ms-auto pt-2 mb-5 main-content-for-print">
            <?php else: ?>
            <main class="col">
                <?php endif; ?>
                {{content}}
            </main>
    </div>
</div>
<?php include_once 'footer.tpl.php'; ?>
<script src="<?= asset('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= asset('assets/js/main.js'); ?>"></script>
</body>
</html>