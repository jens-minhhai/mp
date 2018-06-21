import '../component/checkbox';
import Editable from '../component/common/editable.list';

$(() => {
    $('.j-data_table').DataTable({
        autoWidth: false,
        bSort: false
    });

    $('.j-checkItem').on('change', () => {
        Editable.editable($(this));
    });

    $('.j-checkAll').on('change', () => {
        $('.j-checkItem').trigger('change');
    });

    $('.j-data_table').on('draw.dt', () => {
        $('.j-checkItem').on('change', () => {
            Editable.editable($(this));
        });
        $('.j-checkAll').on('change', () => {
            $('.j-checkItem').trigger('change');
        });
    });
});
