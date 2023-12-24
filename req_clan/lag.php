<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clan Information</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Add Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add Google Fonts (optional) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            overflow: hidden;
        }

        #ribbon-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div id="ribbon-container"></div>

    <!-- Your existing HTML content goes here -->

    <!-- Add Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        // Include Three.js script tag before this code

        // Set up the scene
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer();
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('ribbon-container').appendChild(renderer.domElement);

        // Create falling ribbon particles
        const particles = new THREE.Group();
        scene.add(particles);

        for (let i = 0; i < 1000; i++) {
            const particle = new THREE.Mesh(
                new THREE.BoxGeometry(0.1, 1, 0.1),
                new THREE.MeshBasicMaterial({ color: 0xC0C0C0 })
            );

            particle.position.x = (Math.random() - 0.5) * 20;
            particle.position.y = Math.random() * 10;
            particle.position.z = (Math.random() - 0.5) * 20;

            particles.add(particle);
        }

        // Set up camera position
        camera.position.z = 5;

        // Animation loop
        function animate() {
            requestAnimationFrame(animate);

            // Rotate particles
            particles.rotation.y += 0.002;

            // Render the scene
            renderer.render(scene, camera);
        }

        animate();
    </script>
</body>
</html>
