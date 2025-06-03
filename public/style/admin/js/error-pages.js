document.addEventListener('DOMContentLoaded', function() {
    // Theme toggle functionality
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;

    // Function to toggle theme
    function toggleTheme() {
        const currentTheme = htmlElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'theme-dark' ? 'theme-default' : 'theme-dark';

        // Update HTML attribute
        htmlElement.setAttribute('data-theme', newTheme);

        // Store in localStorage
        localStorage.setItem('theme', newTheme);

        // Note: We're only using localStorage for persistence
        // If you want to add server-side persistence, you would need to create a route
        // to handle the theme preference and uncomment the code below
        /*
        fetch('/admin/set-theme', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ theme: newTheme })
        }).catch(error => {
            console.log('Error saving theme preference:', error);
        });
        */
    }

    // Add click event listener
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }

    // Initialize theme from localStorage if available
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme && savedTheme !== htmlElement.getAttribute('data-theme')) {
        htmlElement.setAttribute('data-theme', savedTheme);
    }

    // Check system preference if no saved theme
    if (!savedTheme) {
        const prefersDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (prefersDarkMode && htmlElement.getAttribute('data-theme') !== 'theme-dark') {
            htmlElement.setAttribute('data-theme', 'theme-dark');
            localStorage.setItem('theme', 'theme-dark');
        }
    }

    // Listen for system theme changes
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem('theme')) { // Only apply if user hasn't set a preference
                const newTheme = e.matches ? 'theme-dark' : 'theme-default';
                htmlElement.setAttribute('data-theme', newTheme);
            }
        });
    }

    // Dots animation
    const dotsContainer = document.getElementById('dotsContainer');
    if (dotsContainer) {
        const numberOfDots = 500; // Reduced number of dots for better performance

        // Create dots with random properties
        for (let i = 0; i < numberOfDots; i++) {
            const dot = document.createElement('div');
            dot.classList.add('dot');

            // Random size between 2px and 6px (smaller dots)
            const size = Math.floor(Math.random() * 5) + 2;
            dot.style.width = `${size}px`;
            dot.style.height = `${size}px`;

            // Random position across the entire page
            const posX = Math.floor(Math.random() * 100);
            const posY = Math.floor(Math.random() * 100);

            dot.style.left = `${posX}%`;
            dot.style.top = `${posY}%`;

            // Random animation duration between 10s and 25s
            const duration = Math.floor(Math.random() * 15) + 10;
            dot.style.animationDuration = `${duration}s`;

            // Random animation delay
            const delay = Math.floor(Math.random() * 8);
            dot.style.animationDelay = `${delay}s`;

            // Random opacity between 0.2 and 0.6
            const opacity = (Math.random() * 0.4 + 0.2).toFixed(2);
            dot.style.opacity = opacity;

            // Add data attributes for parallax effect
            dot.setAttribute('data-depth', (Math.random() * 0.1 + 0.05).toFixed(2));

            // Add dot to container
            dotsContainer.appendChild(dot);

            // Add animation event listeners for tail effect
            dot.addEventListener('animationiteration', function() {
                // When the main movement animation iterates, trigger the tail
                this.classList.add('moving');

                // Remove the moving class after the tail animation completes
                setTimeout(() => {
                    this.classList.remove('moving');
                }, 1500); // Match the tail animation duration (1.5s)
            });
        }

        // Add parallax effect on mouse move
        document.addEventListener('mousemove', function(e) {
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;

            const dots = document.querySelectorAll('.dot');
            dots.forEach(dot => {
                const depth = parseFloat(dot.getAttribute('data-depth'));
                const moveX = (mouseX - 0.5) * depth * 100;
                const moveY = (mouseY - 0.5) * depth * 100;

                dot.style.transform = `translate(${moveX}px, ${moveY}px)`;
            });
        });
    }

    // Toggle error details section
    const errorDetailsToggle = document.getElementById('errorDetailsToggle');
    const errorDetails = document.getElementById('errorDetails');

    if (errorDetailsToggle && errorDetails) {
        errorDetailsToggle.addEventListener('click', function() {
            // Toggle expanded class on details container
            const isExpanded = errorDetails.classList.toggle('expanded');

            // Toggle expanded class on button for icon rotation
            this.classList.toggle('expanded');

            // Update aria-expanded attribute for screen readers
            this.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');

            // Set focus to the details section if expanded (for accessibility)
            if (isExpanded) {
                errorDetails.setAttribute('tabindex', '-1');
                errorDetails.focus();
            } else {
                errorDetails.removeAttribute('tabindex');
            }
        });
    }

    // Add confetti effect to home button and 3-second delay
    const homeButton = document.getElementById('homeButton');
    if (homeButton) {
        homeButton.addEventListener('click', function(e) {
            // Prevent default navigation
            e.preventDefault();

            // Get the href attribute
            const href = this.getAttribute('href');

            // Add a loading state to the button
            this.classList.add('loading');

            // Create a loading indicator with animated dots
            const originalText = this.innerHTML;
            const redirectText = this.getAttribute('data-redirect-text') || 'Redirecting';

            // Create animated dots for the redirecting text
            this.innerHTML = `
                <i class="ti ti-home-2 me-2" aria-hidden="true"></i>
                <div class="redirecting-text">
                    <span>${redirectText}</span>
                    <div class="redirecting-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            `;

            // Get theme colors for confetti
            const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'theme-dark';

            // Configure confetti colors based on theme
            const colors = isDarkTheme
                ? ['#ff4556', '#69afdc', '#ffac14', '#ffffff']
                : ['#ff4556', '#4b91be', '#ff9800', '#ffffff'];

            // Trigger confetti from multiple origins
            const duration = 1500;
            const animationEnd = Date.now() + duration;

            // Function to create confetti burst
            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            // Create multiple bursts of confetti
            const interval = setInterval(function() {
                const timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                // Confetti options
                const particleCount = 50;

                // Left side burst
                confetti({
                    particleCount: particleCount / 2,
                    angle: randomInRange(55, 125),
                    spread: randomInRange(50, 70),
                    origin: { x: 0.1, y: 0.5 },
                    colors: colors,
                    disableForReducedMotion: true
                });

                // Right side burst
                confetti({
                    particleCount: particleCount / 2,
                    angle: randomInRange(55, 125),
                    spread: randomInRange(50, 70),
                    origin: { x: 0.9, y: 0.5 },
                    colors: colors,
                    disableForReducedMotion: true
                });
            }, 250);

            // Delay navigation for 3 seconds
            setTimeout(function() {
                window.location.href = href;
            }, 3000);
        });
    }
});
