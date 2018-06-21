import env from '../env';

export default class Basic {
    static env(path) {
        return (path.split('.').reduce((o, i) => o[i], env));
    }

    static sanitize(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/`/g, '&#96;');
    }

    static nl2br(str) {
        return str.replace(/(?:\r\n|\r|\n)/g, '<br />');
    }

    static br2nl(str) {
        return String(str)
            .replace(/(\r\n|\n|\r)/gm, '')
            .replace(/<br ?\/?>/g, '\n');
    }

    static trim(str) {
        return String(str)
            .replace(/^\s+|\s+$/g, '')
            .replace(/^\n+|\n$/, '');
    }

    static parse(template, data) {
        const regx = /#([^#]+)#/g;
        const params = template.match(regx);

        let string = template;
        $.each(params, (index, column) => {
            const key = column.replace(/#/g, '');

            if (typeof data[key] === 'undefined') {
                return true;
            }

            const value = data[key] != null ? data[key] : '';

            string = string.replace(column, value);
            return true;
        });

        return string;
    }
}
