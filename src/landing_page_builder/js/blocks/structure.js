function structures(editor) {


    editor.BlockManager.add("structure1", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1z"/></svg>
         <p>Structure 1</p>
            `,

        content: `<div class="container">
                     <div class="row">
                         <div class="col"></div>
                     </div>
                </div>`,
    });

    editor.BlockManager.add("structure2", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm9-1v18"/></svg>
        <p>Structure 2</p>
            `,

        content: `<div class="container">
                     <div class="row">
                        <div class="col"></div>
                        <div class="col"></div>
                    </div>
                </div>`,
    });

    editor.BlockManager.add("structure3", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm6-1v18m6-18v18"/></svg>
         <p>Structure 3</p>
            `,

        content: `<div class="container">
                    <div class="row">
                      <div class="col"></div>
                      <div class="col"></div>
                     <div class="col"></div>
                  </div>
        </div>`,
    });

    editor.BlockManager.add("structure4", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 2048 2048"><path fill="currentColor" d="M0 256h384v1536H0V256zm128 1408h128V384H128v1280zm896-1408h384v1536h-384V256zm128 1408h128V384h-128v1280zM512 256h384v1536H512V256zm128 1408h128V384H640v1280zM1920 256v1536h-384V256h384zm-128 128h-128v1280h128V384z"/></svg>
          <p>Structure 4</p>
            `,

        content: `<div class="container">
                    <div class="row">
                         <div class="col"> </div>
                          <div class="col"></div>
                         <div class="col"> </div>
                  <div class="col"></div>
        </div>
        </div>`,
    });

    editor.BlockManager.add("structure5", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 14 14"><g transform="translate(0 14) scale(1 -1)"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="13" height="13" x=".5" y=".5" rx="1"/><path d="M9.5.5v13"/></g></g></svg>
         <p>Structure 5</p>
            `,

        content: `<div class="container">
        <div class="row">
        
        <div class="col-8"></div>
        <div class="col-4"></div>
        </div>
        </div>`,
    });

    editor.BlockManager.add("structure6", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14"><g transform="rotate(180 7 7)"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><rect width="13" height="13" x=".5" y=".5" rx="1"/><path d="M9.5.5v13"/></g></g></svg>  <p>Structure 5</p>
            `,

        content: `<div class="container">
        <div class="row">
        
       
        <div class="col-4"></div>
        <div class="col-8"></div>
        </div>
        </div>`,
    });

}