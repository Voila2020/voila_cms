function navigation_blocks(editor) {
    editor.BlockManager.add("basic-nav", {
        activate: true,
        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/basic_nav1.svg" alt=""><div class="draggable"></div><br> Baisc nav',
        content: `<nav class="navbar navbar-expand-md" aria-label="Fourth navbar example">
    <div class="container">
        <a href="#" class="navbar-brand">
            <img src="${$_SITE}/images/logo.png" width="200" height="50" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
            </ul>
        </div>
    </div>
</nav>`,
    });



    editor.BlockManager.add("fixed-nav", {
        activate: true,
        order: 1,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/basic_nav1.svg" alt=""><div class="draggable"></div><br> Fixed Nav',
        content: ` <nav class="navbar navbar-expand-md fixed-top" aria-label="Fourth navbar example">
    <div class="container">
        <a href="#" class="navbar-brand">
            <img src="${$_SITE}/images/logo.png" width="200" height="50" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
            </ul>
        </div>
    </div>
</nav>`,
    });

    editor.BlockManager.add("nav_ltr", {

        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/nav_2_logos.svg" alt=""><div class="draggable"></div><br> Navigation 2 Logos',
        content: `<nav class="navbar navbar-expand-md" aria-label="Fourth navbar example">
    <div class="container">

        <div class="col">
            <img src="${$_SITE}/images/logo.png" width="200" height="50" />
        </div>
        <div class="col text-right">
            <img src="${$_SITE}/images/logo.png" width="200" height="50" />
        </div>

    </div>
</nav>`,
    });


    editor.BlockManager.add("nav3_ltr", {
        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/logos3.svg" alt=""><div class="draggable"></div><br> Navigation 3 Logos',
        content: `<nav class="navbar navbar-expand-md" aria-label="Fourth navbar example">
<div class="container">

    <div class="col">
        <img src="${$_SITE}/images/logo.png" width="200" height="50" />
    </div>
    <div class="col text-center">
        <img src="${$_SITE}/images/logo.png" width="200" height="50" />
    </div>
    <div class="col text-right">
        <img src="${$_SITE}/images/logo.png" width="200" height="50" />
    </div>

</div>
</nav>`,
    });



    editor.BlockManager.add("nav_rtl", {

        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/nav_2_logos.svg" alt=""><div class="draggable"></div><br> Navigation 2 Logos (Arabic)',
        content: `<nav class="navbar navbar-expand-md" aria-label="Fourth navbar example">
    <div class="container">
    <div class="col">
       <img src="${$_SITE}/images/logo.png" width="200" height="50" />
    </div>
    <div class="col text-right">
       <img src="${$_SITE}/images/logo.png" width="200" height="50" />
    </div>
    </div>
</nav>`,
    });



    editor.BlockManager.add("nav2_ltr", {
        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/nav_logo_contact.svg" alt=""><div class="draggable"></div><br> Navigation with Contact',
        content: ` <nav class="navbar navbar-expand-md " aria-label="Fourth navbar example">
    <div class="container">
        <a href="#" class="navbar-brand me-auto">
            <img src="${$_SITE}/images/logo.png" width="200" height="50" />
        </a>
        <div class="row">
            <div class="col-lg-6 center-all text-center">
                <div class="row">
                    <div class="col-sm-3 center-all text-center">
                        <i class="fa fa-envelope" data-gjs-type="icon-block"></i>
                    </div>
                    <div class="col-sm-9 flex flex-direction-column">
                        <strong>Contact us</strong>
                        <a href="mailto:info@voila.digital">info@voila.digital</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 center-all text-center">
                <div class="row">
                    <div class="col-sm-3 center-all text-center">
                        <i class="fa fa-phone" data-gjs-type="icon-block"></i>
                    </div>
                    <div class="col-sm-9 flex flex-direction-column">
                        <strong>Call us</strong>
                        <a href="tel:00966122619667">00966122619667</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>`,
    });


    editor.BlockManager.add("nav2_rtl", {
        order: 0,
        category: "Navigation",
        label:
            '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/nav_logo_contact.svg" alt=""><div class="draggable"></div><br> Navigation with Contact',
        content: ` <nav class="navbar navbar-expand-md " aria-label="Fourth navbar example">
    <div class="container">
        <a href="#" class="navbar-brand ms-auto">
            <img src="${$_SITE}/images/logo.png" width="200" height="50" />
        </a>
        <div class="row">
            <div class="col-lg-6 center-all text-center">
                <div class="row">
                    <div class="col-sm-3 center-all text-center">
                        <i class="fa fa-envelope" data-gjs-type="icon-block"></i>
                    </div>
                    <div class="col-sm-9 flex flex-direction-column">
                        <strong>Contact us</strong>
                        <a href="mailto:info@voila.digital">info@voila.digital</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 center-all text-center">
                <div class="row">
                    <div class="col-sm-3 center-all text-center">
                        <i class="fa fa-phone" data-gjs-type="icon-block"></i>
                    </div>
                    <div class="col-sm-9 flex flex-direction-column">
                        <strong>Call us</strong>
                        <a href="tel:00966122619667">00966122619667</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>`,
    });
}