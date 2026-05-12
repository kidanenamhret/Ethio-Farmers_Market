/**
 * Live Search functionality
 */

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('live-search');
    if (!searchInput) return;

    // Create results container
    const resultsContainer = document.createElement('div');
    resultsContainer.className = 'glass';
    resultsContainer.style.cssText = `
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        margin-top: 10px;
        max-height: 400px;
        overflow-y: auto;
        display: none;
        padding: 0;
        border-radius: 16px;
    `;
    searchInput.parentElement.appendChild(resultsContainer);

    let debounceTimer;

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value;
        
        clearTimeout(debounceTimer);
        
        if (query.length < 2) {
            resultsContainer.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`${BASE_URL}ajax/live-search.php?q=${encodeURIComponent(query)}`)
                .then(res => res.text())
                .then(html => {
                    resultsContainer.innerHTML = html;
                    resultsContainer.style.display = 'block';
                })
                .catch(err => console.error('Search error:', err));
        }, 300);
    });

    // Close results when clicking outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.style.display = 'none';
        }
    });

    // Show results again when clicking on input
    searchInput.addEventListener('focus', () => {
        if (searchInput.value.length >= 2) {
            resultsContainer.style.display = 'block';
        }
    });
});
