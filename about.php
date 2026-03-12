<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - AI Futuristic Design</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* =====================================
       Global & Body
    ===================================== */
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(270deg, #0f0c29, #302b63, #24243e);
      background-size: 600% 600%;
      animation: gradientBG 20s ease infinite;
      color: #fff;
      overflow-x: hidden;
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* =====================================
       Floating particles
    ===================================== */
    .particle {
      position: absolute;
      border-radius: 50%;
      opacity: 0.6;
      background: rgba(255,255,255,0.2);
      animation: float 10s linear infinite;
    }

    @keyframes float {
      0% { transform: translateY(0) translateX(0) scale(1); opacity:0.5; }
      50% { transform: translateY(-100px) translateX(50px) scale(1.2); opacity:1; }
      100% { transform: translateY(0) translateX(0) scale(1); opacity:0.5; }
    }

    /* =====================================
       Header Section
    ===================================== */
    .header-section {
      padding: 100px 20px;
      text-align: center;
      z-index: 1;
      position: relative;
    }

    .header-section h1 {
      font-size: 3rem;
      text-shadow: 0 0 20px #00f6ff, 0 0 40px #00f6ff;
    }

    .header-section p {
      font-size: 1.3rem;
      text-shadow: 0 0 10px #00f6ff;
    }

    /* =====================================
       Company Info
    ===================================== */
    .company-info-section {
      padding: 60px 20px;
      text-align: center;
      z-index: 1;
      position: relative;
    }

    .company-info-section h2 {
      text-shadow: 0 0 10px #ff00ff;
    }

    /* =====================================
       Team Section
    ===================================== */
    .team-section {
      padding: 60px 20px;
      text-align: center;
      z-index: 1;
      position: relative;
    }

    .team-member {
      margin-bottom: 40px;
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .team-member img {
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #00f6ff;
      transition: transform 0.4s, box-shadow 0.4s, border 0.4s;
    }

    .team-member img:hover {
      transform: scale(1.2) rotate(5deg);
      box-shadow: 0 0 25px #00f6ff, 0 0 50px #ff00ff;
      border: 2px solid #ff00ff;
    }

    /* =====================================
       Contact Section
    ===================================== */
    .contact-section {
      padding: 60px 20px;
      text-align: center;
      z-index: 1;
      position: relative;
    }

    .contact-section li {
      font-size: 1.1rem;
      margin-bottom: 10px;
    }

    /* =====================================
       Fade-in Animation
    ===================================== */
    .fade-in {
      opacity: 0;
      transform: translateY(20px);
      transition: all 0.8s ease-out;
    }

    .fade-in.show {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body>

  <!-- Floating particles -->
  <div class="particle" style="width:10px; height:10px; top:20%; left:15%; animation-duration:8s;"></div>
  <div class="particle" style="width:15px; height:15px; top:50%; left:70%; animation-duration:12s;"></div>
  <div class="particle" style="width:8px; height:8px; top:80%; left:30%; animation-duration:10s;"></div>
  <div class="particle" style="width:12px; height:12px; top:35%; left:50%; animation-duration:9s;"></div>
  <div class="particle" style="width:20px; height:20px; top:65%; left:80%; animation-duration:15s;"></div>

  <!-- Header Section -->
  <section class="header-section">
    <div class="container">
      <h1>e-commerse</h1>
      <p>better service with discount</p>
    </div>
  </section>

  <!-- Company Info Section -->
  <section class="company-info-section fade-in">
    <div class="container">
      <h2>About Us</h2>
      <p>We are a leading AI-driven company creating futuristic solutions for businesses and society. Our mission is to merge technology and creativity, and our vision is to empower the world with intelligent innovation.</p>
    </div>
  </section>

  <!-- Team Section -->
  <section class="team-section fade-in">
    <div class="container">
      <h2>Meet Our Team</h2>
      <div class="row justify-content-center">
        <div class="col-md-4 text-center">
          <div class="team-member">
            <img src="data:image/jpeg;base64,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" alt="John Doe">
            <p>CEO</p>
            <h5 class="mt-3">Nezuko Kamado </h5>
            
          </div>
        </div>
        <div class="col-md-4 text-center">
          <div class="team-member">
            <img src="data:image/jpeg;base64,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" alt="Jane Smith">
            <h5 class="mt-3">CTO</h5>
            <p>Mikasa Ackerman</p>
          </div>
        </div>
        <div class="col-md-4 text-center">
          <div class="team-member">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS2PXqXJ2ATLgK2Cgxye9791FABvf12ExVBAw&s" alt="Mike Johnson">
            <p>COO</p>
             <h5 class="mt-3">Yor Forger</h5>
           
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="contact-section fade-in">
    <div class="container">
      <h2>Contact Us</h2>
      <ul class="list-unstyled">
        <li><strong>Email:</strong> info@aicompany.com</li>
        <li><strong>Phone:</strong> +1 (123) 456-7890</li>
        <li><strong>Address:</strong> 123 AI Street, Tech City</li>
      </ul>
    </div>
  </section>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Scroll fade-in effect
    const faders = document.querySelectorAll('.fade-in');
    const appearOptions = { threshold: 0.2 };

    const appearOnScroll = new IntersectionObserver(function(entries, observer){
      entries.forEach(entry => {
        if(entry.isIntersecting){
          entry.target.classList.add('show');
          observer.unobserve(entry.target);
        }
      });
    }, appearOptions);

    faders.forEach(fader => {
      appearOnScroll.observe(fader);
    });
  </script>
</body>
</html>
