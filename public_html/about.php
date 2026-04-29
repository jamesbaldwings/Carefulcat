<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'About Us - Our Mission to Rescue Small Exotic Cats';
$metaDescription = 'Learn about Careful Cat Rescue, our mission to save and rehome small exotic cats including servals, savannahs, bengals, caracals, and other exotic felines in Murfreesboro, TN.';
$metaKeywords = 'about exotic cat rescue, small exotic cat sanctuary, serval rescue mission, savannah cat rescue, bengal cat rescue, caracal rescue, exotic feline nonprofit, Murfreesboro TN';

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>About Careful Cat Rescue</h1>
        <p>Dedicated to rescuing and rehoming small exotic felines, one paw at a time.</p>
    </div>
</section>

<!-- Our Story Section -->
<section class="section">
    <div class="container">
        <div class="grid grid-2" style="gap: 3rem; align-items: center;">
            <div>
                <h2 class="mb-3">Our Story</h2>
                <p>Careful Cat Rescue began in an unexpected way. After operating a small wildlife rescue for several years, we were asked to take in a close friend's exotic felines when she became ill. What started as a personal commitment quickly became something more.</p>
                
                <p>To protect native wildlife, we made the difficult decision to close our wildlife rescue and focus solely on the care of small exotic cats, reducing the risk of disease transmission between exotic and local species. Through word of mouth, more animals in need began to find their way to us, each with unique challenges, backgrounds, and care requirements.</p>
                
                <p>As the number of exotic cats in private ownership continues to grow across the country, so does the need for responsible, specialized rescue. Recognizing this gap, we chose to make our mission public and expand our efforts.</p>
                
                <p>Today, Careful Cat Rescue is dedicated to providing small exotic felines with safe, knowledgeable care while working to place them in appropriate, well-prepared homes. Each animal receives individualized attention, including veterinary care, proper nutrition, and behavioral support, with a focus on long-term wellbeing.</p>
            </div>
            <div>
                <div class="card">
                    <div class="card-content" style="background-color: var(--primary-color); color: white; text-align: center; padding: 2rem;">
                        <h3 style="color: white; margin-bottom: 1.5rem;">Our Impact</h3>
                        <div class="grid grid-2" style="gap: 2rem;">
                            <div>
                                <div style="font-size: 3rem; font-weight: bold;">500+</div>
                                <div>Exotic Cats Rescued</div>
                            </div>
                            <div>
                                <div style="font-size: 3rem; font-weight: bold;">400+</div>
                                <div>Successful Adoptions</div>
                            </div>
                            <div>
                                <div style="font-size: 3rem; font-weight: bold;">100+</div>
                                <div>Dedicated Volunteers</div>
                            </div>
                            <div>
                                <div style="font-size: 3rem; font-weight: bold;">24/7</div>
                                <div>Specialized Care</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="/donate.php" class="btn btn-primary" style="padding: 12px 30px;">Support Our Mission &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Types of Cats We Rescue Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Types of Cats We Rescue</h2>
            <p class="section-subtitle">We specialize in rescuing small wild cats and high-content exotic hybrid breeds that require experienced, dedicated care.</p>
        </div>
        
        <div class="grid grid-2" style="gap: 2rem;">
            <div class="card">
                <div class="card-content">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Small Wild Cats</h3>
                    <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <h4 style="margin-bottom: 0.25rem;">Serval <span style="font-weight: 400; font-size: 0.875rem; color: var(--text-light);">(Leptailurus serval)</span></h4>
                        <p style="margin: 0;">African wild cat, popular for its leopard-like spots and long legs. Servals are athletic, intelligent, and require specialized enclosures and diets.</p>
                    </div>
                    <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <h4 style="margin-bottom: 0.25rem;">Caracal <span style="font-weight: 400; font-size: 0.875rem; color: var(--text-light);">(Caracal caracal)</span></h4>
                        <p style="margin: 0;">Known as the African Lynx, characterized by long, black ear tufts. Caracals are powerful and agile, requiring experienced handlers.</p>
                    </div>
                    <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <h4 style="margin-bottom: 0.25rem;">Bobcat <span style="font-weight: 400; font-size: 0.875rem; color: var(--text-light);">(Lynx rufus)</span></h4>
                        <p style="margin: 0;">Sometimes kept as exotic pets, known for high intelligence and strong bonds with their caretakers.</p>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 0.25rem;">Other Species</h4>
                        <p style="margin: 0;">We also rescue lynx, fishing cats, and other small wild felines that need sanctuary or rehoming.</p>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Exotic Hybrid Breeds</h3>
                    <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <h4 style="margin-bottom: 0.25rem;">Savannah <span style="font-weight: 400; font-size: 0.875rem; color: var(--text-light);">(Serval × Domestic)</span></h4>
                        <p style="margin: 0;">Highly intelligent, active, and tall. F1/F2 generations are closest to the wild ancestor and require experienced owners.</p>
                    </div>
                    <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <h4 style="margin-bottom: 0.25rem;">Bengal <span style="font-weight: 400; font-size: 0.875rem; color: var(--text-light);">(Asian Leopard Cat × Domestic)</span></h4>
                        <p style="margin: 0;">A popular spotted hybrid with a wild appearance but domestic behavior. Bengals are energetic and need plenty of stimulation.</p>
                    </div>
                    <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <h4 style="margin-bottom: 0.25rem;">Caracat <span style="font-weight: 400; font-size: 0.875rem; color: var(--text-light);">(Caracal × Domestic)</span></h4>
                        <p style="margin: 0;">A rare hybrid designed to combine the caracal look with a domestic temperament. These cats are exceptionally unique.</p>
                    </div>
                    <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
                        <h4 style="margin-bottom: 0.25rem;">Chausie <span style="font-weight: 400; font-size: 0.875rem; color: var(--text-light);">(Jungle Cat × Domestic)</span></h4>
                        <p style="margin: 0;">Known for being energetic and athletic, Chausies thrive with active families who understand their needs.</p>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 0.25rem;">And More</h4>
                        <p style="margin: 0;">We welcome all small exotic and hybrid felines in need of rescue, rehabilitation, or rehoming.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p style="font-size: 1.1rem; max-width: 700px; margin: 0 auto 1.5rem;">These incredible animals often end up in rescue due to owners who underestimate the specialized care they require. <strong>Your support helps us provide the expert care they deserve.</strong></p>
            <a href="/donate.php" class="btn btn-primary" style="padding: 12px 30px;">Help Fund Their Care</a>
        </div>
    </div>
