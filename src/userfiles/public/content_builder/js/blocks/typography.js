function typography_blocks(editor) {
    //header block
    editor.BlockManager.add('header', {
        type: 'header',
        activate: true,
        category: 'Typography',
        draggable: true,
        label: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill="currentColor" d="M16.553 4.003a.497.497 0 0 1 .447.506V15.5a.5.5 0 0 1-1 0V6.732a8.576 8.576 0 0 1-2.223 2.184a.5.5 0 1 1-.554-.832c1.415-.943 2.517-2.467 2.787-3.682a.5.5 0 0 1 .543-.4ZM2.5 4a.5.5 0 0 1 .5.5V9h6V4.5a.5.5 0 1 1 1 0v11a.5.5 0 0 1-1 0V10H3v5.5a.5.5 0 0 1-1 0v-11a.5.5 0 0 1 .5-.5Z"/></svg> <p>Header</p>
                `,
        content: `<div class="header">
    <h1 >I'm a header placeholder</h1>
  </div>`,
    });

    //paragraph block
    editor.BlockManager.add('paragraph', {
        type: 'paragraph',
        activate: true,
        category: 'Typography',
        draggable: true,
        label: `<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 256 256"><path fill="currentColor" d="M208 42H96a62 62 0 0 0 0 124h42v42a6 6 0 0 0 12 0V54h28v154a6 6 0 0 0 12 0V54h18a6 6 0 0 0 0-12Zm-70 112H96a50 50 0 0 1 0-100h42Z"/></svg>   <p>Paragraph</p>
                `,
        content: `<div  class="paragraph"><p >
   I am a paragraph.
  </p> </div>`,
    });
}