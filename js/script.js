//generalized script//
let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   searchForm.classList.remove('active');
   profile.classList.remove('active');
}

let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () =>{
   profile.classList.toggle('active');
   searchForm.classList.remove('active');
   navbar.classList.remove('active');
}

let searchForm = document.querySelector('.header .flex .search-form');

document.querySelector('#search-btn').onclick = () =>{
   searchForm.classList.toggle('active');
   navbar.classList.remove('active');
   profile.classList.remove('active');
}

window.onscroll = () =>{
   profile.classList.remove('active');
   navbar.classList.remove('active');
   searchForm.classList.remove('active');
}

document.querySelectorAll('.content-150').forEach(content => {
   if(content.innerHTML.length > 150) content.innerHTML = content.innerHTML.slice(0, 150);
});




//For Landing Page//

$(document).ready(function(){
   // Search functionality
   $("#search").on("keyup", function(){
       let query = $(this).val();
       if (query.length > 2) {
           $.ajax({
               url: "search.php",
               method: "POST",
               data: { search: query },
               success: function(data){
                   $("#search-results").html(data).show();
               }
           });
       } else {
           $("#search-results").hide();
       }
   });

   $(document).click(function(event) {
       if (!$(event.target).closest('.search-box').length) {
           $("#search-results").hide();
       }
   });

   // Carousel functionality
   let currentSlide = 0;

   function updateSlidePosition() {
       const carouselInner = document.querySelector('.carousel-inner');
       const slideWidth = document.querySelector('.carousel-item').clientWidth;
       carouselInner.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
   }
   
   function moveSlide(n) {
       const slides = document.querySelectorAll('.carousel-item');
       currentSlide = (currentSlide + n + slides.length) % slides.length;
       updateSlidePosition();
       updateIndicators();
   }
   
   function goToSlide(n) {
       currentSlide = n;
       updateSlidePosition();
       updateIndicators();
   }
   
   function updateIndicators() {
       const indicators = document.querySelectorAll('.indicator');
       indicators.forEach((indicator, index) => {
           indicator.classList.toggle('active', index === currentSlide);
       });
   }
   
   document.addEventListener('DOMContentLoaded', () => {
       const indicators = document.querySelectorAll('.indicator');
       indicators.forEach((indicator, index) => {
           indicator.addEventListener('click', () => goToSlide(index));
       });
   
       document.querySelector('.prev').addEventListener('click', () => moveSlide(-1));
       document.querySelector('.next').addEventListener('click', () => moveSlide(1));
   
       updateSlidePosition();
       updateIndicators();
   
       // Automatically move to the next slide every 5 seconds
       setInterval(() => moveSlide(1), 5000);
   });
   
   
   // USER DASHBOARD 
   loadCarousel();
   loadAnnouncements();
   loadArticles();
   loadMagazines();
   loadTejidos();

   // Load carousel images
   function loadCarousel() {
       $.ajax({
           url: "./ajax/fetch_carousel.php",
           method: "GET",
           success: function (data) {
               $("#carousel-images").html(data.images);
               $("#carousel-indicators").html(data.indicators);
               // Reinitialize carousel indicators after loading new content
               updateIndicators();
           },
           dataType: "json"
       });
   }

   // Load announcements
   function loadAnnouncements() {
       $.ajax({
           url: "./ajax/fetch_announcements.php",
           method: "GET",
           success: function (data) {
               $("#announcements").html(data);
           }
       });
   }

   // Load articles
   function loadArticles() {
       $.ajax({
           url: "./ajax/fetch_articles.php",
           method: "GET",
           success: function (data) {
               $("#articles").html(data);
           }
       });
   }

   // Load magazines
   function loadMagazines() {
       $.ajax({
           url: "./ajax/fetch_magazines.php",
           method: "GET",
           success: function (data) {
               $("#magazines").html(data);
           }
       });
   }

   // Load tejidos
   function loadTejidos() {
       // Add your tejidos loading logic here
   }

   // All category drop down scripts
   function filterPosts(category) {
       var cards = document.querySelectorAll('.card');

       cards.forEach(function(card) {
           if (category === 'all' || card.getAttribute('data-category') === category) {
               card.style.display = 'block';
           } else {
               card.style.display = 'none';
           }
       });
   }

   function toggleDropdown() {
       document.getElementById("dropdownContent").classList.toggle("show");
   }

   // Close the dropdown if the user clicks outside of it
   window.onclick = function(event) {
       if (!event.target.matches('.filter-button')) {
           var dropdowns = document.getElementsByClassName("dropdown-content");
           for (var i = 0; i < dropdowns.length; i++) {
               var openDropdown = dropdowns[i];
               if (openDropdown.classList.contains('show')) {
                   openDropdown.classList.remove('show');
               }
           }
       }
   }

   // Display all posts by default
   filterPosts('all');
});

//end of script for landing page//





