import Editor from '../component/editor/summernote';
import Dropzone from '../component/dropzone/dropzone';

$(() => {
    const editor = new Editor($('.j-editor'));
    editor.bind();

    const dropzone = new Dropzone($('.j-dropzone'));
    dropzone.bind();
});