</section>

<!-- Mission & Values Section -->
<section class="section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Our Mission & Values</h2>
        </div>
        
        <div class="grid grid-3">
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 1rem;">🏥</h3>
                    <h4>Rescue</h4>
                    <p>We rescue small exotic cats from shelters, private surrenders, and difficult situations, giving them a second chance at life.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 1rem;">💚</h3>
                    <h4>Rehabilitate</h4>
                    <p>Every exotic feline receives medical care, behavioral support, and lots of love to prepare them for adoption.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content text-center">
                    <h3 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 1rem;">🏡</h3>
                    <h4>Rehome</h4>
                    <p>We carefully match each exotic cat with the perfect family to ensure lifelong happiness.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What We Do Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">What We Do</h2>
        </div>
        
        <div class="grid grid-2" style="gap: 2rem;">
            <div class="card">
                <div class="card-content">
                    <h4>🐱 Exotic Cat Rescue & Intake</h4>
                    <p>We accept small exotic cats from various situations including owner surrenders, strays, and shelter transfers. Each feline is assessed and given immediate specialized care.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>💉 Medical Care</h4>
                    <p>All exotic cats receive comprehensive veterinary care including vaccinations, spay/neuter surgery, and treatment for any medical conditions from vets experienced with exotic species.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>🏠 Foster Program</h4>
                    <p>Our network of experienced foster homes provides temporary care for exotic felines who need extra attention or a quieter environment.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>❤️ Adoption Services</h4>
                    <p>We carefully screen potential adopters to ensure each exotic cat goes to a safe, loving, and knowledgeable permanent home.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>📚 Education & Outreach</h4>
                    <p>We educate the community about responsible ownership, specialized care programs, and exotic feline welfare.</p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-content">
                    <h4>🤝 Community Support</h4>
                    <p>We provide resources and support to exotic cat owners in need, helping keep these felines in their homes when possible.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Commitment Section -->
<section class="section">
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
                            <strong>✓ Lifetime Support:</strong> We provide support to adopters for the life of the exotic cat.
                        </li>
                        <li style="padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                            <strong>✓ Transparency:</strong> We operate with full transparency in our finances and operations.
                        </li>
                        <li style="padding: 1rem 0; border-bottom: 1px solid var(--border-color);">
                            <strong>✓ Quality Care:</strong> Every exotic feline receives individualized, high-quality care from experienced handlers.
                        </li>
                        <li style="padding: 1rem 0;">
                            <strong>✓ Community Focus:</strong> We work with the community to create lasting change for small exotic cats.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Get Involved Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Get Involved</h2>
            <p class="section-subtitle">There are many ways you can help support our mission to rescue exotic felines.</p>
        </div>
        
        <div class="grid grid-4">
            <div class="card">
                <div class="card-content text-center">
                    <h4>Adopt</h4>
                    <p>Give an exotic cat a forever home</p>
                    <a href="/adoptions.php" class="btn btn-primary mt-2">View Exotic Cats</a>
                </div>
            </div>
            
            <div class="card" style="border: 2px solid var(--primary-color);">
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
