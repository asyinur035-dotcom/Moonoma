@extends('layouts.app')

@section('content')

<style>
/* LANDING PAGE STYLES */
.landing-container {
    padding: 0 0 50px 0;
    overflow-x: hidden;
}

/* HERO */
.hero {
    display: flex;
    align-items: center;
    gap: 40px;
    padding: 40px 20px;
    background: linear-gradient(135deg, rgba(62, 86, 65, 0.1) 0%, rgba(13, 15, 13, 0.1) 100%);
    border-radius: 30px;
    margin-bottom: 60px;
    border: 1px solid rgba(62, 86, 65, 0.2);
}

.hero-content {
    flex: 1;
}

.hero-content h1 {
    font-size: 42px;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 20px;
    background: linear-gradient(135deg, #fff 0%, #6f8a75 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-content p {
    font-size: 16px;
    color: #6f8a75;
    margin-bottom: 30px;
    line-height: 1.8;
    text-align: justify;
}

.hero-image {
    flex: 1;
    max-width: 450px;
}

.hero-image img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    border: 1px solid rgba(62, 86, 65, 0.3);
}

/* BUTTONS */
.cta-group {
    display: flex;
    gap: 15px;
}

.btn-main {
    background: #3E5641;
    color: #fff;
    padding: 12px 28px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
    box-shadow: 0 10px 20px rgba(62, 86, 65, 0.2);
}

.btn-main:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 25px rgba(62, 86, 65, 0.3);
}

.btn-sec {
    border: 1px solid #3E5641;
    color: #fff;
    padding: 12px 28px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
}

.btn-sec:hover {
    background: rgba(62, 86, 65, 0.1);
}

/* SECTION */
.section {
    margin-bottom: 80px;
    display: flex;
    align-items: center;
    gap: 60px;
}

.section.reverse {
    flex-direction: row-reverse;
}

.section-text {
    flex: 1;
}

.section-text h2 {
    font-size: 28px;
    margin-bottom: 15px;
    color: #fff;
}

.section-text p {
    font-size: 15px;
    color: #888;
    line-height: 1.8;
    text-align: justify;
}

.section-img {
    flex: 1;
}

.section-img img {
    width: 100%;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.05);
}

/* FEATURES GRID */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin-top: 40px;
}

.feature-card {
    background: rgba(13, 15, 13, 0.5);
    border: 1px solid rgba(62, 86, 65, 0.2);
    padding: 30px;
    border-radius: 20px;
    text-align: center;
    transition: 0.3s;
}

.feature-card:hover {
    border-color: #3E5641;
    transform: translateY(-5px);
}

.feature-icon {
    font-size: 32px;
    margin-bottom: 15px;
    display: block;
}

.feature-card h4 {
    margin-bottom: 10px;
    font-size: 18px;
}

.feature-card p {
    font-size: 13px;
    color: #6f8a75;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .hero {
        flex-direction: column;
        text-align: center;
        padding: 40px 20px;
    }
    .cta-group {
        justify-content: center;
    }
    .section {
        flex-direction: column !important;
        text-align: center;
        gap: 30px;
    }
    .hero-content h1 {
        font-size: 32px;
    }
}
</style>

<div class="landing-container">

    <!-- HERO SECTION -->
    <div class="hero">
        <div class="hero-content">
            <h1>Moonoma - Where Skills Meet Vision</h1>
            <p>
                A modern collaboration platform designed to connect creators, developers, and designers. 
                Learn together, build real projects, and advance your career in a supportive ecosystem.
            </p>
            <div class="cta-group">
                <a href="{{ route('rooms') }}" class="btn-main">Explore Rooms</a>
                <a href="{{ route('dashboard') }}" class="btn-sec">My Dashboard</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/hero.png') }}" alt="Moonoma Hero">
        </div>
    </div>

    <!-- WHY MOONOMA SECTION -->
    <div class="section">
        <div class="section-img">
            <img src="{{ asset('images/collaboration.png') }}" alt="Collaboration">
        </div>
        <div class="section-text">
            <h2 style="color: #6f8a75;">Why Moonoma was created?</h2>
            <p>
                Moonoma was born from a simple vision: Collaboration is the key to growth. 
                We realized that many great talents were stuck in silos, working alone without mentors or teammates.
            </p>
            <p style="margin-top:15px;">
                We created a space where everyone has something to teach and something to learn. 
                Not just a chat app, but a workspace that empowers every individual to grow through real interaction.
            </p>
        </div>
    </div>

    <!-- VISION SECTION -->
    <div class="section reverse">
        <div class="section-img">
            <img src="{{ asset('images/vision.png') }}" alt="Vision">
        </div>
        <div class="section-text">
            <h2 style="color: #c9a227;">Vision & Mission</h2>
            <p>
                Our mission is to democratize access to professional guidance and team collaboration. 
                We want Moonoma to be the primary hub for anyone looking to start their career in the creative and technology industries.
            </p>
            <p style="margin-top:15px;">
                Our vision is to build a global community connected through innovative projects, 
                where distance is no longer a barrier to building something extraordinary.
            </p>
        </div>
    </div>

    <!-- FEATURES -->
    <div style="text-align: center; margin-bottom: 40px;">
        <h2 style="font-size: 32px;">Core Experience</h2>
        <p style="color: #6f8a75;">Key features we've built for you.</p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <span class="feature-icon">💬</span>
            <h4>Real-time Chat</h4>
            <p>Instant discussion with team members without delay, equipped with reply and message deletion features.</p>
        </div>
        <div class="feature-card">
            <span class="feature-icon">📁</span>
            <h4>Media Sharing</h4>
            <p>Send files, images, videos, and audio with a capacity of up to 40MB per file.</p>
        </div>
        <div class="feature-card">
            <span class="feature-icon">🛡️</span>
            <h4>Private Rooms</h4>
            <p>Create exclusive workspaces with secure access codes for your internal team.</p>
        </div>
        <div class="feature-card">
            <span class="feature-icon">📄</span>
            <h4>CV Storage</h4>
            <p>Save and share your CV directly on your profile to be noticed by other collaborators.</p>
        </div>
    </div>

</div>

@endsection