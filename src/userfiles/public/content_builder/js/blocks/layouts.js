function layouts_blocks(editor) {
    //container block
    editor.BlockManager.add('container', {
        activate: true,
        
        category: 'Layout',
        type: 'container',
        draggable: true,
        label: `<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 36 36"><path fill="currentColor" d="M32 30H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h28a2 2 0 0 1 2 2v20a2 2 0 0 1-2 2ZM4 8v20h28V8Z" class="clr-i-outline clr-i-outline-path-1"/><path fill="currentColor" d="M9 25.3a.8.8 0 0 1-.8-.8v-13a.8.8 0 0 1 1.6 0v13a.8.8 0 0 1-.8.8Z" class="clr-i-outline clr-i-outline-path-2"/><path fill="currentColor" d="M14.92 25.3a.8.8 0 0 1-.8-.8v-13a.8.8 0 0 1 1.6 0v13a.8.8 0 0 1-.8.8Z" class="clr-i-outline clr-i-outline-path-3"/><path fill="currentColor" d="M21 25.3a.8.8 0 0 1-.8-.8v-13a.8.8 0 0 1 1.6 0v13a.8.8 0 0 1-.8.8Z" class="clr-i-outline clr-i-outline-path-4"/><path fill="currentColor" d="M27 25.3a.8.8 0 0 1-.8-.8v-13a.8.8 0 0 1 1.6 0v13a.8.8 0 0 1-.8.8Z" class="clr-i-outline clr-i-outline-path-5"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                <p>Container</p>
                `,
        content: `<div class="container"></div>`,
    });


    //row block 
    editor.BlockManager.add('Row', {
        activate: true,
        draggable: true,
        category: 'Layout',
        type: "Row",

        label: `<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><path fill="currentColor" d="M2 5h20v14H2V5zm2 2v4h16V7H4zm16 6H4v4h16v-4z"/></svg>   <p>Row</p>
                `,
        content: `<div class="row"></div>`,
    });


    //column block
    editor.BlockManager.add("col", {
        activate: true,
        category: "Layout",
        draggable: true,
        type: 'col',
        label: `<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24"><path fill="currentColor" d="M3 19V5h17.975v14H3Zm2-2h3.325V7H5v10Zm5.325 0h3.325V7h-3.325v10Zm5.325 0h3.325V7H15.65v10Z"/></svg>
            <p>Col</p>
            `,

        content: `<div class="col"></div>`,
    });
}