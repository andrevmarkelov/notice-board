import { apiUrl } from './functions.js';

export const createAd = async (adData) => {
    const query = `${apiUrl}ad`;

    const requestOptions = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(adData)
    };

    try {
        const response = await fetch(query, requestOptions);
        const data = await response.json();

        if (!response.ok) {
            return { error: data.message }
        }

        return data ? data.response : [];
    } catch (error) {
        return error;
    }
}
