// import env from '../../app/env';
import Basic from './basic';

export default class Request {
    static get(url = '') {
        return fetch(url, { credentials: 'same-origin' })
            .then((response) => {
                if (response.ok) {
                    return response;
                }
                return new Promise((resolve, reject) => {
                    const error = {
                        status: response.status,
                        statusText: response.statusText
                    };
                    reject(error);
                });
            })
            .then(response => response.json())
            .catch((error) => {
                throw new Error(error);
            });
    }

    static async token() {
        const token = await this.get(Basic.env('url.token'));
        return token.token;
    }

    static async post(url, data = {}, header = {}) {
        const token = await this.token();
        const headers = $.extend(header, {
            'Mp-Csrf-Token': token
        });
        return fetch(url, {
            credentials: 'same-origin',
            method: 'post',
            headers: new Headers(headers),
            body: data
        })
            .then((response) => {
                if (response.ok) {
                    return response;
                }

                return new Promise((resolve, reject) => {
                    const error = {
                        status: response.status,
                        statusText: response.statusText
                    };
                    reject(error);
                });
            })
            .then(response => response.json())
            .catch((error) => {
                throw new Error(error);
            });
    }

    static uploadFile(files) {
        const data = new FormData();
        $.each(files, (i, file) => {
            data.append(i, file);
        });

        return this.post(Basic.env('url.upload'), data);
    }
}
