import Checkbox from './common/checkbox';

$(() => {
    $('.j-checkAll').click((e) => {
        const candidate = $(e.target).data('target');
        Checkbox.all($(e.target), $(`.${candidate}`));
    });

    $('.j-checkItem').click((e) => {
        const collection = $('.j-checkItem');
        const checkAll = $('.j-checkAll');
        Checkbox.item($(e.target), collection, checkAll);
    });
});
