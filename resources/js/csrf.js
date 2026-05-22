const readMetaToken = () => document.querySelector('meta[name="csrf-token"]')?.content || '';

const writeMetaToken = (token) => {
    const meta = document.querySelector('meta[name="csrf-token"]');

    if (meta && token) {
        meta.content = token;
    }
};

export const getCsrfToken = () => readMetaToken();

export const setCsrfToken = (token) => writeMetaToken(token);

export const applyResponseCsrfToken = (response) => {
    const token = response.headers.get('X-CSRF-TOKEN');

    if (token) {
        writeMetaToken(token);
    }
};

export const csrfHeaders = (extra = {}) => {
    const token = readMetaToken();

    return {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...(token ? { 'X-CSRF-TOKEN': token } : {}),
        ...extra,
    };
};
