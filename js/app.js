window.addEventListener("DOMContentLoaded", () => {
    searchTermList();

    searchForm.addEventListener("submit", (e) => {
        e.preventDefault();

        if(searchTerm.value !== '' && searchTerm.value.length > 1 ){
            saveSearchTerm(searchTerm.value);

            wikipediaSearch(searchTerm.value);
        }else{
            alert("Debe introducir un término para realizar una búsqueda.");
        }

        
    });

    recentSearchToggleButton.addEventListener("click", function(e){
        recentSearchSidebar.classList.toggle("translated");
        this.textContent = recentSearchSidebar.classList.contains("translated") ? "Mostrar búsquedas recientes" : "Ocultar búsquedas recientes";
    });

    recentSearchList.addEventListener("click", (linkEvent) => {
        linkEvent.preventDefault();
        wikipediaSearch(linkEvent.target.dataset.term);
    });

    closeRecentSearchContainer.addEventListener('click', () => {
        recentSearchSidebar.classList.add('translated');
        recentSearchToggleButton.textContent = "Mostrar búsquedas recientes";
    });
});

const searchForm = document.querySelector("#searchForm");
const searchTerm = document.getElementById("searchTerm");
const resultsTable = document.querySelector('.results-table');
const alertError = document.querySelector('.alert-error');
const resultsTableAlert = document.querySelector('.results-table-info');
const recentSearchList = document.querySelector('.recent-search-list');
const recentSearchAlert = document.querySelector('.recent-search-alert');
const recentSearchSidebar = document.querySelector('.recent-search-container');
const recentSearchToggleButton = document.querySelector('#toggleRecentSearchButton');
const recentSearchAlertError = document.querySelector('.recent-search-alert-error');
const resultsTableError = document.querySelector('.results-table-error');
const searchBoxError = document.querySelector('.search-box-error');
const resultsBoxOverlay = document.querySelector('.results-box-overlay');
const closeRecentSearchContainer = document.querySelector('.close-recent-search-container');


// LISTA DE TÉRMINOS DE BÚSQUEDA 

const searchTermList = async() => {
    const searchTermListEndpoint = '../api.php?action=listSearchTerms';

    try{
        const apiResponse = await fetch(searchTermListEndpoint);
        if(apiResponse.ok){
            const data = await apiResponse.json();
            const apiData = data.data;
            
            if(apiData.length){
                const recentSearchListHtml = apiData.map(item => `<li><a href="" class="recent-search-link" data-term="${item.term}" title="Fecha de búsqueda: ${new Date(item.search_date).toLocaleString('es-ES')}">${item.term}</a></li>`).join('');
                recentSearchList.innerHTML = recentSearchListHtml;
                recentSearchList.classList.remove('hidden');
                recentSearchAlert.classList.add('hidden');
            }else{
                recentSearchAlert.classList.remove('hidden');
            }
        }else{
            const data = await apiResponse.json();
            console.log(data.error);
            recentSearchAlert.classList.add('hidden');
            recentSearchAlertError.classList.remove('hidden');
        }
       
        
    }catch(error){
        console.log(error);
    }
}

const saveSearchTerm = async(searchTermValue) => {
    try{
        const saveSearchTermResponse = await fetch('../api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'action=saveSearchTerm&searchTerm=' + encodeURIComponent(searchTermValue)
        });

        if(saveSearchTermResponse.ok){
            searchTermList();
        }else{
            searchBoxError.classList.remove('hidden');
            setTimeout(function(){
                searchBoxError.classList.add('hidden');
            }, 3000);
        }
    }catch(error){
        console.log("Error:", error);
    }
}

const wikipediaSearch = async (searchTermValue) => {
    const wikipediaSearchEndpoint = `https://es.wikipedia.org/w/api.php?action=query&list=search&srsearch=${searchTermValue}&format=json&origin=*`;
    
    try{
        resultsBoxOverlay.classList.remove('hidden');
        const wikipediaResponse = await fetch(wikipediaSearchEndpoint);
        if(wikipediaResponse.ok){
            const data = await wikipediaResponse.json();
            const results = await data.query.search;

            const resultsTableHtml = results.map(item => `<tr><td>${item.title}</td><td>${item.snippet}</td></tr>`).join('');

            resultsTable.querySelector('tbody').innerHTML = resultsTableHtml;
            resultsTable.classList.remove('hidden');
            resultsTableAlert.classList.add('hidden');
            searchTerm.value = '';
        }else{
            resultsTableAlert.classList.add('hidden');
            resultsTableError.classList.remove('hidden');
        }

    }catch(error){
        console.log("Error en la llamada a API Wikipedia: ", error);
    }finally{
        resultsBoxOverlay.classList.add('hidden');
    }
};
