import {fetchUtils, UpdateParams} from "react-admin";
import queryString from "query-string";
import {DataProvider} from "ra-core/src/types";
import {Options} from "ra-core/src/dataProvider/fetch";
import addUploadFeature from "./addUploadFeature";

const apiUrl = process.env.API_URL ?? 'https://rashin.me';

const httpClient = (url: string, options: Options = {}) => {
    if (!options.headers) {
        options.headers = new Headers({
            Accept: 'application/json'
        });
    }
    // options.credentials = 'include';
    return fetchUtils.fetchJson(url, options);
}

const dataProvider: DataProvider = {
    getList: (resource, params) => {
        const { page, perPage } = params.pagination;
        const { field, order } = params.sort;

        const query = {
            sort: JSON.stringify({field: field, order: order}),
            offset: (page - 1) * perPage,
            limit: perPage,
            filter: JSON.stringify(params.filter),
        };

        const url = `${apiUrl}/${resource}?${queryString.stringify(query)}`;

        return httpClient(url).then(({ json }) => ({
            data: json.data,
            total: json.pagination.total,
        }));
    },

    getOne: (resource, params) =>
        httpClient(`${apiUrl}/${resource}/${params.id}`).then(({ json }) => ({
            data: json,
        })),

    getMany: (resource, params) => {
        const query = {
            filter: JSON.stringify({ id: params.ids }),
        };
        const url = `${apiUrl}/${resource}?${queryString.stringify(query)}`;
        return httpClient(url).then(({ json }) => ({ data: json.data }));
    },

    getManyReference: (resource, params) => {
        const { page, perPage } = params.pagination;
        const { field, order } = params.sort;
        const query = {
            sort: JSON.stringify([field, order]),
            range: JSON.stringify([(page - 1) * perPage, page * perPage - 1]),
            filter: JSON.stringify({
                ...params.filter,
                [params.target]: params.id,
            }),
        };
        const url = `${apiUrl}/${resource}?${queryString.stringify(query)}`;

        return httpClient(url).then(({ json }) => ({
            data: json.data,
            total: json.pagination.total,
        }));
    },

    update: (resource: string, params: UpdateParams) => {
        return httpClient(`${apiUrl}/${resource}/${params.id}`, {
            method: 'PATCH',
            body: JSON.stringify(params.data),
        }).then(({ json }) => ({ data: json }))
    },

    updateMany: (resource, params) => {
        const query = {
            filter: JSON.stringify({ id: params.ids}),
        };
        return httpClient(`${apiUrl}/${resource}?${queryString.stringify(query)}`, {
            method: 'PUT',
            body: JSON.stringify(params.data),
        }).then(({ json }) => ({ data: json }));
    },

    create: (resource, params) =>
        httpClient(`${apiUrl}/${resource}`, {
            method: 'POST',
            body: JSON.stringify(params.data),
        }).then(({ json }) => ({
            data: { ...params.data, id: json.id },
        })),

    delete: (resource, params) =>
        httpClient(`${apiUrl}/${resource}/${params.id}`, {
            method: 'DELETE',
        }).then(({ json }) => ({ data: json })),

    deleteMany: (resource, params) => {
        const query = {
            filter: JSON.stringify({ id: params.ids}),
        };
        return httpClient(`${apiUrl}/${resource}?${queryString.stringify(query)}`, {
            method: 'DELETE',
        }).then(({ json }) => ({ data: json }));
    },
    sendForm: (resource: string, formData: FormData) =>
      httpClient(`${apiUrl}/${resource}`, {
        method: 'POST',
        body: formData,
      }).then(({ json }) => ({
        data: { ...json },
      })),
    get: (resource: string) =>
      httpClient(`${apiUrl}/${resource}`).then(({ json }) => (json)),
    patch: (resource: string, params) => {
      console.log(params)
      return httpClient(`${apiUrl}/${resource}`, {
        method: 'PATCH',
        body: JSON.stringify(params),
      }).then(({ json }) => (json))
},
};

const uploadCapableDataProvider = addUploadFeature(dataProvider);

export default uploadCapableDataProvider;
