import Request from '../common/request';
import Basic from '../common/basic';
import App from '../common/app';

export default class Dropzone {
    constructor($element) {
        this.$el = $element;
        this.fileinput = '.j-file';
        this.hover = 'dropzone__dragover';
        this.tpl = '.dropzone--tpl';
        this.canvas = '.j-dropzone--canvas';
        this.placeholder = '.j-dropzone--placeholder';

        this.callback = (response, $target) => {
            const $container = $target.parent().find(this.canvas);
            const tpl = $container.find('.dropzone--tpl').html();
            $.each(response, (index, obj) => {
                const data = {
                    id: obj.id,
                    src: obj.thumbnail_medium,
                    title: obj.title
                };

                const html = Basic.parse(tpl, data);
                $container.append(html);
            });
        };
    }

    static getMaxUpload($target) {
        const max = $target.data('max_upload');
        if (max) {
            return max;
        }
        return $target.attr('multiple') ? App.env('max_upload_file') : 1;
    }

    static reachLimitUpload($target, maxUpload) {
        const $parent = $target.parent();
        const currentUpload = $parent.find('.dropzone--item').length;

        return currentUpload >= maxUpload;
    }

    async upload(files, $target) {
        const $feedback = $target.parent().find('.j-dropzone--feedback');
        const $spinner = $target.find('.j-spinner');
        const maxUpload = Dropzone.getMaxUpload($target);
        const reached = Dropzone.reachLimitUpload($target, maxUpload);

        $feedback.removeClass('dropzone--feedback').html('');

        if (reached) {
            $feedback.addClass('dropzone--feedback').html($.i18n('maximum_upload'));
            return false;
        }

        $spinner.show();
        const list = Array.from(files);
        if (list) {
            let count = list.length;
            while (count > maxUpload) {
                list.pop();
                count -= 1;
            }
            try {
                const response = await Request.uploadFile(list);
                this.callback(response, $target);
            } catch (error) {
                $feedback.addClass('dropzone--feedback').html(error);
            }
        }

        $spinner.hide();
        return true;
    }

    bind() {
        $(this.fileinput).on('change', async (event) => {
            const { files } = event.target;
            const $target = $(event.currentTarget);
            await this.upload(files, $target);
            $target.val('');
        });

        $(this.canvas).on('click', '.j-remove', (event) => {
            $(event.currentTarget)
                .closest('.dropzone--item')
                .remove();
        });

        $(this.placeholder).on('click', (event) => {
            event.preventDefault();
            event.stopPropagation();

            const $target = $(event.currentTarget);
            $target
                .parent()
                .find(this.fileinput)
                .click();
        });

        this.$el
            .on('dragover dragenter', (event) => {
                event.preventDefault();
                event.stopPropagation();
                $(event.currentTarget).addClass(this.hover);
            })
            .on('dragleave', (event) => {
                event.preventDefault();
                event.stopPropagation();

                $(event.currentTarget).removeClass(this.hover);
            })
            .on('dragexit', (event) => {
                event.preventDefault();
                event.stopPropagation();

                $(event.currentTarget).removeClass(this.hover);
            })
            .on('drop', async (event) => {
                event.preventDefault();
                event.stopPropagation();

                const $target = $(event.currentTarget);
                const { files } = event.originalEvent.dataTransfer;

                $target.removeClass(this.hover);
                this.upload(files, $target);
            });
    }
}
