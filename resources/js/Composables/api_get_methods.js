import { apiUrl } from './functions.js';

export const getAds = async (limit, params = null) => {
    let query = `${apiUrl}ads`;
    if (limit) query += `/limit/${limit}`;
    if (params) query += params

    try {
        const response = await fetch(query);
        const data = await response.json();
        return data ? data.response.ads : [];
    } catch (error) {
        return error;
    }
}

export const getAdById = async(id, fields = null) => {
    let query = `${apiUrl}ad/${id}`;
    if (fields) query+= `?fields=${fields}`

    try {
        const response = await fetch(query);
        const data = await response.json();

        if (!response.ok) {
            return { error: data.response.message }
        }

        return data ? data.response : [];
    } catch (error) {
        return error;
    }
}
