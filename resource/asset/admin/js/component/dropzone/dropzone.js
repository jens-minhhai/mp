import request from '../common/request';
import basic from '../common/basic';

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

                const html = basic.parse(tpl, data);
                $container.append(html);
            });
        };
    }

    static getMaxUpload($target) {
        const max = $target.data('max_upload');
        if (max) {
            return max;
        }
        return $target.attr('multiple') ? 10 : 1;
    }

    static reachLimitUpload($target, maxUpload, notify = true) {
        const $parent = $target.parent();
        const currentUpload = $parent.find('.dropzone--item').length;
        const reached = currentUpload >= maxUpload;

        if (notify) {
            let msg = '';
            if (reached) {
                msg = $.i18n('maximum_upload');
            }
            $parent.find('.j-dropzone--feedback').html(msg);
        }

        return reached;
    }

    async upload(files, $target) {
        const $spinner = $target.find('.j-spinner');
        const maxUpload = Dropzone.getMaxUpload($target);
        const reached = Dropzone.reachLimitUpload($target, maxUpload);

        if (reached) {
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
                const response = await request.uploadFile(list);
                this.callback(response, $target);
            } catch (error) {
                console.log(error);
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
