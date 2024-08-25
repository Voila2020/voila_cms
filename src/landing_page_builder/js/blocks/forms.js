
function voila_forms(editor) {
    $.get(
        $_SITE + "/admin/forms/all-forms?landing_page_id=" + $id,
        function (data) {
            data.forEach((e) => {

                editor.BlockManager.add("voila_form" + e.id, {
                    activate: true,
                    category: "Forms: " + e.name,
                    label: `<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path class="gjs-block-svg-path" d="M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z" fill-rule="nonzero"></path>
                          <path class="gjs-block-svg-path" d="M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z" fill-rule="nonzero"></path>
                          <rect class="gjs-block-svg-path" x="2" y="15" width="10" height="3" rx="0.5"></rect>
                      </svg>
                      <p>${e.name}</p>
                      `,
                    content: `
                      <div class="pt-4 pb-4 ps-4 pe-4">
                              <h2 class="text-center">Get Started today!</h2>
                              <p class="text-center">Kindly take a moment to fill out the form below with your name and contact information.</p>
                                  ${e.html}
                              </div>
                              `,
                });


                editor.BlockManager.add("voila_form_column" + e.id, {
                    activate: true,
                    category: "Forms: " + e.name,
                    label: `<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path class="gjs-block-svg-path" d="M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z" fill-rule="nonzero"></path>
                <path class="gjs-block-svg-path" d="M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z" fill-rule="nonzero"></path>
                <rect class="gjs-block-svg-path" x="2" y="15" width="10" height="3" rx="0.5"></rect>
            </svg>
            <p>${e.name} - Two Columns</p>
            `,
                    content: `
                      <div class="container">
                          <div class="row flex-lg-row-reverse">
                          <div class="col-10 col-sm-8 col-lg-6 flex-direction-column">
                              <div class="pt-4 pb-4 ps-4 pe-4">
                                  <h2 class="text-center">Get Started today!</h2>
                                  <p class="text-center">Kindly take a moment to fill out the form below with your name and contact information.</p>
                                  ${e.html}
                              </div>
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
            `,
                });


                editor.BlockManager.add("voila_form_columns_image" + e.id, {
                    activate: true,
                    category: "Forms: " + e.name,
                    label: `<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path class="gjs-block-svg-path" d="M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z" fill-rule="nonzero"></path>
                          <path class="gjs-block-svg-path" d="M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z" fill-rule="nonzero"></path>
                          <rect class="gjs-block-svg-path" x="2" y="15" width="10" height="3" rx="0.5"></rect>
                      </svg>
                      <p>${e.name} - With Image</p>
                      `,
                    content: `
                      <div>
                          <div class="container">
                              <div class="row">
                                  <div class="col-md-12 col-lg-5 col-sm-12 center-all text-center flex-direction-column">
                                      <div class="pt-4 pb-4 ps-4 pe-4">
                                          <h2 class="text-center">Get Started today!</h2>
                                          <p class="text-center">Kindly take a moment to fill out the form below with your name and contact information.</p>
                                          ${e.html}
                                      </div>
                                  </div>
                                  <div class="col-lg-7 col-md-12 col-sm-12 center-all text-center flex-direction-column">
                                  <img src="https://bootstrapmade.com/demo/templates/FlexStart/assets/img/hero-img.png" class="img-fluid" alt="">
                                  </div>
                              </div>
                          </div>
                      </div>
                      `,
                });
            });

        }
    );
}
