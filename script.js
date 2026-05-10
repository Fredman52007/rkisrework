document.getElementById('searchBox')?.addEventListener('input', function(e) {
    let query = e.target.value;
    if(query.length < 2) return;

    fetch('search.php?q=' + query)
        .then(response => response.text())
        .then(data => {
            document.getElementById('results').innerHTML = data;
        });
});