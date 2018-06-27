import env from '../env';

export default class App {
    static env(path) {
        return (path.split('.').reduce((o, i) => o[i], env));
    }

    static i18n(path, def = '') {
        const msg = $.i18n(path);
        if (msg === path) {
            return def;
        }
        return msg;
    }
}
