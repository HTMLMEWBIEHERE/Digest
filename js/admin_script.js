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



function searchLogs() {
   // Get the search input value
   var searchValue = document.getElementById('searchInput').value.toLowerCase();
   console.log('Search Value:', searchValue);  // Debugging
   
   // Get all table rows
   var rows = document.querySelectorAll('.activity-logs-table tbody tr');
   
   // Loop through each row and hide or show based on the search value
   rows.forEach(function(row) {
       var title = row.cells[1].textContent.toLowerCase();
       var createdBy = row.cells[2].textContent.toLowerCase();
       var timestamp = row.cells[3].textContent.toLowerCase();
       var status = row.cells[4].textContent.toLowerCase();
       
       // Check if any of the cells contains the search value
       if (title.includes(searchValue) || createdBy.includes(searchValue) || timestamp.includes(searchValue) || status.includes(searchValue)) {
           row.style.display = ''; // Show the row
       } else {
           row.style.display = 'none'; // Hide the row
       }
   });
}
