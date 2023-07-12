function structures(editor) {

    editor.BlockManager.add("structure1", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `
        <div class="container custom-padding" style="background-color:white">
        <div class="row outlines" style="background-color:#e6f0f1">
            <div class="col-sm col-md col-lg col-xl outlines" ></div>
        </div>
   </div>
<div class="draggable"></div><br> structure 1  `,
        content: `<div class="container custom-padding">
                     <div class="row">
                         <div class="col-sm col-md col-lg col-xl"></div>
                     </div>
                </div>`,
    });

    editor.BlockManager.add("structure2", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `
        <div class="container custom-padding" style="background-color:white">
                     <div class="row"  style="background-color:#e6f0f1">
                        <div class="col-sm col-md col-lg col-xl outlines" ></div>
                        <div class="col-sm col-md col-lg col-xl outlines"></div>
                    </div>
                </div>
<div class="draggable"></div><br> structure 2`,

        content: `<div class="container custom-padding">
                     <div class="row">
                        <div class="col-sm col-md col-lg col-xl "></div>
                        <div class="col-sm col-md col-lg col-xl"></div>
                    </div>
                </div>`,
    });

    editor.BlockManager.add("structure3", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `
        <div class="container custom-padding" style="background-color:white">
        <div class="row" style="background-color:#e6f0f1">
          <div class="col-sm col-md col-lg col-xl outlines"></div>
          <div class="col-sm col-md col-lg col-xl outlines"></div>
         <div class="col-sm col-md col-lg col-xl outlines"></div>
      </div>
</div>
<div class="draggable"></div><br> structure 3`,

        content: `<div class="container custom-padding">
                    <div class="row">
                      <div class="col-sm col-md col-lg col-xl"></div>
                      <div class="col-sm col-md col-lg col-xl"></div>
                     <div class="col-sm col-md col-lg col-xl"></div>
                  </div>
        </div>`,
    });

    editor.BlockManager.add("structure4", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `

        <div class="container custom-padding" style="background-color:white">
                    <div class="row outlines" style="background-color:#e6f0f1">
                         <div class="col-sm col-md col-lg col-xl outlines"> </div>
                          <div class="col-sm col-md col-lg col-xl outlines"></div>
                         <div class="col-sm col-md col-lg col-xl outlines"> </div>
                  <div class="col-sm col-md col-lg col-xl outlines"></div>
        </div>
        </div><div class="draggable"></div><br>structure 3
            `,

        content: `<div class="container custom-padding">
                    <div class="row">
                         <div class="col-sm col-md col-lg col-xl"> </div>
                          <div class="col-sm col-md col-lg col-xl"></div>
                         <div class="col-sm col-md col-lg col-xl"> </div>
                  <div class="col-sm col-md col-lg col-xl"></div>
        </div>
        </div>`,
    });

    editor.BlockManager.add("structure5", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `<div class="container custom-padding" style="background-color:white"  >
        <div class="row outlines" style="background-color:#e6f0f1">

        <div class="col-12 col-md-8 outlines"></div>
        <div class="col-6 col-md-4 outlines"></div>
        </div>
        </div><div class="draggable"></div><br> structure 5
            `,

        content: `<div class="container custom-padding" >
        <div class="row">

        <div class="col-12 col-md-8"></div>
        <div class="col-6 col-md-4"></div>
        </div>
        </div>`,
    });

    editor.BlockManager.add("structure6", {
        activate: true,
        category: "Structure",
        draggable: true,
        label: `<div class="container custom-padding" style="background-color:white">
        <div class="row outlines"  style="background-color:#e6f0f1">


        <div class="col-6 col-md-4 outlines"></div>
        <div class="col-12 col-md-8 outlines"></div>
        </div>
        </div><div class="draggable"></div><br> structure 6  `,

        content: `<div class="container custom-padding">
        <div class="row">


        <div class="col-6 col-md-4"></div>
        <div class="col-12 col-md-8"></div>
        </div>
        </div>`,
    });

}
