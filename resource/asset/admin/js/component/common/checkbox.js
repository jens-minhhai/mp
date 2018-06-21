export default class Checkbox {
    static all(target, collection) {
        const checked = $(target).is(':checked');
        $(collection).prop('checked', checked);
    }

    static item(target, collection, checkAll) {
        const all = $(collection).not(':checked').length === 0;
        $(checkAll).prop('checked', all);
    }

    static collection(collection, positive = true) {
        return () => {
            const checked = [];
            $(collection).each(() => {
                if ($(this).is(':checked') === positive) {
                    checked.push($(this).val());
                }
            });
            return checked;
        };
    }
}
