const loader = document.querySelector(".loader");


        window.addEventListener("scroll", function(){
            var header = document.querySelector("header");
            header.classList.toggle("sticky",window.scrollY > 0);
        })

        
        function toggleMenu(){
            var menuToggle = document.querySelector(".toggle");
            var menu = document.querySelector(".menu");
            var header = document.querySelector("header");
            menuToggle.classList.toggle("active");
            menu.classList.toggle("active");
            if(window.scrollY == 0){
                header.classList.toggle("sticky");
            }
        }

        function abcd(){
            var input = document.getElementById("header-input");
            input.classList.toggle("show-input");
        }