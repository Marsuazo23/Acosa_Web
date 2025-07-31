    <div class="slider-container">
        <div class="slider">
        <img src="public/imgs/slider/slider1.png" alt="Slide 1" class="slide active">
        <img src="public/imgs/slider/slider2.jpg" alt="Slide 2" class="slide">
        </div>

        <button class="prev" aria-label="Previous slide">&#10094;</button>
        <button class="next" aria-label="Next slide">&#10095;</button>
    </div>
 
    <h1>Mejores Ofertas</h1>
    <div class="product-list">
        {{foreach productsOnSale}}
        <div class="product" data-productId="{{productId}}">
            <div class="discount">{{discount}}</div> 
            <img src="{{productImgUrl}}" alt="{{productName}}">
            <h2>{{productName}}</h2>
            <span class="price">
            <span class="original-price">L. {{originalPrice}}</span> L. {{productPrice}}
            </span>
            <button class="add-to-cart" onclick="location.href='index.php?page=Pages\\detailProducts&productId={{productId}}'">Comprar</button>        
        </div>
        {{endfor productsOnSale}}
    </div>

        <div class="center-button">
        <button class="btn-buybest" onclick="location.href='index.php?page=Pages\\categoryProducts&categoryId=5&name=Ofertas'">
            COMPRAR MEJORES OFERTAS
        </button>
    </div>

    <div class="ads-container">
        <img src="public/imgs/ads/ad1.png" alt="Publicidad 1" 
            onclick="window.location.href='index.php?page=Pages\\detailProducts&productId=18'">
        <img src="public/imgs/ads/ad2.png" alt="Publicidad 2" 
            onclick="window.location.href='index.php?page=Pages\\detailProducts&productId=17'">
    </div>


    <h1>Iniciar Compra</h1>
    <div class="product-list">
        {{foreach featuredByCategory}}
        <div class="product" data-productId="{{productId}}">
            <img src="{{productImgUrl}}" alt="{{productName}}">
            <h2>{{productName}}</h2>
            <span class="price">L. {{productPrice}}</span>
            <button class="add-to-cart" onclick="location.href='index.php?page=Pages\\detailProducts&productId={{productId}}'">Comprar</button>        
        </div>
        {{endfor featuredByCategory}}
    </div>

    <div class="center-button">
        <button class="add-to-cart" onclick="location.href='index.php?page=Pages_categories'">COMPRAR MÁS PRODUCTOS</button>
    </div>

    <script>
        let current = 0;
        const slides = document.querySelectorAll('.slide');
        const prevBtn = document.querySelector('.prev');
        const nextBtn = document.querySelector('.next');

        function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        }

        function nextSlide() {
        current = (current + 1) % slides.length;
        showSlide(current);
        }

        function prevSlide() {
        current = (current - 1 + slides.length) % slides.length;
        showSlide(current);
        }

        nextBtn.addEventListener('click', () => {
        nextSlide();
        resetInterval();
        });

        prevBtn.addEventListener('click', () => {
        prevSlide();
        resetInterval();
        });

        let slideInterval = setInterval(nextSlide, 5000);

        function resetInterval() {
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, 5000);
        }
    </script>


