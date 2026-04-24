<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About Us';
$metaDescription = 'Learn about Careful Cat Rescue, our mission, and our commitment to saving and rehoming cats in need.';

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>About Careful Cat Rescue</h1>
        <p>Dedicated to saving lives, one paw at a time.</p>
    </div>
</section>

<!-- Our Story Section -->
<section class="section">
    <div class="container">
        <div class="grid grid-2" style="gap: 3rem; align-items: center;">
            <div>
                <h2 class="mb-3">Our Story</h2>
                <p>Careful Cat Rescue was founded with a simple but powerful mission: to provide a safe haven for cats in need and find them loving forever homes.</p>
                
                <p>What started as a small operation has grown into a comprehensive rescue organization serving the Murfreesboro, TN area. We rescue cats from shelters, the streets, and difficult situations, providing them with medical care, rehabilitation, and lots of love while we search for their perfect families.</p>
                
                <p>Every cat that comes through our doors receives individualized care, including veterinary treatment, spay/neuter services, vaccinations, and behavioral support. We believe every cat deserves a second chance, regardless of age, health status, or background.</p>
            </div>
            <div>
                <div class="card">
                    <div class="card-content" style="background-color: var(--primary-color); color: white; text-align: center; padding: 2rem;">
                        <h3 style="color: white; margin-bottom: 1.5rem;">Our Impact</h3>
                        <div class="grid grid-2" style="gap: 2rem;">
                            <div>
                                <div style="font-size: 3rem; font-weight: bold;">500+</div>
                                <div>Cats Rescued</div>
                            </div>
                            <div>
                                <div style="font-size: 3rem; font-weight: bold;">400+</div>
                                <div>Adoptions</div>
                            </div>
                            <div>
                                <div style="font-size: 3rem; font-weight: bold;">100+</div>
                                <div>Volunteers</div>
                            </div>
                            <div>
                                <div style="font-size: 3rem; font-weight: bold;">24/7</div>
                                <div>Care</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Our Mission & Values</h2>
        </div>
        
        <div class="grid grid-3">
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 1rem;">🏥</h3>
                    <h4>Rescue</h4>
                    <p>We rescue cats from shelters, streets, and difficult situations, giving them a second chance at life.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 1rem;">💚</h3>
                    <h4>Rehabilitate</h4>
                    <p>Every cat receives medical care, behavioral support, and lots of love to prepare them for adoption.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 1rem;">🏡</h3>
                    <h4>Rehome</h4>
                    <p>We carefully match each cat with the perfect family to ensure lifelong happiness.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What We Do Section -->
<section class="section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">What We Do</h2>
        </div>
        
        <div class="grid grid-2" style="gap: 2rem;">
            <div class="card">
                <div class="card-content">
                    <h4>🐱 Cat Rescue & Intake</h4>
                    <p>We accept cats from various situations including owner surrenders, strays, and shelter transfers. Each cat is assessed and given immediate care.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>💉 Medical Care</h4>
                    <p>All cats receive comprehensive veterinary care including vaccinations, spay/neuter surgery, and treatment for any medical conditions.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>🏠 Foster Program</h4>
                    <p>Our network of foster homes provides temporary care for cats who need extra attention or a quieter environment.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>❤️ Adoption Services</h4>
                    <p>We carefully screen potential adopters to ensure each cat goes to a safe, loving, and permanent home.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>📚 Education & Outreach</h4>
                    <p>We educate the community about responsible pet ownership, spay/neuter programs, and cat welfare.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>🤝 Community Support</h4>
                    <p>We provide resources and support to cat owners in need, helping keep cats in their homes when possible.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Our Commitment</h2>
            <p class="section-subtitle">We are committed to the highest standards of animal welfare and rescue operations.</p>
        </div>
        
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="card">
                <div class="card-content">
                    <ul style="list-style: none; padding: 0;">
                        <li style="padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                            <strong>✓ No-Kill Philosophy:</strong> We never euthanize for space or time constraints.
                        </li>
                        <li style="padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                            <strong>✓ Lifetime Support:</strong> We provide support to adopters for the life of the cat.
                        </li>
                        <li style="padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                            <strong>✓ Transparency:</strong> We operate with full transparency in our finances and operations.
                        </li>
                        <li style="padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                            <strong>✓ Quality Care:</strong> Every cat receives individualized, high-quality care.
                        </li>
                        <li style="padding: 1rem 0;">
                            <strong>✓ Community Focus:</strong> We work with the community to create lasting change for cats.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Get Involved Section -->
<section class="section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Get Involved</h2>
            <p class="section-subtitle">There are many ways you can help support our mission.</p>
        </div>
        
        <div class="grid grid-4">
            <div class="card">
                <div class="card-content text-center">
                    <h4>Adopt</h4>
                    <p>Give a cat a forever home</p>
                    <a href="/adoptions.php" class="btn btn-primary mt-2">View Cats</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h4>Donate</h4>
                    <p>Support our rescue efforts</p>
                    <a href="/donate.php" class="btn btn-primary mt-2">Donate Now</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h4>Volunteer</h4>
                    <p>Join our team</p>
                    <a href="/volunteer.php" class="btn btn-primary mt-2">Learn More</a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h4>Sponsor</h4>
                    <p>Become a sponsor</p>
                    <a href="/contact.php" class="btn btn-primary mt-2">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

