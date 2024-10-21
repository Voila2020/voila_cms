

function headings_blocks(editor) {
editor.BlockManager.add("heading1", {
    activate: true,
    category: "Headings",
    label:
        '<embed  src="' +
        $_SITE +
        '/landing_page_builder/blocks/images/heading1.svg" alt=""><div class="draggable"></div><br> Heading1',
    content: `<div>
      <div class="container">
      <div class="center-all">
          <img class="d-block center-all text-center" src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
          <h1 class="display-5 fw text-center">Centered hero</h1>
          <div class="col-lg-6 ms-auto me-auto center-all text-center flex-direction-column">
              <p class="lead">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful JavaScript
                  plugins.
              </p>
              <div class="d-grid gap-2 d-sm-flex">
              <a href="#formLandingPage" type="button" class="second-background btn-voila-custom btn-lg gap-3">
                  Primary button
              </a>
              </div>
          </div>
      </div>
      </div>
      </div>`,
});


editor.BlockManager.add("heading2", {
    activate: true,
    category: "Headings",
    label:
        '<embed  src="' +
        $_SITE +
        '/landing_page_builder/blocks/images/heading2.svg" alt=""><div class="draggable"></div><br> Heading2',
    content: `
    <section id="hero" class="hero d-flex center-all text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 d-flex flex-column center-all text-center flex-direction-column">
                    <h1>We offer modern solutions for growing your business</h1>
                    <h2>We are team of talented designers making websites with Bootstrap</h2>
                    <div class="center-all text-center">
                        <div class="center-all text-center">
                            <a href="#formLandingPage" class="second-background btn-voila-custom btn-get-started scrollto center-all text-center">
                                  Get Started
                                <i class="bi bi-arrow-right" data-gjs-type="icon-block"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 hero-img center-all text-center flex-direction-column">
                    <img src="https://bootstrapmade.com/demo/templates/FlexStart/assets/img/hero-img.png" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </section>`,
});
}