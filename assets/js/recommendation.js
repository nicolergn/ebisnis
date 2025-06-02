let excludeProductId = document.body.dataset.excludeId;

let loadedProductIds = Array.from(document.querySelectorAll('.rec-recommendation-card-link'))
    .map(el => el.href.split('product_id=')[1]);

document.getElementById('loadMore').addEventListener('click', function () {
    const button = this;
    const offset = parseInt(button.getAttribute('data-offset'));

    fetch('includes/loadMoreProducts.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            offset: offset,
            loaded_ids: loadedProductIds,
            exclude_product_id: excludeProductId // Kirim product_id yang sedang dibuka
        })
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === "") {
            button.style.display = "none";
            document.getElementById('noMoreProducts').style.display = "block";
        } else {
            document.getElementById('recommendationGrid').insertAdjacentHTML('beforeend', data);

            button.setAttribute('data-offset', offset + 36);

            const newIds = Array.from(document.querySelectorAll('.rec-recommendation-card-link'))
                .map(el => el.href.split('product_id=')[1]);
            loadedProductIds = [...new Set([...loadedProductIds, ...newIds])];
        }
    });
});