const search = document.querySelector('.search-container')

if (search) {
    const input = search.querySelector('.search-container input')
    input.addEventListener('input', async () => {
        const response = await fetch('../api/api_search.php?' + encodeForAjax({ q: input.value }))
        const items = await response.json()

        const suggestions = search.querySelector('#search-suggestions')
        suggestions.innerHTML = ''
        for (const item of items) {
            const row = document.createElement('option')
            row.value = item.name
            suggestions.appendChild(row)
        }
    })
}

function encodeForAjax(data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}