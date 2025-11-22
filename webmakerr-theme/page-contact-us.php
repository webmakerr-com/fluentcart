<?php
/* Template Name: Contact Us */

$webhook_url = get_option('raphael_webhook_url');

get_header();
?>

<style>
    :root {
        --contact-radius: 4px;
        --contact-border: 1px solid #e7edf5;
    }

    .contact-hero {
        background: linear-gradient(120deg, #f8fbff 0%, #f6fff9 100%);
        border-radius: var(--contact-radius);
        overflow: hidden;
    }

    .contact-card,
    .contact-note,
    .faq-card,
    .contact-form-card,
    .contact-metric,
    .accordion-item,
    .accordion-button {
        border-radius: var(--contact-radius) !important;
    }

    .contact-card,
    .faq-card,
    .contact-metric,
    .contact-form-card {
        border: var(--contact-border);
    }

    .contact-card {
        background-color: #ffffff;
    }

    .contact-note {
        background: #f7fbff;
        border: var(--contact-border);
    }

    .contact-bullet svg {
        flex-shrink: 0;
    }

    .contact-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 999px;
        background: rgba(32, 201, 151, 0.08);
        color: #0f5132;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-size: 11px;
    }

    .contact-metric {
        border: 1px solid #e7f5ef;
        box-shadow: 0 10px 30px rgba(12, 69, 45, 0.05);
    }

    .contact-form-card {
        box-shadow: 0 12px 40px rgba(0, 33, 71, 0.08);
    }

    .contact-form-card .form-label {
        font-size: 0.95rem;
        color: #0b1727;
    }

    .contact-form-card .form-control,
    .contact-form-card textarea {
        border-radius: var(--contact-radius);
        padding: 12px 14px;
    }

    .contact-form-card .form-control:focus,
    .contact-form-card textarea:focus,
    .accordion-button:focus {
        border-color: #20c997;
        box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, 0.15);
    }

    .contact-form-card .btn {
        border-radius: var(--contact-radius);
        box-shadow: 0 12px 30px rgba(32, 201, 151, 0.25);
    }

    .faq-card .accordion-item {
        overflow: hidden;
        border: 1px solid #ecf1f7;
    }

    .faq-card .accordion-button {
        padding: 16px 18px;
    }

    .faq-card .accordion-button:not(.collapsed) {
        background-color: #f6fff9;
        color: #0f5132;
        box-shadow: none;
    }

    .contact-bullet span {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f3faf6;
        border-radius: 50%;
    }

    .contact-metrics {
        gap: 16px;
    }

    .contact-list li {
        align-items: flex-start;
    }

    .contact-list strong {
        display: block;
        margin-bottom: 4px;
    }

    @media (max-width: 767.98px) {
        .contact-hero {
            padding-top: 2.75rem !important;
        }

        .contact-metrics {
            grid-template-columns: 1fr;
        }

        .contact-pill {
            width: 100%;
            justify-content: center;
            text-align: center;
        }

        .contact-hero h1 {
            font-size: 2.05rem;
        }

        .contact-trust {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 768px) {
        .contact-form-card {
            position: sticky;
            top: 32px;
        }
    }
</style>

<section class="py-5 py-lg-6 contact-hero">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-7 order-1 order-lg-2">
                <div class="card border-0 shadow-lg contact-form-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                            <div>
                                <span class="contact-pill">Support ticket</span>
                                <h2 class="h4 fw-bold mt-3 mb-1">Tell us how we can help</h2>
                                <p class="text-secondary small mb-0">Share your goal and we will guide you to the fastest, most effective next step.</p>
                            </div>
                            <div class="text-success fw-semibold small">Average response &lt; 24 hours</div>
                        </div>
                        <div id="formAlert" class="mb-3" aria-live="polite"></div>
                        <form id="ticketForm" class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">First Name</label>
                                <input type="text" name="sender[first_name]" class="form-control" placeholder="Alex" autocomplete="given-name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="sender[email]" class="form-control" placeholder="name@example.com" autocomplete="email" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Ticket Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="What do you need help with?" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Ticket Content <span class="text-danger">*</span></label>
                                <textarea name="content" rows="5" class="form-control" placeholder="Share context, goals, and timelines." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success w-100 py-3 fw-semibold" id="submitBtn">
                                    Submit Ticket
                                </button>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-wrap align-items-center gap-3 text-secondary small contact-note p-3 bg-light">
                                    <span class="text-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M2 2.5A1.5 1.5 0 0 1 3.5 1h9A1.5 1.5 0 0 1 14 2.5v11a.5.5 0 0 1-.777.416L8 10.101l-5.223 3.815A.5.5 0 0 1 2 13.5z"></path>
                                        </svg>
                                    </span>
                                    <span><strong class="text-dark d-block">Your request stays private.</strong> We only use your details to respond and will confirm as soon as your ticket is created.</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 order-2 order-lg-1">
                <div class="d-flex flex-column gap-3">
                    <div class="contact-pill align-self-start">Contact Us</div>
                    <h1 class="display-5 fw-bold lh-sm mb-2">Partner with our team</h1>
                    <p class="fs-5 text-secondary mb-3">Share your request and a member of our partnerships desk will respond with a clear plan. Expect concise updates, transparent timelines, and outcomes that move your goals forward.</p>
                    <div class="d-grid gap-3 contact-metrics contact-trust" style="grid-template-columns: repeat(2, minmax(0, 1fr));">
                        <div class="p-3 bg-white shadow-sm h-100 border contact-card">
                            <div class="d-flex align-items-center gap-2 fw-semibold mb-1">
                                <span class="text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M13.485 1.929a.75.75 0 0 1 0 1.06l-7.07 7.071-3.182-3.182a.75.75 0 0 1 1.06-1.06l2.122 2.121 6.01-6.01a.75.75 0 0 1 1.06 0"></path>
                                    </svg>
                                </span>
                                Rapid response
                            </div>
                            <p class="mb-0 text-secondary small">Most tickets receive a tailored reply in under one business day.</p>
                        </div>
                        <div class="p-3 bg-white shadow-sm h-100 border contact-card">
                            <div class="d-flex align-items-center gap-2 fw-semibold mb-1">
                                <span class="text-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M13.485 1.929a.75.75 0 0 1 0 1.06l-7.07 7.071-3.182-3.182a.75.75 0 0 1 1.06-1.06l2.122 2.121 6.01-6.01a.75.75 0 0 1 1.06 0"></path>
                                    </svg>
                                </span>
                                Dedicated specialist
                            </div>
                            <p class="mb-0 text-secondary small">Your inquiry is handled by the right subject-matter expert from the start.</p>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-3 text-secondary small">
                        <div class="d-flex align-items-center gap-2 contact-bullet">
                            <span class="text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M2 2.5A1.5 1.5 0 0 1 3.5 1h9A1.5 1.5 0 0 1 14 2.5v11a.5.5 0 0 1-.777.416L8 10.101l-5.223 3.815A.5.5 0 0 1 2 13.5z"></path>
                                </svg>
                            </span>
                            Priority hours: Monday – Friday, 9:00 AM – 6:00 PM (local time)
                        </div>
                        <div class="d-flex align-items-center gap-2 contact-bullet">
                            <span class="text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1 2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 3.5 7-3.5V4a1 1 0 0 0-1-1zm13 3.383-4.708 2.354L15 11.105zm-.034 5.553-5.64-2.82L8 10.933l-1.326-.66-5.64 2.82A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.064M1 11.105l4.708-2.468L1 6.383z"></path>
                                </svg>
                            </span>
                            Prefer email? We will follow up from our shared inbox for clarity and speed.
                        </div>
                        <div class="d-flex align-items-center gap-2 contact-bullet">
                            <span class="text-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8a8 8 0 1 1-16 0 8 8 0 0 1 16 0m-8.5 3.5a.5.5 0 0 0 1 0V8a.5.5 0 0 0-1 0zM8 6a.75.75 0 1 0 0-1.5A.75.75 0 0 0 8 6"/>
                                </svg>
                            </span>
                            We prioritize concise communication so you know exactly what happens next.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <div class="p-4 p-md-5 shadow-sm border bg-light contact-card">
                    <p class="text-uppercase text-secondary small fw-semibold mb-2">Guidance</p>
                    <h2 class="h4 fw-bold mb-3">What to expect</h2>
                    <ul class="list-unstyled d-flex flex-column gap-3 text-secondary mb-0 contact-list">
                        <li class="d-flex gap-3">
                            <span class="text-success mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M13.485 1.929a.75.75 0 0 1 0 1.06l-7.07 7.071-3.182-3.182a.75.75 0 0 1 1.06-1.06l2.122 2.121 6.01-6.01a.75.75 0 0 1 1.06 0"></path>
                                </svg>
                            </span>
                            <span><strong class="text-dark">Clear next steps.</strong> We summarise your request and confirm the milestones or follow-up documents we need.</span>
                        </li>
                        <li class="d-flex gap-3">
                            <span class="text-success mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M13.485 1.929a.75.75 0 0 1 0 1.06l-7.07 7.071-3.182-3.182a.75.75 0 0 1 1.06-1.06l2.122 2.121 6.01-6.01a.75.75 0 0 1 1.06 0"></path>
                                </svg>
                            </span>
                            <span><strong class="text-dark">Direct collaboration.</strong> A dedicated specialist partners with you and loops in the right experts when needed.</span>
                        </li>
                        <li class="d-flex gap-3">
                            <span class="text-success mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M13.485 1.929a.75.75 0 0 1 0 1.06l-7.07 7.071-3.182-3.182a.75.75 0 0 1 1.06-1.06l2.122 2.121 6.01-6.01a.75.75 0 0 1 1.06 0"></path>
                                </svg>
                            </span>
                            <span><strong class="text-dark">Status transparency.</strong> We keep updates concise—no spam, just the decisions and actions that matter.</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm faq-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                            <div>
                                <div class="text-uppercase fw-semibold text-success small mb-2">Help Center</div>
                                <h2 class="h4 fw-bold mb-0">Frequently Asked Questions</h2>
                            </div>
                            <div class="badge bg-success-subtle text-success fw-semibold">Updated weekly</div>
                        </div>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item border-0 mb-3 shadow-sm">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button shadow-none fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        How quickly will I receive a response?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-secondary">
                                        Our partnership team aims to reply within one business day. Complex requests may take up to 48 hours.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0 mb-3 shadow-sm">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed shadow-none fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        What information should I include in my ticket?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-secondary">
                                        Please provide your objectives, timelines, and any relevant links or references to help us understand your needs.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0 mb-3 shadow-sm">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed shadow-none fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        Can I update my request after submission?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-secondary">
                                        Yes. Simply reply to the confirmation email you receive, or submit a new ticket referencing your previous request.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0 mb-3 shadow-sm">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed shadow-none fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        Who will handle my partnership inquiry?
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-secondary">
                                        A dedicated member of our partnerships team will review your request and coordinate with the right specialists.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0 shadow-sm">
                                <h2 class="accordion-header" id="headingFive">
                                    <button class="accordion-button collapsed shadow-none fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        Do you offer support in multiple languages?
                                    </button>
                                </h2>
                                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-secondary">
                                        We primarily operate in English, but we can accommodate other languages through our regional support partners when available.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
const WEBHOOK_URL = "<?php echo esc_url( $webhook_url ); ?>";

document.getElementById("ticketForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const btn = document.getElementById("submitBtn");
    const alertBox = document.getElementById("formAlert");
    btn.disabled = true;
    btn.textContent = "Submitting...";

    const form = e.target;

    const payload = {
        title: form.title.value,
        content: form.content.value,
        sender: {
            first_name: form["sender[first_name]"].value,
            email: form["sender[email]"].value
        }
    };

    try {
        const res = await fetch(WEBHOOK_URL, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });

        if (res.ok) {
            alertBox.innerHTML = `<div class="alert alert-success">Your ticket has been created successfully.</div>`;
            form.reset();
        } else {
            throw new Error();
        }
    } catch (err) {
        alertBox.innerHTML = `<div class="alert alert-danger">Something went wrong. Please try again.</div>`;
    }

    btn.disabled = false;
    btn.textContent = "Submit Ticket";
});
</script>

<?php get_footer(); ?>
