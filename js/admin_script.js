let header = document.querySelector('.header');

document.querySelector('#menu-btn').onclick = () =>{
   header.classList.toggle('active');
}

window.onscroll = () =>{
   header.classList.remove('active');
}

document.querySelectorAll('.posts-content').forEach(content => {
   if(content.innerHTML.length > 100) content.innerHTML = content.innerHTML.slice(0, 100);
});



function filterLogs() {
   const searchInput = document.getElementById('searchInput').value.toLowerCase();
   const typeFilter = document.getElementById('typeFilter').value.toLowerCase();
   const rows = document.querySelectorAll('.activity-logs-table tbody tr');

   rows.forEach(row => {
       const type = row.querySelector('td:first-child').textContent.toLowerCase();
       const title = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
       const creator = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

       const typeMatch = typeFilter === 'all' || type === typeFilter;
       const searchMatch = searchInput === '' || 
           title.includes(searchInput) || 
           creator.includes(searchInput);

       row.style.display = (typeMatch && searchMatch) ? '' : 'none';
   });
}

document.getElementById('searchInput').addEventListener('keydown', function(event) {
   if (event.key === 'Enter') {
       event.preventDefault();
       filterLogs();
   }
});

document.getElementById('typeFilter').addEventListener('change', filterLogs);






