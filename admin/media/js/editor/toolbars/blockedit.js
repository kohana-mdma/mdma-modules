var RTOOLBAR = {
    html: { name: 'html', title: RLANG.html, func: 'toggle' },
    fullscreen: { name: 'fullscreen', title: RLANG.fullscreen, func: 'fullscreen' },
    separator1: { name: 'separator' },
    bold:   {exec: 'Bold', name: 'bold', title: RLANG.bold},
    italic:     {exec: 'italic', name: 'italic', title: RLANG.italic},
    ul:      {exec: 'insertunorderedlist', name: 'unorderlist', title: '&bull; ' + RLANG.unorderedlist},
    ol:      {exec: 'insertorderedlist', name: 'orderlist', title: '1. ' + RLANG.orderedlist},
    separator3: { name: 'separator' },
    image: { name: 'image', title: RLANG.image, func: 'showImage' },
    link:
    {
        name: 'link', title: RLANG.link, func: 'show',
        dropdown:
        {
            link:   {name: 'link', title: RLANG.link_insert, func: 'showLink'},
            unlink: {exec: 'unlink', name: 'unlink', title: RLANG.unlink}
        }
    },
    separator4: { name: 'separator' },
    typo:  {name: 'typo', title: "Оттипографировать", func: 'typo'},
};