<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$pageTitle = 'Frequently Asked Questions About Exotic Cat Rescue & Adoption';
$metaDescription = 'Find answers to common questions about adopting exotic cats, volunteering at our sanctuary, donating, and supporting Careful Cat Rescue in Murfreesboro, TN.';
$metaKeywords = 'exotic cat rescue FAQ, adopt exotic cat questions, exotic feline adoption process, volunteer exotic cat sanctuary, donate exotic cat rescue';

require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="padding: 60px 0;">
    <div class="container">
        <h1>Frequently Asked Questions</h1>
        <p>Find answers to common questions about our exotic cat rescue, adoption process, and how you can help.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div style="max-width: 900px; margin: 0 auto;">
            
            <!-- Adoption FAQs -->
            <h2 class="mb-3">Adoption</h2>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>How do I adopt an exotic cat?</h4>
                    <p>Start by browsing our <a href="/adoptions.php">available exotic cats</a>. When you find one you're interested in, fill out an adoption application. We'll review your application, conduct a home visit if needed, and help you meet the exotic feline. If it's a good match, we'll complete the adoption paperwork and you can take your new companion home!</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>What is the adoption fee?</h4>
                    <p>Adoption fees vary by exotic cat but typically range from $75-$150. This fee covers spay/neuter surgery, vaccinations, microchipping, deworming, and flea treatment. All exotic felines are fully vetted before adoption.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>Can I adopt if I rent my home?</h4>
                    <p>Yes! We just need written permission from your landlord confirming that exotic cats are allowed. We're happy to provide a letter template if needed.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>Do you adopt to homes with other pets?</h4>
                    <p>Absolutely! Many of our exotic cats do well with other pets. We'll help match you with an exotic feline whose personality is compatible with your existing pets. We may require a meet-and-greet to ensure everyone gets along.</p>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-content">
                    <h4>How long does the adoption process take?</h4>
                    <p>The process typically takes 3-7 days from application to bringing your exotic cat home. This allows time for application review, reference checks, and scheduling a meet-and-greet.</p>
                </div>
            </div>
            
            <!-- General FAQs -->
            <h2 class="mb-3 mt-5">General Questions</h2>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>Where are you located?</h4>
                    <p>We're based in Murfreesboro, TN. Visits are by appointment only. <a href="/book-visit.php">Schedule a visit</a> to meet our exotic cats and see our facility.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>Are you a no-kill shelter?</h4>
                    <p>Yes! We are committed to a no-kill philosophy. We never euthanize for space or time constraints. Exotic cats stay with us until they find their forever homes, no matter how long it takes.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>Do you accept owner surrenders?</h4>
                    <p>We accept surrenders on a case-by-case basis depending on our current capacity. Please <a href="/contact.php">contact us</a> to discuss your situation. We also provide resources to help keep exotic cats in their current homes when possible.</p>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-content">
                    <h4>Can I visit without an appointment?</h4>
                    <p>No, all visits must be scheduled in advance. This ensures we can give you proper attention and that our exotic cats aren't overwhelmed. <a href="/book-visit.php">Book a visit</a> online.</p>
                </div>
            </div>
            
            <!-- Volunteering FAQs -->
            <h2 class="mb-3 mt-5">Volunteering</h2>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>How can I volunteer?</h4>
                    <p>We have many volunteer opportunities! Fill out our <a href="/volunteer.php">volunteer application</a> and we'll contact you about orientation and training.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>Do I need experience to volunteer?</h4>
                    <p>No experience necessary! We provide training for all volunteers. If you love exotic felines and want to help, we'll teach you everything you need to know.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>What is the minimum time commitment?</h4>
                    <p>We ask for a minimum commitment of 4 hours per month, but you can volunteer as much as you'd like! We have flexible scheduling to accommodate your availability.</p>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-content">
                    <h4>Can teenagers volunteer?</h4>
                    <p>Yes! Volunteers ages 16-17 are welcome with parental consent. Volunteers under 16 must be accompanied by a parent or guardian.</p>
                </div>
            </div>
            
            <!-- Donations FAQs -->
            <h2 class="mb-3 mt-5">Donations</h2>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>How can I donate?</h4>
                    <p>You can make a monetary donation through our <a href="/donate.php">donation page</a>. We also accept supplies, food, and other items. <a href="/contact.php">Contact us</a> for our current wish list.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>Are donations tax-deductible?</h4>
                    <p>We are working toward our 501(c)(3) nonprofit status. Please contact us for the latest information about the tax-deductibility of your donation. We provide receipts for all contributions.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>Can I sponsor a specific exotic cat?</h4>
                    <p>Yes! You can sponsor an exotic cat's care while they wait for adoption. Sponsorships help cover food, medical care, and daily needs. <a href="/donate.php">Donate now</a> or <a href="/contact.php">contact us</a> to learn more.</p>
                </div>
            </div>
            
            <div class="card mb-3">
                <div class="card-content">
                    <h4>What supplies do you need?</h4>
                    <p>We always need cat food (high protein dry and frozen meats), litter, indestructable toys, bedding, cleaning supplies, and medical supplies. <a href="/contact.php">Contact us</a> for our current wish list and drop-off information.</p>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Still Have Questions Section -->
<section class="section" style="background-color: var(--bg-light);">
    <div class="container text-center">
        <h2 class="section-title">Still Have Questions?</h2>
        <p class="section-subtitle">We're here to help! Reach out and we'll be happy to answer any questions you have about exotic cat rescue and adoption.</p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="/contact.php" class="btn btn-primary">Contact Us</a>
            <a href="/donate.php" class="btn btn-outline">Support Our Mission</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
