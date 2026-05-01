export async function apiGet(url) {
    const response = await fetch(url, {
        headers: {
            Accept: 'application/json'
        }
    });

    if (!response.ok) {
        throw new Error(`Falha ao consultar ${url}: ${response.status}`);
    }

    return response.json();
}
