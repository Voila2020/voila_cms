function features_blocks(editor) {
editor.BlockManager.add("feature-image", {
    activate: true,
    category: "Features",
    label:
        '<embed  src="' +
        $_SITE +
        '/landing_page_builder/blocks/images/feature_image.svg" alt=""><div class="draggable"></div><br> Feature with Image',
    content: `  <div>
    <div class="container">
        <div class="row flex-lg-row-reverse center-all text-center">
            <div class="col-10 col-sm-8 col-lg-6 center-all text-center flex-direction-column">
                <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="d-block ms-auto me-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
            </div>
            <div class="col-lg-6 center-all text-center flex-direction-column">
                <h1 class="display-5">Responsive left-aligned hero with image</h1>
                <p class="lead">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful
                    JavaScript plugins.</p>
                <div class="d-grid gap-2 d-md-flex left-all text-left">
                <a href="#formLandingPage" style="color:white" class="second-background btn-voila-custom btn-lg">
                Primary
             </a>
                </div>
            </div>
        </div>
    </div>
    </div>`,
});


editor.BlockManager.add("feature-image1", {
    activate: true,
    category: "Features",
    label:
        '<embed  src="' +
        $_SITE +
        '/landing_page_builder/blocks/images/feature_image_r.svg" alt=""><div class="draggable"></div><br> Feature with Image (Reverse)',
    content: ` <div>
    <div class="container">
        <div class="row flex-lg-row-reverse center-all text-center">
            <div class="col-lg-6 center-all text-center flex-direction-column">
                <h1 class="display-5">Responsive left-aligned hero with image</h1>
                <p class="lead">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful
                    JavaScript plugins.</p>
                <div class="d-grid gap-2 d-md-flex left-all text-left">
                <a href="#formLandingPage" style="color:white" class="second-background btn-voila-custom btn-lg">
                Primary
             </a>
                </div>
            </div>
            <div class="col-10 col-sm-8 col-lg-6 center-all text-center flex-direction-column">
                <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="d-block ms-auto me-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
            </div>
        </div>
    </div>
    </div>`,
});



editor.BlockManager.add("feature-3-images", {
    activate: true,
    category: "Features",
    label:
        '<embed  src="' +
        $_SITE +
        '/landing_page_builder/blocks/images/3_features_img.svg" alt=""><div class="draggable"></div><br> 3 Feature (Images)',
    content: `<style>
    .feature-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 4rem;
        height: 4rem;
        margin-bottom: 1rem;
        font-size: 2rem;
        color: #fff;
        border-radius: .75rem;
    }
</style>
<div>
<div class="container">
    <h2 class=" center-all text-center">Columns with icons</h2>
    <div class="row g-4 row-cols-1 row-cols-lg-3">
        <div class="feature col center-all text-center">
            <div class="">
            <img src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72">
            </div>
            <h2>Featured title</h2>
            <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
            <a href="#formLandingPage" class="icon-link">
          Call to action
          <i class="fa fa-arrow-right fa-sm" data-gjs-type="icon-block"></i>
        </a>
        </div>
        <div class="feature col center-all text-center">
            <div class="">
            <img src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72">
            </div>
            <h2>Featured title</h2>
            <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
            <a href="#formLandingPage" class="icon-link">
          Call to action
          <i class="fa fa-arrow-right fa-sm" data-gjs-type="icon-block"></i>
        </a>
        </div>
        <div class="feature col center-all text-center">
            <div class="">
            <img src="https://getbootstrap.com/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72">

            </div>
            <h2>Featured title</h2>
            <p>Paragraph of text beneath the heading to explain the heading. We'll add onto it with another sentence and probably just keep going until we run out of words.</p>
            <a href="#formLandingPage" class="icon-link">
          Call to action
          <i class="fa fa-arrow-right fa-sm" data-gjs-type="icon-block"></i>

        </a>
        </div>
    </div>
</div>
</div>`,
});


editor.BlockManager.add("feature-cards", {
    activate: true,
    category: "Features",
    label:
        '<embed  src="' +
        $_SITE +
        '/landing_page_builder/blocks/images/features_cards.svg" alt=""><div class="draggable"></div><br> Features Cards',
    content: `<div>
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3 flex-wrap-wrap center-all text-center">
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                              View
                            </a>
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                               Edit
                            </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                            View
                          </a>
                          <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                             Edit
                          </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                            View
                          </a>
                          <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                             Edit
                          </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                            View
                          </a>
                          <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                             Edit
                          </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                            View
                          </a>
                          <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                             Edit
                          </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIHZpZXdCb3g9IjAgMCAyNCAyNCIgc3R5bGU9ImZpbGw6IHJnYmEoMCwwLDAsMC4xNSk7IHRyYW5zZm9ybTogc2NhbGUoMC43NSkiPgogICAgICAgIDxwYXRoIGQ9Ik04LjUgMTMuNWwyLjUgMyAzLjUtNC41IDQuNSA2SDVtMTYgMVY1YTIgMiAwIDAgMC0yLTJINWMtMS4xIDAtMiAuOS0yIDJ2MTRjMCAxLjEuOSAyIDIgMmgxNGMxLjEgMCAyLS45IDItMnoiPjwvcGF0aD4KICAgICAgPC9zdmc+"
                        class="card-img-top" />
                    <div class="card-body">
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
                        </p>
                        <div class="d-flex justify-content-between center-all text-center">
                            <div role="group" class="btn-group">
                            <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                            View
                          </a>
                          <a class="second-background btn-voila-custom btn-sm btn-outline-secondary">
                             Edit
                          </a>
                            </div>
                            <small class="text-muted">9 mins</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>`,
});

editor.BlockManager.add("section1", {
    activate: true,
    category: "Features",
    label:
        '<embed  src="' +
        $_SITE +
        '/landing_page_builder/blocks/images/icons_titles.svg" alt=""><div class="draggable"></div><br> 3 Icons with Title',
    content: `<div>
    <div class="container">
     <div class="row g-4 row-cols-1 row-cols-lg-3 center-all text-center">
      <div class="col">
       <div class="row center-all text-center">
        <div class="col-4 flex-direction-column">
         <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="img-fluid">
        </div>
        <div class="col-8 flex-direction-column">
           Advanced security
        </div>
       </div>
      </div>
      <div class="col">
       <div class="row center-all text-center">
        <div class="col-4 flex-direction-column">
         <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="img-fluid">
        </div>
        <div class="col-8 flex-direction-column">
          Access and data control
        </div>
       </div>
      </div>
      <div class="col">
        <div class="row center-all text-center">
          <div class="col-4 flex-direction-column">
            <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="img-fluid">
          </div>
          <div class="col-8 flex-direction-column">
             Cyberthreat protection
           </div>
        </div>
      </div>
     </div>
    </div>
   </div>
   `,
});



editor.BlockManager.add("featureVideo", {
    activate: true,
    category: "Features",
    label:
        '<embed  src="' +
        $_SITE +
        '/landing_page_builder/blocks/images/video.svg" alt=""><div class="draggable"></div><br> Video with Text',
    content: `  <section id="hero" class="hero d-flex center-all text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 hero-img center-all text-center flex-direction-column">
                <video controls></video>
            </div>
            <div class="col-lg-6 d-flex flex-column center-all text-center flex-direction-column">
                <h1>We offer modern solutions for growing your business</h1>
                <h2>We are team of talented designers making websites with Bootstrap</h2>
                <div class="center-all text-center">
                    <div class="center-all text-center">
                        <a href="#formLandingPage" class="second-background btn-voila-custom btn-get-started scrollto center-all text-center">
                              Get Started
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
`,
});

editor.BlockManager.add("iconsWithImg", {
    activate: true,
    category: "Features",
    label:
        '<embed  src="' +
        $_SITE +
        '/landing_page_builder/blocks/images/icons_with_link.svg" alt=""><div class="draggable"></div><br> Image with Icons',
    content: `<section>
      <div class="container">
          <div class="row">
              <div class="col-lg-6 flex-direction-column">
                  <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" class="img-fluid">
              </div>
              <div class="col-lg-6 flex-direction-column">
                  <div>
                      <h3>Test</h3>
                  </div>
                  <div class="d-flex">
                      <div class="col-2 flex-direction-column">
                          <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" alt="">
                      </div>
                      <div class="col-10 ps-4 flex-direction-column">
                          <p>Lorem ipsum dolor sit amet consectetur adipiscing elit Integer adipiscing erat</p>
                      </div>
                  </div>

                  <div class="d-flex">
                      <div class="col-2 flex-direction-column">
                          <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" alt="">
                      </div>
                      <div class="col-10 ps-4 flex-direction-column">
                          <p>Lorem ipsum dolor sit amet consectetur adipiscing elit Integer adipiscing erat</p>
                      </div>
                  </div>

                  <div class="d-flex">
                      <div class="col-2 flex-direction-column">
                          <img src="https://getbootstrap.com/docs/5.1/examples/heroes/bootstrap-themes.png" alt="">
                      </div>
                      <div class="col-10 ps-4 flex-direction-column">
                          <p>Lorem ipsum dolor sit amet consectetur adipiscing elit Integer adipiscing erat</p>
                      </div>
                  </div>
                  <div class="text-center">
                      <a href="#formLandingPage" class="second-background btn-voila-custom btn-get-started scrollto center-all text-center">
                          Get Started
                      </a>
                  </div>
              </div>


          </div>
      </div>
  </section>
     `,
});

}