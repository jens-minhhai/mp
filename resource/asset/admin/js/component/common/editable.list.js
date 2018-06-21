export default class Editable {
    static editable($target) {
        const checked = $target.is(':checked');
        const target = $target.data('target');
        if (checked) {
            $(`#${target}`).removeAttr('readonly');
        } else {
            $(`#${target}`).attr('readonly', 'readonly');
        }
    }
}
