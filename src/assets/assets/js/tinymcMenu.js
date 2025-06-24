const customMenu = {
    cards: {
        title: 'Cards',
        items: 'card-style-1'
    }
};

function registerMenu(editor) {
    //*************************************
    editor.ui.registry.addMenuItem('card-style-1', {
        text: 'Add Card Style (1)',
        onAction: function () {
            // Open a dialog to ask how many cards
            editor.windowManager.open({
                title: 'Add Cards Style (1)',
                body: {
                    type: 'panel',
                    items: [
                        {
                            type: 'input',
                            name: 'cardCount',
                            label: 'Number of Cards',
                            inputMode: 'numeric',
                            placeholder: 'e.g. 3'
                        }
                    ]
                },
                buttons: [
                    {
                        type: 'cancel',
                        text: 'Cancel'
                    },
                    {
                        type: 'submit',
                        text: 'Insert',
                        primary: true
                    }
                ],
                onSubmit: function (api) {
                    const data = api.getData();
                    const count = parseInt(data.cardCount);

                    if (isNaN(count) || count < 1) {
                        editor.windowManager.alert('Please enter a valid number greater than 0.');
                        return;
                    }

                    let allCardsHTML = '<div class="row">';
                    for (let i = 1; i <= count; i++) {
                        allCardsHTML += `
                                        <div class="col-lg-4 col-md-4 col-sm-12" style="margin-bottom:30px">
                                            <h2>Card Title ${i}</h2>
                                            <p>This is card number ${i}.</p>
                                        </div>
                                    `;

                    }
                    allCardsHTML += `</div>  &nbsp;`;

                    editor.insertContent(allCardsHTML);
                    api.close();
                }
            });
        }
    });
}

window.registerMenu = registerMenu;
window.customMenu = customMenu;