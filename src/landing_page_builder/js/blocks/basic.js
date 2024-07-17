function basic_blocks(editor) {
    // Add blocks to basic tab
    editor.BlockManager.add('text', {
        id: 'text',
        label: 'Text',
        category: 'Basic',
        media: `<svg  viewBox="0 0 24 24">
        <path fill="currentColor" d="M18.5,4L19.66,8.35L18.7,8.61C18.25,7.74 17.79,6.87 17.26,6.43C16.73,6 16.11,6 15.5,6H13V16.5C13,17 13,17.5 13.33,17.75C13.67,18 14.33,18 15,18V19H9V18C9.67,18 10.33,18 10.67,17.75C11,17.5 11,17 11,16.5V6H8.5C7.89,6 7.27,6 6.74,6.43C6.21,6.87 5.75,7.74 5.3,8.61L4.34,8.35L5.5,4H18.5Z" />
        </svg>`,
        activate: true,
        content: {
            type: 'text',
            content: 'Insert your text here',
            style: { padding: '10px' },
        }
    });

    editor.BlockManager.add('link', {
        id: 'link',
        label: 'Link',
        category: 'Basic',
        media: `<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 20 20"><path fill="currentColor" d="M9.26 13a2 2 0 0 1 .01-2.01A3 3 0 0 0 9 5H5a3 3 0 0 0 0 6h.08a6.06 6.06 0 0 0 0 2H5A5 5 0 0 1 5 3h4a5 5 0 0 1 .26 10zm1.48-6a2 2 0 0 1-.01 2.01A3 3 0 0 0 11 15h4a3 3 0 0 0 0-6h-.08a6.06 6.06 0 0 0 0-2H15a5 5 0 0 1 0 10h-4a5 5 0 0 1-.26-10z"/></svg>`,
        activate: true,
        content: {
            type: 'link',
            content: 'Insert your link here',
            style: { color: '#d983a6' }
        }
    });

    editor.BlockManager.add('image', {
        id: 'image',
        label: 'Image',
        category: 'Basic',
        media: `<svg  viewBox="0 0 24 24">
        <path fill="currentColor" d="M8.5,13.5L11,16.5L14.5,12L19,18H5M21,19V5C21,3.89 20.1,3 19,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19Z" />
        </svg>`,
        activate: true,
        content: { type: 'image' }
    });

    editor.BlockManager.add('video', {
        id: 'video',
        label: 'Video',
        category: 'Basic',
        media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 128C0 92.7 28.7 64 64 64H320c35.3 0 64 28.7 64 64V384c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V128zM559.1 99.8c10.4 5.6 16.9 16.4 16.9 28.2V384c0 11.8-6.5 22.6-16.9 28.2s-23 5-32.9-1.6l-96-64L416 337.1V320 192 174.9l14.2-9.5 96-64c9.8-6.5 22.4-7.2 32.9-1.6z"/></svg>`,
        activate: true,
        content: { type: 'video' }
    });

    editor.BlockManager.add('icon-block', {
        label: 'Icon Block',
        category: 'Basic',
        media: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
  <!-- Star icon -->
  <path d="M257.8 11.7l-64.2 134.6-145.3 22.3c-12.2 1.9-17.1 17.2-7.8 25.8L123 259.4l-29.5 144.2c-2.1 12.9 11.2 22.7 22.9 16.8L256 388.7l140.6 74.7c11.6 6.2 25-4 22.9-16.8L388 259.4l92.6-105c9.3-8.6 4.4-23.9-7.8-25.8l-145.2-22.3-64.2-134.6c-4.8-10-18.8-10-23.6 0z"/>
</svg>`,
        content: {
            type: 'icon-block',
        },
    });

}
