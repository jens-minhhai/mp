import request from '../common/request';

export default class Summernote {
    constructor($element) {
        this.$el = $element;
        this.toolbar();
    }

    toolbar(mode = 'default') {
        let toolbar = [];
        switch (mode) {
            default:
                toolbar = [
                    ['headline', ['style']],
                    ['fontclr', ['color']],
                    ['alignment', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview']],
                    ['help', ['help']]
                ];
        }
        this.toolbar = toolbar;
        return this;
    }

    bind() {
        this.$el.each((index, item) => {
            const $editor = $(item);

            $editor.summernote({
                height: $editor.data('height') || 200,
                minHeight: null,
                maxHeight: null,
                toolbar: this.toolbar,
                callbacks: {
                    onImageUpload: async (files) => {
                        const response = await request.uploadFile(files);
                        if (response) {
                            $.each(response, (key, target) => {
                                $editor.summernote('insertImage', target.url, ($image) => {
                                    $image.attr('data-id', target.id);
                                    $image.attr('class', 'j-delete_file editor-image');
                                });
                            });
                        }
                    }
                }
            });
        });
    }
}
