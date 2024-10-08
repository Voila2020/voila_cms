function footers_blocks(editor) {
    editor.BlockManager.add("footer1", {
        activate: true,
        category: "Footers",
        label: '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/footer.svg" alt=""><div class="draggable"></div><br> Footer1',
        content: ` <div>
    <div class="container">
        <div class="col-sm-12 center-all text-center flex-direction-column">
            <p class="address center-all text-center">HAS HO Building, Second Floor - Mohammed Ibn Abdul Aziz St. </p>
            <p class="contact-us center-all text-center">Contact Us: +966 12 261 9667</p>
            <div class="social center-all text-center col-sm-12">
                <a target="_blank" class="text-decoration-none" href="https://twitter.com/Voila_digital"> <span class="fa-brands fa-square-x-twitter fa-2x"></span> </a>
                <a target="_blank" class="text-decoration-none" href="https://www.facebook.com/voila2006">
                    <span class="fa fa-facebook-square fa-2x"></span> </a>
                <a target="_blank" class="text-decoration-none" href="https://www.youtube.com/channel/UC4-HB822j9rNrxiVrErrz-Q">
                    <span class="fa fa-youtube-square fa-2x"></span> </a>
                <a target="_blank" class="text-decoration-none" href="https://www.linkedin.com/company/voila">
                    <span class="fa fa-linkedin fa-2x"></span> </a>
            </div>
        </div>
    </div>
    </div>`,
    });



    editor.BlockManager.add("footer2", {
        activate: true,
        category: "Footers",
        label: '<embed  src="' +
            $_SITE +
            '/landing_page_builder/blocks/images/footer.svg" alt=""><div class="draggable"></div><br> Footer2',
        content: `<div style="background-color:#262627">
    <div class="col-sm-12 center-all text-center flex-direction-column">
        <div class="social center-all text-center col-sm-12">
            <a target="_blank" href="https://twitter.com/ctelecoms2007"
                class="text-decoration-none">
                <span class="fa-brands fa-square-x-twitter fa-2x first-color m-2"></span>
            </a>
            <a target="_blank" href="https://www.facebook.com/pages/Ctelecoms/1567811026830567?ref=hl" class="text-decoration-none">
                <span class="fa fa-facebook-square fa-2x first-color m-2"></span>
            </a>
            <a target="_blank" href="https://www.youtube.com/channel/UCLgRAm3ywVYwIK5I4N4jBGQ" class="text-decoration-none">
                <span class="fa fa-youtube-square fa-2x first-color m-2"></span>
            </a>
            <a target="_blank" href="https://www.linkedin.com/company/ctelecoms" class="text-decoration-none">
                <span class="fa fa-linkedin fa-2x first-color m-2"></span>
            </a>
        </div>
    </div>
</div>`,
    });

}