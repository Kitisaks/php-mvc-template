<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="author" content="<?= SITE["author"] ?? "DEVED-FRAMEWORK" ?>">
    <meta name="csrf_token" content="<?= $_SESSION["_csrf_token"] ?>">
    <title><?= SITE["title"] ?></title>
    <link rel="icon" href="https://tailwindui.com/img/logos/workflow-mark.svg?color=indigo&shade=600">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <?= App\View::assets_include() ?>
</head>
<body>
    <?= App\View::partial(LayoutView::class, "_header.html") ?>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:max-w-7xl lg:px-8 lg:grid lg:grid-cols-12 lg:gap-8">
            <?= App\View::partial(LayoutView::class, "_sidebar.html") ?>

            <main class="lg:col-span-9 xl:col-span-6">
                <?= $GLOBALS["@inner_content@"] ?>
            </main>

            <?= App\View::partial(LayoutView::class, "_aside.html") ?>
        </div>
    </div>
    
</body>
</html>