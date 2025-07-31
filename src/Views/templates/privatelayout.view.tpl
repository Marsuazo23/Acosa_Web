<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{SITE_TITLE}}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{BASE_DIR}}/public/css/appstyle.css" />
    <script src="https://kit.fontawesome.com/f41481edd3.js" crossorigin="anonymous"></script>
    {{foreach SiteLinks}}
    <link rel="stylesheet" href="{{~BASE_DIR}}/{{this}}" />
    {{endfor SiteLinks}}
    {{foreach BeginScripts}}
    <script src="{{~BASE_DIR}}/{{this}}"></script>
    {{endfor BeginScripts}}
  </head>

  <body>
    <header class="{{userRole}}">

      <input type="checkbox" class="menu_toggle" id="menu_toggle" />
      <label for="menu_toggle" class="menu_toggle_icon">
        <div class="hmb dgn pt-1"></div>
        <div class="hmb hrz"></div>
        <div class="hmb dgn pt-2"></div>
      </label>
      <h1>{{SITE_TITLE}}</h1>
      <nav id="menu">
        <ul>
          <li><a href="index.php?page={{PRIVATE_DEFAULT_CONTROLLER}}"><i class="fas fa-home"></i>&nbsp;Inicio</a></li>
          {{foreach NAVIGATION}}
              <li><a href="{{nav_url}}">{{nav_label}}</a></li>
          {{endfor NAVIGATION}}
          <li><a href="index.php?page=sec_logout"><i class="fas fa-sign-out-alt"></i>&nbsp;Salir</a></li>
        </ul>
      </nav>
      {{with login}}
      <span class="username">{{userName}} <a href="index.php?page=sec_logout"><i class="fas fa-sign-out-alt"></i></a></span>
      {{endwith login}}

    <div class="cart-icon {{userRole}}">
      <a href="index.php?page=Checkout-Checkout">
        <i class="fas fa-shopping-cart"></i>
        <span class="cart-count">{{CART_COUNT}}</span>
      </a>
    </div>

    </header>
    
    <main>
      {{{page_content}}}
    </main>

    <footer>
    <footer class="footer">
      <div class="footer-logos">
      <a href="https://www.facebook.com/share/1BnhbZCfZs/" target="_blank">
        <img src="public/imgs/logos/logo1.png" alt="Facebook">
      </a>
      <a href="https://www.instagram.com/acosahn?igsh=MTVzN3U3a2V1YmJ4bA==" target="_blank">
        <img src="public/imgs/logos/logo2.png" alt="Instagram">
      </a>
      <a href="https://wa.link/h6mins" target="_blank">
        <img src="public/imgs/logos/logo3.png" alt="Whatsapp">
      </a>
      <a href="https://x.com/ACOSAHN?t=iyPemUOMIBy0UjHuf9XOaA&s=08" target="_blank">
        <img src="public/imgs/logos/logo4.png" alt="X">
      </a>
      </div>

      <nav class="footer-nav">
        <a href="index.php">INICIO</a>
        <a href="index.php?page=Pages_aboutUs">NOSOTROS</a>
        <a href="index.php?page=Pages_categories">CATEGORÍAS</a>
        <a href="index.php?page=Pages_informacion">INFORMACIÓN</a>
      </nav>

      <div class="footer-brand">Acosa</div>

      <div class="footer-copy">
        Todos los Derechos Reservados {{~CURRENT_YEAR}} &copy {{SITE_TITLE}}
      </div>
    </footer>

    {{foreach EndScripts}}
    <script src="{{~BASE_DIR}}/{{this}}"></script>
    {{endfor EndScripts}}
  </body>
</html>
